<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeFooter extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'description'];

    // Relasi ke Footer
    public function footers()
    {
        return $this->hasMany(Footer::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('type');
        });
    }
}
