<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archives extends Model
{
    use HasFactory;
    protected $fillable = [
        'cif',
        'rekening_pinjaman',
        'nama',
        'wilayah',
        'user_id',
        'plafond',
        'kategori',
        'file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
