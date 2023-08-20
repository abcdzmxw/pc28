define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kefu/session/index' + location.search,
                    add_url: 'kefu/session/add',
                    edit_url: 'kefu/session/edit',
                    del_url: 'kefu/session/del',
                    multi_url: 'kefu/session/multi',
                    record_url: 'kefu/record/sessionRecord/session_id/{ids}',
                    table: 'kefu_session',
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
                        {
                            field: 'kefuuser.nickname',
                            title: __('Kefuuser.nickname'),
                            operate: 'LIKE',
                            placeholder: '模糊查找'
                        },
                        {field: 'fu_user_nickname', 'title': __('绑定用户'), operate: false},
                        // {field: 'kefuuser.avatar', title: __('Kefuuser.avatar'),operate:false , events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'csr.nickname', title: __('Crs Nickname'), operate: 'LIKE', placeholder: '模糊查找'},
                        // {field: 'csr_id', title: __('Csr_id')},
                        {field: 'user_message_count', title: __('user_message_count'), operate: false},
                        {field: 'csr_message_count', title: __('csr_message_count'), operate: false},
                        {
                            field: 'createtime',
                            title: __('Createtime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate', title: __('Operate'), table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'record',
                                    title: '审查聊天记录',
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    icon: 'fa fa-file-text-o',
                                    url: $.fn.bootstrapTable.defaults.extend.record_url,
                                }
                            ]
                        }
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
                url: 'kefu/session/recyclebin' + location.search,
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
                                    url: 'kefu/session/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'kefu/session/destroy',
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