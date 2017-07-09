<?php $gdo instanceof GWF_DownloadToken; $dl = $gdo->getDowload(); $file = $dl->getFile(); ?>
<?php
$user = GWF_User::current();
?>
<md-card class="gwf-downloadtoken">
  <md-card-title>
    <md-card-title-text>
      <span class="md-headline">
        <div><?php l('card_title_downloadtoken', [htmle($dl->getTitle())]); ?></div>
        <div class="gwf-card-subtitle"><?php l('card_title_downloadprice', [$dl->displayPrice()]); ?></div>
      </span>
    </md-card-title-text>
  </md-card-title>
  <gwf-div></gwf-div>
  <md-card-content flex>
    <div><?php l('name'); ?>: <?php html($file->getName()); ?></div>
    <div><?php l('type'); ?>: <?php echo $file->getType(); ?></div>
    <div><?php l('size'); ?>: <?php echo $file->displaySize(); ?></div>
    <div><?php l('downloads'); ?>: <?php echo $dl->getDownloads(); ?></div>
    <div><?php l('votes'); ?>: <?php echo $dl->gdoColumn('dl_votes')->renderCell(); ?></div>
    <div><?php l('rating'); ?>: <?php echo $dl->gdoColumn('dl_rating')->renderCell(); ?></div>
    <div><?php l('price'); ?>: <?php echo $dl->displayPrice(); ?></div>
  </md-card-content>
</md-card>
