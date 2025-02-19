<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Repositories;

use LaravelCompany\Mail\Models\Tag;

class TagTenantRepository extends BaseTenantRepository
{

    protected $modelName = Tag::class;


    public function update($workspaceId, $id, array $data)
    {
        $instance = $this->find($workspaceId, $id);

        $this->executeSave($workspaceId, $instance, $data);

        return $instance;
    }


    public function syncSubscribers(Tag $tag, array $subscribers = [])
    {
        return $tag->subscribers()->sync($subscribers);
    }


    public function destroy($workspaceId, $id): bool
    {
        $instance = $this->find($workspaceId, $id);

        $instance->subscribers()->detach();
        $instance->campaigns()->detach();

        return $instance->delete();
    }


    public function store($workspaceId, array $data)
    {

        $this->checkTenantData($data);

        $instance = $this->getNewInstance();


        $save =  $this->executeSave($workspaceId, $instance, $data);


        if ($data['subscribers']!= null) {

            $save->subscribers()->sync($data['subscribers']);

        }

        return $save;

    }

}
