<script type="text/javascript">
////	Resize
lightboxWidth(700);
</script>

<style>
[name='title']			{width:400px; max-width:80%; margin-bottom:20px;}
.vEvtOptionsLabel img	{max-height:15px;}
.vMessageQuoted					{overflow:auto; max-height:100px; margin-bottom:20px; opacity:0.7; background:#eee; border-radius:5px; padding:5px; font-style:italic; font-size:95%;}
.vMessageQuoted [src*='quote2']	{float:right;}
.vSubMessInfos					{box-shadow:0 6px 6px -6px #999;}
.vSubMessDescription			{margin-top:10px;}
</style>

<form action="index.php" method="post" onsubmit="return finalFormControl()" enctype="multipart/form-data">

	<!--MESSAGE A CITER?-->
	<?php if(!empty($messageParent)){ ?>
	<div class="vMessageQuoted">
		<img src="app/img/forum/quote2.png">
		<span class="vSubMessInfos"><?= $messageParent->title ?></span>
		<div class="vSubMessDescription"><?= $messageParent->description ?></div>
	</div>
	<?php } ?>

	<!--TITRE & DESCRIPTION (EDITOR)-->
	<input type="text" name="title" value="<?= $curObj->title ?>" class="editInputText" placeholder="<?= Txt::trad("title") ?>">
	<textarea name="description"><?= $curObj->description ?></textarea>

	<!--"_idMessageParent" & MENU COMMUN-->
	<input type="hidden" name="_idMessageParent" value="<?= Req::getParam("_idMessageParent") ?>">
	<?= $curObj->menuEditValidate() ?>
</form>