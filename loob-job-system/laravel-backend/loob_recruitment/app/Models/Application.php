<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'full_name',
        'phone', 
        'email',
        'position',
        'work_experience',
        'status',
        'job_listing_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'applicant_name',
        'applicant_email', 
        'cover_letter'
    ];

    // Accessors for Flutter compatibility
    public function getApplicantNameAttribute()
    {
        return $this->full_name;
    }

    public function getApplicantEmailAttribute() 
    {
        return $this->email;
    }

    public function getCoverLetterAttribute()
    {
        return $this->work_experience;
    }

    /**
     * Get the job listing that this application is for
     */
    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }
}
