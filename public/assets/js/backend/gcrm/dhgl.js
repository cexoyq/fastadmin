define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gcrm/dhgl/index',
                    add_url: 'gcrm/dhgl/add',
                    edit_url: 'gcrm/dhgl/edit',
                    del_url: 'gcrm/dhgl/del',
                    multi_url: 'gcrm/dhgl/multi',
                    table: 'gcrm_dhlog',
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
                        {field: 'name', title: __('Name')},
                        {field: 'xh', title: __('Xh')},
                        {field: 'sl', title: __('Sl')},
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