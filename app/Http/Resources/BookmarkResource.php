<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->skripsi->id,
            'judul' => $this->skripsi->judul,
            'angkatan' => $this->skripsi->angkatan,
            // tambahkan kolom lain dari model Bookmark jika perlu
        ];
    }
}
