<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'capacity',
        'location',
        'status',
    ];

    // Relación: una mesa puede tener muchas reservas (historial)
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}