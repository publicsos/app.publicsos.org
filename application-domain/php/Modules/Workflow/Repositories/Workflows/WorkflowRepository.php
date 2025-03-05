<?php
declare(strict_types=1);
namespace Modules\Workflow\Repositories\Workflows;


use Modules\Workflow\Models\Workflow;
use Modules\Mail\Repositories\BaseEloquentRepository;

class WorkflowRepository extends BaseEloquentRepository
{
    protected $modelName = Workflow::class;

    private int $workspaceID = 1;

    public function getWorkflows(int $workspaceId):mixed
    {
        return $this->getQueryBuilder()->where([
            'workspace_id' => $workspaceId
        ])->paginate();
    }

    public function store(array $data)
    {
        array_push($data, ['workspace_id' => $this->workspaceID]);

        $this->instance = $this->getNewInstance();

        return $this->executeSave($data);
    }

}
