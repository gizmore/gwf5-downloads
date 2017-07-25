<?php
/**
 * Download form.
 * 
 * @author gizmore
 * @since 5.0
 * @version 5.0
 * @see GWF_Download
 */
final class Download_Crud extends GWF_MethodCrud
{
	public function gdoTable() { return GWF_Download::table(); }
	public function hrefList() { return href('Download', 'List'); }
	
	public function execute()
	{
		$response = parent::execute();
		$tabs = Module_Download::instance()->renderTabs();
		return $tabs->add($response);
	}
	
	protected function crudCreateTitle()
	{
	    $this->title('ft_download_upload', [$this->getSiteName()]);
	}
	
	public function createForm(GWF_Form $form)
	{
		$user = GWF_User::current();
		parent::createForm($form);
		if (!$user->hasPermission('staff'))
		{
			$form->removeField('dl_price');
		}
	}
	
	public function createFormButtons(GWF_Form $form)
	{
		parent::createFormButtons($form);
		$user = GWF_User::current();
		if ($user->isStaff())
		{
			if ($this->gdo && !$this->gdo->isAccepted())
			{
				$form->addField(GDO_Submit::make('accept'));
			}
		}
	}

	public function afterCreate(GWF_Form $form)
	{
		$user = GWF_User::current();
		if ($user->isStaff())
		{
			$this->gdo->saveVars(array(
				'dl_accepted' => GWF_Time::getDate(),
				'dl_acceptor' => GWF_User::SYSTEM_ID,
			), false);
		}
		else
		{
			$this->onAcceptMail($form);
			return $this->message('msg_download_awaiting_accept');
		}
	}
	
	###################
	### Accept Mail ###
	###################
	private function onAcceptMail(GWF_Form $form)
	{
		$iso = GWF_Trans::$ISO;
		foreach (GWF_User::admins() as $admin)
		{
			GWF_Trans::$ISO = $admin->getLangISO();
			$this->onAcceptMailTo($form, $admin);
		}
		GWF_Trans::$ISO = $iso;
	}

	private function onAcceptMailTo(GWF_Form $form, GWF_User $user)
	{
		$dl = $this->gdo; $dl instanceof GWF_Download;

		# Sender
		$mail = new GWF_Mail();
		$mail->setSender(GWF_BOT_NAME);
		$mail->setSenderName(GWF_BOT_NAME);
		
		# Body
		$username = $user->displayNameLabel();
		$sitename = $this->getSiteName();
		$type = $dl->getType();
		$size = $dl->displaySize();
		$title = htmle($dl->getTitle());
		$info = $dl->displayInfo();
		$uploader = $dl->getCreator()->displayNameLabel();
		
		$link = GWF_HTML::anchor(url('Download', 'Approve', "&id={$dl->getID()}&token={$dl->gdoHashcode()}"));
		$args = [$username, $sitename, $type, $size, $title, $info, $uploader, $link];
		$mail->setBody(t('mail_body_download_pending', $args));
		$mail->setSubject(t('mail_subj_download_pending', [$sitename]));
		
		# Send
		$mail->sendToUser($user);
	}
}
