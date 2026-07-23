<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateExperience extends Model
{
    use HasFactory;

    protected $table = 'candidate_experience';

    protected $fillable = [
        'candidate_id',
        'employer',
        'job_title',
        'start_date',
        'end_date',
        'responsibilities',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    public function candidateProfile(): BelongsTo
    {
        return $this->belongsTo(CandidateProfile::class, 'candidate_id');
    }

    public function getIsCurrentAttribute(): bool
    {
        return is_null($this->end_date);
    }
}
