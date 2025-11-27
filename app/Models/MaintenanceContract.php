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

    protected static function booted() 
    {
        static::created(function (MaintenanceContract $contract) {

            $interval = $contract->interval;

            // Fälligkeitsdatum: Startdatum + Intervall (Monate)
            $dueDate = $contract->start_date->copy()->addMonths($interval->interval_months);

            // Buchungsfenster
            $bookingWindowStart = $dueDate->copy()->subDays($interval->booking_window_days);
            $bookingWindowEnd   = $dueDate->copy();

            // Erstellt automatisch den ersten Task
            $contract->tasks()->create([
                'company_id'            => $contract->company_id,
                'due_date'              => $dueDate,
                'booking_window_start'  => $bookingWindowStart,
                'booking_window_end'    => $bookingWindowEnd,
                'status'                => 'pending',
            ]);
        });
    }

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
