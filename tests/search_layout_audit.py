import os
import sys
from urllib.parse import parse_qsl, urlencode, urlsplit, urlunsplit

from playwright.sync_api import sync_playwright


BASE_URL = os.environ.get(
    "GFGF_AUDIT_URL",
    "http://localhost/gfgf-v3/?s=archiv",
)
IGNORE_HTTPS_ERRORS = os.environ.get("GFGF_IGNORE_HTTPS_ERRORS") == "1"
VIEWPORTS = ((1440, 1000), (768, 1024), (390, 844))


def search_url(term: str) -> str:
    parts = urlsplit(BASE_URL)
    query = dict(parse_qsl(parts.query, keep_blank_values=True))
    query["s"] = term
    return urlunsplit((parts.scheme, parts.netloc, parts.path, urlencode(query), parts.fragment))


def expect(condition: bool, message: str, failures: list[str]) -> None:
    if not condition:
        failures.append(message)


def main() -> int:
    failures: list[str] = []

    with sync_playwright() as playwright:
        browser = playwright.chromium.launch(headless=True)
        context = browser.new_context(ignore_https_errors=IGNORE_HTTPS_ERRORS)
        page = context.new_page()

        for width, height in VIEWPORTS:
            label = f"{width}x{height}"
            page.set_viewport_size({"width": width, "height": height})
            response = page.goto(search_url("archiv"), wait_until="networkidle", timeout=60_000)

            expect(response is not None and response.status == 200, f"{label}: HTTP status is not 200", failures)
            expect(page.locator(".search-page").count() == 1, f"{label}: search template is missing", failures)
            expect(page.locator(".search-page h1").count() == 1, f"{label}: expected exactly one H1", failures)
            expect(
                "archiv" in page.locator(".search-page h1").inner_text().lower(),
                f"{label}: search query is missing from H1",
                failures,
            )

            cards = page.locator(".search-results__grid > article")
            card_count = cards.count()
            expect(card_count > 0, f"{label}: no result cards found", failures)
            expect(page.locator(".content-area").count() == 0, f"{label}: legacy content wrapper still rendered", failures)
            expect(page.locator(".search-results__item .entry-content").count() == 0, f"{label}: full page content rendered in card", failures)
            expect(page.locator(".search-results__item h1").count() == 0, f"{label}: nested H1 rendered in card", failures)

            dimensions = page.evaluate(
                "({scrollWidth: document.documentElement.scrollWidth, "
                "clientWidth: document.documentElement.clientWidth, "
                "scrollHeight: document.documentElement.scrollHeight})"
            )
            expect(
                dimensions["scrollWidth"] <= dimensions["clientWidth"],
                f"{label}: horizontal overflow ({dimensions})",
                failures,
            )
            expect(
                dimensions["scrollHeight"] < 5_000,
                f"{label}: search page is unexpectedly tall ({dimensions['scrollHeight']} px)",
                failures,
            )

            if card_count:
                card_boxes = cards.evaluate_all(
                    "els => els.map(el => { const r = el.getBoundingClientRect(); "
                    "return {x: Math.round(r.x), width: r.width}; })"
                )
                columns = len({box["x"] for box in card_boxes})
                expected_columns = 1 if width <= 720 else 2
                expect(
                    columns == expected_columns,
                    f"{label}: expected {expected_columns} result column(s), got {columns}",
                    failures,
                )

                links = page.locator(".search-results__link")
                expect(links.count() == card_count, f"{label}: each card needs one action link", failures)
                min_link_height = min(links.evaluate_all("els => els.map(el => el.getBoundingClientRect().height)"))
                expect(min_link_height >= 44, f"{label}: result action is below 44 px", failures)

        page.set_viewport_size({"width": 390, "height": 844})
        response = page.goto(
            search_url("gfgf-layout-test-ohne-treffer-928374"),
            wait_until="networkidle",
            timeout=60_000,
        )
        expect(response is not None and response.status == 200, "empty search: HTTP status is not 200", failures)
        expect(page.locator(".search-empty").count() == 1, "empty search: guidance panel is missing", failures)
        expect(page.locator(".search-empty__form").count() == 1, "empty search: retry form is missing", failures)
        expect(page.locator(".search-results__item").count() == 0, "empty search: result cards are present", failures)
        empty_dimensions = page.evaluate(
            "({scrollWidth: document.documentElement.scrollWidth, clientWidth: document.documentElement.clientWidth})"
        )
        expect(
            empty_dimensions["scrollWidth"] <= empty_dimensions["clientWidth"],
            f"empty search: horizontal overflow ({empty_dimensions})",
            failures,
        )

        context.close()
        browser.close()

    if failures:
        print("Search layout audit failed:")
        for failure in failures:
            print(f"- {failure}")
        return 1

    print("Search layout audit passed for result and empty states at all tested widths.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
