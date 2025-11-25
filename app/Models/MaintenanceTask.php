<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceTask extends Model
{
    /** @use HasFactory<\Database\Factories\MaintenanceTaskFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'maintenance_contract_id',
        'due_date',
        'booking_window_start',
        'booking_window_end',
        'status',
        'appointment_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'booking_window_start' => 'date',
        'booking_window_end' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function contract()
    {
        return $this->belongsTo(MaintenanceContract::class, 'maintenance_contract_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function proposals()
    {
        return $this->hasMany(AppointmentProposal::class);
    }
}
