<?php
final class Download_Admin extends GWF_MethodQueryTable
{
	use GWF_MethodAdmin;

	public function getPermission() { return 'staff'; }
	
	public function execute()
	{
		$response = parent::execute();
		return $this->renderNavBar('Download')->add($response);
	}
	
	public function getQuery()
	{
		return GWF_Download::table()->select();
	}
}
