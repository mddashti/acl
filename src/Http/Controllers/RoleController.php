<?php

namespace Niyam\ACL\Http\Controllers;

use Illuminate\Http\Response;
use Niyam\ACL\Model\User;
use Niyam\ACL\Model\PositionTag;
use Niyam\ACL\Helper\Graph;
use Niyam\ACL\Infrastructure\BaseController;
use Spatie\Permission\Models\Role;


class RoleController extends BaseController
{
    public function getRelation($roleX)
    {
        // role 1 origin
        $roleX = (int)$roleX;
        // role 2 destination
        $roleY = $this->request->get('y');
        // level
        $level = $this->request->get('level');
        // tag
        $tag = $this->request->get('tag');
        // direction [up, down]
        $direction = $this->request->get('direction', 'parent'); // (parent || child)


        if (!$roleX)
            return response()->_json(Response::HTTP_NOT_FOUND, 'origin not found!');


        $_allRoles = Role::all(['id', 'parent_id']);
        $allRoles = [];
        foreach ($_allRoles as $ar) {
            if ($direction == 'child') // check direction
                $allRoles[$ar['parent_id']] = $ar['id'];
            else // if type == parent
                $allRoles[$ar['id']] = $ar['parent_id'];
        }

        if ($level) {
            $relation = $this->getRolesByLevel($roleX, $level, $allRoles);
        } else if ($roleY || $tag) {
            $createNodes = $this->createNodes($allRoles);
            $graph = new Graph($createNodes);

            if ($tag) {
                $parent_id = PositionTag::where(['role_id' => $roleX, 'tag_id' => $tag])->get('parent_role_id');
                if (!count($parent_id))
                    return response()->_json(Response::HTTP_NOT_FOUND, 'tag destination not found!');

                $relation = $graph->breadthFirstSearch($roleX, $parent_id[0]['parent_role_id']);
            } else {
                $relation = $graph->breadthFirstSearch($roleX, $roleY);
            }
        } else {
            return response()->_json(Response::HTTP_NOT_FOUND, 'destination not found!');
        }


        $users = User::role($relation)->get();

        return response()->_json(Response::HTTP_OK, 'OK', ['users' => $users]);
    }

    public static function getRolesByLevel($start, $level, array $data)
    {
        $result = [(int)$start];
        for ($i = 0; $i < $level; $i++) {

            if (!isset($data[$start]) || $data[$start] == 0)
                break;

            $start = $data[$start];
            $result[] = $start;
        }

        return $result;
    }

    public static function createNodes(array $data)
    {
        $result = [];
        foreach ($data as $id => $parent_id) {
            if ($id != 0 && $parent_id != 0) {
                $result[$id][] = $parent_id;
                $result[$parent_id][] = $id;
            }
        }

        return $result;
    }

    //// OLD ////////////////////////////////////////////////////////////////////
    private function isUniqueTitleName($id, string $title, string $name)
    {
        if (!empty($name))
            return count(Role::where([['title', '=', $title], ['id', '!=', $id]])->orWhere([['name', '=', $name], ['id', '!=', $id]])->get()) > 0 ? false : true;
        return count(Role::where([['title', '=', $title], ['id', '!=', $id]])->get()) > 0 ? false : true;
    }

    public function postRole()
    {
        $id = (!empty($this->request->id)) ? $this->request->id : 0;
        $parent_id = ($this->request->has('parentId') && !empty($this->request->parentId)) ? $this->request->parentId : 0;

        $type = $this->request->is('positions') ? 1 : 0;
        $title = $this->request->title;
        $name = $type ? $title : $this->request->name;
        $departmentId = ($this->request->has('departmentId') && !empty($this->request->departmentId)) ? $this->request->departmentId : null;
        $positionUsers = $type ? $this->request->positionUsers : [];

        $isUniqe = $this->isUniqueTitleName($id, $title, $name);


        if ($isUniqe) {
            if (empty($id)) {
                $parent_id = $parent_id;
            } else {
                $parent_id = $id;
            }
            $data = ['parent_id' => $parent_id, 'name' => $name, 'title' => $title, 'type' => $type, 'department_id' => $departmentId];
            if ($insertRoleId = Role::create($data)) {
                foreach ($positionUsers as $userId) {
                    $user = User::findOrFail($userId);
                    $user->assignRole($insertRoleId);
                }
                return;
            }
        } else {
            return Response('عنوان/نام وارد شده تکراری میباشد.', Response::HTTP_BAD_REQUEST);
        }
    }

