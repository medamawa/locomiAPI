@component('mail::message')

@if (!empty($user_name))
    {{ $user_name }} さん
@endif

**以下の認証リンクをクリックしてください。**
@component('mail::button', ['url' => $url])
メールアドレスを認証する
@endcomponent

@if (!empty($url))
##### 「ログインして本登録を完了する」ボタンをクリックできない場合は、下記のURLをコピーしてブラウザに貼り付けてください。
##### {{ $url }}
@endif

---

※もしこのメールに覚えがない場合は破棄してください。

---

Thanks,<br>
{{ config('app.name') }}
@endcomponent
