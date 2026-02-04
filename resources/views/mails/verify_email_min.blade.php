<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メールアドレス確認</title>
</head>
<body>
<p>{{ $user->username ?? 'ユーザー様' }}</p>

<p>
    この度はご登録ありがとうございます。<br>
    以下のURLをクリックして、メールアドレスの確認を完了してください。
</p>

<p>
    <a href="{{ $url }}">{{ $url }}</a>
</p>

<p>
    このメールに心当たりがない場合は、破棄してください。
</p>

<hr>
<p>
    DIY LAB<br>
    https://diy-lab.com
</p>
</body>
</html>
