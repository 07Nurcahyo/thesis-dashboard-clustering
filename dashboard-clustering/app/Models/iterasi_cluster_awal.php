<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iterasi_cluster_awal extends Model
{
    use HasFactory;

    protected $table = 'iterasi_cluster_awal';
    protected $primaryKey = 'id_iterasi_cluster_awal';
    protected $fillable = [
        'id_iterasi_cluster_awal',
        'id_data_pekerja', //fk
        'cluster', //fk
        'garis_kemiskinan',
        'upah_minimum',
        'pengeluaran',
        'rr_upah'
    ];
    public function data_pekerja()
    {
        return $this->belongsTo(data_pekerja::class, 'id_data_pekerja', 'id');
    }
    public function cluster()
    {
        return $this->belongsTo(cluster::class, 'cluster', 'cluster');
    }
}
