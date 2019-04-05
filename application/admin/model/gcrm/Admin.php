<?php

namespace app\admin\model\gcrm;

use think\Model;
use fast\Tree;

class Admin extends Model
{
    // 表名
    protected $name = 'admin';
    
    /**
     * 取得当前组织机构的工程师用户
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getAdminGCSTreeList()
    {
        $authXm = new AuthXm();
        $zzjg = $authXm->getZzjg();   //取得当前用户所属的组织机构

        //Tree类的用法 ，输出费用类型多级选择
        //$List = $this->field(['id','nickname'])->order('id', 'desc')->select();
        $List =  $authXm->getAdminList();
        // 执行查询
        //$xmList = collection($xmModel->field(['id','name'])->order('weigh', 'desc')->select())->toArray();
        //Tree::instance()->init($List);
        //$list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'nickname');    //getTreeArray只有取父级的ID号才能将本级组织机构填充到树形数组
        $arrdata = [0 => __('None')];
        foreach ($List as $k => &$v) {
            $arrdata[$v['id']] = $v['nickname'];
        }
        //var_dump($arrdata);
        return $arrdata;
    }

    /**
     * 取得当前组织机构的销售用户
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getAdminXSTreeList()
    {
        $authXm = new AuthXm();
        $zzjg = $authXm->getZzjg();   //取得当前用户所属的组织机构

        //Tree类的用法 ，输出费用类型多级选择
        //$List = $this->field(['id','nickname'])->order('id', 'desc')->select();
        $List =  $authXm->getAdminList();
        // 执行查询
        //$xmList = collection($xmModel->field(['id','name'])->order('weigh', 'desc')->select())->toArray();
        //Tree::instance()->init($List);
        //$list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'nickname');    //getTreeArray只有取父级的ID号才能将本级组织机构填充到树形数组
        $arrdata = [0 => __('None')];
        foreach ($List as $k => &$v) {
            $arrdata[$v['id']] = $v['nickname'];
        }
        //var_dump($arrdata);
        return $arrdata;
    }

    /**
     * 取得当前组织机构的所有用户
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getZzjgTreeList()
    {
        $authXm = new AuthXm();
        $zzjg = $authXm->getZzjg();   //取得当前用户所属的组织机构

        //Tree类的用法 ，输出费用类型多级选择
        //$List = $this->field(['id','nickname'])->order('id', 'desc')->select();
        $List =  $authXm->getAdminList();
        // 执行查询
        //$xmList = collection($xmModel->field(['id','name'])->order('weigh', 'desc')->select())->toArray();
        //Tree::instance()->init($List);
        //$list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'nickname');    //getTreeArray只有取父级的ID号才能将本级组织机构填充到树形数组
        $arrdata = [0 => __('None')];
        foreach ($List as $k => &$v) {
            $arrdata[$v['id']] = $v['nickname'];
        }
        //var_dump($arrdata);
        return $arrdata;
    }

}
