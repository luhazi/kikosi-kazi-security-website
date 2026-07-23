<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_LABELS = [
        'submitted'            => 'Submitted',
        'under_review'         => 'Under Review',
        'shortlisted'          => 'Shortlisted',
        'interview_scheduled'  => 'Interview Scheduled',
        'successful'           => 'Successful',
        'rejected'             => 'Rejected',
        'withdrawn'            => 'Withdrawn',
    ];

    protected $fillable = [
        'job_id',
        'candidate_id',
        'cover_letter',
        'status',
        'applied_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'applied_at' => 'datetime',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(CandidateProfile::class, 'candidate_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ApplicationStatusLog::class);
    }
}
