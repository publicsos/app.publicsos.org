<?php
declare(strict_types=1);
namespace Modules\Mail\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Mail\DataBuses\DataBus;
use Modules\Mail\Loggers\WorkflowLog;
use Modules\Mail\Triggers\Trigger;


class ProcessWorkflow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Model $model;
    private DataBus $dataBus;
    private Trigger $trigger;
    private WorkflowLog $log;

    public function __construct(Model $model, DataBus $dataBus, Trigger $trigger, WorkflowLog $log)
    {
        $this->model = $model;
        $this->dataBus = $dataBus;
        $this->trigger = $trigger;
        $this->log = $log;

        info('Processing '.json_encode($this->trigger));
    }



    public function handle()
    {
        info('Data Workflow has children '.json_encode($this->trigger->children));
        DB::beginTransaction();
        try{
            info("The total is " . count($this->trigger->children) . ' for ' . $this->trigger->name);
            foreach ($this->trigger->children as $task) {
                $task->init($this->model, $this->dataBus, $this->log);
                $task->execute();
                $task->pastExecute();
            }
        }catch (\Throwable $e) {
            DB::rollBack();
            info('Error in Task ' . $this->trigger->name);
            info($e->getMessage());
            info($e->getTraceAsString());
            $this->log->setError($e->getMessage(), $this->dataBus);
            $this->log->createTaskLogsFromMemory();
        }
        $this->log->finish();
        DB::commit();
    }
}
