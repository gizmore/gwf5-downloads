<?php
/**
 * A download is votable, likeable, purchasable.
 * 
 * @author gizmore
 * @since 3.0
 * @version 5.0
 */
final class GWF_Download extends GDO
{
	#############
	### Votes ###
	#############
	use GWF_VotedObject;
	public function gdoVoteTable() { return GWF_DownloadVote::table(); }
	public function gdoVoteMin() { return 1; }
	public function gdoVoteMax() { return 5; }
	public function gdoVotesBeforeOutcome() { return Module_Download::instance()->cfgVotesOutcome(); }
	public function gdoVoteAllowed(GWF_User $user) { return $user->getLevel() >= $this->getLevel(); }
	
	###########
	### GDO ###
	###########
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('dl_id'),
			GDO_String::make('dl_title')->notNull()->label('title'),
			GDO_Message::make('dl_info')->notNull()->label('info'),
			GDO_Category::make('dl_category'),
			GDO_File::make('dl_file')->notNull(),
			GDO_Int::make('dl_downloads')->unsigned()->notNull()->initial('0')->editable(false)->label('downloads'),
			GDO_Money::make('dl_price'),
			GDO_Level::make('dl_level')->notNull()->initial('0'),
			GDO_VoteCount::make('dl_votes'),
			GDO_VoteRating::make('dl_rating'),
			GDO_CreatedAt::make('dl_created'),
			GDO_CreatedBy::make('dl_creator'),
			GDO_DateTime::make('dl_accepted')->editable(false)->label('accepted_at'),
			GDO_User::make('dl_acceptor')->editable(false)->label('accepted_by'),
			GDO_EditedAt::make('dl_edited'),
			GDO_EditedBy::make('dl_editor'),
			GDO_DeletedAt::make('dl_deleted'),
			GDO_DeletedBy::make('dl_deletor'),
		);
	}
	
	##############
	### Bridge ###
	##############
	
	public function href_edit() { return href('Download', 'Crud', '&id='.$this->getID()); }
	public function href_view() { return href('Download', 'View', '&id='.$this->getID()); }

	public function gdoHashcode() { return self::gdoHashcodeS($this->getVars(['dl_id', 'dl_title', 'dl_category', 'dl_file', 'dl_created', 'dl_creator'])); }

	public function canView(GWF_User $user) { return ($this->isAccepted() && (!$this->isDeleted())) || $user->isStaff(); }
	public function canDownload(GWF_User $user)
	{
		if ($this->isPaid())
		{
			if (!GWF_DownloadToken::hasToken($user, $this))
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		if ($user->getLevel() < $this->getLevel())
		{
			return false;
		}
		if ($this->isDeleted() || (!$this->isAccepted()) )
		{
			return false;
		}
		return true;
	}
	
	public function canPurchase()
	{
		return $this->isPaid();
	}
	
	##############
	### Getter ###
	##############
	
	/**
	 * @return GWF_File
	 */
	public function getFile() { return $this->getValue('dl_file'); }
	public function getFileID() { return $this->getVar('dl_file'); }
	
	/**
	 * @return GWF_User
	 */
	public function getCreator() { return $this->getValue('dl_creator'); }
	public function getCreatorID() { return $this->getVar('dl_creator'); }
	public function getCreateDate() { return $this->getVar('dl_created'); }
	/**
	 * @return GDO_Message
	 */
	public function gdoMessage() { return $this->gdoColumn('dl_info'); }
	
	public function getDownloads() { return $this->getVar('dl_downloads'); }
	public function getRating() { return $this->getVar('dl_rating'); }
	public function getVotes() { return $this->getVar('dl_votes'); }
	
	public function getLevel() { return $this->getVar('dl_level'); }
	public function getPrice() { return $this->getVar('dl_price'); }
	public function displayPrice() { return "€".$this->getVar('dl_price'); }
	public function getType() { return $this->getFile()->getType(); }
	public function getTitle() { return $this->getVar('dl_title'); }
	public function displayInfo() { return $this->gdoMessage()->renderCell(); }
	public function displaySize() { return $this->getFile()->displaySize(); }
	
	public function isAccepted() { return $this->getVar('dl_accepted') !== null; }
	public function isDeleted() { return $this->getVar('dl_deleted') !== null; }
	public function isPaid() { return $this->getPrice() !== null; }
	##############
	### Render ###
	##############
	public function renderCard()
	{
		return GWF_Template::modulePHP('Download', 'card/download.php', ['gdo' => $this]);
	}

	##############
	### Static ###
	##############
	public static function countDownloads()
	{
		if (!($cached = GDOCache::get('gwf_download_count')))
		{
			$cached = self::table()->countWhere("dl_deleted IS NULL AND dl_accepted IS NOT NULL");
			GDOCache::set('gwf_download_count', $cached);
		}
		return $cached;
	}
	
	public function gdoAfterCreate()
	{
		GDOCache::unset('gwf_download_count');
	}
}
