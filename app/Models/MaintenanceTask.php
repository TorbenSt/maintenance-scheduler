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

    protected static function booted()
    {
        static::updated(function (MaintenanceTask $task) {
            if ($task->isDirty('status') && $task->status === 'completed') {
                $task->generateNextTask();
            }
        });
    }

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

    public function generateNextTask()
    {
        // Contract inaktiv? -> nichts tun
        if (!$this->contract->active) {
            return;
        }

        $interval = $this->contract->interval;

        $nextDueDate = $this->due_date->copy()->addMonths($interval->interval_months);

        return $this->contract->tasks()->create([
            'company_id' => $this->company_id,
            'due_date' => $nextDueDate,
            'booking_window_start' => $nextDueDate->copy()->subDays($interval->booking_window_days),
            'booking_window_end' => $nextDueDate->copy(),
            'status' => 'pending',
        ]);
    }

    public function generateProposals(int $count = 3)
    {
        $interval = $this->contract->interval;
        $customer = $this->contract->customer;
        $company  = $this->contract->company;

        $duration = $interval->estimated_duration_minutes;

        // MVP: Startzeiten werden ab booking_window_start täglich vorgeschlagen
        $startDate = $this->booking_window_start->copy()->setTime(10, 0);

        for ($i = 0; $i < $count; $i++) {

            $start = $startDate->copy()->addDays($i);
            $end   = $start->copy()->addMinutes($duration);

            $this->proposals()->create([
                'company_id'            => $company->id,
                'customer_id'           => $customer->id,
                'proposed_starts_at'    => $start,
                'proposed_ends_at'      => $end,
                'token'                 => bin2hex(random_bytes(16)),
                'status'                => 'pending',
            ]);
        }

        // Task-Status auf "proposed" setzen
        $this->update(['status' => 'proposed']);
    }

}
