define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kefu/csrkpi/index' + location.search,
                    add_url: 'kefu/csrkpi/add',
                    edit_url: 'kefu/csrkpi/edit',
                    del_url: 'kefu/csrkpi/del',
                    multi_url: 'kefu/csrkpi/multi',
                    table: 'kefu_csr_config',
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
                        {field: 'admin.nickname', title: __('Admin_id')},
                        {field: 'ceiling', title: __('Ceiling'), sortable: true},
                        {field: 'reception_count', title: __('Reception_count'), sortable: true},
                        {field: 'sum_message_count', title: __('sum_message_count')},
                        {field: 'sum_reception_count', title: __('sum_reception_count')},
                        {
                            field: 'last_reception_time',
                            title: __('Last_reception_time'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime,
                            sortable: true
                        },
                        {
                            field: 'keep_alive',
                            title: __('Keep Alive'),
                            searchList: {"0": __('关'), "1": __('开')},
                            formatter: Table.api.formatter.status
                        },
                        {
                            field: 'status',
                            title: __('Status'),
                            searchList: {
                                "0": __('Status 0'),
                                "1": __('Status 1'),
                                "2": __('Status 2'),
                                "3": __('Status 3')
                            },
                            formatter: Table.api.formatter.status
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
            }
        }
    };
    return Controller;
});