<?php

namespace Niyam\ACL\Http\Controllers;
use Niyam\ACL\Infrastructure\BaseController;

class DocumentationController extends BaseController
{
    public function index()
    {
        $returnArray = [];
        $dirs = array_filter(glob('uploads/documentation/*'), 'is_dir');
        foreach ($dirs as $dir) {
            $aaa['url'] = $dir;
            $aaa['name'] = $this->getDirName($dir);
            $aaa['dir'] = [];

            $bbb = [];
            $subdirs = array_filter(glob("$dir/*"), 'is_dir');
            foreach ($subdirs as $subdir) {
                $bbb['url'] = $subdir;
                $bbb['name'] = $this->getDirName($subdir);
                $bbb['files'] = [];

                $ccc = [];
                $files = (glob($subdir . '/*.*'));
                foreach ($files as $file) {
                    $ccc['url'] = $file;
                    $ccc['name'] = $this->getFileName($file)['filename'];
                    $ccc['ext'] = $this->getFileName($file)['extension'];

                    $bbb['files'][] = $ccc;
                }

                $aaa['dir'][] = $bbb;
            }
            $returnArray[] = $aaa;
        }

        return $returnArray;
    }

    public function getDirName($dir)
    {
        $d = explode('/', $dir);
        return $d[count($d) - 1];
    }

    public function getFileName($file)
    {
        return pathinfo($file);
    }

    /*
    public function show(Documentation $documentation)
    {
        // return $documentation;
    }
*/
}
