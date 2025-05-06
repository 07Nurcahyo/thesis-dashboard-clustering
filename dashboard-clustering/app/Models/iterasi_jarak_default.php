<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class iterasi_jarak_default extends Model
{
    use HasFactory;

    protected $table = 'iterasi_jarak_default';
    protected $primaryKey = 'id_iterasi_jarak_default';
    protected $fillable = [
        'id_iterasi_jarak_default',
        'id_provinsi', //fk
        'tahun',
        'jarak_c1',
        'jarak_c2',
        'jarak_c3',
        'c_terkecil',
        'cluster', //fk
        'jarak_minimum',
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
    public function iterasi_sse_default(): HasMany
    {
        return $this->hasMany(iterasi_sse_default::class, 'id_iterasi_jarak_default', 'id_iterasi_jarak_default');
    }
}
