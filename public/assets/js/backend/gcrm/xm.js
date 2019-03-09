define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gcrm/xm/index',
                    add_url: 'gcrm/xm/add',
                    edit_url: 'gcrm/xm/edit',
                    del_url: 'gcrm/xm/del',
                    multi_url: 'gcrm/xm/multi',
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
                        {field: 'parent_id', title: __('Parent_id')},
                        {field: 'cpx_id', title: __('Cpx_id')},
                        {field: 'shiyebu_id', title: __('Shiyebu_id')},
                        {field: 'gsfl_id', title: __('Gsfl_id')},
                        {field: 'name', title: __('Name')},
                        {field: 'pingtai_id', title: __('Pingtai_id')},
                        {field: 'xmType_id', title: __('Xmtype_id')},
                        {field: 'htbh', title: __('Htbh')},
                        {field: 'crmbh', title: __('Crmbh')},
                        {field: 'htriqi', title: __('Htriqi'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'htJinE', title: __('Htjine')},
                        {field: 'xiaoShou_id', title: __('Xiaoshou_id')},
                        {field: 'gongChengShi_id', title: __('Gongchengshi_id')},
                        {field: 'xiangMuJingLi_id', title: __('Xiangmujingli_id')},
                        {field: 'keHu_id', title: __('Kehu_id')},
                        {field: 'keHuLianXiRen', title: __('Kehulianxiren')},
                        {field: 'keHuLianXiRenTel', title: __('Kehulianxirentel')},
                        {field: 'xmnr', title: __('Xmnr')},
                        {field: 'tag', title: __('Tag')},
                        {field: 'weigh', title: __('Weigh'), operate:'BETWEEN'},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'delete_time', title: __('Delete_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"0":__('Status 0')}, formatter: Table.api.formatter.status},
                        {field: 'remark', title: __('Remark')},
                        {field: 'description', title: __('Description')},
                        {field: 'type.name', title: __('Type.name')},
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