"""Frontend and responsive regression audit for the document search."""

import os
from urllib.parse import parse_qs, urlparse

from playwright.sync_api import sync_playwright


SEARCH_URL = os.environ.get(
    "GFGF_SERVICE_DOCS_URL",
    "http://localhost/gfgf-v3/?page_id=8",
)
LANDING_URL = os.environ.get(
    "GFGF_ARCHIVE_LANDING_URL",
    "http://localhost/gfgf-v3/?page_id=4",
)
IGNORE_HTTPS_ERRORS = os.environ.get("GFGF_IGNORE_HTTPS_ERRORS") == "1"
VIEWPORTS = ((390, 844), (768, 1024), (1366, 768))


def has_query_value(url: str, key: str, expected: str) -> bool:
    return parse_qs(urlparse(url).query).get(key) == [expected]


with sync_playwright() as playwright:
    browser = playwright.chromium.launch()

    for width, height in VIEWPORTS:
        page = browser.new_page(
            viewport={"width": width, "height": height},
            ignore_https_errors=IGNORE_HTTPS_ERRORS,
        )
        errors = []
        page.on("pageerror", lambda error: errors.append(str(error)))
        response = page.goto(SEARCH_URL, wait_until="networkidle")
        assert response and response.status < 400

        assert page.locator("main h1").inner_text() == "Dokumente suchen"
        assert page.locator("#service-docs-query").count() == 1
        assert page.locator("main table").count() == 0
        hero_left = page.locator(".service-docs-page__hero-copy").evaluate(
            "element => element.getBoundingClientRect().left"
        )
        content_left = page.locator(".service-docs-search").evaluate(
            "element => element.getBoundingClientRect().left"
        )
        assert abs(hero_left - content_left) < 1
        assert not page.evaluate(
            "document.documentElement.scrollWidth > document.documentElement.clientWidth"
        )

        search = page.locator("#service-docs-query")
        search.fill("grundig accoro")
        search.press("Enter")
        page.wait_for_load_state("networkidle")

        assert page.locator(".service-doc-card").count() == 1
        card = page.locator(".service-doc-card")
        assert card.locator("h3").inner_text() == "Grundig · Accoro · 102"
        assert "1 Treffer" in page.locator(".service-docs-results__heading p").inner_text()
        assert not page.evaluate(
            "document.documentElement.scrollWidth > document.documentElement.clientWidth"
        )

        details = card.locator("details")
        details.locator("summary").click()
        assert details.evaluate("element => element.open")
        assert "23101" in details.inner_text()

        request_link = card.locator(".service-doc-card__request")
        if width <= 720:
            assert request_link.evaluate(
                "element => element.getBoundingClientRect().height"
            ) >= 44
        request_link.click()
        page.wait_for_load_state("networkidle")

        assert has_query_value(page.url, "doc_idx", "23101")
        assert page.locator(".service-docs-request__document").count() == 1
        assert "Grundig" in page.locator(".service-docs-request__document").inner_text()
        assert page.locator(".service-docs-request > p").inner_text() == (
            "Bitte geben Sie Ihre Kontaktdaten ein."
        )
        assert not page.locator(".service-docs-page__help").is_visible()
        form = page.locator(".service-docs-request__form")
        assert form.count() == 1
        assert form.locator("input[name=document_idx]").get_attribute("value") == "23101"
        assert form.locator("input[name=firma], input[name=titel]").count() == 0
        assert form.locator("input[name=first_name][required]").count() == 1
        assert form.locator("input[name=last_name][required]").count() == 1
        assert form.locator("input[name=email][required]").count() == 1
        assert form.locator("input[name=member]").count() == 1
        assert form.locator("textarea[name=message]").count() == 1
        assert form.locator("a", has_text="Datenschutzerklärung").count() == 1
        assert not page.evaluate(
            "document.documentElement.scrollWidth > document.documentElement.clientWidth"
        )
        assert not errors, errors
        print(f"OK document search {width}x{height}")
        page.close()

    page = browser.new_page(ignore_https_errors=IGNORE_HTTPS_ERRORS)
    page.goto(SEARCH_URL, wait_until="networkidle")
    page.locator("#service-docs-query").fill("Grundig")
    page.locator(".service-docs-search__form button").click()
    page.wait_for_load_state("networkidle")
    assert page.locator(".service-doc-card").count() == 25
    next_link = page.locator(".service-docs-pagination a.next")
    assert next_link.count() == 1
    next_link.click()
    page.wait_for_load_state("networkidle")
    assert has_query_value(page.url, "docs_page", "2")
    assert page.locator(".service-doc-card").count() == 25
    print("OK pagination")
    page.close()

    page = browser.new_page(ignore_https_errors=IGNORE_HTTPS_ERRORS)
    page.goto(LANDING_URL, wait_until="networkidle")
    link = page.locator(".archive-teaser__button a", has_text="Dokument suchen")
    assert link.count() == 1
    target = link.get_attribute("href")
    assert target
    linked = page.request.get(target, fail_on_status_code=False)
    assert linked.status < 400
    page.goto(target, wait_until="domcontentloaded")
    assert page.locator("main h1").inner_text() == "Dokumente suchen"
    print("OK archive overview document link")

    browser.close()
