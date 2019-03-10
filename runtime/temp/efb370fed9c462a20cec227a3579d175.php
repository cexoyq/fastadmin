<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:67:"/data/fastadmin/public/../application/admin/view/gcrm/xmgl/add.html";i:1552221365;s:58:"/data/fastadmin/application/admin/view/layout/default.html";i:1547349021;s:55:"/data/fastadmin/application/admin/view/common/meta.html";i:1547349021;s:57:"/data/fastadmin/application/admin/view/common/script.html";i:1547349021;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !$config['fastadmin']['multiplenav']): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Pid'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('row[pid]', $xmdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required']); ?>
            <!--input id="c-parent_id" data-rule="required" data-source="parent/index" class="form-control selectpage" name="row[parent_id]" type="text" value=""-->
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Cpx_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('row[cpx_id]', $cpxdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required']); ?>
            <!--input id="c-cpx_id" data-rule="required" data-source="cpx/index" class="form-control selectpage" name="row[cpx_id]" type="text" value=""-->
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Shiyebu_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('row[shiyebu_id]', $sybdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required']); ?>
            <!--input id="c-shiyebu_id" data-rule="required" data-source="shiyebu/index" class="form-control selectpage" name="row[shiyebu_id]" type="text" value=""-->
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Gstype_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('row[gstype_id]', $gstypedata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required']); ?>
            <!--input id="c-gstype_id" data-rule="required" data-source="gstype/index" class="form-control selectpage" name="row[gstype_id]" type="text" value=""-->
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-name" data-rule="required" class="form-control" name="row[name]" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Zzjg_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('row[zzjg_id]', $zzjgdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required']); ?>
            <!--input id="c-zzjg_id" data-rule="required" data-source="zzjg/index" class="form-control selectpage" name="row[zzjg_id]" type="text" value=""-->
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Xmtype_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('row[xmtype_id]', $xmtypedata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required']); ?>
            <!--input id="c-xmtype_id" data-rule="required" data-source="xmtype/index" class="form-control selectpage" name="row[xmtype_id]" type="text" value=""-->
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Htbh'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-htbh" data-rule="required" class="form-control" name="row[htbh]" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Crmbh'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-crmbh" class="form-control" name="row[crmbh]" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Htriqi'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-htriqi" class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="row[htriqi]" type="text" value="<?php echo date('Y-m-d'); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Htjine'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-htJinE" data-rule="required" class="form-control" name="row[htJinE]" type="number">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Xiaoshou_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('row[xiaoShou_id]', $userdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required']); ?>
            <!--input id="c-xiaoShou_id" data-rule="required" data-source="xiaoShou/index" class="form-control selectpage" name="row[xiaoShou_id]" type="text" value=""-->
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Gongchengshi_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('row[gongChengShi_id]', $userdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required']); ?>
            <!--input id="c-gongChengShi_id" data-rule="required" data-source="gongChengShi/index" class="form-control selectpage" name="row[gongChengShi_id]" type="text" value=""-->
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Xiangmujingli_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('row[xiangMuJingLi_id]', $userdata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required']); ?>
            <!--input id="c-xiangMuJingLi_id" data-rule="required" data-source="xiangMuJingLi/index" class="form-control selectpage" name="row[xiangMuJingLi_id]" type="text" value=""-->
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Kehu_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('row[kehu_id]', $kehudata, null, ['class'=>'form-control selectpicker', 'data-rule'=>'required']); ?>
            <!--input id="c-kehu_id" data-rule="required" data-source="kehu/index" class="form-control selectpage" name="row[kehu_id]" type="text" value=""-->
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Kehulianxiren'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-kehuLianXiRen" class="form-control" name="row[kehuLianXiRen]" type="number">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Kehulianxirentel'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-kehuLianXiRenTel" class="form-control" name="row[kehuLianXiRenTel]" type="number">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Xmnr'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-xmnr" class="form-control" name="row[xmnr]" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Tag'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-tag" class="form-control" name="row[tag]" type="text">
        </div>
    </div>
    <!--div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Weigh'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-weigh" class="form-control" name="row[weigh]" type="number">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Deletetime'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-deletetime" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[deletetime]" type="text" value="<?php echo date('Y-m-d H:i:s'); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            
            <div class="radio">
            <?php if(is_array($statusList) || $statusList instanceof \think\Collection || $statusList instanceof \think\Paginator): if( count($statusList)==0 ) : echo "" ;else: foreach($statusList as $key=>$vo): ?>
            <label for="row[status]-<?php echo $key; ?>"><input id="row[status]-<?php echo $key; ?>" name="row[status]" type="radio" value="<?php echo $key; ?>" <?php if(in_array(($key), explode(',',"1"))): ?>checked<?php endif; ?> /> <?php echo $vo; ?></label> 
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>

        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Remark'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-remark" class="form-control" name="row[remark]" type="text">
        </div>
    </div-->
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Description'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-description" class="form-control" name="row[description]" type="text">
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>