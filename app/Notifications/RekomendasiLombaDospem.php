<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RekomendasiLombaDospem extends Notification
{
    protected $idLomba;
    protected $namaDosen;

    public function __construct($idLomba, $namaDosen)
    {
        $this->idLomba = $idLomba;
        $this->namaDosen = $namaDosen;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $lomba = \App\Models\LombaModel::find($this->idLomba);

        return [
            'type' => 'rekomendasi_lomba_dosen',
            'title' => 'Rekomendasi dari Dosen',
            'pesan' => "Dosen {$this->namaDosen} merekomendasikan kamu untuk lomba {$lomba->nama_lomba}.",
            'id_lomba' => $this->idLomba,
            'url' => route('lomba.showMahasiswa', ['id' => $this->idLomba]),
        ];
    }
}
