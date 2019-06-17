<?php

namespace Niyam\ACL\Http\Controllers;

use Niyam\ACL\Infrastructure\BaseController;

class FileController extends BaseController
{
    public function index()
    {
        echo '<form method="Post" enctype="multipart/form-data" action="users/2">';
        echo '<input type="hidden" name="_method" value="patch">';
        echo '<span style="color:red;font-size:11px;">AVATAR:</span> <input type="file" name="avatar"><br>';
        echo '<span style="color:red;font-size:11px;">SIGNATURE:</span> <input type="file" name="signature"><br>';
        echo '<input type="submit">';
        echo '</form>';
    }

    public static function upload($file, $destinationPath)
    {
        $fileAvatarExt = $file->getClientOriginalExtension();
        $fileAvatarName = time() . '-' . rand(10000, 99999) . '.' . $fileAvatarExt;

        try {
            $fileMove = $file->move($destinationPath, $fileAvatarName);
            return $fileAvatarName;
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
