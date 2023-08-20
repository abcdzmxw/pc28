define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kefu/leavemessage/index' + location.search,
                    add_url: 'kefu/leavemessage/add',
                    edit_url: 'kefu/leavemessage/edit',
                    del_url: 'kefu/leavemessage/del',
                    multi_url: 'kefu/leavemessage/multi',
                    table: 'kefu_leave_message',
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
                            field: 'kefuuser.avatar',
                            title: __('Kefuuser.avatar'),
                            events: Table.api.events.image,
                            formatter: Table.api.formatter.image,
                            operate: false
                        },
                        {
                            field: 'kefuuser.nickname',
                            title: __('Kefuuser.nickname'),
                            operate: 'LIKE',
                            placeholder: '模糊查找'
                        },
                        {field: 'fu_user_nickname', 'title': __('绑定用户'), operate: false},
                        {field: 'name', title: __('Name'), operate: 'LIKE', placeholder: '模糊查找'},
                        {field: 'contact', title: __('Contact')},
                        {
                            field: 'kefuuser.referrer',
                            title: __('Kefuuser.referrer'),
                            formatter: Controller.api.formatter.url
                        },
                        {
                            field: 'message',
                            title: __('Message'),
                            operate: 'LIKE',
                            placeholder: '模糊查找',
                            formatter: Controller.api.formatter.value_substring
                        },
                        {
                            field: 'createtime',
                            title: __('Createtime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
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
            },
            formatter: {
                value_substring: function (value) {
                    let value_length = value.replace(/[\u0391-\uFFE5]/g, "aa").length;

                    return value_length > 20 ? value.substring(0, 20) + '...' : value;
                },
                url: function (value, row, index) {
                    if (value) {
                        value = value.split(' IP');
                        return value[0] ? Table.api.formatter.url(value[0], row, index) : '';
                    } else {
                        return '';
                    }
                }
            }
        }
    };
    return Controller;
});