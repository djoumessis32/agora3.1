<script type="text/javascript">
////	Acces guest à un espace  (accès direct ou avec mot de passe)
function publicSpaceAccess(_idSpace, password)
{
	// Accès direct sans password  /  Saisie du mot de passe  /  Controle Ajax du mot de passe
	if(password==0)			{redir("?_idSpaceAccess="+_idSpace);}
	else if(password==1)	{lightboxPrompt("<?= Txt::trad("password") ?>", "publicSpaceAccess('"+_idSpace+"',$('.promptInputText').val());", "password");}
	else if(password.length>1)
	{
		var ajaxUrl="?action=publicSpaceAccess&password="+encodeURIComponent(password)+"&_idSpace="+encodeURIComponent(_idSpace);
		$.ajax(ajaxUrl).done(function(ajaxResult){
			if(find("true",ajaxResult))	{redir("?_idSpaceAccess="+_idSpace+"&password="+password);}
			else						{displayNotif("<?= Txt::trad("espace_password_erreur") ?>");  return false;}
		});
	}
}

////	Init la page
$(function(){
	// apparition en "fade" du formulaire
	$(".vBlocks").fadeIn(800);
	// Mets le focus sur l'input du login
    $("[name='connectLogin']").focus();
	//Fait clignoter le "vForgotPassword" si une mauvaise authentification vient d'être faite
	<?php if(Req::isParam("msgNotif") && (in_array("MSG_NOTIF_identification",Req::getParam("msgNotif")) || in_array(Txt::trad("MSG_NOTIF_identification"),Req::getParam("msgNotif")))){ ?>
		$(".vForgotPassword").addClass("underlineShadow").css("color","#d00").effect("pulsate",{times:20},20000);
	<?php } ?>
});
</script>


<style>
/*Header Bar*/
.vHeaderBar					{height:40px;}
.vHeaderBarSub				{display:table-cell; padding:5px; vertical-align:middle;}
.vHeaderBarSub:nth-child(2)	{text-align:right;}

/*Identification*/
.vPage				{display:table; position:absolute; height:100%; width:100%;}/*position absolute pour prendre toute la hauteur de page*/
.vPageSub			{display:table-cell; text-align:center; vertical-align:middle; padding-top:0px; padding-bottom:80px;}
.vBlocks			{display:none; margin:auto; text-align:center; width:650px;}
.vBlocks:nth-child(2)	{margin-top:50px;}
.vBlockIcon			{display:block; position:absolute; margin:-20px;}
.vConnectInputs		{padding-top:30px; padding-bottom:30px; text-align:center;}
.vConnectLogin, .vConnectPassword, .vConnectSubmit	{margin:5px;}
.vConnectLogin		{width:180px;}
.vConnectPassword	{width:100px;}
.vConnectOptions	{display:table; width:100%;}
.vConnectOptionsSub	{display:table-cell; text-align:left; padding:5px;}
.vConnectOptionsSub:nth-child(2)	{text-align:right;}
.vForgotPassword	{margin-left:15px;}
img[src*='check']	{height:16px;}

/*Accès public*/
.vPublic			{display:table; width:100%;}
.vPublicSub			{display:table-cell; padding:15px;}
.vPublicSub div		{margin-top:8px;}
.vPublicSub:nth-child(1)	{text-align:right;}
.vPublicSub:nth-child(2)	{text-align:left;}

/*Responsive*/
@media screen and (max-width:650px){
	.vHeaderBar					{height:30px;}
	.vHeaderBarSub:nth-child(2)	{font-size:90%; font-weight:normal;}
	.vPageSub					{padding-bottom:0px;}
	.vBlocks					{width:95%; max-width:650px;}
	.vBlockIcon					{margin:-10px; height:45px;}
	.vConnectLogin, .vConnectPassword, .vConnectSubmit	{width:70%; min-width:150px; margin:auto; margin-bottom:10px;}
	.vForgotPassword			{display:block; margin:0px;}
}
</style>


<div class="headerBar vHeaderBar" id="headerBarRight">
	<h3 class="vHeaderBarSub"><?= Ctrl::$agora->name ?></h3>
	<div class="vHeaderBarSub"><?= Ctrl::$agora->description ?></div>
</div>


<div class="vPage"><div class="vPageSub">
	<!--IDENTIFICATION FORM-->
	<form action="index.php" method="post" class="sBlock vBlocks" OnSubmit="return controlConnect()">
		<img src="app/img/connection.png" class="vBlockIcon">
		<div class="vConnectInputs">
			<input type="text" name="connectLogin" class="vConnectLogin" value="<?= $defaultLogin ?>" placeholder="<?= Txt::trad("placeholderLogin") ?>" title="<?= Txt::trad("placeholderLogin") ?>">
			<input type="password" name="connectPassword" class="vConnectPassword" placeholder="<?= Txt::trad("password") ?>" title="<?= Txt::trad("password") ?>">
			<?php if(Req::isParam(["targetObjUrl","_idSpaceAccess"])){ ?>
				<input type="hidden" name="targetObjUrl" value="<?= Req::getParam("targetObjUrl") ?>">
				<input type="hidden" name="_idSpaceAccess" value="<?= Req::getParam("_idSpaceAccess") ?>">
			<?php } ?>
			<button type="submit" class="vConnectSubmit"><?= Txt::trad("connexion") ?></button>
		</div>
		<div class="vConnectOptions">
			<div class="vConnectOptionsSub">
				<?php if(!empty($usersInscription)){ ?>
					<span class="vUserInscription sLink" onclick="lightboxOpen('?action=usersInscription')" title="<?= Txt::trad("usersInscription_info") ?>"><img src="app/img/check.png">&nbsp;<?= Txt::trad("usersInscription") ?></span>
				<?php } ?>
			</div>
			<div class="vConnectOptionsSub">
				<input type="checkbox" name="rememberMe" value="1" id="boxRememberMe" checked>
				<label for="boxRememberMe" title="<?= Txt::trad("connexion_auto_info") ?>"><?= Txt::trad("connexion_auto") ?> !</label>
				<span class="vForgotPassword sLink" onclick="lightboxOpen('?action=forgotPassword')" title="<?= Txt::trad("password_oublie_info") ?>"><?= Txt::trad("password_oublie") ?></span>
			</div>
		</div>
	</form>

	<!--ESPACES PUBLICS-->
	<?php if(!empty($objPublicSpaces)){ ?>
		<div class="sBlock vBlocks no_selection">
			<img src="app/img/publicBig.png" class="vBlockIcon">
			<div class="vPublic">
				<h3 class="vPublicSub"><?= Txt::trad("acces_guest") ?></h3>
				<h3 class="vPublicSub">
				<?php foreach($objPublicSpaces as $tmpSpace){ ?>
					<div><a href="javascript:publicSpaceAccess('<?= $tmpSpace->_id ?>','<?= empty($tmpSpace->password)?0:1 ?>');"><img src="app/img/arrowRight.png">&nbsp; <?= $tmpSpace->name ?></a></div>
				<?php } ?>
				</h3>
			</div>
		</div>
	<?php } ?>
</div></div>