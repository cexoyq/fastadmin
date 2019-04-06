<?php

// +----------------------------------------------------------------------
// | 根据用户所属的组织机构，调用显示相对应的组织机构的项目
// +----------------------------------------------------------------------

namespace app\admin\model\gcrm;

use think\Db;
use think\Config;
use think\Session;
use think\Request;
use think\console\command\make\Model;
use app\admin\library\Auth;

/**
 * 比如用户属于武汉分公司，则用户调用显示项目列表时，只显示武汉分公司的项目列表
 */

/**
 * 权限认证类
 * 功能特性：
 * 1，是对规则进行认证，不是对节点进行认证。用户可以把节点当作规则名称实现对节点进行认证。
 *      $auth=new Auth();  $auth->check('规则名称','用户id')
 * 2，可以同时对多条规则进行认证，并设置多条规则的关系（or或者and）
 *      $auth=new Auth();  $auth->check('规则1,规则2','用户id','and')
 *      第三个参数为and时表示，用户需要同时具有规则1和规则2的权限。 当第三个参数为or时，表示用户值需要具备其中一个条件即可。默认为or
 * 3，一个用户可以属于多个用户组(think_auth_group_access表 定义了用户所属用户组)。我们需要设置每个用户组拥有哪些规则(think_auth_group 定义了用户组权限)
 * 4，支持规则表达式。
 *      在think_auth_rule 表中定义一条规则，condition字段就可以定义规则表达式。 如定义{score}>5  and {score}<100
 * 表示用户的分数在5-100之间时这条规则才会通过。
 */
class AuthXm extends Model
{
    //默认配置
    protected $config = [
        'auth_on'           => 1, // 权限开关
        'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
        'admin' => 'admin', //用户表
        'gcrm_zzjg'        => 'gcrm_zzjg', // 组织机构数据表名
        'gcrm_zzjg_user'         => 'gcrm_zzjg_user', // 用户信息表
        'xm' => 'gcrm_xm',  //项目表
        'kehu' => 'gcrm_kehu',    //客户表
        'gd' => 'gcrm_gd'   //工单表
    ];

