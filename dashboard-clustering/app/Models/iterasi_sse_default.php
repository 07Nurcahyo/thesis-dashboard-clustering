<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iterasi_sse_default extends Model
{
    use HasFactory;

    protected $table = 'iterasi_sse_default';
    protected $primaryKey = 'id_iterasi_sse_default';
    protected $fillable = [
        'id_iterasi_sse_default',
        'id_iterasi_jarak_default', //fk
        'sse',
        'created_at',
        'updated_at',
    ];

    public function iterasi_jarak_default()
    {
        return $this->belongsTo(iterasi_jarak_default::class, 'id_iterasi_jarak_default', 'id_iterasi_jarak_default');
    }
}
