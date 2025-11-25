<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceContract extends Model
{
    /** @use HasFactory<\Database\Factories\MaintenanceContractFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'customer_id',
        'maintenance_interval_id',
        'start_date',
        'active',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function interval()
    {
        return $this->belongsTo(MaintenanceInterval::class, 'maintenance_interval_id');
    }

    public function tasks()
    {
        return $this->hasMany(MaintenanceTask::class);
    }
}