    public $id = null;   //用户的id号

    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
        $auth = Auth::instance();
        $this->id = $auth->id;
    }

    /***
     * 根据用户id获取用户的组织机构，返回值为数组
     * @param $uid int 用户id
     * @return arrar 用户所属的组织机构 arrar() array(1) { [0]=> array(5) { ["uid"]=> int(1) ["zzjg_id"]=> int(22) ["id"]=> int(22) ["pid"]=> int(2) ["name"]=> string(15) "西安分公司" } }
     */
    public function getZzjg($uid = null)
    {
        $auth = Auth::instance();
        $this->id = $auth->id;
        $_uid = is_null($uid) ? $this->id : $uid;
        $zzjg = [];
        // 执行查询
        $user_zzjg = Db::name($this->config['gcrm_zzjg'])
            ->alias('a')
            ->join('__' . strtoupper($this->config['gcrm_zzjg_user']) . '__ ag', 'a.id = ag.zzjg_id', 'LEFT')
            ->field('ag.uid,ag.zzjg_id,a.id,a.pid,a.name')
            ->where("ag.uid='{$_uid}'")
            ->select();
        //$zzjg[$uid] = $user_zzjg ?: [];
        return $user_zzjg[0] ?: [];    //返回：array(5) { ["uid"]=> int(1) ["zzjg_id"]=> int(22) ["id"]=> int(22) ["pid"]=> int(2) ["name"]=> string(15) "西安分公司" } }
    }

    /***
     * 根据用户id获取用户的组织机构，返回值为integer
     * @param $uid int 用户id
     * @return arrar 用户所属的组织机构 arrar() array(1) { [0]=> array(5) { ["uid"]=> int(1) ["zzjg_id"]=> int(22) ["id"]=> int(22) ["pid"]=> int(2) ["name"]=> string(15) "西安分公司" } }
     */
    public function getZzjgID($uid = null)
    {
        $auth = Auth::instance();
        $this->id = $auth->id;
        $_uid = is_null($uid) ? $this->id : $uid;
        $zzjg = [];
        // 执行查询
        $user_zzjg = Db::name($this->config['gcrm_zzjg'])
            ->alias('a')
            ->join('__' . strtoupper($this->config['gcrm_zzjg_user']) . '__ ag', 'a.id = ag.zzjg_id', 'LEFT')
            ->field('a.id')
            ->where("ag.uid='{$_uid}'")
            ->find();
        //$zzjg[$uid] = $user_zzjg ?: [];
        if (null === $user_zzjg){
            return null;
        }
        return $user_zzjg['id'];    //return:3
    }

    /***
     * 根据用户id获取用户的组织机构及下级组织机构，返回值为数组
     * @param $uid int 用户id
     * @return arrar 用户所属的组织机构及下级组织机构 arrar()： array(1) { [0]=> array(1) { ["zzjgs"]=> string(10) "-1,1,2,3,22" } }
     */
    public function getAllZzjgs($uid = null)
    {
        $auth = Auth::instance();
        $this->id = $auth->id;
        $_uid = is_null($uid) ? $this->id : $uid;
        $zzjgs = [];
        // 执行查询
        $user_zzjg = Db::query("select getChildzzjg('{$_uid}') as zzjgs");
        $zzjgs[$uid] = $user_zzjg ?: [];    //$zzjgs[$uid][0]['zzjgs'];
        return $zzjgs[$uid][0]['zzjgs'];    //return: -1,1,2,3,22
    }

    /***
     * 根据当前用户id，得到用户组织机构，然后根据组织机构，取得组织机构及子组织机构下的所有项目列表
     */
    public function getXmList()
    {
        $zzjgIds = $this->getAllZzjgs();  //取得当前用户的组织机构及子组织机构的ID号，以逗号分隔
        //Tree类的用法 ，输出多级选择，只能选择自己的组织机构及子组织机构以下的项目
        $List = Db::name($this->config['xm'])
            ->field(['id', 'pid', 'name'])
            ->whereIn('zzjg_id', $zzjgIds)
            ->order('weigh', 'desc')
            ->select();
        return $List;
    }

    /***
     * 根据当前用户id，得到用户组织机构，然后根据组织机构，取得当前用户所属组织机构及子组织机构下的所有用户
     * 返回数组 array(3) { [0]=> array(4) { ["id"]=> int(8) ["username"]=> string(7) "xiongyi" ["name"]=> string(6) "熊义" ["zzjg_id"]=> int(3) } [1]=> array(4) { ["id"]=> int(3) ["username"]=> string(8) "xiongyue" ["name"]=> string(6) "熊岳" ["zzjg_id"]=> int(3) } [2]=> array(4) { ["id"]=> int(2) ["username"]=> string(3) "gxy" ["name"]=> string(9) "龚幸亚" ["zzjg_id"]=> int(3) } } 
     */
    public function getAdminList($uid = null)
    {
        $zzjgs = $this->getAllZzjgs();
        // 执行查询
        $a = Db::name($this->config['admin'])
            ->alias('a')
            ->join('__' . strtoupper($this->config['gcrm_zzjg_user']) . '__ ag', 'a.id = ag.uid', 'LEFT')
            ->field(['a.id'=>'id','a.username','a.nickname'=>'name','ag.zzjg_id'])
            ->whereIn('ag.zzjg_id', $zzjgs)
            ->order('id', 'desc')
            ->select();
        return $a;
    }
    /***
     * 根据当前用户id，得到用户组织机构，然后根据组织机构，取得当前用户所属组织机构及子组织机构下的所有用户的id
     * 返回数组 array(5) { [0]=> int(10) [1]=> int(8) [2]=> int(3) [3]=> int(2) [4]=> int(1) }
     */
    public function getAdminIds($uid = null)
    {
        $zzjgs = $this->getAllZzjgs();
        // 执行查询
        $a = Db::name($this->config['admin'])
            ->alias('a')
            ->join('__' . strtoupper($this->config['gcrm_zzjg_user']) . '__ ag', 'a.id = ag.uid', 'LEFT')
            //->field(['a.id'=>'id','a.username','a.nickname'=>'name','ag.zzjg_id'])
            ->whereIn('ag.zzjg_id', $zzjgs)
            ->order('id', 'desc')
            ->column('a.id');
        return $a;
    }

    /***
     * 根据当前用户id，得到用户组织机构，然后根据组织机构，取得组织机构及子组织机构下的所有客户列表
     */
    public function getKehuList()
    {
        $zzjgIds = $this->getAllZzjgs();  //取得当前用户的组织机构及子组织机构的ID号，以逗号分隔
        //Tree类的用法 ，输出多级选择，只能选择自己的组织机构及子组织机构以下的项目
        $List = Db::name($this->config['kehu'])
            ->field(['id', 'pid', 'name'])
            ->whereIn('zzjg_id', $zzjgIds)
            ->order('weigh', 'desc')
            ->select();
        return $List;
    }

    /***
     * 根据当前用户id，得到用户组织机构，然后根据组织机构，取得组织机构及子组织机构下的所有，没有完成的工单列表
     */
    public function getGdList()
    {
        $zzjgIds = $this->getAllZzjgs();  //取得当前用户的组织机构及子组织机构的ID号，以逗号分隔
        //Tree类的用法 ，输出多级选择，只能选择自己的组织机构及子组织机构以下的项目
        $List = Db::name($this->config['gd'])
            ->field(['id', 'pid', 'gddd'])
            ->whereNotIn('clzt_id',7)      //只读取没有结束的工单，1表示已结束的工单，
            ->whereIn('zzjg_id', $zzjgIds)
            ->order('weigh', 'desc')
            ->select();
        return $List;
    }    
}
