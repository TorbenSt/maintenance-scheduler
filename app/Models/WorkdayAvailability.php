<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkdayAvailability extends Model
{
    /** @use HasFactory<\Database\Factories\WorkdayAvailabilityFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'technician_id',
        'date',
        'max_appointments',
        'is_day_off',
    ];

    protected $casts = [
        'date' => 'date',
        'is_day_off' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
