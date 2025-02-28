<?php
declare(strict_types=1);
namespace Modules\Mail\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

use Modules\Mail\DataBuses\ValueResource;

use Modules\Mail\Models\Workflow;
use Modules\Mail\Repositories\Workflows\WorkflowLogRepository;
use Modules\Mail\Repositories\Workflows\WorkflowRepository;

use Modules\Mail\Tasks\Task;
use Modules\Mail\Triggers\ReRunTrigger;
use Modules\Mail\Triggers\Trigger;

use Modules\Mail\Facades\LaravelMail;


class WorkflowController extends Controller
{

    protected WorkflowRepository $workflowRepository;
    protected WorkflowLogRepository $logRepository;

    private int $workspaceID = 1;

    public function __construct(WorkflowRepository $workflowRepository, WorkflowLogRepository $logRepository)
    {
        $this->workflowRepository = $workflowRepository;
        $this->logRepository = $logRepository;
    }


    public function index():View
    {
        $workspaceId = $this->workspaceID;

        $workflows = $this->workflowRepository->getWorkflows($workspaceId);

        return view('mail::backend.workflows.index', ['workflows' => $workflows]);
    }

    /**
     * @throws \Exception
     */
    public function show(int $id):View
    {
        $workflow = $this->workflowRepository->find($id);

        return view('mail::backend.workflows.diagram', ['workflow' => $workflow]);

        return view('mail::backend.workflows.new-diagram', ['workflow' => $workflow]);
    }

    public function create():View
    {
        return view('mail::backend.workflows.create');
    }

    /**
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $workflow = $this->workflowRepository->store($request->all());

        return redirect(route('backend.workflows.show', ['workflow' => $workflow]));
    }

    /**
     * @throws \Exception
     */
    public function edit($id)
    {
        $workflow = $this->workflowRepository->find($id);

        return view('mail::backend.workflows.edit', [
            'workflow' => $workflow,
        ]);
    }

    /**
     * @throws \Exception
     */
    public function update(Request $request, $id):RedirectResponse
    {
        $this->workflowRepository->update($id,$request->all());

        return redirect(route('mail::backend.workflows.index'));
    }


    /**
     * @throws \Exception
     */
    public function delete(int $id):RedirectResponse
    {
        $this->workflowRepository->find($id)->delete();

        return redirect(route('mail::backend.workflows.index'));
    }

    /**
     * @throws \Exception
     */
    public function addTask($id, Request $request)
    {
        $node = $request->get('node');

        $workflow = $this->workflowRepository->find($id);

        if ($node['data']['type'] == 'trigger') {
            return [
                'task' => '',
            ];
        }


        $task = Task::where('workflow_id', $workflow->id)->where('node_id', $request->id)->first();

        if (! empty($task)) {
            $task->pos_x = $request->pos_x;
            $task->pos_y = $request->pos_y;
            $task->save();

            return ['task' => $task];
        }

        if (array_key_exists($node['name'], config('workflows.tasks'))) {

            $task = config('workflows.tasks')[$node['name']]::create([
                'type' => config('workflows.tasks')[$node['name']],
                'workflow_id' => $workflow->id,
                'name' => $node['name'],
                'data_fields' => null,
                'node_id' => $request->get('node')['id'],
                'pos_x' => $request->get('node')['pos_x'],
                'pos_y' => $request->get('node')['pos_y'],

            ]);
        }


        return [
            'task' => $task,
            'node_id' => $request->id,
        ];
    }

    public function addTrigger(int $id, Request $request)
    {
        $workflow = $this->workflowRepository->find($id);

        $triggerType = config('workflows.triggers.types')[$request->node['name']] ?? null;

        if ($triggerType) {
            $trigger = $triggerType::create([
                'type' => $triggerType,
                'workflow_id' => $workflow->id,
                'name' => $request->node['name'],
                'data_fields' => null,
                'pos_x' => $request->node['pos_x'],
                'pos_y' => $request->node['pos_y'],
            ]);

            return [
                'trigger' => $trigger,
                'node_id' => $request->id,
            ];
        }

        throw new \RuntimeException('Invalid trigger type');
    }


    /**
     * @throws \Exception
     */
    public function changeConditions(int $workflowId, Request $request)
    {
        $workflow = $this->workflowRepository->find($workflowId);

        $element = match ($request->type) {
            'task' => $workflow->tasks->find($request->id),
            'trigger' => $workflow->triggers->find($request->id),
            default => throw new \RuntimeException('Invalid type'),
        };

        $element->conditions = $request->data;
        $element->save();

        return $element;
    }

    /**
     * @throws \Exception
     */
    public function changeValues($id, Request $request)
    {
        $workflow = $this->workflowRepository->find($id);

        if ($request->type == 'task') {
            $element = $workflow->tasks->find($request->id);
        }

        if ($request->type == 'trigger') {
            $element = $workflow->triggers->find($request->id);
        }

        $data = [];

        foreach ($request->data as $key => $value) {
            $path = explode('->', $key);
            $data[$path[0]][$path[1]] = $value;
        }
        $element->data_fields = $data;
        $element->save();

        return $element;
    }

    public function updateNodePosition($id, Request $request)
    {
        $element = $this->getElementByNode($id, $request->node);

        $element->pos_x = $request->node['pos_x'];
        $element->pos_y = $request->node['pos_y'];
        $element->save();

        return ['status' => 'success'];
    }

