<?


$list_pages = $_['pages']['max']/$_['pages']['count'];
$list_pages = (is_float($list_pages) ? intval($list_pages)+1 : $list_pages);
$this_pages = $_['pages']['pages'];

if($this_pages > 1)
{
    _title($_['pages']['this']['name'].', страница '.$_['pages']['pages']);

}

$post_get = array_merge($_POST, $_GET);
$_['url']['query'] = _aimp($post_get, $sep1 = '=', $sep2 = '&');
$_['url']['query'] = ($_['url']['query'] ? '?'.$_['url']['query'] : '');

if($list_pages > 1)
{
    ?>
    <script type="text/javascript">
    $(function(){
        $('#paginator').paginator({pagesTotal:<?=$list_pages?>,
            pagesSpan:11,
            pageCurrent:<?=$this_pages?>,
            baseUrl: 'page%number%.html<?=$_['url']['query']?>',
            lang: {
                next  : "",
                last  : "",
                prior : "",
                first : "",
                arrowRight : String.fromCharCode(8594),
                arrowLeft  : String.fromCharCode(8592)
            }
        });
    })
    </script>

    <div class="paginator" id="paginator">
        <table width="100%">
            <tr>
    <?

    if($_[$№]['first'])
    {
        print ($this_pages > 1?'<a href="'.$_['url']['path'].'/'.$_['url']['query'].'" class="first">&middot;&middot;&middot;</a> ':'<span class="first">&middot;&middot;&middot;</span> ');

    }

    if($_[$№]['next'])
    {
        print '<td class="left top">';
        print ($this_pages > 1?'<a href="'.$_['url']['path'].'/'.(($this_pages == 2)?'':'page'.($this_pages - 1).'.html').$_['url']['query'].'" rel="'.($this_pages - 1).'">&larr;</a>':'<span class="prev">&larr;</span> ');
        print '</td><td class="spaser"></td>';

    }

    ?>
    <td rowspan="2" align="center">
        <table>
            <tr>
    <?

        for($i = 1; $i <= $list_pages; $i++)
        {
            if($this_pages==$i)
            {
                print '<td width="7%"><span><strong>'.$i.'</strong></span></td>';

            } else {

                print '<td width="7%"><span><a href="'.$_['url']['path'].'/'.($i > 1?'page'.$i.'.html':'').$_['url']['query'].'">'.$i.'</a></span></td>';

            }

        }

    ?>
            </tr>

            <tr><td colspan="11"><div class="scroll_bar"><div class="scroll_trough"></div><div style="width: 8px; left: 3.60938px;" class="scroll_thumb"><div class="scroll_knob"></div></div><div style="width: 3px; left: 3.75px;" class="current_page_mark"></div></div></td></tr>
        </table>
    </td>
    <?

    if($_[$№]['next'])
    {
        print '<td class="spaser"></td><td class="right top">';
        print ($this_pages < $list_pages?'<a href="'.$_['url']['path'].'/page'.($this_pages + 1).'.html'.$_['url']['query'].'" rel="'.($this_pages + 1).'">&rarr;</a>':'<span class="next">&rarr;</span> ');
        print '</td>';

    }

    if($_[$№]['first'])
    {
        print ($this_pages < $list_pages?'<a href="'.$_['url']['path'].'/page'.$list_pages.'.html'.$_['url']['query'].'" class="last">&middot;&middot;&middot;</a> ':'<span class="last">&middot;&middot;&middot;</span> ');

    }

    ?>
            </tr>
        </table>
    </div>
    <?

}


?>
