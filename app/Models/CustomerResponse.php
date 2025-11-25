<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerResponse extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerResponseFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'customer_id',
        'appointment_proposal_id',
        'response',
        'comment',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function appointmentProposal()
    {
        return $this->belongsTo(AppointmentProposal::class);
    }
}
