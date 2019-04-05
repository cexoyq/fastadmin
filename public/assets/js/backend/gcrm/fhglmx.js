define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gcrm/fhglmx/index',
                    add_url: 'gcrm/fhglmx/add',
                    edit_url: 'gcrm/fhglmx/edit',
                    del_url: 'gcrm/fhglmx/del',
                    multi_url: 'gcrm/fhglmx/multi',
                    table: 'gcrm_fhlogmx',
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
                        {field: 'pid', title: __('Pid'),visible: false},
                        //{field: 'gd_id', title: __('Gd_id')},
                        {field: 'gd.gddd', title: __('Gd.gddd')},
                        {field: 'xm', title: __('Xm')},
                        {field: 'name', title: __('Name')},
                        {field: 'xh', title: __('Xh')},
                        {field: 'sn', title: __('Sn')},
                        {field: 'sl', title: __('Sl')},
                        {field: 'fwlx.name', title: __('Fwlx.name')},
                        {field: 'fhlx.name', title: __('Fhlx.name')},
                        {field: 'wxf', title: __('Wxf')},
                        {field: 'gzsm', title: __('Gzsm')},
                        {field: 'jydh', title: __('Jydh')},
                        //{field: 'jyr_id', title: __('Jyr_id')},
                        //{field: 'glr_id', title: __('Glr_id')},
                        {field: 'jyr.nickname', title: __('Jyr.nickname')},
                        {field: 'glr.nickname', title: __('Glr.nickname')},
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
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});