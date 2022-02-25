<script type="text/javascript">
lightboxWidth(600);//Resize
</script>

<style>
input[name='adress']	{width:99%;}
</style>

<form action="index.php" method="post" onsubmit="return finalFormControl()" enctype="multipart/form-data">
	<!--URL & DESCRIPTION-->
	<input type="url" name="adress" value="<?= empty($curObj->adress)?"http://":$curObj->adress ?>" placeholder="<?= Txt::trad("LINK_adress") ?>">
	<textarea name="description" placeholder="<?= Txt::trad("description") ?>"><?= $curObj->description ?></textarea>
	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>