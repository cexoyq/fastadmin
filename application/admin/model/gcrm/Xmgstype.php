<?php

namespace app\admin\model\gcrm;

use think\Model;
use fast\Tree;

class Xmgstype extends Model
{
    // 表名
    protected $name = 'gcrm_xmgstype';
    

    /**
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getGsTypeTreeList()
    {
        //Tree类的用法 ，输出费用类型多级选择
        $List = $this->field(['id', 'pid', 'name'])->order('weigh', 'desc')->select();
        // 执行查询
        //$xmList = collection($xmModel->field(['id','name'])->order('weigh', 'desc')->select())->toArray();
        Tree::instance()->init($List);
        $list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');

        $arrdata = [0 => __('None')];
        foreach ($list as $k => &$v) {
            $arrdata[$v['id']] = $v['name'];
        }
        return $arrdata;
    }

}
