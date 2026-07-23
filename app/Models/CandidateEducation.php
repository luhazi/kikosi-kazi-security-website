<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateEducation extends Model
{
    use HasFactory;

    protected $table = 'candidate_education';

    protected $fillable = [
        'candidate_id',
        'institution',
        'qualification',
        'field_of_study',
        'year_completed',
        'grade',
    ];

    protected function casts(): array
    {
        return [
            'year_completed' => 'integer',
        ];
    }

    public function candidateProfile(): BelongsTo
    {
        return $this->belongsTo(CandidateProfile::class, 'candidate_id');
    }
}
