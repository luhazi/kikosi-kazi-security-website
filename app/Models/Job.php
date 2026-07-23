<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    /** Filterable employment categories (key => label). */
    public const EMPLOYMENT_TYPES = [
        'freelance'  => 'Freelance',
        'full_time'  => 'Full Time',
        'internship' => 'Internship',
        'part_time'  => 'Part Time',
        'temporary'  => 'Temporary',
    ];

    /** Suggested departments (admin can still type a custom one). */
    public const DEPARTMENTS = [
        'Security Operations',
        'Human Resources',
        'Insurance & Risk',
        'Cleaning & Facilities',
        'Finance & Accounts',
        'Administration',
        'Information & Communication Technology (ICT)',
        'Procurement & Logistics',
        'Sales & Marketing',
        'Operations',
        'Customer Service',
        'Legal & Compliance',
        'Health, Safety & Environment (HSE)',
        'Training & Development',
        'Fleet & Transport',
        'Projects & Engineering',
    ];

    /** All Tanzania regions (admin can still type a more specific location). */
    public const TZ_REGIONS = [
        'Arusha', 'Dar es Salaam', 'Dodoma', 'Geita', 'Iringa', 'Kagera', 'Katavi',
        'Kigoma', 'Kilimanjaro', 'Lindi', 'Manyara', 'Mara', 'Mbeya', 'Morogoro',
        'Mtwara', 'Mwanza', 'Njombe', 'Pemba North', 'Pemba South', 'Pwani (Coast)',
        'Rukwa', 'Ruvuma', 'Shinyanga', 'Simiyu', 'Singida', 'Songwe', 'Tabora',
        'Tanga', 'Zanzibar North', 'Zanzibar South & Central', 'Zanzibar Urban & West',
    ];

    protected $fillable = [
        'title',
        'department',
        'discipline',
        'location',
        'vacancies',
        'description',
        'requirements',
        'salary_min',
        'salary_max',
        'deadline',
        'status',
        'job_type',
        'employment_type',
        'client_name',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'deadline'   => 'date',
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')
                     ->where('deadline', '>=', Carbon::today());
    }

    /** Human label for the employment type (defaults to Full Time). */
    public function employmentTypeLabel(): string
    {
        return self::EMPLOYMENT_TYPES[$this->employment_type] ?? 'Full Time';
    }
}
