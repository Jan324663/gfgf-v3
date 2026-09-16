"""Frontend regression audit for the Gutenberg-based archive pages."""

import os
from urllib.parse import urljoin, urlparse

from playwright.sync_api import sync_playwright


LANDING_URL = os.environ.get(
    "GFGF_ARCHIVE_LANDING_URL",
    "http://localhost/gfgf-v3/?page_id=4",
)
DETAIL_URL = os.environ.get(
    "GFGF_ARCHIVE_DETAIL_URL",
    "http://localhost/gfgf-v3/?page_id=5",
)
IGNORE_HTTPS_ERRORS = os.environ.get("GFGF_IGNORE_HTTPS_ERRORS") == "1"
VIEWPORTS = ((390, 844), (1366, 768))


def computed_px(page, selector, property_name):
    return page.locator(selector).first.evaluate(
        """(element, propertyName) =>
            parseFloat(getComputedStyle(element).getPropertyValue(propertyName))""",
        property_name,
    )


def vertical_gap(page, upper_selector, lower_selector):
    return page.evaluate(
        """([upperSelector, lowerSelector]) => {
            const upper = document.querySelector(upperSelector).getBoundingClientRect();
            const lower = document.querySelector(lowerSelector).getBoundingClientRect();
            return Math.round((lower.top - upper.bottom) * 10) / 10;
        }""",
        [upper_selector, lower_selector],
    )


def horizontal_offset(page, first_selector, second_selector):
    return page.evaluate(
        """([firstSelector, secondSelector]) => {
            const first = document.querySelector(firstSelector).getBoundingClientRect();
            const second = document.querySelector(secondSelector).getBoundingClientRect();
            return Math.round((first.left - second.left) * 10) / 10;
        }""",
        [first_selector, second_selector],
    )


def document_boxes(page):
    return page.locator(
        ".archive-collection, .archive-collections__media"
    ).evaluate_all(
        """elements => elements.map(element => {
            const box = element.getBoundingClientRect();
            return {
                x: Math.round((box.x + scrollX) * 10) / 10,
                y: Math.round((box.y + scrollY) * 10) / 10,
                width: Math.round(box.width * 10) / 10,
                height: Math.round(box.height * 10) / 10,
            };
        })"""
    )


def assert_internal_links(page, page_url):
    origin = urlparse(page_url).netloc
    hrefs = page.locator("a[href]").evaluate_all(
        "elements => [...new Set(elements.map(element => element.href))]"
    )
    failures = []

    for href in hrefs:
        parsed = urlparse(href)
        if parsed.scheme not in ("http", "https") or parsed.netloc != origin:
            continue

        response = page.request.get(urljoin(page_url, href), fail_on_status_code=False)
        if response.status >= 400:
            failures.append((response.status, href))

    assert not failures, failures


with sync_playwright() as playwright:
    browser = playwright.chromium.launch()

    for width, height in VIEWPORTS:
        page = browser.new_page(
            viewport={"width": width, "height": height},
            ignore_https_errors=IGNORE_HTTPS_ERRORS,
        )
        errors = []
        page.on("pageerror", lambda error: errors.append(str(error)))
        page.goto(LANDING_URL, wait_until="domcontentloaded")
        assert page.locator(".archive-teaser").count() == 3
        assert page.locator(".archive-offer").count() == 1
        expected_landing_gap = 28 if width <= 720 else 40
        assert vertical_gap(
            page, ".archive-landing__intro-inner", ".archive-teasers"
        ) == expected_landing_gap
        assert (
            vertical_gap(page, ".archive-teasers", ".archive-offer")
            == expected_landing_gap
        )
        assert horizontal_offset(
            page, ".archive-landing__intro-inner", ".archive-teasers"
        ) == 0
        assert page.evaluate("document.documentElement.scrollWidth <= window.innerWidth")
        if width > 1000:
            assert_internal_links(page, LANDING_URL)
        assert not errors, errors
        print(f"OK landing {width}x{height}")
        page.close()

        page = browser.new_page(
            viewport={"width": width, "height": height},
            ignore_https_errors=IGNORE_HTTPS_ERRORS,
        )
        errors = []
        page.on("pageerror", lambda error: errors.append(str(error)))
        page.goto(DETAIL_URL, wait_until="domcontentloaded")
        assert page.locator(".archive-stat").count() == 5
        assert page.locator(".archive-collection").count() == 6
        assert page.locator(".archive-collection__dialog-source").count() == 6
        assert page.locator(".archive-download-group a").count() == 18
        assert computed_px(
            page,
            ".archive-detail__hero-copy > .wp-block-group__inner-container > p:not(.archive-detail__tagline)",
            "margin-bottom",
        ) == 28
        assert computed_px(page, ".archive-tour__badge", "margin-top") == 0
        assert computed_px(
            page, ".archive-tour__content .wp-block-buttons", "margin-top"
        ) == 24
        assert computed_px(
            page, ".archive-visit__content .wp-block-buttons", "margin-top"
        ) == 24
        assert computed_px(page, ".archive-timeline__date", "margin-top") == 0
        assert computed_px(page, ".archive-timeline__date", "margin-bottom") == 5

        images = page.locator(".archive-detail__content img")
        assert images.count() == 5
        for index in range(images.count()):
            image = images.nth(index)
            image.scroll_into_view_if_needed()
            image.evaluate(
                """element => element.complete && element.naturalWidth
                    ? true
                    : new Promise(resolve => {
                        element.addEventListener('load', () => resolve(true), {once: true});
                        element.addEventListener('error', () => resolve(false), {once: true});
                    })"""
            )
            assert image.evaluate("element => element.complete && element.naturalWidth > 0")

        trigger = page.locator(".archive-collection__more .wp-block-button__link").first
        trigger.scroll_into_view_if_needed()
        before = document_boxes(page)
        trigger.click()
        dialog = page.locator("#archive-collection-dialog")
        assert dialog.evaluate("element => element.open")
        assert before == document_boxes(page)
        assert page.locator("#archive-dialog-title").inner_text() == "Schaltpläne & Serviceunterlagen"
        assert "40.000 Serviceunterlagen" in page.locator("#archive-dialog-text").inner_text()
        assert page.locator(".archive-collection-dialog__download").get_attribute("href").endswith(
            "/serviceunterlagen.pdf"
        )
        assert page.locator(":focus").get_attribute("class") == "archive-collection-dialog__close"
        page.keyboard.press("Escape")
        assert not dialog.evaluate("element => element.open")
        assert page.evaluate(
            "document.activeElement === document.querySelector('.archive-collection__more .wp-block-button__link')"
        )

        page.locator(".archive-collection__more .wp-block-button__link").nth(1).click()
        page.locator(".archive-collection-dialog__close").click()
        assert not dialog.evaluate("element => element.open")

        page.locator(".archive-collection__more .wp-block-button__link").nth(5).click()
        page.mouse.click(2, 2)
        assert not dialog.evaluate("element => element.open")
        assert page.evaluate("document.documentElement.scrollWidth <= window.innerWidth")
        if width > 1000:
            assert_internal_links(page, DETAIL_URL)
        assert not errors, errors
        print(f"OK detail {width}x{height}")
        page.close()

    browser.close()
