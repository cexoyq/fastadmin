<?php

namespace app\admin\model\gcrm;

use think\Model;
use fast\Tree;

class Kehu extends Model
{
    // 表名
    protected $name = 'gcrm_kehu';
    
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
    public function getKehuTreeList()
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
