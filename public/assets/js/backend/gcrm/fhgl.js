define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gcrm/fhgl/index',
                    add_url: 'gcrm/fhgl/add',
                    edit_url: 'gcrm/fhgl/edit',
                    del_url: 'gcrm/fhgl/del',
                    multi_url: 'gcrm/fhgl/multi',
                    table: 'gcrm_fhlog',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'riqi', title: __('Riqi'), operate: 'RANGE', addclass: 'datetimerange' },
                        { field: 'dh', title: __('Dh') },
                        { field: 'kdgs', title: __('Kdgs') },
                        { field: 'kddh', title: __('Kddh') },
                        { field: 'mdgs', title: __('Mdgs') },
                        //{field: 'zzjg.name', title: __('Zzjg.name')},
                        { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            //表单验证
            // 初始化表格参数配置
            Table.api.init({
                /*extend: {
                    //index_url: 'gcrm/fhgl/add/index',
                    add_url: 'gcrm/fhgl/add-jl',
                    edit_url: 'gcrm/fhgl/edit-jl',
                    del_url: 'gcrm/fhgl/del-jl',
                    table: 'gcrm_fhlog',
                }*/
            });

            var table = $("#table");
            var $rowIndex = null;
            var $rowId = 0; //全局变量，用于添加行时累加
            // 初始化表格
            table.bootstrapTable({
                //url: $.fn.bootstrapTable.defaults.extend.index_url,
                //pk: 'id',
                uniqueId: "id", //每一行的唯一标识，一般为主键列
                sortName: 'weigh',
                escape: false,  //关闭 字符转义功能
                showToggle: false,  //关闭 浏览模式可以切换卡片视图和表格视图两种模式
                showColumns: false, //关闭 显示隐藏列可以快速切换字段列的显示和隐藏
                showExport: false,  //关闭导出功能
                pagination: false,                   //是否显示分页（*）
                commonSearch: false,    //关闭通用搜索功能
                search: false,   //关闭 键入关键词时将实时从服务端搜索数据
                striped: true,                      //是否显示行间隔色
                silent: true,   //为静默刷新数据
                //toolbar: '#toolbar',                //工具按钮用哪个容器
                /*onClickRow:function (row,$element) {
                    //更改行的选择色
                    $('.info').removeClass('info');
                    $($element).addClass('info');
                },*/
                columns: [
                    [
                        { field: "#", title: "#", checkbox: true }, //是否为首列复选框
                        { field: "id", title: "id", visible: false },  //visible: true 可视
                        { field: 'gd_id', title: __('Gd_id') },
                        { field: 'xm', title: __('Xm') },
                        { field: 'name', title: __('Name'), formatter: Table.api.formatter.label },
                        { field: 'xh', title: __('Xh') },
                        { field: 'sl', title: __('Sl') },
                        { field: 'sn', title: __('Sn'),visible: false },
                        { field: 'gzsm', title: __('Gzsm'),visible: false },
                        { field: 'fhlx_id', title: __('Fhlx.name') },
                        { field: 'fwlx_id', title: __('Fwlx_id') },
                        { field: 'wxf', title: __('Wxf'),visible: false },
                        { field: 'glr_id', title: __('Glr_id'),visible: false },
                        { field: 'jyr_id', title: __('Jyr_id'),visible: true },
                        { field: 'jydh', title: __('Jydh'),visible: false },
                        {
                            field: 'operate', title: __('Operate'), table: table,
                            //events: Table.api.events.operate,
                            formatter: //rowOperate,
                                function (value, row, index) {
                                    return '<a href="javascript:void(0);" id="delThisRow" class="delThisRow btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i>x</a>';
                                },
                            events: {
                                'click .delThisRow': function (e, value, row, index) {
                                    /*console.log("value:" + value);
                                    console.log("row:" + row);
                                    console.log("index:"+index);*/

                                    //$(this).parent().parent().remove();//删除表格里的行，但是没有删除数据
                                    //$("#table").bootstrapTable('remove', { field: "#", values: [true] });//根据选中的行来删除
                                    table.bootstrapTable('removeByUniqueId', row.id);
                                    var selectedContent = $("#table").bootstrapTable('getData');
                                    table.bootstrapTable('refreshOptions', { data: selectedContent });
                                },
                            },
                        }
                    ]
                ],
            });

            // 为表格绑定事件
            //Table.api.bindevent(table);//为渲染的表格绑定上增删改查等事件

            //////////////////////////
            //表格行点击事件
            table.on('click-row.bs.table', function (e, row, $element) {
                //更改行的选择色
                $('.info').removeClass('info');
                $($element).addClass('info');

                $rowIndex = $element.data('index');  //取得行号
                //console.log("选中行的index:" + $rowIndex + row.rowid + ";" + row.name);
                $("#c-gd_id").val(row.gd_id);
                //$("#c-gd_id").find("option[value='" + row.gd_id + "']").attr("selected",true);
                $("#c-xm").val(row.xm);
                $("#c-name").val(row.name);
                $('#c-xh').val(row.xh);
                $("#c-sl").val(row.sl);
                $("#c-sn").val(row.sn);
                $("#c-gzsm").val(row.gzsm);
                $("#c-fhlx_id").val(row.fhlx_id);
                //$("#c-fhlx_id").find("option[value='" + row.fhlx_id + "']").attr("selected",true);
                $("#c-fwlx_id").val(row.fwlx_id);
                //$("#c-fwlx_id").find("option[value='" + row.fwlx_id + "']").attr("selected",true);
                $("#c-wxf").val(row.wxf);
                $("#c-glr_id").val(row.glr_id);
                //$("#c-glr_id").find("option[value='" + row.glr_id + "']").attr("selected",true);
                $("#c-jyr_id").val(row.jyr_id);
                //$("#c-jyr_id").find("option[value='" + row.jyr_id + "']").attr("selected",true);
                $("#c-jydh").val(row.jydh);
            });
            //新增明细
            $(document).on('click', "#btn_add_mx", function () {
                var $form = $("form[role=form1]");
                if (!$form.isValid()) {
                    Toastr.error('请输入完整的明细数据!');
                    return;
                };
                var $data = [{
                    id: $rowId,
                    gd_id: $("#c-gd_id").val(),
                    xm: $("#c-xm").val(),
                    name: $("#c-name").val(),
                    xh: $('#c-xh').val(),
                    sl: $("#c-sl").val(),
                    sn: $("#c-sn").val(),
                    gzsm: $("#c-gzsm").val(),
                    fhlx_id: $("#c-fhlx_id").val(),
                    fwlx_id:$("#c-fwlx_id").val(),
                    wxf:$("#c-wxf").val(),
                    glr_id:$("#c-glr_id").val(),    //关联人
                    jyr_id: $("#c-jyr_id").val(),
                    jydh: $("#c-jydh").val(),
                }];
                $rowId++;   //要与表格的index同步，index是从0开始的，不同步的话，更新行的时候会加1
                $("#table").bootstrapTable('append', $data)
            });
            //修改明细
            $(document).on('click', "#btn_edit_mx", function () {
                var $form = $("form[role=form1]");
                if (!$form.isValid()) {
                    Toastr.error('请输入完整的明细数据!');
                    return;
                };
                var $data = {
                    "gd_id": $("#c-gd_id").val(),
                    "xm": $("#c-xm").val(),
                    "name": $("#c-name").val(),
                    "xh": $('#c-xh').val(),
                    "sl": $("#c-sl").val(),
                    "sn": $("#c-sn").val(),
                    "gzsm": $("#c-gzsm").val(),
                    "fhlx_id": $("#c-fhlx_id").val(),
                    "fwlx_id":$("#c-fwlx_id").val(),
                    "wxf":$("#c-wxf").val(),
                    "glr_id":$("#c-glr_id").val(),    //关联人
                    "jyr_id": $("#c-jyr_id").val(),
                    "jydh": $("#c-jydh").val(),
                }
                $("#table").bootstrapTable('updateRow', { index: $rowIndex, row: $data });
            });
            //发货类型 下拉框更改时，选择为借用还回显示隐藏框
            $(document).on('change', "#c-fhlx_id", function () {
                if ($(this).val() == 16) {
                    //如果是是借用返回，则显示出借用信息，并设置验证为一定要求输入
                    $("#wxf-zone").toggleClass("hide", true);
                    //$("#c-wxf").removeAttr("data-rule", "required");
                    $("#c-wxf").val("0");

                    $("#relation-zone").toggleClass("hide", false);
                    $("#c-jyr_id").attr("data-rule", "required");
                    $("#c-jydh").attr("data-rule", "required");
                } else if ($(this).val() == 15) {
                    //如果是返修，则显示公司收取的维修费
                    $("#relation-zone").toggleClass("hide", true);
                    $("#c-jyr_id").removeAttr("data-rule", "required");
                    $("#c-jydh").removeAttr("data-rule", "required");
                    $("#c-jyr_id").val("0");
                    $("#c-jydh").val("0");

                    $("#wxf-zone").toggleClass("hide", false);
                    //$("#c-wxf").attr("data-rule", "required");
                }
                else {
                    //先设置所有隐藏的元素隐藏起来
                    $("#relation-zone").toggleClass("hide", true);
                    $("#wxf-zone").toggleClass("hide", true);
                    //再移除 表单验证
                    $("#c-jyr_id").removeAttr("data-rule", "required");
                    $("#c-jydh").removeAttr("data-rule", "required");
                    //$("#c-wxf").removeAttr("data-rule", "required");
                    //再清空
                    $("#c-jyr_id").val("0");
                    $("#c-jydh").val("0");
                    $("#c-wxf").val("0");
                }
                //$("#relation-zone").toggleClass("hide", false);
            });
            //ajax提交
            $(document).on('click', ".btn-execute", function () {
                var $form = $(this).closest("form");
                if (!$form.isValid()) {
                    Toastr.error('请输入完整!');
                    return;
                };
                //var $f = $form.serializeArray();
                var $f = $form.serializeArray();   //以url方式提交，数据是：?a=1&b=2 
                var $f = JSON.stringify($form.serializeArray());
                var $selectedContent = $("#table").bootstrapTable('getData');//取得表格数据
                //console.log($form.serializeArray());   
                //console.log($selectedContent);
                //转换序列化的表单数据到数组
                var o = {};
                $.each($form.serializeArray(), function () {
                    if (o[this.name]) {
                        if (!o[this.name].push) {
                            o[this.name] = [o[this.name]];
                        }
                        o[this.name].push(this.value || '');
                    } else {
                        o[this.name] = this.value || '';
                    }
                });
                //console.log(o);
                var $data = { row: o, mx: $selectedContent };
                console.log(JSON.stringify($data));
                //////
                Fast.api.ajax({
                    type: 'get',
                    url: "gcrm/fhgl/add1",
                    //contentType: "application/json", //必须这样写
                    //dataType: "json",
                    data: $data,
                }, function (data, ret) {
                    //成功的回调
                    console.log("成功的回调!");
                    console.log("返回数据:" + JSON.stringify(data));
                    //console.log("ret:" + JSON.stringify(ret));
                    return true;
                }, function () {
                    //失败的回调
                    console.log("失败的回调!");
                    return false;
                });
            });

            Controller.api.bindevent();
        },
        edit: function () {
                        //表单验证
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gcrm/fhglmx/index1',
                    //add_url: 'gcrm/fhglmx/add',
                    //edit_url: 'gcrm/fhglmx/edit',
                    del_url: 'gcrm/fhglmx/del',
                    table: 'gcrm_fhlogmx',
                }
            });

            var table = $("#table");
            var $rowIndex = null;
            var $rowId = 0; //全局变量，用于添加行时累加
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                //pk: 'id',
                uniqueId: "rowid", //每一行的唯一标识，一般为主键列
                sortName: 'weigh',
                escape: false,  //关闭 字符转义功能
                showToggle: false,  //关闭 浏览模式可以切换卡片视图和表格视图两种模式
                showColumns: false, //关闭 显示隐藏列可以快速切换字段列的显示和隐藏
                showExport: false,  //关闭导出功能
                pagination: false,                   //是否显示分页（*）
                commonSearch: false,    //关闭通用搜索功能
                search: false,   //关闭 键入关键词时将实时从服务端搜索数据
                striped: true,                      //是否显示行间隔色
                silent: true,   //为静默刷新数据
                //toolbar: '#toolbar',                //工具按钮用哪个容器
                /*onClickRow:function (row,$element) {
                    //更改行的选择色
                    $('.info').removeClass('info');
                    $($element).addClass('info');
                },*/
                queryParams : {
                    pid:$("#c-id").val(),   //单据的id号
                },
                columns: [
                    [
                        { field: "#", title: "#", checkbox: true }, //是否为首列复选框
                        { field: "id", title: "id", visible: false },  //visible: true 可视
                        { field: "rowid", title: "rowid", visible: false },  //visible: true 可视
                        { field: 'pid', title: __('pid'),visible:false },   //单据编号
                        { field: 'gd.gddd', title: __('Gd_id') },
                        { field: 'xm', title: __('Xm') },
                        { field: 'name', title: __('Name'), formatter: Table.api.formatter.label },
                        { field: 'xh', title: __('Xh') },
                        { field: 'sl', title: __('Sl') },
                        { field: 'sn', title: __('Sn'),visible: false },
                        { field: 'gzsm', title: __('Gzsm'),visible: false },
                        { field: 'fhlx.name', title: __('Fhlx.name') },
                        { field: 'fwlx.name', title: __('Fwlx_id') },
                        { field: 'wxf', title: __('Wxf'),visible: false },
                        { field: 'glr.nickname', title: __('Glr_id'),visible: false },
                        { field: 'jyr.nickname', title: __('Jyr_id'),visible: true },
                        { field: 'jydh', title: __('Jydh'),visible: false },
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate},
                        /*{
                            field: 'operate', title: __('Operate'), table: table,
                            //events: Table.api.events.operate,
                            formatter: //rowOperate,
                                function (value, row, index) {
                                    return '<a href="javascript:void(0);" id="delThisRow" class="delThisRow btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i>x</a>';
                                },
                            events: {
                                'click .delThisRow': function (e, value, row, index) {
                                    console.log("value:" + value);
                                    console.log("row:" + row);
                                    console.log("index:"+index);

                                    //$(this).parent().parent().remove();//删除表格里的行，但是没有删除数据
                                    //$("#table").bootstrapTable('remove', { field: "#", values: [true] });//根据选中的行来删除
                                    table.bootstrapTable('removeByUniqueId', row.rowid);
                                    var selectedContent = $("#table").bootstrapTable('getData');
                                    table.bootstrapTable('refreshOptions', { data: selectedContent });
                                },
                            },
                        }*/
                    ]
                ],
            });

            // 为表格绑定事件
            Table.api.bindevent(table);//为渲染的表格绑定上增删改查等事件

            //////////////////////////
            //表格行点击事件
            table.on('click-row.bs.table', function (e, row, $element) {
                //更改行的选择色
                $('.info').removeClass('info');
                $($element).addClass('info');

                $rowIndex = $element.data('index');  //取得行号
                //console.log(row);
                console.log("rowIndex:" + $rowIndex);
                //console.log("关联工单号：" + row.gd_id);
                //console.log("选中行的index:" + $rowIndex + row.rowid + ";" + row.name);
                $("#c-mxid").val(row.id);
                $("#c-mxpid").val(row.pid);
                $("#c-gd_id").val(row.gd_id);
                //$("#c-gd_id").find("option[value='" + row.gd_id + "']").attr("selected",true);
                $("#c-xm").val(row.xm);
                $("#c-name").val(row.name);
                $('#c-xh').val(row.xh);
                $("#c-sl").val(row.sl);
                $("#c-sn").val(row.sn);
                $("#c-gzsm").val(row.gzsm);
                $("#c-fhlx_id").val(row.fhlx_id);
                //$("#c-fhlx_id").find("option[value='" + row.fhlx_id + "']").attr("selected",true);
                $("#c-fwlx_id").val(row.fwlx_id);
                //$("#c-fwlx_id").find("option[value='" + row.fwlx_id + "']").attr("selected",true);
                $("#c-wxf").val(row.wxf);
                $("#c-glr_id").val(row.glr_id);
                //$("#c-glr_id").find("option[value='" + row.glr_id + "']").attr("selected",true);
                $("#c-jyr_id").val(row.jyr_id);
                //$("#c-jyr_id").find("option[value='" + row.jyr_id + "']").attr("selected",true);
                $("#c-jydh").val(row.jydh);
            });
            //新增明细
            $(document).on('click', "#btn_add_mx", function () {
                var $form = $("form[role=form1]");
                if (!$form.isValid()) {
                    Toastr.error('请输入完整的明细数据!');
                    return;
                };
                var $row = {
                    rowid: $rowId,
                    pid:$("#c-id").val(),
                    gd_id: $("#c-gd_id").val(),
                    xm: $("#c-xm").val(),
                    name: $("#c-name").val(),
                    xh: $('#c-xh').val(),
                    sl: $("#c-sl").val(),
                    sn: $("#c-sn").val(),
                    gzsm: $("#c-gzsm").val(),
                    fhlx_id: $("#c-fhlx_id").val(),
                    fwlx_id:$("#c-fwlx_id").val(),
                    wxf:$("#c-wxf").val(),
                    glr_id:$("#c-glr_id").val(),    //关联人
                    jyr_id: $("#c-jyr_id").val(),
                    jydh: $("#c-jydh").val(),
                };
                $rowId++;   //要与表格的index同步，index是从0开始的，不同步的话，更新行的时候会加1
                var $data={data:$row};
                console.log("新增行:" + $rowId);
                console.log(JSON.stringify($data));
                //////
                Fast.api.ajax({
                    type: 'get',
                    url: "gcrm/fhglmx/add1",
                    //contentType: "application/json", //必须这样写
                    //dataType: "json",
                    data: $data,
                }, function (data, ret) {
                    //成功的回调
                    //Toastr.success('ok!');
                    $("#table").bootstrapTable('refresh');
                    return true;
                }, function () {
                    //失败的回调
                    //Toastr.error('更新明细失败!');
                    return false;
                });
                //$("#table").bootstrapTable('append', [$row])//表格尾添加行
            });
            //修改明细
            $(document).on('click', "#btn_edit_mx", function () {
                var $form = $("form[role=form1]");
                if (!$form.isValid()) {
                    Toastr.error('请输入完整的明细数据!');
                    return;
                };
                var $row = {
                    "id":$("#c-mxid").val(),
                    "pid":$("#c-mxpid").val(),
                    "gd_id": $("#c-gd_id").val(),
                    "xm": $("#c-xm").val(),
                    "name": $("#c-name").val(),
                    "xh": $('#c-xh').val(),
                    "sl": $("#c-sl").val(),
                    "sn": $("#c-sn").val(),
                    "gzsm": $("#c-gzsm").val(),
                    "fhlx_id": $("#c-fhlx_id").val(),
                    "fwlx_id":$("#c-fwlx_id").val(),
                    "wxf":$("#c-wxf").val(),
                    "glr_id":$("#c-glr_id").val(),    //关联人
                    "jyr_id": $("#c-jyr_id").val(),
                    "jydh": $("#c-jydh").val(),
                }
                var $data={data:$row};
                console.log("更新行:" + $rowIndex);
                console.log(JSON.stringify($data));
                //////
                Fast.api.ajax({
                    type: 'get',
                    url: "gcrm/fhglmx/edit1",
                    //contentType: "application/json", //必须这样写
                    //dataType: "json",
                    data: $data,
                }, function (data, ret) {
                    //成功的回调
                    //Toastr.success('ok!');
                    $("#table").bootstrapTable('refresh');
                    return true;
                }, function () {
                    //失败的回调
                    //Toastr.error('更新明细失败!');
                    return false;
                });
                //$("#table").bootstrapTable('updateRow', { index: $rowIndex, row: $row });//表格更新行
            });
            //发货类型 下拉框更改时，选择为借用还回显示隐藏框
            $(document).on('change', "#c-fhlx_id", function () {
                if ($(this).val() == 16) {
                    //如果是是借用返回，则显示出借用信息，并设置验证为一定要求输入
                    $("#wxf-zone").toggleClass("hide", true);
                    //$("#c-wxf").removeAttr("data-rule", "required");
                    $("#c-wxf").val("0");

                    $("#relation-zone").toggleClass("hide", false);
                    $("#c-jyr_id").attr("data-rule", "required");
                    $("#c-jydh").attr("data-rule", "required");
                } else if ($(this).val() == 15) {
                    //如果是返修，则显示公司收取的维修费
                    $("#relation-zone").toggleClass("hide", true);
                    $("#c-jyr_id").removeAttr("data-rule", "required");
                    $("#c-jydh").removeAttr("data-rule", "required");
                    $("#c-jyr_id").val("0");
                    $("#c-jydh").val("0");

                    $("#wxf-zone").toggleClass("hide", false);
                    //$("#c-wxf").attr("data-rule", "required");
                }
                else {
                    //先设置所有隐藏的元素隐藏起来
                    $("#relation-zone").toggleClass("hide", true);
                    $("#wxf-zone").toggleClass("hide", true);
                    //再移除 表单验证
                    $("#c-jyr_id").removeAttr("data-rule", "required");
                    $("#c-jydh").removeAttr("data-rule", "required");
                    //$("#c-wxf").removeAttr("data-rule", "required");
                    //再清空
                    $("#c-jyr_id").val("0");
                    $("#c-jydh").val("0");
                    $("#c-wxf").val("0");
                }
                //$("#relation-zone").toggleClass("hide", false);
            });
            //ajax提交
            $(document).on('click', ".btn-execute", function () {
                var $form = $(this).closest("form");
                if (!$form.isValid()) {
                    Toastr.error('请输入完整!');
                    return;
                };
                //var $f = $form.serializeArray();
                var $f = $form.serializeArray();   //以url方式提交，数据是：?a=1&b=2 
                var $f = JSON.stringify($form.serializeArray());
                var $selectedContent = $("#table").bootstrapTable('getData');//取得表格数据
                //console.log($form.serializeArray());   
                //console.log($selectedContent);
                //转换序列化的表单数据到数组
                var o = {};
                $.each($form.serializeArray(), function () {
                    if (o[this.name]) {
                        if (!o[this.name].push) {
                            o[this.name] = [o[this.name]];
                        }
                        o[this.name].push(this.value || '');
                    } else {
                        o[this.name] = this.value || '';
                    }
                });
                //console.log(o);
                var $data = { row: o, mx: $selectedContent };
                console.log(JSON.stringify($data));
                //////
                Fast.api.ajax({
                    type: 'get',
                    url: "gcrm/fhgl/edit1",
                    //contentType: "application/json", //必须这样写
                    //dataType: "json",
                    data: $data,
                }, function (data, ret) {
                    //成功的回调
                    return true;
                }, function () {
                    //失败的回调
                    return false;
                });
            });
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
                Form.api.bindevent($("form[role=form1]"));

            }
        }
    };
    return Controller;
});