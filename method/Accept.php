<?php
final class Download_Accept extends GWF_Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$table = GWF_Download::table();
		$id = Common::getGetString('id', '0');
		if ( (!($download = $table->find($id, false))) || 
			 ($download->gdoHashcode() !== Common::getGetString('token')) )
		{
			return $this->error('err_gdo_not_found', [$table->gdoClassName(), get_called_class(), htmle($id)]);
		}
		$download->saveVars(array(
			'dl_accepted' => GWF_Time::getDate(),
			'dl_acceptor' => Common::getGetInt('by'),
		));
	}
}