<ul class="art-hmenu"><?$array = array(    'http://habrahabr.ru/' => '������������ �����',    'http://dirty.ru/' => '���������� ĸ���',    'http://google.ru/' => '������ �� ����, ������ ������ ��� �� ����',);foreach($array as $k => $v){    ?><li><?        ?><a href="<?=$k?>" target="_blank"><span class="l"></span><span class="r"></span><span class="t"><?=$v?></span></a><?    ?></li><?}?></ul>