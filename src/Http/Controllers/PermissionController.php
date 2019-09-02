<?php

namespace Niyam\ACL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Niyam\ACL\Model\Permission;
use Niyam\ACL\Infrastructure\BaseController;

class PermissionController extends BaseController
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getPermissions()
    {
        return Permission::all();
    }

    public function getTreeElement(int $gId, int $parentId, array &$arr, array &$arrChild, $rolPer)
    {
        $query = array();
        if ($parentId == 0) // شاخه اصلی پدرش صفر است
            $query = Permission::where(['id' => $gId])->get();
        else
            $query = Permission::where(['parent_id' => $parentId])->get();

        if (count($query) > 0)
            foreach ($query as $row) {
                if ($parentId == 0) {
                    $arr['id'] = $row->id;
                    $arr['text'] = $row->title;
                    $arr['data'] = [
                        'parentId' => $row->parent_id,
                        'name' => $row->name
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
                        'name' => $row->name
                    ];
                    $arrChild2 = array();
                    $this->getTreeElement($row->id, $row->id, $arr, $arrChild2, $rolPer);
                    if (is_array($arrChild2))
                        $arrChild[$i]['children'] = $arrChild2;
                }
            } else
            return true;
    }


    /**
     * return permission tree
     *
     * @param int $roleId
     * @return array
     */
    public function getTreePermissions()
    {
        $dataRet = $data = $arrChild = array();

        $query = Permission::where(['parent_id' => 0])->get();
        if (count($query) > 0)
            foreach ($query as $row) {
                $this->getTreeElement($row->id, 0, $data, $arrChild, []);
                array_push($dataRet, $data);
                $data = $arrChild = array();
            }

        return $dataRet;
    }

    public function getTreeKendoElement(int $gId, int $parentId, array &$arr, array &$arrChild, $rolPer)
    {
        $query = array();
        if ($parentId == 0) // شاخه اصلی پدرش صفر است
            $query = Permission::where(['id' => $gId])->get();
        else
            $query = Permission::where(['parent_id' => $parentId])->get();

        if (count($query) > 0)
            foreach ($query as $row) {
                if ($parentId == 0) {
                    $arr['value'] = $row->id;
                    $arr['text'] = $row->title;
                    $arr['data'] = [
                        'parentId' => $row->parent_id,
                        'name' => $row->name,
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
                    ];
                    $arrChild2 = array();
                    $this->getTreeKendoElement($row->id, $row->id, $arr, $arrChild2, $rolPer);
                    if (is_array($arrChild2))
                        $arrChild[$i]['items'] = $arrChild2;
                }
            } else
            return true;
    }

    public function getTreeKendoPermissions()
    {
        $dataRet = $data = $arrChild = array();

        $query = Permission::where(['parent_id' => 0])->get();
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
        return count(Permission::where(['parent_id' => $id])->get()) ? 't' : 'f';
    }

    private function isUniqueTitleName($id, string $title, string $name)
    {
        return count(Permission::where([['title', '=', $title], ['id', '!=', $id]])->orWhere([['name', '=', $name], ['id', '!=', $id]])->get()) > 0 ? false : true;
    }

    public function storePermission()
    {
        $id = (!empty($this->request->id)) ? $this->request->id : 0;
        $parent_id = (!empty($this->request->parentId)) ? $this->request->parentId : 0;

        $name = $this->request->name;
        $title = $this->request->title;
        $isEdit = $this->request->isEdit;

        $isUniqe = $this->isUniqueTitleName($id, $title, $name);

        if ($isUniqe) {
            if (!$isEdit) {
                if (empty($id)) {
                    $data = ['parent_id' => $parent_id, 'name' => $name, 'title' => $title];
                } else {
                    $data = ['parent_id' => $id, 'name' => $name, 'title' => $title];
                }
                return Permission::create($data);
            } else {
                $data = ['name' => $name, 'title' => $title];
                $predicate = ['id' => $id];
                return Permission::where($predicate)->update($data);
            }
        } else {
            return Response('عنوان/نام وارد شده تکراری میباشد.', Response::HTTP_BAD_REQUEST);
        }
    }

    public function deletePermission($permission)
    {
        return (string)Permission::findOrFail($permission)->delete();
    }
}
