import os
import re
import csv

MODELS_DIR = "app/Models"
OUTPUT_FILE = "models_export.csv"

# プロパティ抽出用
TABLE_REGEX = re.compile(r"protected\s+\$table\s*=\s*['\"](\w+)['\"]")
PK_REGEX = re.compile(r"protected\s+\$primaryKey\s*=\s*['\"](\w+)['\"]")
FILLABLE_REGEX = re.compile(r"protected\s+\$fillable\s*=\s*\[([^\]]*)\]", re.S)
GUARDED_REGEX = re.compile(r"protected\s+\$guarded\s*=\s*\[([^\]]*)\]", re.S)
CASTS_REGEX = re.compile(r"protected\s+\$casts\s*=\s*\[([^\]]*)\]", re.S)

# 関数定義
FUNC_REGEX = re.compile(r"public\s+function\s+(\w+)\s*\(")

# リレーション判定（中で hasMany/ belongsTo などが使われているかを見る）
RELATION_REGEX = re.compile(r"return\s+\$this->(hasMany|belongsTo|hasOne|belongsToMany)\s*\(")

def extract_array(content):
    items = re.findall(r"['\"](\w+)['\"]", content)
    return items

def parse_model(file_path):
    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()

    model_name = os.path.basename(file_path).replace(".php", "")

    table = TABLE_REGEX.search(content)
    pk = PK_REGEX.search(content)
    fillable = FILLABLE_REGEX.search(content)
    guarded = GUARDED_REGEX.search(content)
    casts = CASTS_REGEX.search(content)

    # public functions
    functions = FUNC_REGEX.findall(content)

    # 関数ごとのリレーション情報
    relations = []
    for func in functions:
        # 関数ブロックをざっくり抜き出し
        func_pattern = re.compile(rf"public\s+function\s+{func}\s*\([^)]*\)\s*\{{([\s\S]*?)\}}", re.S)
        func_block = func_pattern.search(content)
        if func_block:
            relation_match = RELATION_REGEX.search(func_block.group(1))
            if relation_match:
                relations.append(f"{func}:{relation_match.group(1)}")

    return {
        "model": model_name,
        "table": table.group(1) if table else "",
        "primaryKey": pk.group(1) if pk else "id",
        "fillable": ", ".join(extract_array(fillable.group(1))) if fillable else "",
        "guarded": ", ".join(extract_array(guarded.group(1))) if guarded else "",
        "casts": ", ".join(re.findall(r"['\"](\w+)['\"]\s*=>\s*['\"](\w+)['\"]", casts.group(1))) if casts else "",
        "functions": ", ".join(functions),
        "relations": ", ".join(relations),
    }

def main():
    rows = []
    for filename in os.listdir(MODELS_DIR):
        if filename.endswith(".php"):
            filepath = os.path.join(MODELS_DIR, filename)
            rows.append(parse_model(filepath))

    # CSV出力
    with open(OUTPUT_FILE, "w", newline="", encoding="utf-8") as csvfile:
        writer = csv.DictWriter(
            csvfile,
            fieldnames=["model", "table", "primaryKey", "fillable", "guarded", "casts", "functions", "relations"]
        )
        writer.writeheader()
        writer.writerows(rows)

    print(f"✅ Exported to {OUTPUT_FILE}")

if __name__ == "__main__":
    main()
