import os
import re
import csv

MIGRATIONS_DIR = "database/migrations"
OUTPUT_FILE = "migrations_export.csv"

# カラム定義を正規表現で抜き出す
COLUMN_REGEX = re.compile(r"\$table->(\w+)\(['\"](\w+)['\"](.*)\);")

def parse_migration(file_path):
    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()

    # テーブル名
    table_match = re.search(r"Schema::create\(['\"](\w+)['\"]", content)
    if not table_match:
        return None, []

    table_name = table_match.group(1)

    # カラム定義をすべて取得
    columns = []
    for match in COLUMN_REGEX.finditer(content):
        col_type, col_name, options = match.groups()
        columns.append({
            "table": table_name,
            "column": col_name,
            "type": col_type,
            "options": options.strip()
        })

    return table_name, columns


def main():
    rows = []
    for filename in os.listdir(MIGRATIONS_DIR):
        if filename.endswith(".php"):
            filepath = os.path.join(MIGRATIONS_DIR, filename)
            table_name, cols = parse_migration(filepath)
            if cols:
                rows.extend(cols)

    # CSV出力
    with open(OUTPUT_FILE, "w", newline="", encoding="utf-8") as csvfile:
        writer = csv.DictWriter(csvfile, fieldnames=["table", "column", "type", "options"])
        writer.writeheader()
        writer.writerows(rows)

    print(f"✅ Exported to {OUTPUT_FILE}")


if __name__ == "__main__":
    main()
