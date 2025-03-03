<?php

namespace Modules\Workflow\Tasks;

use Illuminate\Database\Eloquent\Model;
use Modules\Workflow\DataBuses\DataBus;

interface TaskInterface
{

    public function execute(): void;


    public function checkConditions(Model $model, DataBus $data): bool;
}
