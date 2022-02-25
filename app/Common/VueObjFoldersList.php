<style>
.folderContentDescription	{display:inline-block; line-height:25px; margin-left:20px;}
</style>

<?php foreach($foldersList as $tmpFolder){ ?>
	<div class="sBlock <?= $objDisplayClass ?>" <?= $tmpFolder->blockIdForMenuContext() ?>>
		<?= $tmpFolder->menuContext(); ?>
		<div class="objTable">
			<div class="objLabelIcon"><a href="<?= $tmpFolder->getUrl() ?>"><img src="app/img/folder.png"></a></div>
			<div class="objLabelMain"><a href="<?= $tmpFolder->getUrl() ?>" title="<?= $tmpFolder->description ?>"><?= Txt::reduce($tmpFolder->name,50) ?></a></div>
			<div class="objLabelDetails"><?= $tmpFolder->folderOtherDetails()." <div class='folderContentDescription'>".$tmpFolder->folderContentDescription() ?></div></div>
		</div>
	</div>
<?php } ?>