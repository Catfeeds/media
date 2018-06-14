<?php
namespace App\Repositories;

use App\Handler\Common;
use App\Models\GroupAssociation;

class GroupAssociationsRepository extends BaseRepository
{
    private $model;

    public function __construct(GroupAssociation $model)
    {
        $this->model = $model;
    }

    /**
     * 说明: 组列表
     *
     * @param $per_page
     * @return mixed
     * @author 罗振
     */
    public function groupAssociationsList(
        $per_page
    )
    {
        return $this->model->where('storefronts_id', Common::user()->storefront->id)->paginate($per_page);
    }

    /**
     * 说明: 添加组
     *
     * @param $request
     * @return mixed
     * @author 罗振
     */
    public function addGroupAssociations(
        $request
    )
    {
        return $this->model->create([
            'storefronts_id' => Common::user()->storefront->id,
            'name' => $request->name,
            'group_leader_id' => $request->group_leader_id
        ]);
    }

    /**
     * 说明: 修改组长
     *
     * @param $request
     * @param GroupAssociation $groupAssociation
     * @return bool
     * @author 罗振
     */
    public function updateGroupAssociation(
        $request,
        GroupAssociation $groupAssociation
    )
    {
        $groupAssociation->storefronts_id = Common::user()->storefront->id;
        $groupAssociation->name = $request->name;
        $groupAssociation->group_leader_id = $request->group_leader_id;

        if (!$groupAssociation->save()) return false;
        return true;
    }
}
