<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'no_of_person',
        'adult',
        'child',
        'package_id',
        'book_camp'
    ];
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    
}
