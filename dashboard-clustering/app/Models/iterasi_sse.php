<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iterasi_sse extends Model
{
    use HasFactory;

    protected $table = 'iterasi_sse';
    protected $primaryKey = 'id_iterasi_sse';
    protected $fillable = [
        'id_iterasi_sse',
        'id_iterasi_jarak', //fk
        'sse',
        'created_at',
        'updated_at',
    ];

    public function iterasi_jarak()
    {
        return $this->belongsTo(iterasi_jarak::class, 'id_iterasi_jarak', 'id_iterasi_jarak');
    }
    public function iterasi_cluster_baru()
    {
        return $this->hasMany(iterasi_cluster_baru::class, 'id_iterasi_sse', 'id_iterasi_sse');
    }
}
