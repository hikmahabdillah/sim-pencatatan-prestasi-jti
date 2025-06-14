<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\LombaModel;

class AdminRekomendasiNotification extends Notification
{
    protected $idLomba;
    protected $namaDospem;

    public function __construct($idLomba, $namaDospem)
    {
        $this->idLomba = $idLomba;
        $this->namaDospem = $namaDospem;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $lomba = \App\Models\LombaModel::find($this->idLomba);

        return [
            'type' => 'dospem',
            'title' => 'Rekomendasi Dosen Pembimbing',
            'pesan' => "Kamu mendapat rekomendasi dosen pembimbing {$this->namaDospem} untuk lomba " . ($lomba->nama_lomba ?? 'Nama Lomba Tidak Ditemukan') . ".",
            'id_lomba' => $this->idLomba,
            'url' => $lomba ? route('lomba.showMahasiswa', ['id' => $this->idLomba]) : '#',
        ];
    }
}