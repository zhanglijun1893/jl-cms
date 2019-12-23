<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\BaseController;
use think\facade\Config;
use think\facade\Filesystem;

class Upload extends BaseController
{
    public function index()
    {
        if (request()->isPost()) {
            $file = request()->file('image');
            $root = request()->file('root');
            $domain = "";
            if ($root==1) $domain = request()->domain();
            //error_log(json_encode($file),3,$_SERVER['DOCUMENT_ROOT'].'/err_log.txt');
            $path = request()->file('path') ? trim(request()->file('path')) : "topic" ;
            $file_name = Filesystem::disk('public')->putFile( $path, $file);
            if ($file_name) {
                $p = Config::get('filesystem.disks.public.visibility').Config::get('filesystem.disks.public.url');
                $file_path = $domain.'/'.$p.'/'.$file_name;
                return $this->success(['url'=>$file_path]);
            }
        }
        return $this->fail();
    }
}
