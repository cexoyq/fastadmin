<?php

namespace app\admin\model\gcrm;

use think\Model;

class Fhlogmx extends Model
{
    // 表名
    protected $name = 'gcrm_fhlogmx';
    
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


    public function jyr()
    {
        return $this->belongsTo('Admin', 'jyr_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function glr()
    {
        return $this->belongsTo('Admin', 'glr_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function fwlx()
    {
        return $this->belongsTo('Sys', 'fwlx_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function fhlx()
    {
        return $this->belongsTo('Sys', 'fhlx_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function gd()
    {
        return $this->belongsTo('Gd', 'gd_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    /**
     * 增加 单据明细数据
     * @access public
     * @param array  $data     数据
     * @param integer  $pid       单据的ID号是明细数据的pid
     * @return $arr|false
     */
    public function add($data,$pid){
        foreach($data as &$v){
            $v["pid"] = $pid;
            unset($v["id"]);
        }
        $ok = $this->allowField(true)->saveAll($data,false);
        if (is_array($ok)){
            return true;
        }
        return false;
    }

    /**
     * 更新 单据明细数据
     * @access public
     * @param array  $data     数据
     * @param integer  $pid       单据的ID号是明细数据的pid
     * @return $arr|false
     */
    public function edit($data,$pid){
        foreach($data as &$v){
            $v["pid"] = $pid;
            if ($v["id"] == 0){
                unset($v["id"]);
            }    //如果id字段值为0，则删除此元素
        }
        $ok = $this->allowField(true)->saveAll($data,true);
        if (is_array($ok)){
            return true;
        }
        return false;
    }
}
