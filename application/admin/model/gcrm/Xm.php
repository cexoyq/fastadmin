<?php

namespace app\admin\model\gcrm;

use think\Model;
use fast\Tree;

class Xm extends Model
{
    // 表名
    protected $name = 'gcrm_xm';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
        //'deletetime_text',
        //'status_text'
    ];


    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    /**
     * return 用于在前台页面生成树形选择{:build_select('row[xm_id]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required'])}
     * 用法：$xmModel = new \app\admin\model\gcrm\Xm;  $xmdata = $xmModel->getXmTreeList();  $this->view->assign('xmdata', $xmdata);
     */
    public function getXmTreeList()
    {
        $authXm = new AuthXm();
        $xmList = $authXm->getXmList();
        //$zzjgIds = $authXm->getAllZzjgs();  //取得当前用户的组织机构及子组织机构的ID号，以逗号分隔
        //Tree类的用法 ，输出多级选择，只能选择自己的组织机构及子组织机构以下的项目
        //$xmList = $this->field(['id', 'pid', 'name'])->whereIn('zzjg_id',$zzjgIds)->order('weigh', 'desc')->select();

        Tree::instance()->init($xmList);
        $xmlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
        $xmdata = [0 => __('None')];
        foreach ($xmlist as $k => &$v) {
                $xmdata[$v['id']] = $v['name'];
            }
        //$this->view->assign('xmdata', $xmdata);
        return $xmdata;
    }

    public function getStatusList()
    {
        //return ['1' => __('Status 1'),'0' => __('Status 0')];
    }


    public function getDeletetimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['deletetime']) ? $data['deletetime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setDeletetimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


    public function kehu()
    {
        return $this->belongsTo('Kehu', 'kehu_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function xmcpx()
    {
        return $this->belongsTo('Xmcpx', 'xmcpx_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function xmgstype()
    {
        return $this->belongsTo('Xmgstype', 'xmgstype_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function xmshiyebu()
    {
        return $this->belongsTo('Xmshiyebu', 'xmshiyebu_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function xmtype()
    {
        return $this->belongsTo('Xmtype', 'xmtype_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function zzjg()
    {
        return $this->belongsTo('Zzjg', 'zzjg_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
