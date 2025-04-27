<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsi';
    protected $primaryKey = 'id_provinsi';
    public $incrementing = false;
    protected $fillable = ['id_provinsi', 'nama_provinsi', 'created_at', 'updated_at'];
    public function data_pekerja():HasMany {
        return $this->hasMany(data_pekerja::class, 'id_provinsi', 'id_provinsi');
    }

}
