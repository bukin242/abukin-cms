<?


function _ui_datepicker($id = 'datepicker', $param = array("inline" => "true")) {

    $str = '<script type="text/javascript">';

    $str .= "
    $(function()
    {
        $('.".$id."').datepicker({

                "._aimp($param, ':', ', ')."

        });

    });
    ";

    $str .= '</script>';

    print $str;

}


function _ui_timepicker($id = 'timepicker', $param = array("showButtonPanel" => "false", "timeFormat" => "'hh:mm:ss'", "showSecond" => "true")) {

    $str = '<script type="text/javascript">';

    $str .= "
    $(function()
    {
        $('.".$id."').timepicker({

                "._aimp($param, ':', ', ')."

        });

    });
    ";

    $str .= '</script>';

    print $str;

}


function _ui_datetimepicker($id = 'timepicker', $param = array("showButtonPanel" => "false", "timeFormat" => "'hh:mm:ss'", "showSecond" => "true")) {

    $str = '<script type="text/javascript">';

    $str .= "
    $(function()
    {
        $('.".$id."').datetimepicker({

                "._aimp($param, ':', ', ')."

        });

    });
    ";

    $str .= '</script>';

    print $str;

}


function _ui_error($str, $mess = '') {

    return '
    <div class="ui-widget">
        <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
            <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
            <strong>'.$str.'</strong>'.$mess.'</p>
        </div>
    </div>';

}


function _ui_highlight($str, $mess = '') {

    return '
    <div class="ui-widget">
        <div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; margin-bottom: 10px; padding: 0 .7em;">
            <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
            <strong>'.$str.'</strong>'.$mess.'</p>
        </div>
    </div>';

}


function _ui_slider($name_, $param = array('range' => 'true', 'min' => '0', 'max' => '200000', 'values' => '[ 0, 0 ]')) {

    $str = '<script type="text/javascript">';

    $str .= "
    $(function()
    {
        $('#slider-".$name_."').slider({
            slide: function(event, ui) {

                $('[name=".$name_."]').val((ui.values[ 0 ] > 0 && ui.values[ 0 ] != ui.values[ 1 ] ? ui.values[ 0 ] + ' - ' : '') + (ui.values[ 1 ] > 0 ? ui.values[ 1 ] : ''));

            },

            "._aimp($param, ':', ', ')."

        });

    });
    ";

    $str .= '</script>';

    print $str;

    return '<span id="slider-'.$name_.'"></span>';

}


?>
