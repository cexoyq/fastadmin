define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gcrm/index',
                    add_url: 'gcrm/add',
                    edit_url: 'gcrm/edit',
                    del_url: 'gcrm/del',
                    multi_url: 'gcrm/multi',
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
                        {field: 'pid', title: __('Pid')},
                        {field: 'gd_id', title: __('Gd_id')},
                        {field: 'xm', title: __('Xm')},
                        {field: 'name', title: __('Name')},
                        {field: 'xh', title: __('Xh')},
                        {field: 'sn', title: __('Sn')},
                        {field: 'sl', title: __('Sl')},
                        {field: 'fwlx_id', title: __('Fwlx_id')},
                        {field: 'fhlx_id', title: __('Fhlx_id')},
                        {field: 'wxf', title: __('Wxf')},
                        {field: 'gzsm', title: __('Gzsm')},
                        {field: 'jydh', title: __('Jydh')},
                        {field: 'jyr_id', title: __('Jyr_id')},
                        {field: 'glr_id', title: __('Glr_id')},
                        {field: 'admin.nickname', title: __('Admin.nickname')},
                        {field: 'sys.name', title: __('Sys.name')},
                        {field: 'gd.gddd', title: __('Gd.gddd')},
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