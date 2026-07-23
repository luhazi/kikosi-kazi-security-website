<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /** Curated Bootstrap Icons for the service picker (icon name => friendly label). */
    public const ICON_OPTIONS = [
        'shield-check'    => 'Shield — Security',
        'shield-lock'     => 'Shield Lock — Protection',
        'people'          => 'People — Team / Guards',
        'person-badge'    => 'Badge — Staff / Officers',
        'person-check'    => 'Person Check — Recruitment',
        'mortarboard'     => 'Graduation — Training',
        'briefcase'       => 'Briefcase — Business',
        'clipboard-check' => 'Checklist — Compliance',
        'calculator'      => 'Calculator — Payroll',
        'umbrella'        => 'Umbrella — Insurance',
        'heart-pulse'     => 'Heart Pulse — Life / Medical',
        'car-front'       => 'Car — Motor',
        'truck'           => 'Truck — Fleet',
        'house'           => 'House — Property',
        'fire'            => 'Fire — Fire Cover',
        'building'        => 'Building — Facilities',
        'stars'           => 'Sparkle — Cleaning',
        'droplet'         => 'Droplet — Sanitisation',
        'recycle'         => 'Recycle — Waste',
        'tools'           => 'Tools — Maintenance',
        'camera-video'    => 'Camera — CCTV',
        'gear'            => 'Gear — Operations',
        'bank'            => 'Bank — Finance',
        'geo-alt'         => 'Location — Site',
        'clock'           => 'Clock — 24/7',
        'award'           => 'Award — Quality',
        'graph-up-arrow'  => 'Growth — Results',
    ];

    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'icon',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** The icon to display — the chosen one, or a sensible default for the category. */
    public function displayIcon(): string
    {
        if (! empty($this->icon)) {
            return $this->icon;
        }

        return [
            'security'  => 'shield-check',
            'hr'        => 'people',
            'insurance' => 'umbrella',
            'cleaning'  => 'stars',
        ][$this->category] ?? 'gear';
    }
}
