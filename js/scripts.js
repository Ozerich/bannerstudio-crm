$(function () {

    function Popup(_element) {
        var that = this;
        if ($('#popup_overlay').length === 0) {
            this.overlay = $('<div id="popup_overlay" style="position:absolute;background: #000; opacity: 0.8;left:0;right:0;top:0;bottom:0;z-index:10001"></div>').appendTo($('body'));
        }
        else {
            this.overlay = $('#popup_overlay');
        }

        if ($('#popup_window').length === 0) {
            this.window = $('<div id="popup_window" style="position: absolute; background: #ececec; border-radius: 5px; padding: 5px; z-index: 10002"></div>').appendTo($('body'));
        } else {
            this.window = $('#popup_window');
        }

        this.overlay.hide();
        this.window.hide();

        _element = $(_element).clone().show();

        this.window.append(_element);


        this.show = function () {
            that.overlay.fadeIn(function () {
                that.window.show();
                that.center();
            });
        };

        this.hide = function () {
            that.window.hide();
            that.overlay.fadeOut();
        };

        this.overlay.click(function () {
            that.hide();
        });

        this.center = function () {
            that.window.css("top", Math.max(0, (($(window).height() - $(this.window).outerHeight()) / 2) +
                $(window).scrollTop()) + "px");
            that.window.css("left", Math.max(0, (($(window).width() - $(this.window).outerWidth()) / 2) +
                $(window).scrollLeft()) + "px");
            return this;
        }
    }

    // хедер
    (function () {

        var $profile_block = $('.profile-block');
        var $submenu = $profile_block.find('.dropdown-menu');

        $profile_block.find('.dropdown-toggle').on('click', function () {
            $submenu.fadeToggle();
            return false;
        });

        $(document).on('click', function (event) {
            if ($(event.target).parents('.profile-block').length === 0) {
                $submenu.fadeOut();
            }
        });

    })();

    // страница с формой проекта
    (function () {

        var $form = $('#form_project');
        if ($form.length === 0) return;

        var $customer_block = $('#widget_customer');
        var $worker_block = $('#widget_worker');
        var $status_select = $form.find('.status-select');

        $form.find('#closed').click(function () {

            if ($(this).is(':checked')) {
                $status_select.attr('disabled', 'disabled');
            }
            else {
                $status_select.removeAttr('disabled');
            }

        });


        $form.find('#insert_from_worker').on('click', function () {

            $customer_block.find('.text').val($worker_block.find('.text').val());

            return false;
        });

        $form.find('#insert_from_customer').on('click', function () {

            $worker_block.find('.text').val($customer_block.find('.text').val());

            return false;
        });


        $form.find('.btn-select-left').on('click', function () {

            var $block = $(this).parents('.lists');
            var $source_select = $block.find('.source-select');
            var $destination_select = $block.find('.destination-select');

            $destination_select.find('option:selected').each(function () {
                $source_select.append($(this).clone());
                $(this).remove();
            });

            return false;
        });

        $form.find('.btn-select-right').on('click', function () {

            var $block = $(this).parents('.lists');
            var $source_select = $block.find('.source-select');
            var $destination_select = $block.find('.destination-select');

            $source_select.find('option:selected').each(function () {
                $destination_select.append($(this).clone());
                $(this).remove();
            });

            return false;
        });


        $form.find('input[type=submit]').on('click', function () {

            var $hid_workers = $('#hid_workers_list'), $hid_customers = $('#hid_customers_list');

            $form.find('.destination-select').each(function () {
                var values = [];

                $(this).find('option').each(function () {
                    values.push($(this).val());
                });

                $('#hid_' + $(this).attr('for')).val(values.length > 0 ? values.join(',') : '');
            });

            return true;
        });

    })();


    // страница с страницей проект
    (function () {
        if ($('#page_project').length === 0)return;

        var $description_block = $('.additional-info');
        var $btn_hide_description = $('#btn_hide_description');
        var $btn_show_description = $('#btn_show_description');
        var $btn_edit_project = $('#btn_edit_project');

        $btn_show_description.click(function () {
            $btn_hide_description.hide();
            $btn_edit_project.show();
            $btn_show_description.hide();

            $description_block.slideDown(function () {
                $btn_hide_description.show();
            });
            return false;
        });

        $btn_hide_description.click(function () {
            $btn_show_description.show();
            $btn_edit_project.hide();
            $btn_hide_description.hide();
            $description_block.slideUp();
            return false;
        });


        var $comments_form = $('.comments-form');

        function addFileInput() {
            var $item = $comments_form.find('.file-item.example').clone();

            $item.removeClass('example').show();

            $comments_form.find('.files').append($item);
        }

        addFileInput();

        $comments_form.on('change', 'input:file', function () {
            var $block = $(this).parents('.file-item');
            var filename = $(this).val().substring($(this).val().lastIndexOf('\\') + 1, $(this).val().length);

            $block.find('.filename-block .filename').text(filename);

            $block.find('.file').hide();
            $block.find('.filename-block').css('display', 'inline-block');

            addFileInput();
        });

        $comments_form.on('click', '.delete-file', function () {
            var $block = $(this).parents('.file-item');
            $block.remove();

            return false;
        });


        $('#btn_add_comment').on('click', function () {

            var $message_textarea = $('.comments-form').find('textarea');
            if ($message_textarea.val().length === 0) {
                alert('Сообщение не может быть пустым');
                return;
            }

            var $btn = $(this).prop('disabled', true);

            function successCallback() {
                $btn.prop('disabled', false);
                $message_textarea.val('');

                $comments_form.find('.file-item').not('.example').remove();
                addFileInput();

                var $comments_block = $('.comments-block');
                var $loader = $comments_block.find('.loader').fadeIn();

                $.get('/projects/comments/' + $('#project_id').val(), function (data) {
                    $comments_block.find('.comments-list').html(data);
                    $comments_block.find('[mode]').hide();
                    $comments_block.find('[mode=' + $('#active_mode').val() + ']').show();
                    $loader.fadeOut();
                });
            }

            $comments_form.find('.file-item:last').remove();

            $comments_form.find('.file-item:visible').find('.status').text('в очереди');
            $comments_form.find('.file-item:visible').find('.delete-file').hide();

            $.post('/projects/add_comment', {message: $message_textarea.val(), project_id: $('#project_id').val(), mode: $('#active_mode').val()}, function (data) {

                data = jQuery.parseJSON(data);

                var comment_id = data.comment_id;
                var count = $comments_form.find('.file-item:visible').length;

                if (count === 0) {
                    successCallback();
                }
                else {
                    $comments_form.find('.file-item:visible').each(function (ind) {
                        var $file_item = $(this);

                        $file_item.find('.status').text('загрузка...');
                        $file_item.find('input:file').attr('id', 'comment_file_' + ind);

                        (function ($file_item, is_last) {
                            $.ajaxFileUpload({
                                url: '/projects/upload_comment_file/comment/' + comment_id,
                                secureuri: false,
                                fileElementId: 'comment_file_' + ind,
                                dataType: 'json',
                                success: function (data, status) {
                                    if (data != 0) {
                                        $file_item.find('.status').addClass('status-success').text('Загружен');
                                    }
                                    else {
                                        $file_item.find('.status').addClass('status-error').text('Ошибка');
                                    }

                                    if (is_last) {
                                        successCallback();
                                    }
                                }
                            });
                        })($file_item, ind === count - 1);
                    });
                }


            });

            return false;
        });


        var $comments_block = $('.comments-block');

        $comments_block.on('click', '.btn-delete', function () {

            if (!confirm('Вы уверены?')) {
                return false;
            }

            var $file_row = $(this).parents('li');

            $.get('/projects/delete_comment_file/' + $file_row.data('id'));

            $file_row.fadeOut(function () {
                $(this).remove();
            });

            return false;
        });


        function setMode(mode) {
            $('.project-footer').removeClass('customer').removeClass('worker').addClass(mode);
            if (mode == 'customer') {
                $('[mode=worker]').hide();
                $('[mode=customer]').show();
            } else {
                $('[mode=customer]').hide();
                $('[mode=worker]').show();
            }

            $('#active_mode').val(mode);
        }


        $('.project-footer').find('.switch').on('click', function () {

            $('.project-footer').find('.switch').removeClass('active');
            $(this).addClass('active');

            setMode($(this).hasClass('customer') ? 'customer' : 'worker');

            return false;
        });


        var popup = new Popup($('#popup_form'));

        var openPopup = function (mode) {
            var $popup_form = popup.window;

            $popup_form.find('textarea').val('');
            $popup_form.find('.files').empty();

            var files = [];

            $('.comment-item:visible .files li').each(function () {
                if ($(this).find('input[type=checkbox]').is(':checked')) {
                    files.push($(this));
                }
            });

            for (var i = 0; i < files.length; i++) {
                var file = $('<li data-id="' + files[i].data('id') + '"><span class="filename">' + files[i].find('.filename').text() + '</span><span class="size">' + files[i].find('.size').text() + '</span></li>')
                $popup_form.find('.files').append(file);
            }


            $popup_form.find('label').toggle(files.length > 0);

            popup.show();
        };

        $('#btn_to_customer').click(function () {
            openPopup('customer');
            return false;
        });

        $('#btn_to_worker').click(function () {
            openPopup('worker');
            return false;
        });


    })();
});
