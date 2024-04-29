<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skripsi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'skripsi';

    protected $fillable = [
        'judul',
        'nama',
        'nim',
        'angkatan',
        'jurusan',
    ];

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
