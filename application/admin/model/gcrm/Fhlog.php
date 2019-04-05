<?php

namespace app\admin\model\gcrm;

use think\Model;

class Fhlog extends Model
{
    // 表名
    protected $name = 'gcrm_fhlog';
    
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
        return ['1' => __('Status 1'),'0' => __('Status 0')];
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


    public function zzjg()
    {
        return $this->belongsTo('Zzjg', 'zzjg_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function gd()
    {
        return $this->belongsTo('gd', 'gd_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function fhlx(){
        return $this->belongsTo('sys', 'fhlx_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
