# DIY LAB - Claude Code プロジェクト設定

## このサイトについて
- サイト名: DIY LAB
- 概要: DIY情報を発信するWebサイト
- フレームワーク: Laravel（12.25.0）
- PHPバージョン: 8.2.12

## 私について
- 職種: Webディレクター
- スキル: コーディングは補助的に対応可能。技術的な詳細説明は不要
- 希望: 変更前に必ず何をするか説明してから実行すること

---

## ローカル開発環境（Windows / XAMPP）

- OS: Windows
- 開発環境: XAMPP
- ローカルURL: http://127.0.0.1:8000/（※実際のURLに変更）
- プロジェクトフォルダ: C:\xampp\htdocs\diy_lab_app（※実際のパスに変更）
- PHPの場所: C:\xampp\php\php.exe
- Composerの場所: ※グローバルインストール済みであれば「composer」コマンドで実行可

### よく使うローカルコマンド
```bash
php artisan serve        # ローカルサーバー起動
php artisan migrate      # DBマイグレーション
php artisan cache:clear  # キャッシュクリア
composer install         # パッケージインストール
npm run dev              # フロントエンドビルド
```

---

## 本番環境（Xサーバー）

- サーバー: Xサーバー
- 本番URL: https://diy-lab.net/
- デプロイ方法: 手動FTP、FileZILA
- PHPバージョン（本番）: PHP8.3.21
- 本番のLaravelパス: /home/chasercb750/diy-lab.net/diy_lab_app/

### Xサーバー注意事項
- 本番への直接ファイル変更は原則禁止。必ずローカルで確認してからデプロイ
- .envファイルは本番・ローカルで別管理。Gitにはコミットしない
- storage/ と bootstrap/cache/ のパーミッションは 775 に設定済み

---

## Git / GitHub

- リポジトリ: https://github.com/Shoki-Hiraga/diy_lab_app
- メインブランチ: main（※masterの場合は変更）
- 運用ルール:
  - 機能追加は feature/機能名 ブランチで作業
  - 本番反映前に必ず確認を求めること
  - .env / storage/ / vendor/ はGit管理外（.gitignoreで除外済み）

---

## Laravelプロジェクト構成

```
diylab/
├── app/
│   ├── Http/Controllers/   # コントローラー
│   ├── Models/             # モデル
├── resources/
│   ├── views/              # Bladeテンプレート
│   └── js/ css/            # フロントエンド
├── routes/
│   └── web.php             # ルーティング
├── database/
│   ├── migrations/         # マイグレーション
│   └── seeders/            # シーダー
├── .env                    # 環境変数（Git管理外）
└── public/                 # Webルート
```

---

## 注意事項・ルール

- **本番反映前に必ず確認を求めること**
- **データベースの削除・リセット系コマンドは必ず確認してから実行**
- php artisan migrate:fresh や db:wipe は本番では絶対に実行しない
- コード変更時はどのファイルを変更したか必ず教えること
- エラーが出たら storage/logs/laravel.log を確認する

---

## よく使う機能・画面（わかる範囲で記入）

- ※例: 記事投稿機能（/admin/posts）
- ※例: ユーザー管理（/admin/users）
- ※例: お問い合わせフォーム（/contact）

---

## 困ったときの確認場所

| 問題 | 確認場所 |
|------|----------|
| Laravelエラー | storage/logs/laravel.log |
| XAMPPが起動しない | XAMPPコントロールパネル |
| Xサーバーのエラー | Xサーバー管理画面 > エラーログ |
| GitHubへのpushエラー | git status / git log で確認 |