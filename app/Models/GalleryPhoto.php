<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    use HasFactory;

    /** Album / activity categories used to group and filter photos. */
    public const CATEGORIES = [
        'operations'   => 'Operations & Deployments',
        'training'     => 'Training & Development',
        'events'       => 'Events & Ceremonies',
        'clients'      => 'Client Sites',
        'csr'          => 'Community & CSR',
        'awards'       => 'Awards & Recognition',
        'team'         => 'Our Team',
    ];

    protected $fillable = [
        'title',
        'caption',
        'image_path',
        'category',
        'event_date',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'event_date'   => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /** Human label for this photo's category. */
    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? 'General';
    }
}
