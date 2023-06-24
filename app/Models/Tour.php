<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = ['name', 'travel_id', 'start_date', 'end_date', 'price'];

    public function price(): Attribute
    {
        return Attribute::make(
            get : fn ($value) => $value / 100,
            set : fn ($value) => $value * 100
        );
    }
}
