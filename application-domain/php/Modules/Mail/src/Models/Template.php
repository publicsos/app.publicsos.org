<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Models;

use Carbon\Carbon;
use Database\Factories\TemplateFactory;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Template extends BaseModel
{
    use HasFactory;

    protected static function newFactory()
    {
        return TemplateFactory::new();
    }

    protected $table = 'templates';

    protected $fillable = [
            "workspace_id",
            "name",
            "thumbnail",
            "content",
            "premium",
            "rating",
            "previewUrl",
            "slug",
            "votes",
            "type",
        ];
    

    /**
     * Campaigns using this template
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function isInUse(): bool
    {
        return $this->campaigns()->count() > 0;
    }

    protected $casts = [
        'votes' => 'array',
        'content' => 'string',
    ];

    protected $content;

    public  function content(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
