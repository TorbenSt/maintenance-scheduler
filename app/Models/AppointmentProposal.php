<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentProposal extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentProposalFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'maintenance_task_id',
        'customer_id',
        'appointment_id',
        'proposed_starts_at',
        'proposed_ends_at',
        'token',
        'status',
    ];

    protected $casts = [
        'proposed_starts_at' => 'datetime',
        'proposed_ends_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function maintenanceTask()
    {
        return $this->belongsTo(MaintenanceTask::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function customerResponse()
    {
        return $this->hasOne(CustomerResponse::class);
    }
}
