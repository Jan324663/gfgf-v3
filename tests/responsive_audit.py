"""Responsive browser audit for the local GFGF V3 WordPress site.

Requires Python Playwright and its Chromium browser. Run from the repository root:
    python tests/responsive_audit.py
"""

import os
from pathlib import Path
from playwright.sync_api import sync_playwright


URL = os.environ.get("GFGF_AUDIT_URL", "http://localhost/gfgf-v3/")
IGNORE_HTTPS_ERRORS = os.environ.get("GFGF_IGNORE_HTTPS_ERRORS") == "1"
OUTPUT = Path("responsive-audit")
VIEWPORTS = [
    (280, 653),
    (320, 568),
    (360, 640),
    (375, 667),
    (390, 844),
    (412, 915),
    (480, 800),
    (600, 960),
    (568, 320),
    (667, 375),
    (719, 900),
    (720, 1024),
    (721, 900),
    (768, 1024),
    (820, 1180),
    (844, 390),
    (980, 900),
    (981, 900),
    (1024, 768),
    (1180, 820),
    (1280, 800),
    (1366, 768),
    (1440, 900),
    (1499, 900),
    (1500, 900),
    (1920, 1080),
    (2560, 1440),
]


MEASURE = """
() => {
    const rect = (selector) => {
        const element = document.querySelector(selector);
        if (!element) return null;
        const box = element.getBoundingClientRect();
        const style = getComputedStyle(element);
        return {
            x: Math.round(box.x * 10) / 10,
            y: Math.round(box.y * 10) / 10,
            width: Math.round(box.width * 10) / 10,
            height: Math.round(box.height * 10) / 10,
            display: style.display,
            overflow: style.overflow,
        };
    };

    const visible = (element) => {
        const style = getComputedStyle(element);
        const box = element.getBoundingClientRect();
        return style.display !== 'none' && style.visibility !== 'hidden' && box.width > 0 && box.height > 0 &&
            box.bottom > 0 && box.right > 0 && box.left < innerWidth;
    };

    const tooSmall = [...document.querySelectorAll('a, button, input')]
        .filter(visible)
        .map((element) => {
            const box = element.getBoundingClientRect();
            return {
                text: (element.innerText || element.getAttribute('aria-label') || element.name || '').trim(),
                width: Math.round(box.width),
                height: Math.round(box.height),
            };
        })
        .filter((item) => item.width < 44 || item.height < 44);

    const wrappedNav = [...document.querySelectorAll('.site-navigation a')]
        .filter(visible)
        .map((element) => {
            const range = document.createRange();
            range.selectNodeContents(element);
            return {
                text: element.textContent.trim(),
                textHeight: Math.round(range.getBoundingClientRect().height),
                lineHeight: parseFloat(getComputedStyle(element).lineHeight),
            };
        })
        .filter((item) => item.textHeight > item.lineHeight * 1.55);

    const boxesOverlap = (first, second) => {
        if (!first || !second) return false;
        return first.x < second.x + second.width &&
            first.x + first.width > second.x &&
            first.y < second.y + second.height &&
            first.y + first.height > second.y;
    };

    const brand = rect('.site-brand');
    const navigation = rect('.site-navigation');
    const search = rect('.site-search');
    const join = rect('.site-header__join');
    const menu = rect('.site-header__menu');

    return {
        viewport: {width: innerWidth, height: innerHeight},
        documentWidth: document.documentElement.scrollWidth,
        horizontalOverflow: document.documentElement.scrollWidth > document.documentElement.clientWidth,
        header: rect('.site-header'),
        headerInner: rect('.site-header__inner'),
        brand,
        navigation,
        search,
        join,
        menu,
        footer: rect('.site-footer'),
        menuExpanded: document.querySelector('.site-header__menu')?.getAttribute('aria-expanded'),
        navVisibility: getComputedStyle(document.querySelector('.site-navigation')).visibility,
        tooSmall,
        wrappedNav,
        overlaps: {
            brandNavigation: boxesOverlap(brand, navigation),
            brandSearch: boxesOverlap(brand, search),
            navigationSearch: boxesOverlap(navigation, search),
            searchJoin: boxesOverlap(search, join),
            brandMenu: boxesOverlap(brand, menu),
            searchMenu: boxesOverlap(search, menu),
        },
    };
}
"""


def has_problem(result):
    return (
        result["horizontalOverflow"]
        or result["header"]["height"] > result["viewport"]["height"]
        or result["tooSmall"]
        or result["wrappedNav"]
        or any(result["overlaps"].values())
    )


OUTPUT.mkdir(exist_ok=True)
with sync_playwright() as playwright:
    browser = playwright.chromium.launch()
    context = browser.new_context(ignore_https_errors=IGNORE_HTTPS_ERRORS)
    page = context.new_page()
    page.goto(URL, wait_until="networkidle")

    for width, height in VIEWPORTS:
        page.set_viewport_size({"width": width, "height": height})
        page.goto(URL, wait_until="networkidle")
        closed = page.evaluate(MEASURE)

        menu_button = page.locator(".site-header__menu")
        opened = None
        interaction_problem = False
        if menu_button.is_visible():
            menu_button.click()
            page.wait_for_timeout(250)
            opened = page.evaluate(MEASURE)

            if width in {280, 320, 390, 568, 720}:
                page.screenshot(
                    path=OUTPUT / f"{width}x{height}-menu-open.png",
                    full_page=True,
                )

            last_item_reachable = page.evaluate("""
                () => {
                    const navigation = document.querySelector('.site-navigation');
                    const links = navigation.querySelectorAll('a');
                    navigation.scrollTop = navigation.scrollHeight;
                    const navBox = navigation.getBoundingClientRect();
                    const lastBox = links[links.length - 1].getBoundingClientRect();
                    return lastBox.bottom <= navBox.bottom + 1 && lastBox.top >= navBox.top - 1;
                }
            """)
            if not last_item_reachable:
                interaction_problem = True
                print(f"FAIL {width}x{height} last menu item is not reachable")

            page.keyboard.press("Escape")
            page.wait_for_timeout(250)
            escape_closed = page.evaluate(MEASURE)
            if escape_closed["menuExpanded"] != "false" or escape_closed["navVisibility"] != "hidden":
                interaction_problem = True
                print(f"FAIL {width}x{height} Escape did not close menu: {escape_closed}")

        state = "FAIL" if has_problem(closed) or (opened and has_problem(opened)) or interaction_problem else "OK"
        print(
            f"{state} {width}x{height} overflow={closed['horizontalOverflow']} "
            f"header={closed['header']['height']} footer={closed['footer']['height']} "
            f"small={closed['tooSmall']} wrapped={closed['wrappedNav']} "
            f"overlaps={closed['overlaps']} "
            f"openHeader={opened['header']['height'] if opened else '-'}"
        )
        if opened and has_problem(opened):
            print(f"     OPEN PROBLEM {opened}")

        if state == "FAIL" or width in {320, 390, 768, 1024, 1366, 1920}:
            page.screenshot(
                path=OUTPUT / f"{width}x{height}-{'open' if opened else 'desktop'}.png",
                full_page=True,
            )

    browser.close()
