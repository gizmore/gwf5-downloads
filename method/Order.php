<?php
final class Download_Order extends Payment_Order
{
	public function getOrderable()
	{
		$download = GWF_Download::table()->find(Common::getRequestString('id'));
		$user = GWF_User::current()->persistent();
		return GWF_DownloadToken::blank(array(
			'dlt_user' => $user->getID(),
			'dlt_download' => $download->getID(),
		));
	}
	
	public function execute()
	{
		return $this->initOrderable();
	}
	
	public function createForm(GWF_Form $form)
	{
		
	}
	
}
