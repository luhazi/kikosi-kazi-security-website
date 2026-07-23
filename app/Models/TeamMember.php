<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'department',
        'bio',
        'photo_path',
        'email',
        'linkedin',
        'is_ceo',
        'ceo_message',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_ceo'     => 'boolean',
            'is_active'  => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo_path
            ? asset('storage/' . $this->photo_path)
            : asset('images/avatar-placeholder.png');
    }
}
