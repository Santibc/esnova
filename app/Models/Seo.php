<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;

    protected $table = 'seo';

    protected $fillable = [
        'page_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'og_url',
        'og_site_name',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_site',
        'twitter_creator',
        'schema_markup',
        'focus_keyword',
        'breadcrumb_title',
        'sitemap_include',
        'sitemap_priority',
        'sitemap_changefreq',
        'is_active',
        'seo_score',
        'seo_analysis'
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'sitemap_include' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
