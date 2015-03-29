$(function(){

    table = $('.table');
    var checked = [];

    $(table).find('th.checkbox').each(function(index) {

        $(this).html('<a href="#" class="checked">'+$(this).text()+'</a>');

    });

    $("form").submit(function() {

        delete_checked = 0;
        delete_length = $('td.delete').length;

        if(delete_length)
        {
            $(table).find('td.delete').find('input:checkbox').each(function(index) {

                if($(this).attr('checked'))
                {
                    delete_checked++ ;

                }

            });

            if(delete_length == delete_checked)
            {
                return confirm('Внимание! Вы удаляете все записи на странице.');

            }

        }

    });

    $('.checked').bind('click', function() {

        th_index = $(this).parent().index();
        is_checked = $($(table).find('td').get(th_index)).find('input:checkbox').attr('checked');

        if(is_checked)
        {
            checked[th_index] = true;

        }

        $(table).find('td.type_checkbox').each(function(index) {

            td_index = $(this).index() - 1;

            if(th_index == td_index)
            {
                if(checked[th_index])
                {
                    $(this).find('input:checkbox').removeAttr('checked');

                } else {

                    $(this).find('input:checkbox').attr('checked', 'checked');

                }

            }

        });

        if (checked[th_index])
        {
            checked[th_index] = false;

        } else {

            checked[th_index] = true;

        }

        return false;

    });

});
