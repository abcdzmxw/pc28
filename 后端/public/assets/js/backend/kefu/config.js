define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'upload'], function ($, undefined, Backend, Table, Form, Upload) {
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                search: true,
                advancedSearch: true,
                pagination: true,
                extend: {
                    "index_url": "kefu/config/index",
                    "add_url": "",
                    "edit_url": "",
                    "del_url": "",
                    "multi_url": "",
                }
            });

            Form.api.bindevent($("form[role=form]"));

            $('input[name="row[csr_admin]"]').data("eSelect", function () {
                Controller.api.build_csr_config()
            });

            $('input[name="row[csr_admin]"]').data("eTagRemove", function (data) {
                if (!Controller.selectPageReady) {
                    Controller.selectPageReady = true;
                } else {
                    Controller.api.build_csr_config()
                }

            });

            $(document).on('click', '.run_config', function () {
                Fast.api.open("addon/config?name=kefu", __('Setting'));
            });

            $('input[name="row[csr_distribution]"]').on('change', function (that) {
                var csr_distribution = $(that.target).val();
                var tis = '';

                if (csr_distribution == 0) {
                    tis = '按工作强度：优先分配给当前接待量最少的客服,若有多个客服接待量相同,则分配给其中最久未进行接待的客服';
                } else if (csr_distribution == 1) {
                    tis = '智能分配：根据接待上限和当前接待量，分配给最能接待的客服';
                } else if (csr_distribution == 2) {
                    tis = '轮流分配：每次都分配给最久未进行接待的客服';
                }

                $('#distribution_help').html(tis);
            })
        },
        selectPageReady: false,
        api: {
            build_csr_config: function () {

                var csr = $('input[name="row[csr_admin]"]').selectPageText();
                var csrs = csr.split(",");

                for (var i = 0; i < csrs.length; i++) {
                    Controller.api.build_csr_input(csrs[i]);
                }

                // 删除-获取已构建的配置框
                var csr_config_list = $('#csr_config').children("div");
                var csr_config_length = $('#csr_config').children("div").length;

                if (csrs.length) {

                    for (var i = 0; i < csr_config_length; i++) {
                        let csr = $(csr_config_list[i]).data('name').toString();

                        if (csrs.indexOf(csr) == -1) {
                            Controller.api.build_csr_input(csr, true);
                        }
                    }
                } else {
                    for (let i in csr_config_length) {
                        let csr = $(csr_config_list[i]).data('name')
                        Controller.api.build_csr_input(csr, true);
                    }
                }
            },
            build_csr_input: function (username, is_del = false) {

                if (!username) {
                    return;
                }

                var csr_item = $('#csr_config').children("[data-name='" + username + "']");

                if (is_del && csr_item.length) {
                    // 删除
                    csr_item.remove();
                    return;
                } else if (csr_item.length) {
                    return;
                }

                var el = $('\
                    <div data-name="' + username + '">\
                        <div class="form-group">\
                            <label class="control-label">' + username + ':</label>\
                            <div class="input-group">\
                                <div class="input-group-addon">接待上限</div>\
                                <input class="form-control" name="row[csr_config][' + username + ']" placeholder="请输入接待上限人数" value="1" />\
                                <div class="input-group-addon">人</div>\
                            </div>\
                        </div>\
                    </div>\
                    ');

                $('#csr_config').append(el)//插入
            }
        }
    };

    return Controller;
})