define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gcrm/gdgl/index',
                    add_url: 'gcrm/gdgl/add',
                    edit_url: 'gcrm/gdgl/edit',
                    del_url: 'gcrm/gdgl/del',
                    multi_url: 'gcrm/gdgl/multi',
                    table: 'gcrm_gd',
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
                        {field: 'id', title: __('id')},
                        {field: 'riqi', title: __('Riqi'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'gddd', title: __('Gddd')},
                        {field: 'gzxx', title: __('Gzxx')},
                        {field: 'clzt', title: __('Clzt')},
                        //{field: 'gdmx.gzxx', title: __('Gdmx.gzxx')},
                        //{field: 'gdmx.sbdd', title: __('Gdmx.sbdd')},
                        //{field: 'gdmx.sb', title: __('Gdmx.sb')},
                        //{field: 'gdmx.clzt', title: __('Gdmx.clzt')},
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