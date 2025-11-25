<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'company_name',
        'email',
        'phone',
        'street',
        'postal_code',
        'city',
        'country',
        'lat',
        'lng',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function contracts()
    {
        return $this->hasMany(MaintenanceContract::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function responses()
    {
        return $this->hasMany(CustomerResponse::class);
    }

    public function proposals()
    {
        return $this->hasMany(AppointmentProposal::class);
    }
}
