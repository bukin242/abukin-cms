<?


$_['flash_iterator'] = 0;

function _flash($file = '', $width='100%', $height='100%', $src = '', $id = '') {
    global $_;
    $_['flash_iterator']++ ;

    if($file)
    {
        $img = '';
        $prepend = '';

        if($src)
        {
            $img = '<img src="'.$src.'"'.(is_numeric($width) ? ' width="'.$width.'"' : '').(is_numeric($height) ? ' height="'.$height.'"' : '').'/>';

        }

        if(!$id)
        {
            $flash_id = 'flash_iterator'.$_['flash_iterator'];
            print '<div id="'.$flash_id.'">'.$img.'</div>';
            $id = $flash_id;

        } else {

            $prepend = ($img ? "$('#".$id."').prepend('".$img."')" : "");

        }

?><script type="text/javascript"> $(function(){ $('#<?=$id?>').flash({ src: '<?=$file?>', <?=($width ? "width: '".$width."'," : "").($height ? " height: '".$height."'" : "")?> }); <?=$prepend?>}); </script>
<?

    }


}


?>
