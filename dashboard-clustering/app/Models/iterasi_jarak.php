<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class iterasi_jarak extends Model
{
    use HasFactory;

    protected $table = 'iterasi_jarak';
    protected $primaryKey = 'id_iterasi_jarak';
    protected $fillable = [
        'id_iterasi_jarak',
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
    public function iterasi_sse(): HasMany
    {
        return $this->hasMany(iterasi_sse::class, 'id_iterasi_jarak', 'id_iterasi_jarak');
    }
}
