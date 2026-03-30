<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    /**
     * Testimonials are global and stored in the central database.
     */
    protected $connection = 'central';

    protected $fillable = [
        'name',
        'user_id',
        'tenant_id',
        'content',
        'rating',
        'image_url',
        'is_active',
        'source',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
    ];
}
