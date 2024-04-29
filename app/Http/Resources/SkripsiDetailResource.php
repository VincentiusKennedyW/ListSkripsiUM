<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkripsiDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'nama' => $this->nama,
            'nim' => $this->nim,
            'jurusan' => $this->jurusan,
            'angkatan' => $this->angkatan,
        ];
    }
}
