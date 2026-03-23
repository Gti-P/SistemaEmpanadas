<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'address',
        'city',
        'phone',
        'is_counter_client',
    ];

    protected $casts = [
        'is_counter_client' => 'boolean',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public static function counterClient()
    {
        return static::where('is_counter_client', true)->first();
    }
}
