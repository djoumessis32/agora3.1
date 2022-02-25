<script type="text/javascript">
lightboxWidth(550);//Resize
</script>

<style>
.percentBar				{margin:10px 15px 0px 0px;}/*idem*/
.objField>.fieldLabel	{font-weight:normal;}/*idem*/
.vUserImg,.vUserDetails	{display:table-cell;}
.vUserImg img			{max-width:150px;}
.vUserDetails			{padding-left:15px; line-height:25px;}
</style>

<div class="fancyboxContent">
	<div class="lightboxObjTitle">
		<?php if($curObj->editRight()){ ?><a href="javascript:lightboxOpen('<?= $curObj->getUrl("edit") ?>')" class="lightboxObjEditIcon" title="<?= Txt::trad("modifier") ?>"><img src="app/img/edit.png"></a><?php } ?>
		<?= $curObj->display("all") ?>
	</div>
	<hr class="hrGradient hrMargins">
	<div class="vUserImg"><?= $curObj->getImg() ?></div>
	<div class="personDetails vUserDetails">
		<?= $curObj->getFields("profile") ?>
		<div class="objField">
			<div class="fieldLabel"><img src="app/img/user/userConnection.png"> <?= Txt::trad("USER_lastConnection") ?></div>
			<div class="fieldValue"><?= !empty($curObj->lastConnection) ? Txt::displayDate($curObj->lastConnection) : Txt::trad("USER_pas_connecte") ?></div>
		</div>
	</div>
	<?= $curObj->menuAttachedFiles() ?>
</div>