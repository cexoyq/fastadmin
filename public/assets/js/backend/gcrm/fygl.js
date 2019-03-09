define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gcrm/fygl/index',
                    add_url: 'gcrm/fygl/add',
                    edit_url: 'gcrm/fygl/edit',
                    del_url: 'gcrm/fygl/del',
                    multi_url: 'gcrm/fygl/multi',
                    table: 'gcrm_fylog',
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
                        {field: 'xm.name', title: __('Xm.name')},
                        {field: 'fytype.name', title: __('Fytype.name')},
                        {field: 'name', title: __('Name')},
                        {field: 'jine', title: __('Jine')},
                        {field: 'riqi', title: __('Riqi'), operate:'RANGE', addclass:'datetimerange'},
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