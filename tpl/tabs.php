<?php
$bar = GDO_Bar::make();
$bar->addFields(array(
	GDO_Link::make('link_downloads')->href(href('Download', 'List')),
	GDO_Link::make('link_upload')->href(href('Download', 'Crud')),
));
echo $bar->renderCell();
