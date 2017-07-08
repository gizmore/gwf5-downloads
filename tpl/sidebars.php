<?php $navbar instanceof GWF_Navbar;
if ($navbar->isLeft())
{
	$count = GWF_Download::countDownloads();
	$navbar->addField(GDO_Link::make('a')->label('link_downloads', [$count])->href(href('Download', 'List')));
}
