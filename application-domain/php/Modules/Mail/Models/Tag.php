<?php

declare(strict_types=1);

namespace Modules\Mail\Models;

use Carbon\Carbon;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Tag extends BaseModel
{
    use HasFactory;


    /** @var string */
    protected $table = 'tags';

    /** @var array */
    protected $fillable = [
        'name',
        'workspace_id'
    ];

    /** @var array */
    protected $withCount = [
        'subscribers'
    ];

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_tag');
    }

    /**
     * Subscribers in this tag.
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(Subscriber::class, 'tag_subscriber')->withTimestamps();
    }

    /**
     * Active subscribers in this tag.
     */
    public function activeSubscribers(): BelongsToMany
    {
        return $this->subscribers()
            ->whereNull('unsubscribed_at')
            ->withTimestamps();
    }
}
