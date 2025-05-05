<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'reservation_date',
        'reservation_time',
        'guest_count',
        'table_id',
        'status',
        'notes',
        'employee_id',
    ];

    // Relación: una reserva pertenece a una mesa
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Relación: una reserva fue registrada por un empleado
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}