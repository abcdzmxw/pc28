define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'agent_report/index' + location.search,
                    // add_url: 'game/add',
                    // edit_url: 'game/edit',
                    // del_url: 'game/del',
                    // multi_url: 'game/multi',
                    // import_url: 'game/import',
                    table: 'game',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                searchFormVisible: true,
                searchFormTemplate: 'customformtpl',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', formatter: Table.api.formatter.search,title: __('Id')},
                        {field: 'username', title: "用户名", operate: false},
                        {field: 'in', title: "充值汇总", operate: false},
                        {field: 'up', title: "提款汇总", operate: false},
                        {field: 'use', title: "消费汇总", operate: false},
                        {field: 'profit', title: "盈利汇总", operate: false},
                        {field: 'hit', title: "中奖汇总", operate: false},
                        {field: 'money', title: "余额汇总", operate: false},
                        {field: 'score', title: "彩金汇总", operate: false},
                        {field: 'weigh', title: "赠送汇总", operate: false},
                        // {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            //当表格数据加载完成时
            table.on('load-success.bs.table', function (e, data) {
                //这里可以获取从服务端获取的JSON数据
                console.log(data);
                //这里我们手动设置底部的值
                $("#recharge").text(data.allInfo.in);
                $("#withdraw").text(data.allInfo.up);
                $("#expand").text(data.allInfo.use);
                $("#profit").text(data.allInfo.profit);
                $("#winning").text(data.allInfo.hit);
                $("#balance").text(data.allInfo.money);
                $("#handsel").text(data.allInfo.score);
                $("#give").text(data.allInfo.weigh);
            });

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
                url: 'game/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',

                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), align: 'left'},
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
                                    url: 'game/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'game/destroy',
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