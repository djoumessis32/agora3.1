<script type="text/javascript">
lightboxWidth(550);//Resize
</script>

<style>
body							{background-image:url('app/img/contact/background.png'); background-position: bottom right;}
.percentBar						{margin:10px 15px 0px 0px;}/*idem*/
.objField>.fieldLabel			{font-weight:normal;}/*idem*/
.vContactImg,.vContactDetails	{display:table-cell;}
.vContactImg img				{max-width:150px;}
.vContactDetails				{padding-left:15px; line-height:25px;}
</style>

<div class="fancyboxContent">
	<div class="lightboxObjTitle">
		<?php if($curObj->editRight()){ ?><a href="javascript:lightboxOpen('<?= $curObj->getUrl("edit") ?>')" class="lightboxObjEditIcon" title="<?= Txt::trad("modifier") ?>"><img src="app/img/edit.png"></a><?php } ?>
		<?= $curObj->display("all") ?>
	</div>
	<hr class="hrGradient hrMargins">
	<div class="vContactImg"><?= $curObj->getImg() ?></div>
	<div class="personDetails vContactDetails"><?= $curObj->getFields("profile") ?></div>
	<?= $curObj->menuAttachedFiles() ?>
</div>