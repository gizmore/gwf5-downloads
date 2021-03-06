<?php
/**
 * Purchasable download token. 
 * @author gizmore
 * @since 3.0
 * @version 5.0
 */
final class GWF_DownloadToken extends GDO implements GWF_Orderable
{
	#############
	### Order ###
	#############
	public function getOrderCancelURL(GWF_User $user) { return url('Download', 'List'); }
	public function getOrderSuccessURL(GWF_User $user) { return url('Download', 'View', 'id='.$this->getDownloadID()); }
	public function getOrderTitle(string $iso) { return tiso($iso, 'card_title_downloadtoken', [htmle($this->getDowload()->getTitle())]); }
	public function getOrderPrice() { return $this->getDowload()->getPrice(); }
	public function canPayOrderWith(GWF_PaymentModule $module) { return true; }
	public function onOrderPaid()
	{
		$this->insert();
		return new GWF_Message('msg_download_purchased');
	}

	###########
	### GDO ###
	###########
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDO_User::make('dlt_user')->primary(),
			GDO_Object::make('dlt_download')->table(GWF_Download::table())->primary(),
			GDO_Token::make('dlt_token')->notNull(),
			GDO_CreatedAt::make('dlt_created'),
			GDO_CreatedBy::make('dlt_creator'),
		);
	}
	
	/**
	 * @return GWF_User
	 */
	public function getUser() { return $this->getValue('dlt_user'); }
	public function getUserID() { return $this->getVar('dlt_user'); }

	/**
	 * @return GWF_Download
	 */
	public function getDowload() { return $this->getValue('dlt_download'); }
	public function getDowloadID() { return $this->getVar('dlt_download'); }
	
	/**
	 * @return GWF_User
	 */
	public function getCreatedBy() { return $this->getValue('dlt_creator'); }
	public function getCreatedAt() { return $this->getVar('dlt_created'); }
	public function getToken() { return $this->getVar('dlt_token'); }
	
	public static function hasToken(GWF_User $user, GWF_Download $dl)
	{
		return self::table()->select('1')->where("dlt_user={$user->getID()} AND dlt_download={$dl->getID()}")->first()->exec()->fetchValue() === '1';
	}

	##############
	### Render ###
	##############
	public function renderCard()
	{
		return GWF_Template::modulePHP('Download', 'card/download_token.php', ['gdo' => $this]);
	}
	
}
