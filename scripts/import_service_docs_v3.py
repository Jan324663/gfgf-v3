"""Import the existing 64k V2 document index into the V3 purpose-built table.

The script never creates WordPress posts or postmeta rows. It upserts the
existing source records into the table created by gfgf-service-docs.

Examples:
    python scripts/import_service_docs_v3.py --target local --apply
    python scripts/import_service_docs_v3.py --target live --apply
"""

from __future__ import annotations

import argparse
import re
import xml.etree.ElementTree as ET
from pathlib import Path

import pymysql
import pymysql.cursors


PROJECT_NOTES = Path(r"C:\Users\info\dev\GFGF.org V3 Codex")
SOURCE_XML = Path(
    r"C:\Users\info\dev\GFGF Modernisierung\db export\mm_sunterlagen_20260511_182531.xml"
)
SOURCE_COLUMNS = (
    "firma",
    "gename",
    "gtyp",
    "gtypaddon",
    "titel",
    "autor",
    "heft",
    "vonseite",
    "bisseite",
    "artddok",
    "titeldruck",
    "jahr",
    "bemerk",
    "bemerk1",
    "bemerk2",
    "bemerk3",
    "idx_value",
    "ablage_ordner",
    "ordner_nummer",
)


def parse_key_values(path: Path) -> dict[str, str]:
    values: dict[str, str] = {}
    for line in path.read_text(encoding="utf-8-sig").splitlines():
        match = re.match(r"\s*([^:=]+?)\s*[:=]\s*(.*?)\s*$", line)
        if match:
            values[match.group(1).strip().lower()] = match.group(2).strip()
    return values


def source_connection():
    return pymysql.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="gfgf",
        charset="utf8mb4",
        cursorclass=pymysql.cursors.SSDictCursor,
        autocommit=True,
    )


def target_config(target: str) -> tuple[dict, str]:
    if target == "local":
        return (
            {
                "host": "127.0.0.1",
                "user": "root",
                "password": "",
                "database": "gfgf_v3",
                "charset": "utf8mb4",
                "cursorclass": pymysql.cursors.DictCursor,
                "autocommit": False,
            },
            "wp_",
        )

    database_values = parse_key_values(PROJECT_NOTES / "WP Db Zugaenge.txt")
    ftp_text = (PROJECT_NOTES / "ftp.txt").read_text(encoding="utf-8-sig")
    host_match = re.search(r"IP-Adresse\s+([0-9.]+)", ftp_text)
    if not host_match:
        raise RuntimeError("Live database host is missing")

    required = ("name", "db benutzer", "db pw", "tabellen-präfix")
    if any(not database_values.get(key) for key in required):
        raise RuntimeError("Live database credentials are incomplete")

    return (
        {
            "host": host_match.group(1),
            "user": database_values["db benutzer"],
            "password": database_values["db pw"],
            "database": database_values["name"],
            "charset": "utf8mb4",
            "cursorclass": pymysql.cursors.DictCursor,
            "autocommit": False,
            "connect_timeout": 15,
            "read_timeout": 60,
            "write_timeout": 60,
        },
        database_values["tabellen-präfix"],
    )


def normalize(value) -> str:
    return "" if value is None else str(value).strip()


def load_pc_by_idx() -> dict[str, str]:
    """Read the PC/datacarrier field omitted by the former V2 WP migration."""
    pc_by_idx: dict[str, str] = {}
    for _event, record in ET.iterparse(SOURCE_XML, events=("end",)):
        if record.tag != "datarecord":
            continue
        idx = normalize(record.findtext("s_idx"))
        pc = normalize(record.findtext("s_pc"))
        if idx and pc:
            pc_by_idx[idx] = pc
        record.clear()
    return pc_by_idx


def target_row(row: dict, pc_by_idx: dict[str, str]) -> tuple:
    values = [normalize(row[column]) for column in SOURCE_COLUMNS]
    pc = pc_by_idx.get(values[16], "")
    search_text = " ".join(value for value in (*values, pc) if value)
    return (
        int(row["contao_id"]),
        values[0],
        values[1],
        values[2],
        values[3],
        values[4],
        values[5],
        values[6],
        values[7],
        values[8],
        values[9],
        values[10],
        values[11],
        values[12],
        values[13],
        values[14],
        values[15],
        values[17],
        values[18],
        pc,
        values[16],
        search_text,
        "published",
    )


