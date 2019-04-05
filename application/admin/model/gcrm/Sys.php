<?php

namespace app\admin\model\gcrm;

use think\Model;
use fast\Tree;

class Sys extends Model
{
    // 表名
    protected $name = 'gcrm_sys';

    /**
     * 取得工单的处理状态
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getClztTreeList()
    {
        //Tree类的用法 ，输出费用类型多级选择
        $where['pid'] = 1;
        $List = $this->field(['id', 'pid', 'name'])
            ->where($where)
            ->order('id', 'desc')
            ->select();
        Tree::instance()->init($List);
        $list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(1), 'name');
        $datas = [0 => __('None')];
        foreach ($list as $k => &$v) {
            $datas[$v['id']] = $v['name'];
        }
        return $datas;
    }
    
    /**
     * 取得工单的工单类型
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getGdlxTreeList()
    {
        //Tree类的用法 ，输出费用类型多级选择
        $where['pid'] = 8;
        $List = $this->field(['id', 'pid', 'name'])
            ->where($where)
            ->order('id', 'desc')
            ->select();
        Tree::instance()->init($List);
        $list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(8), 'name');
        $datas = [0 => __('None')];
        foreach ($list as $k => &$v) {
            $datas[$v['id']] = $v['name'];
        }
        return $datas;
    }

    /**
     * 取得发货的类型
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getFhlxTreeList()
    {
        //Tree类的用法 ，输出费用类型多级选择
        $where['pid'] = 14;
        $List = $this->field(['id', 'pid', 'name'])
            ->where($where)
            ->order('weigh', 'asc')
            ->select();
        Tree::instance()->init($List);
        $list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(14), 'name');
        $datas = [0 => __('None')];
        foreach ($list as $k => &$v) {
            $datas[$v['id']] = $v['name'];
        }
        return $datas;
    }

    /**
     * 取得售后服务的类型
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getFwlxTreeList()
    {
        //Tree类的用法 ，输出费用类型多级选择
        $where['pid'] = 20;
        $List = $this->field(['id', 'pid', 'name'])
            ->where($where)
            ->order('weigh', 'asc')
            ->select();
        Tree::instance()->init($List);
        $list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(20), 'name');
        $datas = [0 => __('None')];
        foreach ($list as $k => &$v) {
            $datas[$v['id']] = $v['name'];
        }
        return $datas;
    }



}
