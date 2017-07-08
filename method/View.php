<?php
final class Download_View extends GWF_Method
{
	public function execute()
	{
		$user = GWF_User::current();
		$table = GWF_Download::table();
		$module = Module_Download::instance();
		$id = Common::getGetInt('id');
		$dl = $table->find($id);
		if (!$dl->canView($user))
		{
			throw new GWF_Exception('err_gdo_not_found', [$table->gdoClassName(), $id]);
		}
		$tabs = $module->renderTabs();
		return $tabs->add($dl->renderCard());
	}
}
