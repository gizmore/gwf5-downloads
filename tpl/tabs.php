<?php
$bar = GDO_Bar::make();
$count = GWF_Download::countDownloads();
$bar->addFields(array(
	GDO_Link::make('link_downloads')->href(href('Download', 'List'))->label('link_downloads', [$count]),
	GDO_Link::make('link_upload')->href(href('Download', 'Crud')),
));
echo $bar->renderCell();
