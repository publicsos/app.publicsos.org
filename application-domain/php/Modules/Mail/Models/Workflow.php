<?php
declare(strict_types=1);
namespace Modules\Mail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workflow extends BaseModel
{
    private array $data = []; // Explicit property type

    protected $table = 'workflows';

    protected  $fillable = [
        'name',
        'email_service_id',
    ];

    //belongs to
    public function emailService(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(EmailService::class);
    }

    /**
     * Get the tasks associated with the workflow.
     *
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(\Modules\Mail\Tasks\Task::class);
    }

    /**
     * Get the triggers associated with the workflow.
     *
     * @return HasMany
     */
    public function triggers(): HasMany
    {
        return $this->hasMany(\Modules\Mail\Triggers\Trigger::class);
    }

    /**
     * Get the logs associated with the workflow.
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(\Modules\Mail\Loggers\WorkflowLog::class);
    }

    /**
     * Get a trigger by its class type.
     *
     * @param string $class
     * @return Model|null
     */
    public function getTriggerByClass(string $class): ?Model
    {
        return $this->triggers()->where('type', $class)->first();
    }
}
