define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        offset: 0,
        limit: 10,
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kefu/record/index' + location.search,
                    add_url: 'kefu/record/add',
                    edit_url: 'kefu/record/edit',
                    del_url: 'kefu/record/del',
                    multi_url: 'kefu/record/multi',
                    record_url: 'kefu/record/index/session_id/{session_id}',
                    table: 'kefu_record',
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
                        {field: 'session_id', title: __('Session_id')},
                        {
                            field: 'sender_identity',
                            title: __('Sender_identity'),
                            searchList: {"0": __('Sender_identity 0'), "1": __('Sender_identity 1')},
                            formatter: Table.api.formatter.normal,
                            sortable: true
                        },
                        {field: 'sender_id', title: __('Sender_id'), sortable: true},
                        {
                            field: 'message_type',
                            title: __('Message_type'),
                            searchList: {
                                "0": __('Message_type 0'),
                                "1": __('Message_type 1'),
                                "2": __('Message_type 2'),
                                "3": __('Message_type 3'),
                                "4": __('Message_type 4'),
                                "5": __('Message_type 5')
                            },
                            formatter: Table.api.formatter.normal,
                            sortable: true
                        },
                        {
                            field: 'message',
                            title: __('Message'),
                            operate: 'LIKE',
                            placeholder: '模糊查找',
                            formatter: Controller.api.formatter.message
                        },
                        {
                            field: 'status',
                            title: __('Status'),
                            searchList: {"0": __('Status 0'), "1": __('Status 1')},
                            formatter: Table.api.formatter.status,
                            sortable: true
                        },
                        {
                            field: 'createtime',
                            title: __('Createtime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime,
                            sortable: true
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'record',
                                    title: '只看该会话',
                                    classname: 'btn btn-xs btn-info btn-addtabs',
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
            Controller.api.toolbar = Config.toolbar;
        },
        sessionrecord: function () {
            var session_id = Fast.api.query('session_id');
            Controller.api.toolbar = Config.toolbar;

            Controller.api.loadRecord(session_id)

            $(window).scroll(function (e) {

                var scrollTop = $(this).scrollTop();
                var scrollHeight = $(document).height();
                var windowHeight = $(this).height();
                if (scrollTop + windowHeight == scrollHeight) {
                    Controller.api.loadRecord(session_id)
                }
            });

            $(document).on('click', '.record', function (e) {

                var img_obj = $(e.target);
                if (img_obj.hasClass('emoji')) {
                    return;
                }
                img_obj = img_obj[0];
                var scrollTop = $(window).scrollTop();

                layer.photos({
                    photos: {
                        "title": "聊天图片预览",
                        "id": "record",
                        data: [
                            {
                                "src": img_obj.src
                            }
                        ]
                    }, end: function () {
                        $(window).scrollTop(scrollTop)
                    }, anim: 5
                });

            });
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
            formatCard: function (message) {
                var message = message.split('#');
                var message_arr = [];
                for (let i in message) {
                    let message_temp = message[i].split('=');
                    if (typeof message_temp[1] != 'undefined') {
                        message_arr[message_temp[0]] = message_temp[1];
                    }
                }
                return message_arr;
            },
            formatter: {
                message: function (value, row, index) {
                    if (row.message_type == 0 || row.message_type == 3) {

                        let value_length = value.replace(/[\u0391-\uFFE5]/g, "aa").length;
                        return value_length > 20 ? value.substring(0, 20) + '...' : value;
                    } else if (row.message_type == 2) {
                        return Table.api.formatter.url(value, row, index);
                    } else if (row.message_type == 1) {
                        return Table.api.formatter.image(value, row, index);
                    } else if (row.message_type == 4 || row.message_type == 5) {
                        var message = Controller.api.formatCard(value);
                        var card_url = (row.message_type == 4) ? Controller.api.toolbar.goods.card_url : Controller.api.toolbar.order.card_url
                        card_url += '?ref=addtabs&id=' + message['id'];

                        return '<a class="btn-addtabs" href="' + card_url + '">查看</a>';
                    }

                }
            },
            loadRecord: function (session_id) {

                if (Controller.limit === false) {
                    return;
                }

                Fast.api.ajax({
                    url: 'kefu/record/sessionRecord',
                    type: 'GET',
                    dataType: "json",
                    loading: true,
                    data: {
                        addtabs: 1,
                        sort: 'id',
                        order: 'asc',
                        offset: Controller.offset,
                        limit: Controller.limit,
                        filter: '{"session_id":"' + session_id + '"}',
                        op: '{"session_id":"="}',
                        _: new Date().getTime()
                    },
                    success: function (ret) {
                        var index = Layer.load(true);
                        index && Layer.close(index);

                        Controller.offset += 10;
                        // Controller.limit += 10;

                        if (ret.rows.length) {
                            // 渲染到页面
                            for (var i in ret.rows) {
                                Controller.api.buildRecord(ret.rows[i])
                            }

                            if (ret.rows.length < 10) {
                                Controller.limit = false;
                            }
                        } else {
                            Controller.limit = false;
                        }

                    }
                });
            },
            buildRecord: function (row) {
                var message = '';

                row.createtime = Table.api.formatter.datetime(row.createtime)
                row.sender_identity = (row.sender_identity == 0) ? 'me' : 'you';

                if (row.message_type == 1) {
                    message = Controller.api.buildChatImg(row.message, '聊天图片', 'record');
                } else if (row.message_type == 2) {
                    var file_name = row.message.split('.');
                    var file_suffix = file_name[file_name.length - 1];
                    message = Controller.api.buildChatA(row.message, file_suffix, 'record');
                } else if (row.message_type == 3) {
                    Controller.api.buildPrompt(row.message);
                    return;
                } else if (row.message_type == 4 || row.message_type == 5) {
                    var message_arr = Controller.api.formatCard(row.message);

                    var card_url = (row.message_type == 4) ? Controller.api.toolbar.goods.card_url : Controller.api.toolbar.order.card_url
                    card_url += '?ref=addtabs&id=' + message_arr['id'];

                    message = '<a class="btn-addtabs" href="' + card_url + '">\n' +
                        '   <div class="record_card">\n' +
                        '       <img src="' + message_arr['logo'] + '" />\n' +
                        '       <div class="record_card_body">\n' +
                        '           <div class="record_card_title">' + message_arr['subject'] + '</div>\n' +
                        (message_arr['note'] ? '<div class="record_card_note">' + message_arr['note'] + '</div>\n' : '') +
                        '           <div class="record_card_price">\n' +
                        (message_arr['price'] ? '<span>￥' + message_arr['price'] + '</span>\n' : '') +
                        (message_arr['number'] ? '<span>x' + message_arr['number'] + '</span>\n' : '') +
                        '           </div>\n' +
                        '       </div>\n' +
                        '   </div>\n' +
                        '   </a>';
                } else {
                    message = row.message;
                }

                $('.chat').append('<div class="record_item">\
                    <p><span class="user_name">' + row.sender_id + '</span> ' + row.createtime + ' </p>\
                    <div class="bubble ' + row.sender_identity + '">' + message + '</div>\
                    </div>');
            },
            buildChatA: function (filepath, file_suffix, class_name) {
                if (class_name == 'record') {
                    return '<a target="_blank" class="' + class_name + '" href="' + filepath + '">点击下载：' + file_suffix + ' 文件</a>';
                } else {
                    return '<a target="_blank" class="' + class_name + '" href="' + filepath + '">点击下载：' + file_suffix + ' 文件</a>';
                }
            },
            buildChatImg: function (filename, facename, class_name = 'emoji') {

                return '<img class="' + class_name + '" title="' + facename + '" src="' + filename + '" />';
            },
            buildPrompt: function (data) {
                $('.chat').append('<div class="status"><span>' + data + '</span></div>');
            },
        }
    };
    return Controller;
});