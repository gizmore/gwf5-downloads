<?php
final class Download_Order extends Payment_Order
{
	public function getOrder()
	{
		$download = GWF_Download::table()->find(Common::getRequestString('id'));
		$user = GWF_User::current()->persistent();
		return GWF_DownloadToken::blank(array(
			'dlt_user' => $user->getID(),
			'dlt_download' => $download->getID(),
		));
	}
	
}
