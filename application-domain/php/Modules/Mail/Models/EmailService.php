<?php

declare(strict_types=1);

namespace Modules\Mail\Models;

use Carbon\Carbon;
use Database\Factories\EmailServiceFactory;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Workflow\Models\Workflow;

/**
 * @property int $id
 * @property int $workspace_id
 * @property string|null $name
 * @property int $type_id
 * @property array $settings
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property EmailServiceType $type
 * @property EloquentCollection $campaigns
 *
 * @method static EmailServiceFactory factory
 */
class EmailService extends BaseModel
{
    use HasFactory;


    /** @var string */
    protected $table = 'email_services';

    /** @var array */
    protected $fillable = [
        'name',
        'type_id',
        'settings',
    ];

    /** @return array */
    protected function casts(): array
    {
        return [
            'id' => 'int',
            'workspace_id' => 'int',
            'type_id' => 'int'
        ];
    }


    public function type(): BelongsTo
    {
        return $this->belongsTo(EmailServiceType::class, 'type_id');
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'email_service_id');
    }

     public function automations(): HasMany
     {
         return $this->hasMany(Workflow::class, 'email_service_id');
     }

    public function setSettingsAttribute(array $data): void
    {
        $this->attributes['settings'] = encrypt(json_encode($data));
    }

    public function getSettingsAttribute(string $value): array
    {
        return json_decode(decrypt($value), true);
    }

    public function getInUseAttribute(): bool
    {

        return (bool)$this->campaigns()->count();
    }
}
