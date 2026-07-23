<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateProfile extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'alternate_phone',
        'gender',
        'date_of_birth',
        'national_id',
        'nationality',
        'address',
        'city',
        'region',
        'bio',
        'profile_photo',
        'completeness_pct',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth'    => 'date',
            'completeness_pct' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function education(): HasMany
    {
        return $this->hasMany(CandidateEducation::class, 'candidate_id');
    }

    public function experience(): HasMany
    {
        return $this->hasMany(CandidateExperience::class, 'candidate_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'candidate_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'candidate_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->user->name ?? '';
    }
}
