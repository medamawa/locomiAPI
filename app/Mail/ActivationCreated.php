<?php

namespace App\Mail;

use App\Models\Activation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $activation;

    public function __construct(Activation $activation)
    {
        $this->activation = $activation;
    }

    // メール認証にて、メール本文に付与する確認用URL情報を設定
    public function build()
    {
        $apiUrl = config('app.url');
        
        return $this->markdown('emails.activations.created')
        ->with([
            'url' => $apiUrl . "/users/me/verify?code={$this->activation->code}",
            'user_name' => $this->activation->user_name,
        ]);
    }
}
