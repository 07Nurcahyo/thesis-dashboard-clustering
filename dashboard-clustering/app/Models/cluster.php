<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class cluster extends Model
{
    use HasFactory;

    protected $table = 'cluster';
    protected $primaryKey = 'cluster';
    // public $incrementing = false;
    protected $fillable = ['cluster', 'nama_cluster', 'created_at', 'updated_at'];
    public function data_pekerja_cluster():HasMany {
        return $this->hasMany(data_pekerja_cluster::class, 'cluster', 'cluster');
    }
    public function iterasi_jarak_default():HasMany {
        return $this->hasMany(iterasi_jarak_default::class, 'cluster', 'cluster');
    }
    public function iterasi_cluster_baru_default():HasMany {
        return $this->hasMany(iterasi_cluster_baru_default::class, 'cluster', 'cluster');
    }
}
