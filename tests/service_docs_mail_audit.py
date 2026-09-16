"""Submit a local request and verify server-side document lookup in the mail."""

import json
import os
import subprocess
import uuid
from pathlib import Path

from playwright.sync_api import sync_playwright


SEARCH_URL = os.environ.get(
    "GFGF_SERVICE_DOCS_URL",
    "http://localhost/gfgf-v3/?page_id=8&docs_q=telemonde&doc_idx=54315",
)
WP_PATH = Path(os.environ.get("GFGF_LOCAL_WP_PATH", r"C:\laragon\www\gfgf-v3"))
WP_CLI = Path(os.environ.get("GFGF_WP_CLI", r"C:\laragon\bin\wp-cli\wp.bat"))
def wp_option_get(name: str):
    result = subprocess.run(
        [str(WP_CLI), f"--path={WP_PATH}", "option", "get", name, "--format=json"],
        capture_output=True,
        text=True,
        check=True,
    )
    return json.loads(result.stdout)


unique_email = f"service-docs-{uuid.uuid4().hex[:12]}@example.org"

with sync_playwright() as playwright:
    browser = playwright.chromium.launch()
    page = browser.new_page()
    response = page.goto(SEARCH_URL, wait_until="networkidle")
    assert response and response.status < 400
    form = page.locator(".service-docs-request__form")
    assert form.count() == 1
    form.locator("input[name=first_name]").fill("Maria")
    form.locator("input[name=last_name]").fill("Prüfung")
    form.locator("input[name=email]").fill(unique_email)
    form.locator("input[name=member]").check()
    form.locator("textarea[name=message]").fill("Bitte um Prüfung der Unterlage.")

    page.evaluate(
        """() => {
            const field = document.createElement('input');
            field.type = 'hidden';
            field.name = 'firma';
            field.value = 'MANIPULIERTER HERSTELLER';
            document.querySelector('.service-docs-request__form').append(field);
        }"""
    )
    form.locator("button[type=submit]").click()
    page.wait_for_load_state("networkidle")
    assert "request_status=sent" in page.url
    assert page.locator(".service-docs-notice--success").count() == 1
    browser.close()

mail = wp_option_get("gfgf_test_last_service_docs_mail")
body = mail["message"]
subject = mail["subject"]
assert "idx 54315" in subject
assert "Firma / Hersteller: Telemonde" in body
assert "Gerätename: Tuner" in body
assert "Typ: T 600" in body
assert "Typ-Zusatz: 30820" in body
assert "Dokumentart: Serviceunterlage" in body
assert "Bemerkung: Schaltbild" in body
assert "Bemerkung 1: Abgleich" in body
assert "Bemerkung 2: Werkstattbuch" in body
assert "Bemerkung 3: weitere Dokumente" in body
assert "Ablage / Ordner:" in body
assert "PC / Datenträger: x" in body
assert "idx: 54315" in body
assert "GFGF-Mitglied: Ja" in body
assert unique_email in body
assert "MANIPULIERTER HERSTELLER" not in body
assert "serverseitig anhand der idx" in body
print("OK server-side mail document lookup")
