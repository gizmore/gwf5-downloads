<?php $gdo instanceof GWF_Download; $file = $gdo->getFile(); ?>
<?php
$user = GWF_User::current();
?>
<md-card class="gwf-download">
  <md-card-title>
    <md-card-title-text>
      <span class="md-headline">
        <div>“<?php html($gdo->getTitle()); ?>” - <?php echo $gdo->getCreator()->renderCell(); ?></div>
        <div class="gwf-card-date"><?php lt($gdo->getCreateDate()); ?></div>
      </span>
    </md-card-title-text>
  </md-card-title>
  <gwf-div></gwf-div>
  <md-card-content flex>
    <div><?php l('name'); ?>: <?php html($file->getName()); ?></div>
    <div><?php l('type'); ?>: <?php echo $file->getType(); ?></div>
    <div><?php l('size'); ?>: <?php echo $file->displaySize(); ?></div>
    <div><?php l('downloads'); ?>: <?php echo $gdo->getDownloads(); ?></div>
    <div><?php l('votes'); ?>: <?php echo $gdo->gdoColumn('dl_votes')->gdo($gdo)->renderCell(); ?></div>
    <div><?php l('rating'); ?>: <?php echo $gdo->gdoColumn('dl_rating')->gdo($gdo)->renderCell(); ?></div>
    <div><?php l('price'); ?>: <?php echo $gdo->displayPrice(); ?></div>
    <?php echo $gdo->displayInfo(); ?>
  </md-card-content>
  <gwf-div></gwf-div>
  <md-card-actions layout="row" layout-align="end center">
<?php
if ($gdo->canDownload($user))
{
	echo GDO_Button::make('download')->icon('file_download')->href(href('Download', 'File', '&id='.$gdo->getID()))->renderCell();
}
elseif ($gdo->canPurchase($user))
{
	echo GDO_Button::make('purchase')->icon('attach_money')->href(href('Download', 'Order', '&id='.$gdo->getID()))->renderCell();
}
else
{
	
}

?>
  </md-card-actions>
</md-card>
