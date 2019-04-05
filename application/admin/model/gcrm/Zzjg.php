<?php

namespace app\admin\model\gcrm;

use think\Model;
use fast\Tree;

class Zzjg extends Model
{
    // 表名
    protected $name = 'gcrm_zzjg';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'status_text',
        'deletetime_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getStatusList()
    {
        //return ['1' => __('Status 1'),'0' => __('Status 0')];
    }     


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getDeletetimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['deletetime']) ? $data['deletetime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setDeletetimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    /**
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getZzjgTreeList()
    {
        $authXm = new AuthXm();
        $zzjg = $authXm->getZzjg();   //取得当前用户所属的组织机构
        $zzjgIds = $authXm->getAllZzjgs();  //取得当前用户的组织机构及子组织机构的ID号，以逗号分隔
        //Tree类的用法 ，输出费用类型多级选择
        $List = $this->field(['id', 'pid', 'name'])->where('id','IN',$zzjgIds)->order('weigh', 'desc')->select();
        //$List = $this->field(['id', 'pid', 'name'])->order('weigh', 'desc')->select();
        // 执行查询
        //$xmList = collection($xmModel->field(['id','name'])->order('weigh', 'desc')->select())->toArray();
        Tree::instance()->init($List);
        $list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray($zzjg['pid']), 'name');    //getTreeArray只有取父级的ID号才能将本级组织机构填充到树形数组
        $arrdata = [0 => __('None')];
        foreach ($list as $k => &$v) {
            $arrdata[$v['id']] = $v['name'];
        }
        //var_dump($arrdata);
        return $arrdata;
    }


}
