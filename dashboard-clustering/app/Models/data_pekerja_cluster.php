<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class data_pekerja_cluster extends Model
{
    use HasFactory;

    protected $table = 'data_pekerja_cluster';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'cluster', //fk
        'id_provinsi', //fk
        'tahun',
        'garis_kemiskinan',
        'upah_minimum',
        'pengeluaran',
        'rr_upah',
        'created_at',
        'updated_at',
    ];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(provinsi::class, 'id_provinsi', 'id_provinsi');
    }
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(cluster::class, 'cluster', 'cluster');
    }
}
