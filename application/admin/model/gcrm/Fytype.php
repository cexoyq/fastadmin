<?php

namespace app\admin\model\gcrm;

use think\Model;
use fast\Tree;

class Fytype extends Model
{
    // 表名
    protected $name = 'gcrm_fytype';

    /**
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getFyTypeTreeList()
    {
        //Tree类的用法 ，输出费用类型多级选择
        $fyTypeList = $this->field(['id', 'pid', 'name'])->order('weigh', 'desc')->select();
        // 执行查询
        //$xmList = collection($xmModel->field(['id','name'])->order('weigh', 'desc')->select())->toArray();
        Tree::instance()->init($fyTypeList);
        $fyTypelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');

        $fyTypedata = [0 => __('None')];
        foreach ($fyTypelist as $k => &$v) {
            $fyTypedata[$v['id']] = $v['name'];
        }
        return $fyTypedata;
    }
}
