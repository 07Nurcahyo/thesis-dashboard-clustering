<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class iterasi_cluster_baru extends Model
{
    use HasFactory;

    protected $table = 'iterasi_cluster_baru';
    protected $primaryKey = 'id_iterasi_cluster_baru';
    protected $fillable =[
        'id_iterasi_cluster_baru',
        'id_iterasi_sse', //fk
        'cluster', //fk
        'garis_kemiskinan',
        'upah_minimum',
        'pengeluaran',
        'rr_upah',
        'created_at',
        'updated_at',
    ];

    public function iterasi_sse(): BelongsTo
    {
        return $this->belongsTo(iterasi_sse::class, 'id_iterasi_sse', 'id_iterasi_sse');
    }
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(cluster::class, 'cluster', 'cluster');
    }
}
