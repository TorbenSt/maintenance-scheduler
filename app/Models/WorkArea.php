<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkArea extends Model
{
    /** @use HasFactory<\Database\Factories\WorkAreaFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'center_lat',
        'center_lng',
        'radius_km',
    ];

    protected $casts = [
        'center_lat' => 'decimal:7',
        'center_lng' => 'decimal:7',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
