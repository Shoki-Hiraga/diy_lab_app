@extends('layouts.public')

@section('title', '利用規約')

@section('post-header')
    @include('components.common.post-header')
@endsection

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

            <h1 class="legal__title">利用規約</h1>

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
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-1">第1条（適用）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-2">第2条（定義）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-3">第3条（会員登録）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-4">第4条（アカウント管理）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-5">第5条（投稿コンテンツの取扱い）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-6">第6条（禁止事項）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-7">第7条（サービス提供の停止等）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-8">第8条（退会・利用制限・アカウント削除）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-9">第9条（免責事項）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-10">第10条（規約の変更）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-11">第11条（通知または連絡）</a></li>
                <li class="legal__toc-item"><a class="legal__toc-link" href="#section-12">第12条（準拠法・裁判管轄）</a></li>
            </ul>
        </nav>

        <hr class="legal__divider">

        <article class="legal__content" aria-label="利用規約本文">
            <p>
                この利用規約（以下「本規約」といいます。）は、{{ config('app.name', '本サービス') }}（以下「本サービス」といいます。）の提供条件および、
                本サービスを利用するユーザーの皆さま（以下「ユーザー」といいます。）との間の権利義務関係を定めるものです。
                ユーザーは、本規約に同意のうえ、本サービスを利用するものとします。
            </p>

            <section class="legal__section" aria-labelledby="section-1">
                <h2 class="legal__heading" id="section-1">第1条（適用）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>本規約は、ユーザーと本サービス運営者（以下「運営者」といいます。）との間の本サービスの利用に関わる一切の関係に適用されます。</li>
                    <li>運営者が本サービス上で随時掲載するルール、ガイドライン、注意事項等は、本規約の一部を構成するものとします。</li>
                    <li>本規約と前項のルール等が矛盾する場合、特段の定めがない限り、本規約が優先します。</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-2">
                <h2 class="legal__heading" id="section-2">第2条（定義）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>「会員」とは、第3条に基づき会員登録が完了したユーザーをいいます。</li>
                    <li>「投稿コンテンツ」とは、会員が本サービスに投稿・送信・アップロードする文章、画像、動画、URL、コメントその他一切の情報をいいます。</li>
                    <li>「知的財産権」とは、著作権、商標権、特許権その他一切の知的財産に関する権利をいいます。</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-3">
                <h2 class="legal__heading" id="section-3">第3条（会員登録）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>本サービスの利用を希望する者は、本規約に同意のうえ、運営者所定の方法により会員登録を申請し、運営者がこれを承認した時点で会員登録が完了します。</li>
                    <li>
                        運営者は、以下のいずれかに該当すると判断した場合、会員登録の申請を承認しないことがあります。なお、その理由の開示義務を負いません。
                        <ol class="legal__list legal__list--ordered legal__list--nested">
                            <li>登録事項に虚偽、誤記または記載漏れがあった場合</li>
                            <li>過去に本規約違反等により利用停止等の措置を受けたことがある場合</li>
                            <li>未成年であり、法定代理人（保護者等）の同意が確認できない場合（運営者が必要と判断した場合）</li>
                            <li>反社会的勢力等との関与が疑われる場合</li>
                            <li>その他、運営者が登録を相当でないと判断した場合</li>
                        </ol>
                    </li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-4">
                <h2 class="legal__heading" id="section-4">第4条（アカウント管理）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>会員は、自己の責任において、ID・パスワードその他認証情報を適切に管理するものとします。</li>
                    <li>会員の認証情報を用いて行われた一切の行為は、当該会員本人の行為とみなします。</li>
                    <li>認証情報の漏えい、盗用その他不正利用により会員または第三者に損害が生じた場合でも、運営者は運営者の故意または重過失がない限り責任を負いません。</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-5">
                <h2 class="legal__heading" id="section-5">第5条（投稿コンテンツの取扱い）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>投稿コンテンツに関する知的財産権は、当該投稿コンテンツを作成した会員または正当な権利者に帰属します。</li>
                    <li>
                        会員は、運営者に対し、本サービスの提供・運用・改善・宣伝広報（本サービス内での表示、一覧化、サムネイル生成、SNS等での紹介を含みます。）のために必要な範囲で、
                        投稿コンテンツを無償で利用（複製、公衆送信、表示、翻案、必要な範囲での改変等を含みます。）する非独占的な権利を許諾するものとします。
                    </li>
                    <li>運営者は、法令・本規約違反の疑い、第三者からの申立て、または運営上必要と判断した場合、投稿コンテンツを事前の通知なく非公開・削除・表示制限できるものとします。</li>
                    <li>会員は、投稿コンテンツについて、第三者の権利（著作権、肖像権、プライバシー権、名誉権等）を侵害しないことを保証するものとします。</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-6">
                <h2 class="legal__heading" id="section-6">第6条（禁止事項）</h2>
                <p>会員は、本サービスの利用にあたり、以下の行為をしてはなりません。</p>
                <ol class="legal__list legal__list--ordered">
                    <li>法令または公序良俗に違反する行為</li>
                    <li>犯罪行為を助長し、またはこれに関連する行為</li>
                    <li>他者への誹謗中傷、脅迫、嫌がらせ、差別、ヘイト、名誉毀損、信用毀損にあたる行為</li>
                    <li>第三者の著作権、商標権、肖像権、プライバシー権その他権利を侵害する行為</li>
                    <li>なりすまし、虚偽の情報の登録・発信</li>
                    <li>スパム行為、過度な連投、機械的なアクセス、サービスに過度な負荷を与える行為</li>
                    <li>本サービスの運営を妨害する行為、または妨害するおそれのある行為</li>
                    <li>不正アクセス、またはこれを試みる行為</li>
                    <li>本サービスのソースコード、仕組み等の解析・リバースエンジニアリング等（法令で許される場合を除く）</li>
                    <li>他者の個人情報（住所、電話番号、メールアドレス等）を本人の同意なく掲載する行為</li>
                    <li>運営者または第三者に不利益・損害を与える行為</li>
                    <li>その他、運営者が不適切と判断する行為</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-7">
                <h2 class="legal__heading" id="section-7">第7条（サービス提供の停止等）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>
                        運営者は、以下のいずれかに該当する場合、事前の通知なく本サービスの全部または一部の提供を停止または中断することができます。
                        <ol class="legal__list legal__list--ordered legal__list--nested">
                            <li>システム保守、点検、更新を行う場合</li>
                            <li>通信回線、サーバー、クラウド等の障害が発生した場合</li>
                            <li>地震、落雷、火災、停電、天災等の不可抗力により提供が困難となった場合</li>
                            <li>その他、運営者が停止・中断を必要と判断した場合</li>
                        </ol>
                    </li>
                    <li>運営者は、本条に基づく停止・中断により会員または第三者に生じた損害について、運営者の故意または重過失がない限り責任を負いません。</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-8">
                <h2 class="legal__heading" id="section-8">第8条（退会・利用制限・アカウント削除）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>会員は、運営者所定の手続によりいつでも退会できます。</li>
                    <li>
                        運営者は、会員が以下のいずれかに該当すると判断した場合、事前の通知なく、投稿コンテンツの削除、利用制限、アカウント停止または削除等の措置を行うことができます。
                        <ol class="legal__list legal__list--ordered legal__list--nested">
                            <li>本規約に違反した場合</li>
                            <li>登録事項に虚偽があった場合</li>
                            <li>支払停止、破産申立て等があった場合（将来有料機能を導入した場合を含む）</li>
                            <li>一定期間、本サービスの利用がない場合（運営者が別途定める場合）</li>
                            <li>その他、運営者が利用継続を不適当と判断した場合</li>
                        </ol>
                    </li>
                    <li>退会またはアカウント削除後も、運営上・法令上必要な範囲で情報が保持される場合があります（詳細はプライバシーポリシーによります）。</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-9">
                <h2 class="legal__heading" id="section-9">第9条（免責事項）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>運営者は、本サービスに事実上または法律上の瑕疵（安全性、正確性、完全性、有用性、継続性、特定目的適合性等）を含まないことを保証しません。</li>
                    <li>本サービス上のユーザー間のやり取り・取引・紛争は、当事者間で解決するものとし、運営者は運営者の故意または重過失がない限り責任を負いません。</li>
                    <li>運営者は、投稿コンテンツのバックアップを保証しません。必要に応じて会員自身でバックアップを行ってください。</li>
                    <li>
                        運営者が損害賠償責任を負う場合であっても、運営者の故意または重過失がある場合を除き、
                        運営者の責任は、通常生じうる直接損害の範囲に限られます（特別損害・間接損害・逸失利益等は含みません）。
                    </li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-10">
                <h2 class="legal__heading" id="section-10">第10条（規約の変更）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>運営者は、必要と判断した場合、いつでも本規約を変更できます。</li>
                    <li>本規約を変更する場合、運営者は、本サービス上での掲示その他運営者が適切と判断する方法により周知します。</li>
                    <li>変更後の本規約は、周知後に本サービスを利用した時点で同意したものとみなします。</li>
                </ol>
            </section>

            <section class="legal__section" aria-labelledby="section-11">
                <h2 class="legal__heading" id="section-11">第11条（通知または連絡）</h2>
                <p>
                    会員と運営者との通知または連絡は、運営者所定の方法により行うものとします。
                    運営者が会員に対して行う通知は、会員が登録したメールアドレスへの送信、または本サービス上での掲示等により行います。
                </p>
            </section>

            <section class="legal__section" aria-labelledby="section-12">
                <h2 class="legal__heading" id="section-12">第12条（準拠法・裁判管轄）</h2>
                <ol class="legal__list legal__list--ordered">
                    <li>本規約の解釈にあたっては、日本法を準拠法とします。</li>
                    <li>本サービスに関して紛争が生じた場合、運営者の所在地を管轄する裁判所を第一審の専属的合意管轄裁判所とします。</li>
                </ol>
            </section>

            <hr class="legal__divider">

            <aside class="legal__note" aria-label="注意事項">
                <p>
                    ※本規約は一般的なSNS投稿サービスを想定したひな形です。運用実態（解析ツール、広告、課金、外部API、未成年利用、モデレーション方針等）に合わせて調整してください。
                </p>
            </aside>
        </article>
    </div>
</div>
@endsection
