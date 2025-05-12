<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class data_pekerja extends Model
{
    use HasFactory;

    protected $table = 'data_pekerja';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
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
    public function cluster(): HasMany
    {
        return $this->hasMany(iterasi_cluster_awal::class, 'id_data_pekerja', 'id');
    }
}
