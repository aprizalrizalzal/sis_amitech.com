<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusService extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'description'];

    // Relasi ke Service
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('status');
        });
    }
}
