<?php

namespace app\admin\model\gcrm;

use think\Model;

class Dhlog extends Model
{
    // 表名
    protected $name = 'gcrm_dhlog';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        //'status_text',
        //'deletetime_text'
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
        //return ['1' => '启用','0' => '禁用'];
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


    public function xm()
    {
        return $this->belongsTo('Xm', 'xm_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
