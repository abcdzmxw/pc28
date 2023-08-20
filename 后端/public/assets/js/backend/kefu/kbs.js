define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kefu/kbs/index' + location.search,
                    add_url: 'kefu/kbs/add',
                    edit_url: 'kefu/kbs/edit',
                    del_url: 'kefu/kbs/del',
                    multi_url: 'kefu/kbs/multi',
                    table: 'kefu_kbs',
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
                        {
                            field: 'questions',
                            title: __('Questions'),
                            formatter: Controller.api.formatter.value_substring,
                            operate: 'LIKE'
                        },
                        {field: 'match', title: __('Match')},
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'weigh', title: __('Weigh')},
                        {
                            field: 'status',
                            title: __('Status'),
                            searchList: {"0": __('Status 0'), "1": __('Status 1'), "2": __('Status 2')},
                            formatter: Table.api.formatter.status
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
                url: 'kefu/kbs/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {
                            field: 'questions',
                            title: __('Questions'),
                            formatter: Controller.api.formatter.value_substring,
                            operate: 'LIKE'
                        },
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
                                    url: 'kefu/kbs/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'kefu/kbs/destroy',
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
        testmatch: function () {
            $('#str1, #str2').on('change', function () {
                Controller.api.test_match();
            })

            $('.post_test_match').on('click', function () {
                Controller.api.test_match(true);
            })
        },
        api: {
            test_match: function (mandatory = false) {
                var str1 = $('#str1').val();
                var str2 = $('#str2').val();

                if (str1 && str2) {
                    $.post("kefu/kbs/testMatch", {
                        'str1': str1,
                        'str2': str2
                    }, function (res) {
                        if (res.code == 1) {
                            $('.progress').show();
                            $('#match').css('width', res.data + '%').html('匹配度:' + res.data + '%');
                        }
                    })
                } else {
                    if (mandatory) {
                        layer.msg('请输入要计算匹配度的字符串！');
                    }
                }
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));

                $('.test_match').on('click', function () {
                    Backend.api.addtabs('kefu/kbs/testMatch', '匹配度测试');
                });
            },
            formatter: {
                value_substring: function (value) {
                    let value_length = value.replace(/[\u0391-\uFFE5]/g, "aa").length;
                    return value_length > 20 ? value.substring(0, 20) + '...' : value;
                }
            }
        }
    };
    return Controller;
});