def run(target: str, batch_size: int, apply: bool) -> None:
    source = source_connection()
    config, prefix = target_config(target)
    target_db = pymysql.connect(**config)
    table = f"{prefix}gfgf_service_docs"

    try:
        with target_db.cursor() as cursor:
            cursor.execute(f"SELECT COUNT(*) AS total FROM `{table}`")
            before = int(cursor.fetchone()["total"])
        print(f"Target={target} table={table} existing={before}")

        with source.cursor() as cursor:
            cursor.execute("SELECT COUNT(*) AS total FROM wp_gfgf_service_docs_index")
            source_total = int(cursor.fetchone()["total"])
        print(f"Source records={source_total}")

        pc_by_idx = load_pc_by_idx()
        print(f"PC/datacarrier values from original XML={len(pc_by_idx)}")

        if not apply:
            print("Dry run only. Pass --apply to import records.")
            return

        select_sql = """
            SELECT i.*,
                   COALESCE(ablage.meta_value, '') AS ablage_ordner,
                   COALESCE(ordner.meta_value, '') AS ordner_nummer
            FROM wp_gfgf_service_docs_index i
            LEFT JOIN wp_postmeta ablage
              ON ablage.post_id = i.post_id AND ablage.meta_key = 's_ablageo'
            LEFT JOIN wp_postmeta ordner
              ON ordner.post_id = i.post_id AND ordner.meta_key = 's_ordnernum'
            ORDER BY i.contao_id ASC
        """
        insert_sql = f"""
            INSERT INTO `{table}`
                (contao_id, firma, geraetename, geraetetyp, typ_zusatz, titel,
                 autor, heft, von_seite, bis_seite, dokumentart, drucktitel,
                 jahr, bemerkung, bemerkung1, bemerkung2, bemerkung3,
                 ablage_ordner, ordner_nummer, pc, idx_value, search_text, status)
            VALUES
                (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,
                 %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            ON DUPLICATE KEY UPDATE
                firma=VALUES(firma), geraetename=VALUES(geraetename),
                geraetetyp=VALUES(geraetetyp), typ_zusatz=VALUES(typ_zusatz),
                titel=VALUES(titel), autor=VALUES(autor), heft=VALUES(heft),
                von_seite=VALUES(von_seite), bis_seite=VALUES(bis_seite),
                dokumentart=VALUES(dokumentart), drucktitel=VALUES(drucktitel),
                jahr=VALUES(jahr), bemerkung=VALUES(bemerkung),
                bemerkung1=VALUES(bemerkung1), bemerkung2=VALUES(bemerkung2),
                bemerkung3=VALUES(bemerkung3), ablage_ordner=VALUES(ablage_ordner),
                ordner_nummer=VALUES(ordner_nummer), pc=VALUES(pc),
                idx_value=VALUES(idx_value), search_text=VALUES(search_text),
                status=VALUES(status)
        """

        imported = 0
        with source.cursor() as source_cursor:
            source_cursor.execute(select_sql)
            while True:
                rows = source_cursor.fetchmany(batch_size)
                if not rows:
                    break
                payload = [target_row(row, pc_by_idx) for row in rows]
                with target_db.cursor() as target_cursor:
                    target_cursor.executemany(insert_sql, payload)
                target_db.commit()
                imported += len(payload)
                if imported % 5000 < batch_size:
                    print(f"Imported {imported}/{source_total}")

        with target_db.cursor() as cursor:
            cursor.execute(
                f"SELECT COUNT(*) total, COUNT(DISTINCT idx_value) distinct_idx, "
                f"SUM(idx_value IS NULL OR TRIM(idx_value)='') empty_idx, "
                f"SUM(pc IS NOT NULL AND TRIM(pc)<>'') pc_values FROM `{table}`"
            )
            verification = cursor.fetchone()

        if int(verification["total"]) != source_total:
            raise RuntimeError(f"Row-count mismatch: {verification}")
        if int(verification["distinct_idx"]) != source_total or int(verification["empty_idx"]) != 0:
            raise RuntimeError(f"idx verification failed: {verification}")
        if int(verification["pc_values"]) != len(pc_by_idx):
            raise RuntimeError(f"PC/datacarrier verification failed: {verification}")

        print(
            "Verified "
            f"rows={verification['total']} distinct_idx={verification['distinct_idx']} "
            f"empty_idx={verification['empty_idx']} pc_values={verification['pc_values']}"
        )
    finally:
        source.close()
        target_db.close()


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--target", choices=("local", "live"), required=True)
    parser.add_argument("--batch-size", type=int, default=500)
    parser.add_argument("--apply", action="store_true")
    args = parser.parse_args()
    run(args.target, max(50, min(args.batch_size, 2000)), args.apply)


if __name__ == "__main__":
    main()
