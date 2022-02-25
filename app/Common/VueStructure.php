<!doctype html>
<html lang="<?= Txt::trad("HEADER_HTTP") ?>">
	<head>
		<!-- AGORA-PROJECT :: UNDER THE GENERAL PUBLIC LICENSE V2 :: http://www.gnu.org -->
		<meta charset="UTF-8">
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta http-equiv="content-language" content="<?= Txt::trad("HEADER_HTTP") ?>">
		<title><?= (!empty(Ctrl::$agora->name)) ? Ctrl::$agora->name : "Agora-Project" ?></title>
		<meta name="Description" content="<?= (!empty(Ctrl::$agora->description)) ? Ctrl::$agora->description : "Agora-Project" ?>">
		<meta name="application-name" content="Agora-Project">
		<meta name="application-url" content="https://www.agora-project.net">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge"><!--mode compatibilité IE-->
		<link rel="icon" type="image/gif" href="app/img/favicon.gif" />
		<!-- JQUERY & JQUERY-UI -->
		<script src="app/js/jquery-2.1.4.min.js"></script>
		<script src="app/js/jquery-ui/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="app/js/jquery-ui/<?= $skinCss=="white"?"smoothness":"ui-darkness" ?>/jquery-ui.css">
		<script src="app/js/jquery-ui/datepicker-<?= Txt::trad("DATEPICKER") ?>.js"></script>	<!--lang du jquery-ui datepicker-->
		<!-- JQUERY PLUGINS -->
		<script src="app/js/fancybox/jquery.fancybox.pack.js"></script>
		<link href="app/js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="app/js/tooltipster/jquery.tooltipster.min.js"></script>
		<link rel="stylesheet" type="text/css" href="app/js/tooltipster/tooltipster.css">
		<link rel="stylesheet" type="text/css" href="app/js/tooltipster/tooltipster-shadow.css">
		<script type="text/javascript" src="app/js/toastmessage/jquery.toastmessage.js"></script>
		<link rel="stylesheet" type="text/css" href="app/js/toastmessage/toastmessage.css">
		<script src="app/js/timepicker/jquery.timepicker.min.js"></script>
		<link rel="stylesheet" type="text/css" href="app/js/timepicker/jquery.timepicker.css">
		<!-- JS & CSS DE L'AGORA -->
		<script src="app/js/common.js?v<?= VERSION_AGORA ?>"></script><!--toujours après Jquery & plugins Jquery !!-->
		<link href="app/css/common.css?v<?= VERSION_AGORA ?>" rel="stylesheet" type="text/css">
		<link href="app/css/<?= $skinCss ?>.css?v<?= VERSION_AGORA ?>" rel="stylesheet" type="text/css">

		<!-- Parametrage Javascript, Notifications, Triggers JS, etc -->
		<script type="text/javascript">
		//navigateur obsolète (tjs en premier. faire simple "alert()")
		if(isObsoleteIE())	{alert("<?= Txt::trad("version_ie") ?>");}
		langDatepicker="<?= Txt::trad("DATEPICKER") ?>";
		labelConfirmCloseLightbox="<?= Txt::trad("confirmCloseLightbox") ?>";
		labelSpecifierLoginPassword="<?= Txt::trad("specifierLoginPassword") ?>";
		labelDateBeginEndControl="<?= Txt::trad("modif_dates_debutfin") ?>";
		labelEvtConfirm="<?= Txt::trad("CALENDAR_evenement_integrer") ?>";
		labelEvtConfirmNot="<?= Txt::trad("CALENDAR_evenement_pas_integrer") ?>";
		labelUploadMaxFilesize="<?= File::uploadMaxFilesize("error") ?>";
		valueUploadMaxFilesize=<?= File::uploadMaxFilesize() ?>;
		$(function(){
			//Footer avec userFooterHtml
			$("#pageFooter:has(#userFooterHtml)").css("width","100%");
			<?php
			//Affiche les Notifs & Lance les Triggers JS (parent.reload, etc)
			foreach($msgNotif as $tmpNotif)		{echo "displayNotif(\"".addslashes($tmpNotif["message"])."\", \"".$tmpNotif["type"]."\");";}
			foreach($jsTriggers as $tmpTrigger)	{echo $tmpTrigger;}
			if(defined("HOST_DOMAINE"))  {Host::footerJs();}
			?>
		});
		</script>
	</head>

	<body>
		<!--CONTENU DES LIGHTBOX-->
		<div id="lightboxContent"></div>
		<!--BACKGROUND & HEADER MENU & CORPS DE LA PAGE-->
		<?= !empty($pathWallpaper) ? "<img src=\"".$pathWallpaper."\" id='backgroundImg' class='noPrint'>" : null ?>
		<?= $headerMenu.$mainContent ?>
		<!--ICONE AGORA DU FOOTER & FOOTER PERSONNALISE (script de stats, etc)-->
		<?php if(!empty(Ctrl::$isMainPage)){ ?>
		<div id="pageFooterShadow"></div>
		<div id="pageFooter">
			<?= !empty(Ctrl::$agora->footerHtml) ? "<div id='userFooterHtml'>".Ctrl::$agora->footerHtml."</div>" : null ?>
			<div id="agoraIcon"><a href="<?= $pathLogoUrl ?>" target="_blank" title="<?= $pathLogoTitle ?>"><img src="<?= CtrlMisc::pathfooterLogo() ?>"></a></div>
		</div>
		<?php } ?>
	</body>
</html>