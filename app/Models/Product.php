<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'active',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function hasSales(): bool
    {
        return $this->saleItems()->exists();
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'empanada' => 'Empanada',
            'papa_rellena' => 'Papa Rellena',
            default => ucfirst($this->category),
        };
    }
}
