<?php
/**
 * @WeiXinNumber        zhanglijun1893
 * @Copyright			君澜科技
 * @License				http://www.junlankeji.com
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用
 * 不允许对程序代码以任何形式任何目的的再发布,如果商业用途务必到官方购买正版授权
 * 版权所有 君澜科技（北京）有限公司，并保留所有权利。
 *   _               _               _            _ _
 *  (_)             | |             | |          (_|_)
 *  _ _   _ _ __   | | __ _ _ __   | | _____     _ _
 *  | | | | | '_ \  | |/ _` | '_ \  | |/ / _ \   | | |
 *  | | |_| | | | | | | (_| | | | | |   <  __/   | | |
 *  | |\__,_|_| |_| |_|\__,_|_| |_| |_|\_\___|   | |_|
 *  _/ |                                         _/ |
 *  |__/                                         |__/
 */
declare (strict_types=1);

namespace app\admin\controller\system;

use app\model\ConfigModel;
use think\facade\Cache;
use think\Request;
use app\admin\controller\BaseAdminController;

class Config extends BaseAdminController
{
    public function index()
    {
        $list = glob(request()->server('DOCUMENT_ROOT')."/templates/*",8192);
        $template = [];
        $hidden = ['statics','favicon.ico'];
        foreach ($list as $key=>$v) {
            $dirname = basename($v);
            if (in_array($dirname, $hidden)) continue;
            if (file_exists($v.'/config.php')) {
                $tf = include $v.'/config.php';
                $t['value']=$tf['value'];
                $t['label']=$tf['label'];
                $template[] = $t;
            }
        }
        $model = ConfigModel::select();
        $data = [];
        foreach($model as $k=>$val){
            $data[$val['name']] = $val['value'];
        }
        $this->assign("template",$template);
        $this->assign("data",$data);
        return $this->fetch();
    }
    public function save(Request $request)
    {
        $model = ConfigModel::select();
        if ($request->isPost()) {
            $params = $request->put();
            $save = $m = [];
            foreach ($model as $key=>$value) {
                $m[$value['name']] = $value['value'];
            }
            foreach ($params as $key=>$value) {
                $arr['name'] = $key;
                $arr['value'] = $value;
                if(!isset($m[$key])){
                    array_push($save,$arr);
                } else {
                    if ($value != $m[$key]) {
                        ConfigModel::where('name',$key)->save($arr);
                    }
                }
            }
            if (!empty($save)) {
                $c = new ConfigModel();
                $c->saveAll($save);
            }
            //添加缓存
            ConfigModel::setCache();
            return $this->success();
        }
    }
}
