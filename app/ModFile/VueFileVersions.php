<script type="text/javascript">
////	Resize
lightboxWidth(500);

////	Confirmation de suppression de version
function confirmDeleteVersion(dateCrea)
{
	if(confirm("<?= Txt::trad("FILE_confirmer_suppression_version")?>")){
		redir("?ctrl=file&action=DeleteFileVersion&targetObjId=<?= $curObj->_targetObjId ?>&dateCrea="+dateCrea);
	}
}
</script>

<style>
.vFileVersion			{margin-top:20px;}
.versionDetails			{margin-top:8px;}
img[src*='separator']	{margin:0px 8px 0px 8px;}
img[src*='download'],img[src*='delete']		{max-height:20px; margin-top:5px;}
img[src*='delete']		{margin-left:20px;}
</style>

<div class="lightboxTitle"><?= Txt::trad("FILE_versions_de")." <i>".$curObj->name."</i>" ?></div>
<ol reversed>
	<?php foreach($curObj->getVersions() as $tmpVersion){ ?>
		<li class="vFileVersion">
			<?= $tmpVersion["name"] ?>
			<div class="versionDetails">
				<?= Txt::displayDate($tmpVersion["dateCrea"],"full") ?>
				<img src="app/img/separator.png">
				<?= Ctrl::getObj("user",$tmpVersion["_idUser"])->display() ?>
				<img src="app/img/separator.png">
				<?= File::displaySize($tmpVersion["octetSize"]) ?>
				<br>
				<a href="<?= $curObj->urlDownloadDisplay("download",$tmpVersion["dateCrea"]) ?>" target="_blank"><img src="app/img/download.png"> <?= Txt::trad("telecharger")?></a>
				<a href="javascript:confirmDeleteVersion('<?= urlencode($tmpVersion["dateCrea"]) ?>')"><img src="app/img/delete.png"> <?= Txt::trad("supprimer")?></a>
			</div>
		</li>
	<?php } ?>
</ol>
<br>

