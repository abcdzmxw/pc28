define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'quantity_log/index' + location.search,
                    add_url: 'quantity_log/add',
                    edit_url: 'quantity_log/edit',
                    del_url: 'quantity_log/del',
                    multi_url: 'quantity_log/multi',
                    import_url: 'quantity_log/import',
                    table: 'quantity_log',
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
                        {field: 'user_id', title: __('User_id'),formatter: Table.api.formatter.search,},
                        {field: 'user.username', title: "用户名称",formatter: Table.api.formatter.search, operate: 'LIKE'},
                        {field: 'admin_id', title: __('Admin_id'),formatter: Table.api.formatter.search,},
                        {field: 'quantity', title: __('Quantity'), operate:'BETWEEN'},
                        {field: 'over', title: __('Over'), operate:'BETWEEN'},
                        {field: 'mark', title: __('Mark'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'), searchList: {"0":__('Type 0'),"1":__('Type 1'),"2":__('Type 2'),"3":__('Type 3'),"4":__('Type 4')}, formatter: Table.api.formatter.normal},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'user.id', title: __('User.id')},
                        // {field: 'user.group_id', title: __('User.group_id')},
                        // {field: 'user.admin_id', title: __('User.admin_id')},
                        // {field: 'user.nickname', title: __('User.nickname'), operate: 'LIKE'},
                        // {field: 'user.password', title: __('User.password'), operate: 'LIKE'},
                        // {field: 'user.salt', title: __('User.salt'), operate: 'LIKE'},
                        // {field: 'user.pass_code', title: __('User.pass_code'), operate: 'LIKE'},
                        // {field: 'user.email', title: __('User.email'), operate: 'LIKE'},
                        // {field: 'user.mobile', title: __('User.mobile'), operate: 'LIKE'},
                        // {field: 'user.idcard', title: __('User.idcard'), operate: 'LIKE'},
                        // {field: 'user.avatar', title: __('User.avatar'), operate: 'LIKE', events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'user.level', title: __('User.level')},
                        // {field: 'user.gender', title: __('User.gender')},
                        // {field: 'user.birthday', title: __('User.birthday'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        // {field: 'user.bio', title: __('User.bio'), operate: 'LIKE'},
                        // {field: 'user.money', title: __('User.money'), operate:'BETWEEN'},
                        // {field: 'user.score', title: __('User.score')},
                        // {field: 'user.inviter_id', title: __('User.inviter_id')},
                        // {field: 'user.successions', title: __('User.successions')},
                        // {field: 'user.maxsuccessions', title: __('User.maxsuccessions')},
                        // {field: 'user.prevtime', title: __('User.prevtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'user.logintime', title: __('User.logintime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'user.loginip', title: __('User.loginip'), operate: 'LIKE'},
                        // {field: 'user.loginfailure', title: __('User.loginfailure')},
                        // {field: 'user.joinip', title: __('User.joinip'), operate: 'LIKE'},
                        // {field: 'user.jointime', title: __('User.jointime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'user.createtime', title: __('User.createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'user.updatetime', title: __('User.updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'user.token', title: __('User.token'), operate: 'LIKE'},
                        // {field: 'user.status', title: __('User.status'), operate: 'LIKE', formatter: Table.api.formatter.status},
                        // {field: 'user.verification', title: __('User.verification'), operate: 'LIKE'},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                        {
                            field: 'operate',
                            width: "150px",
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'ajax',
                                    title: __('通过'),
                                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                                    icon: 'fa fa-hand-spock-o',
                                    confirm: '是否确认通过请求？',
                                    url: 'quantity_log/passApply',
                                    success: function (data, ret) {
                                        Layer.alert(ret.msg);
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                                {
                                    name: 'ajax',
                                    title: __('拒绝'),
                                    classname: 'btn btn-xs btn-warning btn-magic btn-ajax',
                                    icon: 'fa fa-ban',
                                    confirm: '是否确认拒绝请求？',
                                    url: 'quantity_log/reApply',
                                    success: function (data, ret) {
                                        Layer.alert(ret.msg);
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                            ],
                            formatter: Table.api.formatter.operate
                        },
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
                url: 'quantity_log/recyclebin' + location.search,
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
                                    url: 'quantity_log/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'quantity_log/destroy',
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