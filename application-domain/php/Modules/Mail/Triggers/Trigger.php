<?php
namespace Modules\Mail\Triggers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Modules\Mail\DataBuses\DataBus;
use Modules\Mail\DataBuses\DataBussable;
use Modules\Mail\Fields\Fieldable;
use Modules\Mail\Jobs\ProcessWorkflow;
use Modules\Mail\Loggers\WorkflowLog;
use Modules\Mail\Models\BaseModel;
use Modules\Mail\Tasks\Task;
use function Laravel\Prompts\warning;


class Trigger extends BaseModel
{
    use DataBussable, Fieldable;

    protected $table = 'triggers';

    public $family = 'trigger';

    public static $icon = '<i class="fas fa-question"></i>';

    protected $fillable = [
        'workflow_id',
        'parent_id',
        'type',
        'name',
        'data',
        'node_id',
        'pos_x',
        'pos_y',
    ];

    public static $output = [];
    public static $fields = [];
    public static $fields_definitions = [];

    protected $casts = [
        'data_fields' => 'array',
    ];

    public static $commonFields = [
        'Description' => 'description',
    ];

    public function children()
    {
        return $this->morphMany(Task::class, 'parentable');

    }

    /**
     * Return Collection of models by type.
     *
     * @param  array  $attributes
     * @param  null  $connection
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $entryClassName = '\\'.Arr::get((array) $attributes, 'type');

        if (class_exists($entryClassName)
            && is_subclass_of($entryClassName, self::class)
        ) {
            $model = new $entryClassName();
        } else {
            $model = $this->newInstance();
        }

        info("The attributes -> " . json_encode($attributes));

        $model->exists = true;
        $model->setRawAttributes((array) $attributes, true);
        $model->setConnection($connection ?: $this->connection);

        return $model;
    }

    public function start(Model $model, array $data = [])
    {

        info('Starting Trigger with data '.json_encode($data));
        $log = WorkflowLog::createHelper($this->workflow, $model, $this);
        $dataBus = new DataBus($data);
        try {
            $this->checkConditions($model, $dataBus);
        } catch (\Exception $e) {
            $dataBus = new DataBus($data);
            $log->setError($e->getMessage() . 'stefan', $dataBus);
            exit;
        }

        ProcessWorkflow::dispatch($model, $dataBus, $this, $log);
    }

    /**
     * @throws \Exception
     */
    public function checkConditions(Model $model, DataBus $data): bool
    {
        //TODO: This needs to get smoother :(
        if (empty($this->conditions)) {
            return true;
        }

        $conditions = json_decode($this->conditions);

        foreach ($conditions->rules as $rule) {
            $ruleDetails = explode('-', $rule->id);
            $DataBus = $ruleDetails[0];
            $field = $ruleDetails[1];

            $result = config('workflows.data_resources')[$DataBus]::checkCondition($model, $data, $field, $rule->operator, $rule->value);

            if (! $result) {
                throw new \Exception('The Condition for Task '.$this->name.' with the field '.$rule->field.' '.$rule->operator.' '.$rule->value.' failed.');
            }
        }

        return true;
    }

    public function getSettings()
    {
        return view('mail::backend.layouts.settings_overlay', [
            'element' => $this,
        ]);
    }

    public static function getTranslation(): string
    {
        return __(static::getTranslationKey());
    }

    public static function getTranslationKey(): string
    {
       return __((new \ReflectionClass(new static))->getShortName());
    }
}
