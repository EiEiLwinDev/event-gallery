<?php

namespace App\Models;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description',
        'date',
        'time',
        'location'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
 
}
