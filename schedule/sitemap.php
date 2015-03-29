<?


require_once(dirname(__FILE__).'/../_.php');

_s(
    _files(ROOT.'/'.MOD, array(
        '_setting.php',
        '_include.php',
        '_functions.php')
    )
);

$file = fopen(ROOT."/sitemap.xml", 'w');

fwrite($file, '<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
');

$last_modified = '';
$fields = array();
$fields = _is_fields('pages');
if($fields['last_modified'])
{
    $last_modified = ', last_modified';

}

foreach(_s("SELECT id, url ".$last_modified." FROM pages WHERE public = 1 ORDER BY url ASC") as $k => $v)
{
    $v['url'] = _last_slash($v['url']);
    $pathinfo = pathinfo($v['url']);

    fwrite($file, '<url>
<loc>'._htmlspecialchars('http://'.SITE.($v['url'] == '/' ? '' : $v['url']).($pathinfo['extension'] ? '' : '/')).'</loc>
'.($v['last_modified'] ? '<lastmod>'._sqldate($v['last_modified'], "Y-m-d").'</lastmod>' : '').'
</url>
');

}

fwrite($file, '</urlset>');
fclose($file);


?>
