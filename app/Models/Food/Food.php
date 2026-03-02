<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = "foods";
    // protected $primaryKey = "id";
    protected $fillable = [
        "name",
        "price",
        "category",
        "description",
        "image",
    ];

    public $timestamps = true;
}
