<?php
/**
 * 
 * @author gizmore
 *
 */
final class Download_List extends GWF_MethodQueryCards
{
	public function isGuestAllowed()
	{
		return Module_Download::instance()->cfgGuestDownload();
	}
	
	public function execute()
	{
		$response = parent::execute();
		$tabs = Module_Download::instance()->renderTabs();
		return $tabs->add($response);
	}
	
	public function gdoTable()
	{
		return GWF_Download::table();
	}
	
	public function getQuery()
	{
		return GWF_Download::table()->select('*, gwf_file.*')->joinObject('dl_file')->where("dl_deleted IS NULL AND dl_accepted IS NOT NULL");
	}
	
	public function getHeaders()
	{
		$gdo = GWF_Download::table();
		$file = GWF_File::table();
		return array(
			GDO_EditButton::make(),
			$gdo->gdoColumn('dl_id'),
			$gdo->gdoColumn('dl_title'),
			$file->gdoColumn('file_size'),
			$gdo->gdoColumn('dl_downloads'),
			$gdo->gdoColumn('dl_price'),
			$gdo->gdoColumn('dl_votes'),
			$gdo->gdoColumn('dl_rating'),
			GDO_Button::make('view'),
		);
	}
}
