define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gcrm/xmgl/index',
                    add_url: 'gcrm/xmgl/add',
                    edit_url: 'gcrm/xmgl/edit',
                    del_url: 'gcrm/xmgl/del',
                    multi_url: 'gcrm/xmgl/multi',
                    table: 'gcrm_xm',
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
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'pid', title: __('Pid'), visible: false},
                        {field: 'name', title: __('项目名称')},
                        {field: 'htbh', title: __('合同编号')},
                        {field: 'htriqi', title: __('合同日期'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'htJinE', title: __('合同金额')},
                        {field: 'kehuLianXiRen', title: __('客户联系人')},
                        {field: 'kehu.name', title: __('客户')},
                        {field: 'xmcpx.name', title: __('产品线')},
                        //{field: 'xmgstype.name', title: __('公司')},
                        //{field: 'xmshiyebu.name', title: __('事业部')},
                        {field: 'xmtype.name', title: __('项目类型')},
                        {field: 'zzjg.name', title: __('平台')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                //Form.api.bindevent($("form[role=form]"));
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});