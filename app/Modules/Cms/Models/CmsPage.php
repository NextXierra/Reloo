<?php

namespace App\Modules\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'gjs_data',
        'html_content',
        'css_content',
        'is_homepage',
        'is_active',
    ];

    protected $casts = [
        'gjs_data' => 'array',
        'is_homepage' => 'boolean',
        'is_active' => 'boolean',
    ];
}
