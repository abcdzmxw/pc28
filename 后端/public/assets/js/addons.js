define([], function () {
    require.config({
    paths: {
        'async': '../addons/example/js/async',
        'BMap': ['//api.map.baidu.com/api?v=2.0&ak=mXijumfojHnAaN2VxpBGoqHM'],
    },
    shim: {
        'BMap': {
            deps: ['jquery'],
            exports: 'BMap'
        }
    }
});

if (Config.modulename == 'admin' && Config.controllername == 'index' && Config.actionname == 'index') {

    require.config({
        paths: {
            'kefu': '../addons/kefu/js/kefu'
        },
        shim: {
            'kefu': {
                deps: ['css!../addons/kefu/css/kefu_admin_default.css'],
                exports: 'KeFu'
            }
        }
    });

    require(['kefu'], function (KeFu) {
        KeFu.initialize(document.domain, 'admin');
    });

} else {

    try {
        var parentConifg = window.parent.Config;
    } catch (err) {
        var parentConifg = false;
    }

    if (parentConifg && parentConifg.modulename == 'admin') {
        // 监听后台iframe内的快捷键打开会话窗口
        $(document).on('keyup', function (event) {

            if (window.parent.KeFu) {

                // console.log('当前按钮的code-iframe内:', event.keyCode);

                // 对打开会话窗口的监听
                // 打开会话窗口快捷键[ctrl + /],若需修改，请拿到对应键的keyCode替换下一行的191即可，191代表[/]键的keyCode
                if (event.keyCode === 191 && event.ctrlKey) {

                    if (window.parent.KeFu.last_sender) {
                        if (parseInt(window.parent.KeFu.last_sender) === window.parent.KeFu.session_id) {
                            // 展开分组
                            if (!window.parent.KeFu.group_show.dialogue) {
                                $('#heading_dialogue a').click();
                            }
                        } else {
                            window.parent.KeFu.changeSession(window.parent.KeFu.last_sender);
                            window.parent.KeFu.last_sender = null;
                        }
                    } else if (window.parent.KeFu.window_is_show) {
                        window.parent.KeFu.toggle_window('hide');
                    }

                    if (!window.parent.KeFu.window_is_show) {
                        window.parent.KeFu.toggle_window('show');
                    }
                    return ;
                }
            }

        });

    } else {

        require.config({
            paths: {
                'kefu': '../addons/kefu/js/kefu'
            },
            shim: {
                'kefu': {
                    deps: ['css!../addons/kefu/css/kefu_default.css'],
                    exports: 'KeFu'
                }
            }
        });

        require(['kefu'], function (KeFu) {
            KeFu.initialize(document.domain, 'index');
        });
    }
}
require.config({
    paths: {
        'summernote': '../addons/summernote/lang/summernote-zh-CN.min'
    },
    shim: {
        'summernote': ['../addons/summernote/js/summernote.min', 'css!../addons/summernote/css/summernote.css'],
    }
});
require(['form', 'upload'], function (Form, Upload) {
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        try {
            //绑定summernote事件
            if ($(Config.summernote.classname || '.editor', form).size() > 0) {
                var selectUrl = typeof Config !== 'undefined' && Config.modulename === 'index' ? 'user/attachment' : 'general/attachment/select';
                require(['summernote'], function () {
                    var imageButton = function (context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-file-image-o"/>',
                            tooltip: __('Choose'),
                            click: function () {
                                parent.Fast.api.open(selectUrl + "?element_id=&multiple=true&mimetype=image/", __('Choose'), {
                                    callback: function (data) {
                                        var urlArr = data.url.split(/\,/);
                                        $.each(urlArr, function () {
                                            var url = Fast.api.cdnurl(this, true);
                                            context.invoke('editor.insertImage', url);
                                        });
                                    }
                                });
                                return false;
                            }
                        });
                        return button.render();
                    };
                    var attachmentButton = function (context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-file"/>',
                            tooltip: __('Choose'),
                            click: function () {
                                parent.Fast.api.open(selectUrl + "?element_id=&multiple=true&mimetype=*", __('Choose'), {
                                    callback: function (data) {
                                        var urlArr = data.url.split(/\,/);
                                        $.each(urlArr, function () {
                                            var url = Fast.api.cdnurl(this, true);
                                            var node = $("<a href='" + url + "'>" + url + "</a>");
                                            context.invoke('insertNode', node[0]);
                                        });
                                    }
                                });
                                return false;
                            }
                        });
                        return button.render();
                    };
                    $(Config.summernote.classname || '.editor', form).each(function () {
                        $(this).summernote($.extend(true, {}, {
                            height: 250,
                            lang: 'zh-CN',
                            fontNames: [
                                'Arial', 'Arial Black', 'Serif', 'Sans', 'Courier',
                                'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande',
                                "Open Sans", "Hiragino Sans GB", "Microsoft YaHei",
                                '微软雅黑', '宋体', '黑体', '仿宋', '楷体', '幼圆',
                            ],
                            fontNamesIgnoreCheck: [
                                "Open Sans", "Microsoft YaHei",
                                '微软雅黑', '宋体', '黑体', '仿宋', '楷体', '幼圆'
                            ],
                            toolbar: [
                                ['style', ['style', 'undo', 'redo']],
                                ['font', ['bold', 'underline', 'strikethrough', 'clear']],
                                ['fontname', ['color', 'fontname', 'fontsize']],
                                ['para', ['ul', 'ol', 'paragraph', 'height']],
                                ['table', ['table', 'hr']],
                                ['insert', ['link', 'picture', 'video']],
                                ['select', ['image', 'attachment']],
                                ['view', ['fullscreen', 'codeview', 'help']],
                            ],
                            buttons: {
                                image: imageButton,
                                attachment: attachmentButton,
                            },
                            dialogsInBody: true,
                            followingToolbar: false,
                            callbacks: {
                                onChange: function (contents) {
                                    $(this).val(contents);
                                    $(this).trigger('change');
                                },
                                onInit: function () {
                                },
                                onImageUpload: function (files) {
                                    var that = this;
                                    //依次上传图片
                                    for (var i = 0; i < files.length; i++) {
                                        Upload.api.send(files[i], function (data) {
                                            var url = Fast.api.cdnurl(data.url, true);
                                            $(that).summernote("insertImage", url, 'filename');
                                        });
                                    }
                                }
                            }
                        }, $(this).data("summernote-options") || {}));
                    });
                });
            }
        } catch (e) {

        }

    };
});

});