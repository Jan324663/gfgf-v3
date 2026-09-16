"""Verify that the document search page is editable as valid Gutenberg blocks."""

import os
import re
from pathlib import Path
from urllib.parse import urlparse

from playwright.sync_api import sync_playwright


SITE_URL = os.environ.get("GFGF_EDITOR_SITE_URL", "http://localhost/gfgf-v3")
POST_ID = os.environ.get("GFGF_EDITOR_POST_ID", "8")
CREDENTIAL_FILE = Path(
    os.environ.get(
        "GFGF_WP_CREDENTIAL_FILE",
        r"C:\Users\info\dev\GFGF.org V3 Codex\WP Db Zugaenge.txt",
    )
)
IGNORE_HTTPS_ERRORS = os.environ.get("GFGF_IGNORE_HTTPS_ERRORS") == "1"


def credentials():
    environment_username = os.environ.get("GFGF_WP_USERNAME")
    environment_password = os.environ.get("GFGF_WP_PASSWORD")
    if environment_username and environment_password:
        return environment_username, environment_password

    if urlparse(SITE_URL).hostname in {"localhost", "127.0.0.1"}:
        infrastructure = (Path(__file__).parents[1] / "docs" / "infrastructure.md").read_text(
            encoding="utf-8"
        )
        match = re.search(r"lokales Admin-Passwort:\s*`([^`]+)`", infrastructure)
        if match:
            return "admin", match.group(1)

    values = {}
    for line in CREDENTIAL_FILE.read_text(encoding="utf-8-sig").splitlines():
        match = re.match(r"\s*([^:=]+?)\s*[:=]\s*(.+?)\s*$", line)
        if match:
            values[match.group(1).strip().lower()] = match.group(2).strip()
    username = values.get("wp user") or values.get("user")
    password = values.get("pw")
    if not username or not password:
        raise RuntimeError("WordPress credentials are incomplete")
    return username, password


username, password = credentials()

with sync_playwright() as playwright:
    browser = playwright.chromium.launch()
    context = browser.new_context(ignore_https_errors=IGNORE_HTTPS_ERRORS)
    page = context.new_page()
    page.goto(f"{SITE_URL}/wp-login.php", wait_until="domcontentloaded")
    page.locator("#user_login").fill(username)
    page.locator("#user_pass").fill(password)
    page.locator("#wp-submit").click()
    page.wait_for_load_state("domcontentloaded")
    assert "wp-login.php" not in page.url, "WordPress login failed"

    page.goto(
        f"{SITE_URL}/wp-admin/post.php?post={POST_ID}&action=edit",
        wait_until="domcontentloaded",
    )
    page.wait_for_function(
        "window.wp && wp.data && wp.data.select('core/block-editor')",
        timeout=30_000,
    )
    page.wait_for_timeout(1500)
    result = page.evaluate(
        """() => {
            const roots = wp.data.select('core/block-editor').getBlocks();
            const flattened = [];
            const visit = blocks => blocks.forEach(block => {
                flattened.push(block);
                visit(block.innerBlocks || []);
            });
            visit(roots);
            return {
                rootBlocks: roots.length,
                allBlocks: flattened.length,
                invalidBlocks: flattened.filter(block => block.isValid === false).length,
                dynamicBlocks: flattened.filter(
                    block => block.name === 'gfgf-service-docs/search'
                ).length,
                headings: flattened
                    .filter(block => block.name === 'core/heading')
                    .map(block => block.attributes.content),
                paragraphs: flattened.filter(block => block.name === 'core/paragraph').length,
            };
        }"""
    )

    assert result["rootBlocks"] == 2, result
    assert result["invalidBlocks"] == 0, result
    assert result["dynamicBlocks"] == 1, result
    assert result["headings"] == ["Dokumente suchen", "Hinweise zur Suche"], result
    assert result["paragraphs"] >= 3, result
    print(
        "OK Gutenberg editor "
        f"root={result['rootBlocks']} all={result['allBlocks']} "
        f"invalid={result['invalidBlocks']} dynamic={result['dynamicBlocks']}"
    )

    context.close()
    browser.close()
