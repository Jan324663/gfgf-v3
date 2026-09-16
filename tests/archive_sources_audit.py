"""Frontend regression audit for the editable archive sources page."""

import os
from playwright.sync_api import sync_playwright


SOURCES_URL = os.environ.get(
    "GFGF_ARCHIVE_SOURCES_URL",
    "http://localhost/gfgf-v3/?page_id=17",
)
LANDING_URL = os.environ.get(
    "GFGF_ARCHIVE_LANDING_URL",
    "http://localhost/gfgf-v3/?page_id=4",
)
IGNORE_HTTPS_ERRORS = os.environ.get("GFGF_IGNORE_HTTPS_ERRORS") == "1"
VIEWPORTS = ((390, 844), (1366, 768))


def assert_image_loaded(page):
    image = page.locator(".archive-sources__hero-media img")
    assert image.count() == 1
    image.scroll_into_view_if_needed()
    assert image.evaluate("element => element.complete && element.naturalWidth > 0")


with sync_playwright() as playwright:
    browser = playwright.chromium.launch()

    for width, height in VIEWPORTS:
        page = browser.new_page(
            viewport={"width": width, "height": height},
            ignore_https_errors=IGNORE_HTTPS_ERRORS,
        )
        errors = []
        page.on("pageerror", lambda error: errors.append(str(error)))
        response = page.goto(SOURCES_URL, wait_until="networkidle")
        assert response and response.status < 400

        assert page.locator("main h1").all_inner_texts() == [
            "Weitere Archive & Quellen"
        ]
        assert page.locator("main h2").all_inner_texts() == [
            "Online recherchieren",
            "Museen & Sammlungen",
            "Vereine & Sammlergemeinschaften",
            "Spezialquellen",
            "Externe Angebote",
        ]
        assert page.locator(".archive-source-card").count() == 18
        assert page.locator(".archive-source-grid").count() == 4
        assert page.locator(".archive-museum-finder").count() == 1
        assert page.locator(".archive-source-card__button a").count() == 18
        assert page.locator(".archive-museum-finder__button a").count() == 1

        external_links = page.locator(
            ".archive-source-card__button a, .archive-museum-finder__button a"
        )
        for index in range(external_links.count()):
            link = external_links.nth(index)
            assert link.get_attribute("target") == "_blank"
            rel = (link.get_attribute("rel") or "").split()
            assert "noopener" in rel and "noreferrer" in rel

        assert_image_loaded(page)
        assert not page.evaluate(
            "document.documentElement.scrollWidth > document.documentElement.clientWidth"
        )

        columns = page.locator(".archive-source-grid").first.evaluate(
            "element => getComputedStyle(element).gridTemplateColumns.split(' ').length"
        )
        assert columns == (1 if width <= 720 else 2)

        if width <= 720:
            heights = external_links.evaluate_all(
                "elements => elements.map(element => element.getBoundingClientRect().height)"
            )
            assert min(heights) >= 44

        assert not errors, errors
        print(f"OK sources {width}x{height}")
        page.close()

    page = browser.new_page(ignore_https_errors=IGNORE_HTTPS_ERRORS)
    response = page.goto(LANDING_URL, wait_until="networkidle")
    assert response and response.status < 400
    sources_link = page.locator(".archive-teaser__button a", has_text="Weitere Quellen")
    assert sources_link.count() == 1
    target = sources_link.get_attribute("href")
    assert target
    response = page.request.get(target, fail_on_status_code=False)
    assert response.status < 400
    linked_page = browser.new_page(ignore_https_errors=IGNORE_HTTPS_ERRORS)
    linked_page.goto(target, wait_until="domcontentloaded")
    assert linked_page.locator("main h1").inner_text() == "Weitere Archive & Quellen"
    linked_page.close()
    print("OK archive overview source link")

    browser.close()
