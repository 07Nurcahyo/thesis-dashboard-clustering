<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class iterasi_cluster_baru_default extends Model
{
    use HasFactory;

    protected $table = 'iterasi_cluster_baru_default';
    protected $primaryKey = 'id_iterasi_cluster_baru_default';
    protected $fillable =[
        'id_iterasi_cluster_baru_default',
        'id_iterasi_sse_default', //fk
        'cluster', //fk
        'garis_kemiskinan',
        'upah_minimum',
        'pengeluaran',
        'rr_upah'
    ];

    public function iterasi_sse_default(): BelongsTo
    {
        return $this->belongsTo(iterasi_sse_default::class, 'id_iterasi_sse_default', 'id_iterasi_sse_default');
    }
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(cluster::class, 'cluster', 'cluster');
    }
}
