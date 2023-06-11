<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Travel extends Model
{
    use HasFactory, HasUuids, Sluggable;

    protected $table = 'travels';
    protected $fillable = ['name', 'is_public','description', 'slug', 'number_of_days'];


    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class,'travel_id');
    }

    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected function numberOfNights(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['number_of_days'] - 1,
        );
    }
}
