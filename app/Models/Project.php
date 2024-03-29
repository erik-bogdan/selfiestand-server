<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_title',
        'uuid',
        'project_date',
        'frame_image',
        'is_live_event'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'project_date' => 'datetime',
        'is_live_event' => 'boolean'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
