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
                        { field: 'bh', title: __('Bh') },
                        { field: 'gd.bh', title: __('Gd.bh') },
                        { field: 'xm', title: __('Xm') },
                        { field: 'name', title: __('Name') },
                        { field: 'xh', title: __('Xh') },
                        { field: 'sl', title: __('Sl') },
                        { field: 'fhlx.name', title: __('Fhlx.name') },
                        { field: 'jydh', title: __('Jydh') },
                        { field: 'kdgs', title: __('Kdgs') },
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
                        { field: "id", title:"id",visible: true},  //visible: true 可视
                        { field: 'gd_id', title: __('Gd_id') },
                        { field: 'xm', title: __('Xm') },
                        { field: 'name', title: __('Name'), formatter: Table.api.formatter.label },
                        { field: 'xh', title: __('Xh') },
                        { field: 'sl', title: __('Sl') },
                        { field: 'sn', title: __('Sn') },
                        { field: 'gzsm', title: __('Gzsm') },
                        { field: 'fhlx_id', title: __('Fhlx.name') },
                        { field: 'jyr', title: __('Jyr') },
                        { field: 'jydh', title: __('Jydh') },
                        {
                            field: 'operate', title: __('Operate'), table: table, 
                            //events: Table.api.events.operate,
                            formatter: //rowOperate,
                                function(value, row, index) {
                                    return '<a href="javascript:void(0);" id="delThisRow" class="delThisRow btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i>x</a>';
                                },
                            events: {
                                'click .delThisRow': function(e, value, row, index) {
                                    /*console.log("value:" + value);
                                    console.log("row:" + row);
                                    console.log("index:"+index);*/
                                    
                                    //$(this).parent().parent().remove();//删除表格里的行，但是没有删除数据
                                    //$("#table").bootstrapTable('remove', { field: "#", values: [true] });//根据选中的行来删除
                                    table.bootstrapTable('removeByUniqueId', row.id);
                                    var selectedContent = $("#table").bootstrapTable('getData');
                                    table.bootstrapTable('refreshOptions',{data:selectedContent});
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
                console.log(row);
                console.log("rowIndex:" + $rowIndex); 
                //console.log("选中行的index:" + $rowIndex + row.rowid + ";" + row.name);
                $("#gd_id").val(row.gd_id);
                $("#c-xm").val(row.xm);
                $("#c-name").val(row.name);
                $('#c-xh').val(row.xh);
                $("#c-sl").val(row.sl);
                $("#c-sn").val(row.sn);
                $("#c-gzsm").val(row.gzsm);
                $("#c-fhlx_id").val(row.fhlx_id);
                $("#c-jyr").val(row.jyr);
                $("#c-jydh").val(row.jydh);
            });
            //新增明细
            $(document).on('click', "#btn_add_mx", function () {
                var $data = [{
                    id: $rowId,
                    gd_id: $("#gd_id").val(),
                    xm: $("#c-xm").val(),
                    name: $("#c-name").val(),
                    xh: $('#c-xh').val(),
                    sl: $("#c-sl").val(),
                    sn: $("#c-sn").val(),
                    gzsm: $("#c-gzsm").val(),
                    fhlx_id: $("#c-fhlx_id").val(),
                    jyr: $("#c-jyr").val(),
                    jydh: $("#c-jydh").val(),
                }];
                $rowId++;   //要与表格的index同步，index是从0开始的，不同步的话，更新行的时候会加1
                $("#table").bootstrapTable('append', $data)
            });
            //修改明细
            $(document).on('click', "#btn_edit_mx", function () {
                var $data = {
                    "gd_id": $("#gd_id").val(),
                    "xm": $("#c-xm").val(),
                    "name": $("#c-name").val(),
                    "xh": $('#c-xh').val(),
                    "sl": $("#c-sl").val(),
                    "sn": $("#c-sn").val(),
                    "gzsm": $("#c-gzsm").val(),
                    "fhlx_id": $("#c-fhlx_id").val(),
                    "jyr": $("#c-jyr").val(),
                    "jydh": $("#c-jydh").val(),
                }
                console.log("更新行:" + $rowIndex);
                $("#table").bootstrapTable('updateRow', { index: $rowIndex, row: $data });
            });
            //发货类型 下拉框更改时，选择为借用还回显示隐藏框
            $(document).on('change', "#c-fhlx_id", function () {
                if ($(this).val() == 16) {
                    //如果是是借用返回，则显示出借用信息，并设置验证为一定要求输入
                    $("#wxf-zone").toggleClass("hide", true);
                    $("#c-wxf").removeAttr("data-rule", "required");

                    $("#relation-zone").toggleClass("hide", false);
                    $("#c-jyr").attr("data-rule", "required");
                    $("#c-jydh").attr("data-rule", "required");
                } else if ($(this).val() == 15) {
                    //如果是返修，则显示公司收取的维修费
                    $("#relation-zone").toggleClass("hide", true);
                    $("#c-jyr").removeAttr("data-rule", "required");
                    $("#c-jydh").removeAttr("data-rule", "required");

                    $("#wxf-zone").toggleClass("hide", false);
                    $("#c-wxf").attr("data-rule", "required");
                }
                else{
                    //先设置所有隐藏的元素隐藏起来
                    $("#relation-zone").toggleClass("hide", true);
                    $("#wxf-zone").toggleClass("hide", true);
                    //再移除 表单验证
                    $("#c-jyr").removeAttr("data-rule", "required");
                    $("#c-jydh").removeAttr("data-rule", "required");
                    $("#c-wxf").removeAttr("data-rule", "required");
                }
                //$("#relation-zone").toggleClass("hide", false);
            });
            $(document).on('click', ".btn-execute", function () {
                var $form = $(this).closest("form");
                //var $f = $form.serializeArray();
                var $f = JSON.stringify($form.serializeArray());
                var $selectedContent = $("#table").bootstrapTable('getData');//取得表格数据
                //console.log($form.serializeArray());   
                /**console.log($form.serializeArray());   
                 * [{name: "row[riqi]", value: "2019-04-04"}
                 * {name: "row[zzjg_id]", value: "3"}
                 * {name: "row[jsr]", value: "dd"}
                 * {name: "row[kdgs]", value: ""}
                 * {name: "row[kddh]", value: ""}
                 * {name: "row[mdgs]", value: ""}
                 * {name: "row[fhdz]", value: ""}
                 * {name: "row[shr]", value: ""}
                 * {name: "row[shrdh]", value: ""}]
                 */ 
                //console.log($f);
                /***console.log(JSON.stringify($form.serializeArray()));
                 * [{"name":"row[riqi]","value":"2019-04-04"},{"name":"row[zzjg_id]","value":"3"},{"name":"row[jsr]","value":"dd"},{"name":"row[kdgs]","value":""},{"name":"row[kddh]","value":""},{"name":"row[mdgs]","value":""},{"name":"row[fhdz]","value":""},{"name":"row[shr]","value":""},{"name":"row[shrdh]","value":""}]
                 */
                //console.log($selectedContent);
                //转换序列化的表单数据到数组
                var o = {};
                $.each($form.serializeArray(), function() {   
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
                /***console.log(o);//转换序列化的表单数据到对像后，变成了这样
                 * {row[riqi]: "2019-04-04", row[zzjg_id]: "3", row[jsr]: "dd", row[kdgs]: "", row[kddh]: "", …}row[fhdz]: ""row[jsr]: "dd"row[kddh]: ""row[kdgs]: ""row[mdgs]: ""row[riqi]: "2019-04-04"row[shr]: ""row[shrdh]: ""row[zzjg_id]: "3"__proto__: Object
                 */
                var $data = {"data":o,"mx":$selectedContent};
                console.log(JSON.stringify($data));
                /*
                {"data":{"row[riqi]":"2019-04-04","row[zzjg_id]":"3","row[jsr]":"sfd","row[kdgs]":"","row[kddh]":"","row[mdgs]":"","row[fhdz]":"","row[shr]":"","row[shrdh]":""},"mx":[{"id":0,"xm":"fdsaf","name":"","xh":"","sl":"","sn":"","gzsm":"","fhlx_id":"0","jyr":"","jydh":""}]}
                */
                //////
                Fast.api.ajax({
                    url: "gcrm/fhgl/add1",
                    contentType: "application/json", //必须这样写
                    dataType:"json",
                    data:JSON.stringify($data),
                }, function (data, ret) {
                    console.log(data);
                    console.log(ret);
                }, function () {
                    
                });
            });

            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                //Form.api.bindevent($("form[role=form]"));
                Form.events.validator($("form[role=form]"), function(data, ret){
                    //如果我们需要在提交表单成功后做跳转，可以在此使用location.href="链接";进行跳转
                    console.log("表单验证成功！");
                    return true;
                }, function(data, ret){
                    console.log("表单验证失败！");
                    return false;
                });
                Form.api.bindevent($("form[role=form]"), function(data, ret){
                    //如果我们需要在提交表单成功后做跳转，可以在此使用location.href="链接";进行跳转
                    Toastr.success("成功");
                }, function(data, ret){
                      Toastr.success("失败");
                }, function(success, error){
                    //bindevent的第三个参数为提交前的回调
                    //如果我们需要在表单提交前做一些数据处理，则可以在此方法处理
                    //注意如果我们需要阻止表单，可以在此使用return false;即可
                    //如果我们处理完成需要再次提交表单则可以使用submit提交,如下
                    //Form.api.submit(this, success, error);
                    //-----------------------------------------
                    //Form.api.submit(this, success, error);
                    //return false;
                });
                Form.api.bindevent($("form[role=form1]"));
            }
        }
    };
    return Controller;
});