    public function getRolePermissions($role)
    {
        return Role::findOrFail($role)->permissions;
    }

    public function getRoles()
    {
        $type = $this->request->is('positions') ? 1 : 0;
        return Role::where('type', $type)->get();
    }

    public function getRole($role)
    {
        return Role::findOrFail($role);
    }

    public function getTreeElement(int $gId, int $parentId, array &$arr, array &$arrChild, $rolPer)
    {
        $query = array();
        if ($parentId == 0) // شاخه اصلی پدرش صفر است
            $query = Role::where(['id' => $gId])->get();
        else
            $query = Role::where(['parent_id' => $parentId, 'type' => 1])->get();

        if (count($query) > 0)
            foreach ($query as $row) {
                if ($parentId == 0) {
                    $arr['id'] = $row->id;
                    $arr['text'] = $row->title;
                    $arr['data'] = [
                        'parentId' => $row->parent_id,
                        'name' => $row->name,
                        'departmentId' => $row->department_id
                    ];
                    if (count($rolPer) > 0)
                        foreach ($rolPer as $rp) {
                            if ($rp->id == $row->id)
                                $arr['state']['selected'] = true;
                        }
                    // $arr['state']['opened']=true;
                    $this->getTreeElement($row->id, $row->id, $arr, $arrChild, $rolPer);
                    if (is_array($arrChild))
                        $arr['children'] = $arrChild;
                } else {
                    $i = count($arrChild);
                    // زیر گروههای درخت
                    $arrChild[$i]['id'] = $row->id;
                    $arrChild[$i]['text'] = $row->title;
                    $arrChild[$i]['data'] = [
                        'parentId' => $row->parent_id,
                        'name' => $row->name,
                        'departmentId' => $row->department_id
                    ];
                    $arrChild2 = array();
                    $this->getTreeElement($row->id, $row->id, $arr, $arrChild2, $rolPer);
                    if (is_array($arrChild2))
                        $arrChild[$i]['children'] = $arrChild2;
                }
            } else
            return true;
    }

    public function getTreeKendoElement(int $gId, int $parentId, array &$arr, array &$arrChild, $rolPer)
    {
        $query = array();
        if ($parentId == 0) // شاخه اصلی پدرش صفر است
            $query = Role::where(['id' => $gId])->get();
        else
            $query = Role::where(['parent_id' => $parentId, 'type' => 1])->get();

        if (count($query) > 0)
            foreach ($query as $row) {
                if ($parentId == 0) {
                    $arr['value'] = $row->id;
                    $arr['text'] = $row->title;
                    $arr['data'] = [
                        'parentId' => $row->parent_id,
                        'name' => $row->name,
                        'departmentId' => $row->department_id
                    ];
                    if (count($rolPer) > 0)
                        foreach ($rolPer as $rp) {
                            if ($rp->id == $row->id)
                                $arr['state']['selected'] = true;
                        }
                    // $arr['state']['opened']=true;
                    $this->getTreeKendoElement($row->id, $row->id, $arr, $arrChild, $rolPer);
                    if (is_array($arrChild))
                        $arr['items'] = $arrChild;
                } else {
                    $i = count($arrChild);
                    // زیر گروههای درخت
                    $arrChild[$i]['value'] = $row->id;
                    $arrChild[$i]['text'] = $row->title;
                    $arrChild[$i]['data'] = [
                        'parentId' => $row->parent_id,
                        'name' => $row->name,
                        'departmentId' => $row->department_id
                    ];
                    $arrChild2 = array();
                    $this->getTreeKendoElement($row->id, $row->id, $arr, $arrChild2, $rolPer);
                    if (is_array($arrChild2))
                        $arrChild[$i]['items'] = $arrChild2;
                }
            } else
            return true;
    }

