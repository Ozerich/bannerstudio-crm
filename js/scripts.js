$(function () {

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

});
