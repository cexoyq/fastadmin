<?php

namespace app\admin\model\gcrm;

use think\Model;
use fast\Tree;

class Admin extends Model
{
    // 表名
    protected $name = 'admin';
    
    /**
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getAdminTreeList()
    {
        //Tree类的用法 ，输出费用类型多级选择
        $List = $this->field(['id','nickname'])->order('id', 'desc')->select();
        // 执行查询
        //$xmList = collection($xmModel->field(['id','name'])->order('weigh', 'desc')->select())->toArray();
        $arrdata = [0 => __('None')];
        foreach ($List as $k => $v)
        {
            $arrdata[$v['id']] = $v['nickname'];
        }
        return $arrdata;
    }
}
