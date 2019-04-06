<?php

namespace app\admin\controller\gcrm;

use app\common\controller\Backend;
use think\Config;
use app\admin\model\gcrm\AuthXm;
use app\admin\model\gcrm\Sys;
use app\admin\model\gcrm\Gd;

use think\Db;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard1 extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist = $createlist = [];
        for ($i = 0; $i < 7; $i++)
        {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
        }
        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
        $addonComposerCfg = ROOT_PATH . '/vendor/karsonzhang/fastadmin-addons/composer.json';
        Config::parse($addonComposerCfg, "json", "composer");
        $config = Config::get("composer");
        $addonVersion = isset($config['version']) ? $config['version'] : __('Unknown');
        $this->view->assign([
            'totaluser'        => 35200,
            'totalviews'       => 219390,
            'totalorder'       => 32143,
            'totalorderamount' => 174800,
            'todayuserlogin'   => 321,
            'todayusersignup'  => 430,
            'todayorder'       => 2324,
            'unsettleorder'    => 132,
            'sevendnu'         => '80%',
            'sevendau'         => '32%',
            'paylist'          => $paylist,
            'createlist'       => $createlist,
            'addonversion'       => $addonVersion,
            'uploadmode'       => $uploadmode
        ]);

        $t1 = $this->auth->isLogin();
        if ($t1) {
            echo "            用户已登陆！";
        }

        echo("用户ID：{$this->auth->id}");  //Backend类包含用户id

        //$axm = new \app\admin\model\gcrm\AuthXm;  //这样用不需要在开头 use app\admin\model\gcrm\AuthXm;
        $axm = new AuthXm;                          //这样用需要在开头 use app\admin\model\gcrm\AuthXm;
        echo "getZzjg()";
        var_dump($axm->getZzjg());
        echo "<br>";
        echo "getZzjgID()";
        var_dump($axm->getZzjgID());
        echo "<br>";
        echo "getAllZzjgs()";
        var_dump($axm->getAllZzjgs());
        echo "<br>";

        $uid=$this->auth->id;
        $list = Db::query("select * from getAllXm where find_in_set(zzjg_id,getChildZzjg('{$uid}'))");
        //var_dump($list);

        echo "getAdminsList()";
        var_dump($axm->getAdminList());
        echo "<br>";
        echo "getAdminIds()";
        var_dump($axm->getAdminIds());
        echo "<br>";

        echo "getkehuList()";
        var_dump($axm->getKehuList());
        echo "<br>";

        echo "getClztTreeList()";
        $sys = new Sys;
        $clztData = $sys->getClztTreeList();
        var_dump($clztData);
        echo "<br>";

        echo "getGdlxTreeList()";
        $gdlxData=$sys->getGdlxTreeList();
        var_dump($gdlxData);
        echo "<br>";

        echo "getGdTreeList()";
        $gd = new Gd;
        $gdData = $gd->getGdTreeList();
        var_dump($gdData);
        echo "<br>";

        return $this->view->fetch();
    }

}
