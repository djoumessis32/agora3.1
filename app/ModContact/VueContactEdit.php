<script type="text/javascript">
lightboxWidth(600);//Resize
</script>

<style>
hr	{margin:8px 0px 8px 0px;}
</style>

<form action="index.php" method="post" onsubmit="return finalFormControl()" enctype="multipart/form-data">
	<!--CHAMPS PRINCIPAUX-->
	<?= $curObj->getFields("edit") ?>
	<!--IMAGE-->
	<div class="objField personImgSelect">
		<div class="fieldLabel"><?= $curObj->getImg() ?></div>
		<div class="fieldValue"><?= $curObj->displayImgMenu() ?></div>
	</div>
	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>