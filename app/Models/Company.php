<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'contact_email',
        'contact_phone',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function maintenanceIntervals()
    {
        return $this->hasMany(MaintenanceInterval::class);
    }

    public function maintenanceContracts()
    {
        return $this->hasMany(MaintenanceContract::class);
    }

    public function technicians()
    {
        return $this->hasMany(Technician::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function appointmentProposals()
    {
        return $this->hasMany(AppointmentProposal::class);
    }

    public function customerResponses()
    {
        return $this->hasMany(CustomerResponse::class);
    }

    public function maintenanceTasks()
    {
        return $this->hasMany(MaintenanceTask::class);
    }

    public function workAreas()
    {
        return $this->hasMany(WorkArea::class);
    }

    public function workdayAvailabilities()
    {
        return $this->hasMany(WorkdayAvailability::class);
    }
}
