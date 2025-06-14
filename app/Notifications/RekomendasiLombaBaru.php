<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RekomendasiLombaBaru extends Notification
{
    use Queueable;

    protected $pengusul;
    protected $lomba;

    public function __construct($pengusul, $lomba)
    {
        $this->pengusul = $pengusul;
        $this->lomba = $lomba;
    }

    public function via($notifiable)
    {
        return ['database']; // Disimpan ke DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Rekomendasi Lomba Baru',
            'pesan' => "Kamu mendapatkan rekomendasi lomba baru dari {$this->pengusul}: {$this->lomba}",
            'url' => url('/lomba/'),
        ];
    }
}