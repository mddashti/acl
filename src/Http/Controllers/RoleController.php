<?php

namespace Niyam\ACL\Http\Controllers;

use Niyam\ACL\Model\User;
use Niyam\ACL\Model\Tag;
use Illuminate\Http\Response;
use Niyam\ACL\Model\Department;
use Niyam\ACL\Model\PositionTag;
use Niyam\ACL\Service\ACLService;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Niyam\ACL\Infrastructure\BaseController;


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
        // tag get from role_tag table
        $tag = $this->request->get('tag');
        // direction [up, down]
        $direction = $this->request->get('direction', 'parent'); // (parent || child)

        return ACLService::getUserByXLD($roleX, $roleY, $level, $tag, $direction);
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
        $parent = $this->request->get('parent');
        $getRoles = Role::where('type', $type);
        $getRoles = $parent ? $getRoles->where('parent_id', $parent) : $getRoles;
        return $getRoles->get();
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
                $getDepartmentName = Department::where('id',$row->department_id)->first();
                $departmentName = $getDepartmentName ? $getDepartmentName->name : '';
                if ($parentId == 0) {
                    $arr['id'] = $row->id;
                    $arr['rawText'] = $row->title;
                    $arr['text'] = $row->title.'-'.$departmentName;
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
                    $arrChild[$i]['text'] = $row->title.'-'.$departmentName;
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
                $getDepartmentName = Department::where('id',$row->department_id)->first();
                $departmentName = $getDepartmentName ? $getDepartmentName->name : '';
                if ($parentId == 0) {
                    $arr['value'] = $row->id;
                    $arr['text'] = $row->title.'-'.$departmentName;
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
                    $arrChild[$i]['text'] = $row->title.'-'.$departmentName;
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
        // $query = Role::where(['parent_id' => 0])->get();
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
        return $this->HANDLE_VENDOR_USER($role, $users);
    }

    public function HANDLE_VENDOR_USER($role, $users)
    {
        if($role->name != 'expert_vendor') return;
        $deActiveExistsExpertUsers = DB::connection('vendor')->table('vendor_users')
                    ->where('type', 4)/*->whereIn('sso_id', $users)*/->update([
                        'active' => 0
                    ]);
        
        foreach($users as $userId){
            $existUser = DB::connection('vendor')->table('vendor_users')
                                ->where('type', 4)->where('sso_id', $userId)->update([
                                    'active' => 1
                                ]);
            if($existUser){
                // $existUser->update(['active' => 1]);
            }else{
                $user = User::where('id', $userId)->first();
                if($user){
                    DB::connection('vendor')->table('vendor_users')->insert([
                        'username'      => $user->username,
                        'sso_id'        => $user->id,
                        'model_type'    => 'App\\',
                        'title'         => $user->name,
                        'email'         => $user->email,
                        'mobile'        => $user->mobile,
                        'password'      => bcrypt('123456'),
                        'active'        => true,
                        'type'          => 4
                    ]);
                }
            }
        }
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
        return PositionTag::where(['tag_id' => $tag, 'parent_position_id' => $owner])->get()->pluck('position_id');
    }

    public function postPositionsByOwnerTag($owner, $tag)
    {
        $isEdit = $this->request->isEdit;
        $positions = $this->request->positions;

        //check duplicate for insert
        if (!$isEdit && PositionTag::where(['tag_id' => $tag, 'parent_position_id' => $owner])->count() > 0) {
            return Response('اطلاعات وارد شده تکراری میباشد.', Response::HTTP_BAD_REQUEST);
        }

        PositionTag::where(['tag_id' => $tag, 'parent_position_id' => $owner])->delete();

        $getTagType = Tag::where('id', $tag)->first('type');

        if($getTagType['type'] == 1){
            foreach ($positions as $position) {
                PositionTag::create(['tag_id' => $tag, 'parent_position_id' => $owner, 'position_id' => $position, 'type' => $getTagType['type']]);
            }
        }else/* if($getTagType['type'] == 0)*/{
            PositionTag::create(['tag_id' => $tag, 'parent_position_id' => $owner, 'position_id' => null, 'type' => $getTagType['type']]);
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
        PositionTag::Where(['parent_position_id' => $owner, 'tag_id' => $tag])->delete();
    }
}
