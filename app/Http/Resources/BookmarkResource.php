<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'skripsi' => [
                'id' => $this->skripsi->id,
                'judul' => $this->skripsi->judul,
                'nama' => $this->skripsi->nama,
                'nim' => $this->skripsi->nim,
                'jurusan' => $this->skripsi->jurusan,
                'angkatan' => $this->skripsi->angkatan,
            ],
            // tambahkan kolom lain dari model Bookmark jika perlu
        ];
    }
}
