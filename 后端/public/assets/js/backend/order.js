define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/index' + location.search,
                    add_url: 'order/add',
                    edit_url: 'order/edit',
                    del_url: 'order/del',
                    multi_url: 'order/multi',
                    import_url: 'order/import',
                    table: 'order',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'game_id', title: __('Game_id')},
                        {field: 'issue_id', title: __('Issue_id')},
                        {field: 'quantity_log_id', title: __('Quantity_log_id')},
                        {field: 'user_id', title: __('User_id'),formatter: Table.api.formatter.search,},
                        {field: 'username', title: __('Username')},
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'orderNo', title: __('Orderno'), operate: 'LIKE'},
                        {field: 'quantity', title: __('Quantity'), operate:'BETWEEN'},
                        {field: 'play_type', title: __('Play_type')},
                        {field: 'play_code', title: __('Play_code'), operate: 'LIKE'},
                        {field: 'play_method', title: __('Play_method'), operate: 'LIKE'},
                        {field: 'lottery_code', title: __('Lottery_code'), operate: 'LIKE'},
                        {field: 'lottery_art', title: __('Lottery_art'), searchList: {"0":__('Lottery_art 0'),"1":__('Lottery_art 1')}, formatter: Table.api.formatter.normal},
                        {field: 'profit', title: __('Profit'), operate:'BETWEEN'},
                        {field: 'began_quantity', title: __('Began_quantity'), operate:'BETWEEN'},
                        {field: 'after_quantity', title: __('After_quantity'), operate:'BETWEEN'},
                        {field: 'whether_hit', title: __('Whether_hit'), searchList: {"0":__('Whether_hit 0'),"1":__('Whether_hit 1')}, formatter: Table.api.formatter.normal},
                        {field: 'odds', title: __('Odds'), operate:'BETWEEN'},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), searchList: {"-1":__('Status -1'),"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'order/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'order/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'order/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
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