    public function getElementByNode(string $workflow_id, $node)
    {
        //dd($node);
        if ($node['data']['type'] == 'task') {
            $element = Task::where('workflow_id', $workflow_id)->where('id', $node['data']['task_id'])->first();
        }

        if ($node['data']['type'] == 'trigger') {
            $element = Trigger::where('workflow_id', $workflow_id)->where('id', $node['data']['trigger_id'])->first();
        }

        return $element;
    }

    public function addConnection($id, Request $request)
    {
        $workflow = Workflow::find($id);

        if ($request->parent_element['data']['type'] == 'trigger') {
            $parentElement = Trigger::where('workflow_id', $workflow->id)->where('id', $request->parent_element['data']['trigger_id'])->first();
        }
        if ($request->parent_element['data']['type'] == 'task') {
            $parentElement = Task::where('workflow_id', $workflow->id)->where('id', $request->parent_element['data']['task_id'])->first();
        }
        if ($request->child_element['data']['type'] == 'trigger') {
            $childElement = Trigger::where('workflow_id', $workflow->id)->where('id', $request->child_element['data']['trigger_id'])->first();
        }
        if ($request->child_element['data']['type'] == 'task') {
            $childElement = Task::where('workflow_id', $workflow->id)->where('id', $request->child_element['data']['task_id'])->first();
        }

        $childElement->parentable_id = $parentElement->id;
        $childElement->parentable_type = get_class($parentElement);

        $childElement->save();

        return ['status' => 'success'];
    }

    /**
     * @throws \Exception
     */
    public function removeConnection($id, Request $request)
    {
        $workflow = $this->workflowRepository->find($id);

        $childTask = Task::where('workflow_id', $workflow->id)->where('node_id', $request->input_id)->first();

        $childTask->parentable_id = 0;
        $childTask->parentable_type = null;
        $childTask->save();

        return ['status' => 'success'];
    }

    public function removeTask($id, Request $request)
    {
        $element = $this->getElementByNode($id, $request->node);

        $element->delete();

        return [
            'status' => 'success',
        ];
    }

    /**
     * @throws \Exception
     */
    public function getElementSettings(int $id, Request $request)
    {
        $workflow = $this->workflowRepository->find($id);

        $element = match ($request->type) {
            'task' => Task::where('workflow_id', $workflow->id)->where('id', $request->element_id)->first(),
            'trigger' => Trigger::where('workflow_id', $workflow->id)->where('id', $request->element_id)->first(),
            default => null,
        };


        return view('mail::backend.workflows.layouts.settings_overlay', [
            'element' => $element,
        ]);

    }

    /**
     * @throws \Exception
     */
    public function getElementConditions(int $id, Request $request)
    {
        $workflow = $this->workflowRepository->find($id);

        if ($request->type == 'task') {
            $element = Task::where('workflow_id', $workflow->id)->where('id', $request->element_id)->first();
        }
        if ($request->type == 'trigger') {
            $element = Trigger::where('workflow_id', $workflow->id)->where('id', $request->element_id)->first();
        }

        $filter = [];

        foreach (config('workflows.data_resources') as $resourceName => $resourceClass) {
            $filter[$resourceName] = $resourceClass::getValues($element, null, null);
        }

        return view('mail::backend.workflows.layouts.conditions_overlay', [
            'element' => $element,
            'conditions' => $element->conditions,
            'allFilters' => $filter,
        ]);
    }

    public function loadResourceIntelligence($id, Request $request)
    {
        $workflow = $this->workflowRepository->find($id);

        switch ($request->type) {
            case 'task':
                $element = Task::where('workflow_id', $workflow->id)
                    ->where('id', $request->element_id)
                    ->first();
                break;
            case 'trigger':
                $element = Trigger::where('workflow_id', $workflow->id)
                    ->where('id', $request->element_id)
                    ->first();
                break;
            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }

        if (in_array($request->resource, config('workflows.data_resources'))) {
            $className = $request->resource ?? ValueResource::class;

            // Ensure the class exists and is instantiable
            if (class_exists($className)) {
                $resource = new $className();
                $html = $resource->loadResourceIntelligence($element, $request->value, $request->field_name);
            } else {
                return response()->json(['error' => 'Resource class not found'], 400);
            }
        } else {
            return response()->json(['error' => 'Invalid resource'], 400);
        }

        return response()->json([
            'html' => $html ?? '',
            'id' => $request->field_name,
        ]);

    }

    /**
     * @throws \Exception
     */
    public function getLogs(int $id):View
    {
        $workflow = $this->workflowRepository->find($id);
        $workflowLogs = $workflow->logs()->orderBy('created_at', 'desc')->get();
        return view('mail::backend.::workflows.layouts.logs_overlay', [
            'workflowLogs' => $workflowLogs,
        ]);
    }

    public function reRun(int $workflowLogId):array
    {
        $log = $this->logRepository->find($workflowLogId);

        ReRunTrigger::startWorkflow($log);

        return [
            'status' => 'started',
        ];
    }

    public function triggerButton(Request $request, int $triggerId)
    {
        $trigger = Trigger::findOrFail($triggerId);

        $className = $request->model_class;

        $resource = new $className();

        $model = $resource->find($request->model_id);

        $trigger->start($model, []);

        return redirect()->back()->with('success', 'Button Triggered a Workflow');
    }
}
