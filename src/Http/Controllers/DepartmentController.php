<?php

namespace Niyam\ACL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Niyam\ACL\Model\Department;
use Niyam\ACL\Infrastructure\BaseController;

class DepartmentController extends BaseController
{
    /*
    private $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    */

    public function getDepartments()
    {
        return Department::all();
    }

    public function getTreeElement(int $gId, int $parentId, array &$arr, array &$arrChild, $rolPer)
    {
        $query = array();
        if ($parentId == 0) // شاخه اصلی پدرش صفر است
            $query = Department::where(['id' => $gId])->get();
        else
            $query = Department::where(['parent_id' => $parentId])->get();

        if (count($query) > 0)
            foreach ($query as $row) {
                if ($parentId == 0) {
                    $arr['id'] = $row->id;
                    $arr['text'] = $row->name;
                    $arr['data'] = [
                        'parentId' => $row->parent_id,
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
                    $arrChild[$i]['text'] = $row->name;
                    $arrChild[$i]['data'] = [
                        'parentId' => $row->parent_id,
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
     * return Department tree
     *
     * @param int $roleId
     * @return array
     */
    public function getTreeDepartments()
    {
        $dataRet = $data = $arrChild = array();

        $query = Department::where(['parent_id' => 0])->get();
        if (count($query) > 0)
            foreach ($query as $row) {
                $this->getTreeElement($row->id, 0, $data, $arrChild, []);
                array_push($dataRet, $data);
                $data = $arrChild = array();
            }

        return $dataRet;
    }

    public function isHaveNode(int $id)
    {
        return count(Department::where(['parent_id' => $id])->get()) ? 't' : 'f';
    }


    private function isUniqueName($id, string $name)
    {
        return count(Department::where([['name', '=', $name], ['id', '!=', $id]])->get()) > 0 ? false : true;
    }

    public function storeDepartment(Request $request)
    {
        $id = (!empty($request->id)) ? $request->id : 0;
        $parent_id = (!empty($request->parentId)) ? $request->parentId : 0;

        $name = $request->name;
        $isEdit = $request->isEdit;

        $isUniqe = $this->isUniqueName($id, $name);

        if ($isUniqe) {
            if (!$isEdit) {
                if (empty($id)) {
                    $data = ['parent_id' => $parent_id, 'name' => $name];
                } else {
                    $data = ['parent_id' => $id, 'name' => $name];
                }
                return Department::create($data);
            } else {
                $data = ['name' => $name];
                $predicate = ['id' => $id];
                return Department::where($predicate)->update($data);
            }
        } else {
            return Response('نام وارد شده تکراری میباشد.', Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteDepartment($department)
    {
        return (string)Department::findOrFail($department)->delete();
    }
}
