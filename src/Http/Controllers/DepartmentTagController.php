<?php

namespace Niyam\ACL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Niyam\ACL\Model\DepartmentTag;
use Niyam\ACL\Infrastructure\BaseController;

class DepartmentTagController extends BaseController
{

    public function index()
    {
        return DepartmentTag::all();
    }

    public function show($id)
    {
        return DepartmentTag::where('department_id', $id)->get();
    }

    public function store(Request $request)
    {
        $tags = $request->tags;
        $departments = $request->departments;

        foreach ($departments as $department) {
            DepartmentTag::where('department_id', $department)->delete();
            foreach ($tags as $tag) {
                DepartmentTag::create([
                    'department_id' => $department,
                    'tag_id' => $tag
                ]);
            }
        }
    }
}