    public function getTreeRoles()
    {
        $dataRet = $data = $arrChild = array();

        $query = Role::where(['parent_id' => 0, 'type' => 1])->get();
        if (count($query) > 0)
            foreach ($query as $row) {
                $this->getTreeElement($row->id, 0, $data, $arrChild, []);
                array_push($dataRet, $data);
                $data = $arrChild = array();
            }

        return $dataRet;
    }

    public function getTreeKendoRoles()
    {
        $dataRet = $data = $arrChild = array();

        $query = Role::where(['parent_id' => 0, 'type' => 1])->get();
        if (count($query) > 0)
            foreach ($query as $row) {
                $this->getTreeKendoElement($row->id, 0, $data, $arrChild, []);
                array_push($dataRet, $data);
                $data = $arrChild = array();
            }

        return $dataRet;
    }

    public function isHaveNode(int $id)
    {
        return count(Role::where(['parent_id' => $id])->get()) ? 't' : 'f';
    }

    public function getRoleForUser($user)
    {
        return User::findOrFail($user)->getRoles();
    }

    public function syncRolesForUser($user)
    {
        $roles = $this->request->roles;
        return User::findOrFail($user)->syncRoles($roles);
    }

    public function removeRoleForUser($user, $role)
    {
        return User::findOrFail($user)->removeRole($role);
    }

    public function assignRoleForUser($user, $role)
    {
        return User::findOrFail($user)->assignRole($role);
    }

    //POST {position}/users or /{role}/users
    public function assignRoleToUsers($role)
    {
        $users = $this->request->users;
        $role = Role::findOrFail($role);
        $role->users()->sync($users);
    }

    public function revokeUserRole($user, $role)
    {
        return $this->removeRoleForUser($user, $role);
    }

    public function givePermissionTo($role)
    {
        $permissions = $this->request->permissions;
        return Role::findOrFail($role)->givePermissionTo($permissions);
    }

    public function revokePermissionTo($role, $permission)
    {
        return Role::findOrFail($role)->revokePermissionTo($permission);
    }

    public function syncPermissions($role)
    {
        $permissions = $this->request->all();
        return Role::findOrFail($role)->syncPermissions($permissions);
    }

    public function deleteRole($role)
    {
        Role::where('id', $role)->delete();
    }

    public function getPositionWithTags()
    {
        if ($this->request->has('grouped-by')) {
            $data = PositionTag::with(['position:id,name', 'owner:id,name', 'tag:id,name'])->get();

            $unique = $data->unique(function ($item) {
                return $item['owner'] . $item['tag'];
            });

            return $unique->values()->all();
        }
        return PositionTag::with(['position', 'owner', 'tag'])->get();
    }

    public function getPositionsByOwnerTag($owner, $tag)
    {
        return PositionTag::where(['tag_id' => $tag, 'parent_role_id' => $owner])->get()->pluck('role_id');
    }

    public function postPositionsByOwnerTag($owner, $tag)
    {
        $isEdit = $this->request->isEdit;
        $positions = $this->request->positions;

        //check duplicate for insert
        if (!$isEdit && PositionTag::where(['tag_id' => $tag, 'parent_role_id' => $owner])->count() > 0) {
            return Response('اطلاعات وارد شده تکراری میباشد.', Response::HTTP_BAD_REQUEST);
        }

        PositionTag::where(['tag_id' => $tag, 'parent_role_id' => $owner])->delete();
        foreach ($positions as $position) {
            PositionTag::create(['tag_id' => $tag, 'parent_role_id' => $owner, 'role_id' => $position]);
        }
    }

    public function deletePositionsByOwnerTag($owner, $tag)
    {
        return PositionTag::where(['tag_id' => $tag, 'parent_role_id' => $owner])->delete();
    }

    public function editRole($role)
    {
        $data = $this->request->role;
        $isPosition = $this->request->is('positions/*') ? 1 : 0;
        $users = $isPosition ? $this->request->users : [];

        $role = Role::findOrFail($role);
        $role->update($data);
        if ($isPosition)
            $role->users()->sync($users);
    }

    public function deleteRoleTag($owner, $tag)
    {
        PositionTag::Where(['parent_role_id' => $owner, 'tag_id' => $tag])->delete();
    }
}
