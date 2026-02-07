@extends('layouts.public')

@section('title', 'プライバシーポリシー')

@section('content')
<div class="legal">
    <div class="legal__container">
        <header class="legal__header">
            <p class="legal__meta">
                <span class="legal__meta-label">最終更新日：</span>
                <time datetime="{{ \Illuminate\Support\Str::of($lastUpdated ?? '2026-02-08')->replace('/', '-') }}">
                    {{ $lastUpdated ?? '2026/02/08' }}
                </time>
            </p>

            <h1 class="legal__title">プライバシーポリシー</h1>

            <dl class="legal__summary">
                <div class="legal__summary-row">
                    <dt class="legal__summary-label">サービス名</dt>
                    <dd class="legal__summary-value">DIY LAB</dd>
                </div>
            </dl>
        </header>

        <hr class="legal__divider">

        <nav class="legal__toc" aria-label="目次">
            <h2 class="legal__toc-title">目次</h2>
            <ul class="legal__toc-list">
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-1">第1条（個人情報の定義）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-2">第2条（取得する情報）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-3">第3条（利用目的）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-4">第4条（第三者提供）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-5">第5条（Cookie等の利用）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-6">第6条（アクセス解析ツール）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-7">第7条（投稿コンテンツ）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-8">第8条（個人情報の管理）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-9">第9条（開示・訂正・削除）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-10">第10条（ポリシーの変更）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-11">第11条（お問い合わせ）</a></li>
            </ul>
        </nav>

        <hr class="legal__divider">

        <article class="legal__content" aria-label="プライバシーポリシー本文">
            <p>
                本プライバシーポリシーは、{{ config('app.name', '本サービス') }}（以下「本サービス」といいます。）における、
                ユーザーの個人情報の取扱いについて定めるものです。
            </p>

            <section class="legal__section" aria-labelledby="section-1">
                <h2 class="legal__heading" id="section-1">第1条（個人情報の定義）</h2>
                <p>
                    本ポリシーにおいて「個人情報」とは、氏名、ユーザー名、メールアドレス、
                    その他特定の個人を識別できる情報をいいます。
                </p>
            </section>

            <section class="legal__section" aria-labelledby="section-2">
                <h2 class="legal__heading" id="section-2">第2条（取得する情報）</h2>
                <p>本サービスでは、以下の情報を取得することがあります。</p>
                <ol class="legal__list legal__list--ordered">
                    <li>ユーザー名、メールアドレス</li>
                    <li>プロフィール情報（アイコン画像、自己紹介等）</li>
                    <li>投稿内容、画像、コメント、リアクション</li>
                    <li>IPアドレス、ブラウザ情報、アクセス日時</li>
                    <li>Cookie等を用いた利用状況データ</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-3">
                <h2 class="legal__heading" id="section-3">第3条（利用目的）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>ユーザー登録・ログイン機能の提供</li>
                    <li>投稿・コメント・リアクション機能の提供</li>
                    <li>本人確認およびユーザーサポート対応</li>
                    <li>不正行為・利用規約違反への対応</li>
                    <li>サービス改善および新機能開発</li>
                    <li>重要なお知らせ等の連絡</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-4">
                <h2 class="legal__heading" id="section-4">第4条（第三者提供）</h2>
                <p>
                    運営者は、以下の場合を除き、個人情報を第三者に提供しません。
                </p>
                <ol class="legal__list legal__list--ordered">
                    <li>本人の同意がある場合</li>
                    <li>法令に基づき開示が必要な場合</li>
                    <li>人の生命・身体・財産の保護に必要な場合</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-5">
                <h2 class="legal__heading" id="section-5">第5条（Cookie等の利用）</h2>
                <p>
                    本サービスでは、利便性向上や利用状況の分析のためCookieを使用することがあります。
                    Cookieにより個人を特定する情報は取得されません。
                </p>
                <p>
                    ブラウザ設定によりCookieを無効にすることができますが、
                    一部機能が利用できなくなる場合があります。
                </p>
            </section>

            <section class="legal__section" aria-labelledby="section-6">
                <h2 class="legal__heading" id="section-6">第6条（アクセス解析ツール）</h2>
                <p>
                    本サービスでは、サービス向上のためアクセス解析ツールを導入する場合があります。
                    収集されるデータは匿名であり、個人を特定するものではありません。
                </p>
            </section>

            <section class="legal__section" aria-labelledby="section-7">
                <h2 class="legal__heading" id="section-7">第7条（投稿コンテンツ）</h2>
                <p>
                    ユーザーが投稿したコンテンツは、本サービス上で公開される場合があります。
                    投稿内容に個人情報を含める場合は、ユーザー自身の責任で管理してください。
                </p>
            </section>

            <section class="legal__section" aria-labelledby="section-8">
                <h2 class="legal__heading" id="section-8">第8条（個人情報の管理）</h2>
                <p>
                    運営者は、個人情報の漏えい、改ざん、不正アクセスを防止するため、
                    適切なセキュリティ対策を講じます。
                </p>
            </section>

            <section class="legal__section" aria-labelledby="section-9">
                <h2 class="legal__heading" id="section-9">第9条（開示・訂正・削除）</h2>
                <p>
                    ユーザーは、自己の個人情報について、開示・訂正・削除を求めることができます。
                    運営者所定の方法によりご連絡ください。
                </p>
            </section>

            <section class="legal__section" aria-labelledby="section-10">
                <h2 class="legal__heading" id="section-10">第10条（ポリシーの変更）</h2>
                <p>
                    本ポリシーは、法令やサービス内容の変更に応じて、
                    予告なく改定されることがあります。
                </p>
            </section>

            <section class="legal__section" aria-labelledby="section-11">
                <h2 class="legal__heading" id="section-11">第11条（お問い合わせ）</h2>
                <p>
                    本ポリシーに関するお問い合わせは、
                    本サービスのお問い合わせフォームよりご連絡ください。
                </p>
            </section>

            <hr class="legal__divider">

            <aside class="legal__note" aria-label="注意事項">
                <p>
                    ※本ポリシーは一般的なSNSサービスを想定したひな形です。
                    実際のデータ取得内容や外部サービス利用状況に応じて調整してください。
                </p>
            </aside>
        </article>
    </div>
</div>
@endsection
