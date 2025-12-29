<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerResponse;

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

    public function task()
    {
        return $this->belongsTo(MaintenanceTask::class, 'maintenance_task_id');
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

    public function accept(): void
    {
        // 1) Relationen explizit laden
        $this->load('task.contract.customer', 'task.contract.company');
        
        // 2) Diesen Vorschlag markieren
        $this->update([
            'status' => 'accepted'
        ]);

        $task = $this->task;
        
        // Falls task nicht existiert (sollte nicht vorkommen)
        if (!$task) {
            throw new \Exception('AppointmentProposal hat keine zugehörige MaintenanceTask');
        }

        $contract = $task->contract;
        $customer = $contract->customer;
        $company = $contract->company;

        // 2) Termin anlegen
        $appointment = Appointment::create([
            'company_id' => $company->id,
            'customer_id' => $customer->id,
            'maintenance_task_id' => $task->id,
            'technician_id' => null, // später Matching
            'starts_at' => $this->proposed_starts_at,
            'ends_at' => $this->proposed_ends_at,
            'status' => 'confirmed',
        ]);

        // 3) Task updaten
        $task->update([
            'status' => 'confirmed',
            'appointment_id' => $appointment->id,
        ]);

        // 4) Alle anderen Vorschläge verfallen lassen
        $this->task->proposals()
            ->where('id', '!=', $this->id)
            ->update(['status' => 'expired']);
    }

    public function reject(?string $comment = null): void
    {
        DB::transaction(function () use ($comment) {

            // Proposal auf rejected setzen
            $this->update([
                'status' => 'rejected',
            ]);

            $task = $this->task;
            $contract = $task->contract;

            // Response speichern
            CustomerResponse::create([
                'company_id' => $this->company_id,
                'customer_id' => $this->customer_id,
                'appointment_proposal_id' => $this->id,
                'response' => 'rejected',
                'comment' => $comment,
            ]);

            // Falls bereits ein Appointment existiert, machen wir nichts weiter
            if ($task->appointment_id) {
                return;
            }

            // Wenn es keine offenen Vorschläge mehr gibt (pending) -> Task zurück auf pending
            $hasOpenProposals = $task->proposals()
                ->where('status', 'pending')
                ->exists();

            if (!$hasOpenProposals) {
                $task->update([
                    'status' => 'pending',
                ]);
            }
        });
    }

}
