<?


if(_string_count($_[P]['this']['name']))
{
    ?>
    <h1 class="art-postheader"><?=$_[$�]['this']['name']?></h1>
    <?

}

if(_string_count($_[P]['this']['text']))
{
    print $_[$�]['this']['text'];

}


?>
