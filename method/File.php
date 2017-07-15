<?php
final class Download_File extends GWF_Method
{
	public function execute()
	{
		$user = GWF_User::current();
		$id = Common::getGetString('id', 'id');
		$download = GWF_Download::table()->findById($id);
		if (!$download->canDownload($user))
		{
			GWF_Download::notFoundException(htmle($id));
		}
		
		$download->increase('dl_downloads');
		
		GWF_Hook::call('DownloadFile', [$user, $download]);
		
		return method('GWF', 'GetFile')->executeWithId($download->getFileID());
	}
}
