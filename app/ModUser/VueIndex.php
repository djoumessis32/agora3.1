<script>
////	Init la page
$(function(){
	$("[name=displayUsers]").val("<?= $_SESSION["displayUsers"] ?>");
	if($("[name=displayUsers]").val()=="all")	{$("[name=displayUsers]").addClass("vAllUsersSelected");}
})
</script>

<style>
/* BLOCKS DE CONTENU */
.objDBlock			{max-width:350px; min-width:220px; height:110px;}/*cf. "setObjBlockWidth()"*/
.objLabelMain		{padding:5px 5px 5px 10px;}
.vAllUsersSelected	{color:#b00; font-weight:bold;}
.vGroupLabel		{margin-top:5px; cursor:help;}
.vGroupLabel img	{height:12px;}
.vMenuAlphabet		{margin-right:5px;}
</style>

<div class="pageFull">
	<div class="pageMenu">
		<div class="sBlock">
			<!--MENU "USERS DE L'ESPACE" / "TOUS LES USERS" ("noTooltip" car pb sous Firefox)-->
			<?php if($menuDisplayUsers==true){ ?>
			<div class="moduleMenuLine sLink noTooltip" title="<?= Txt::trad("USER_utilisateurs_site_infos") ?>">
				<div class="moduleMenuIcon"><img src="app/img/user/icon.png"></div>
				<div class="moduleMenuTxt"><select name="displayUsers" onChange="redir('?ctrl=user&displayUsers='+this.value)"><option value="space"><?= Txt::trad("USER_utilisateurs_espace") ?></option><option value="all"><?= Txt::trad("USER_utilisateurs_site") ?></option></select></div>
			</div><hr>
			<?php } ?>
			<!--AJOUTER / AFFECTER DES UTILISATEURS EXISTANTS A L'ESPACE / IMPORTER DES UTILISATEURS-->
			<?php if(self::$curUser->isAdminCurSpace()){ ?>
				<div class="moduleMenuLine sLink" onclick="lightboxOpen('<?= MdlUser::getUrlNew() ?>');" title="<?= $_SESSION["displayUsers"]=="all"?Txt::trad("USER_ajouter_utilisateur_site"):Txt::trad("USER_ajouter_utilisateur_espace") ?>"><div class="moduleMenuIcon"><img src="app/img/plus.png"></div><div class="moduleMenuTxt"><?= Txt::trad("USER_ajouter_utilisateur") ?></div></div>
				<?php if($menuUsersAffectations==true){ ?><div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=user&action=AffectUsers');"><div class="moduleMenuIcon"><img src="app/img/plus.png"></div><div class="moduleMenuTxt"><?= Txt::trad("USER_affecter_utilisateur") ?></div></div><?php } ?>
				<div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=user&action=EditPersonsImportExport');"><div class="moduleMenuIcon"><img src="app/img/exportImport.png"></div><div class="moduleMenuTxt"><?= Txt::trad("importer")."/".Txt::trad("exporter")." ".Txt::trad("import_export_user") ?></div></div>
			<?php } ?>
			<!--ENVOI DES COORDONNEES DE CONNEXION / DES INVITATIONS-->
			<?php if(Ctrl::$curUser->isAdminGeneral()){ ?><div class="moduleMenuLine sLink" title="<?= Txt::trad("USER_envoi_coordonnees_info") ?>" onclick="lightboxOpen('?ctrl=user&action=SendCoordinates');"><div class="moduleMenuIcon"><img src="app/img/user/sendCoordinates.png"></div><div class="moduleMenuTxt"><?= Txt::trad("USER_envoi_coordonnees") ?></div></div><?php } ?>
			<?php if(Ctrl::$curUser->sendInvitationRight()){ ?><div class="moduleMenuLine sLink" title="<?= Txt::trad("USER_envoi_invitation_info") ?>" onclick="lightboxOpen('?ctrl=user&action=SendInvitation');"><div class="moduleMenuIcon"><img src="app/img/mail.png"></div><div class="moduleMenuTxt"><?= Txt::trad("USER_envoi_invitation") ?></div></div><?php } ?>
			<!--GROUPES D'UTILISATEURS (AFFICHAGE ESPACE UNIQUEMENT)-->
			<?php if($_SESSION["displayUsers"]=="space"){ ?><hr>
			<div class="moduleMenuLine">
				<div class="moduleMenuIcon"><img src="app/img/user/userGroup.png"></div>
				<div class="moduleMenuTxt">
					<div <?= Ctrl::$curUser->addGroupRight()?"class='sLink' title=\"".Txt::trad("USER_groupe_info")."\" onclick=\"lightboxOpen('?ctrl=user&action=UserGroupEdit');\"":null ?>><?= Txt::trad("USER_groupe_espace") ?></div>
					<?php foreach(MdlUserGroup::getGroups(Ctrl::$curSpace) as $tmpGroup)  {echo "<div class='vGroupLabel sLink' title=\"".$tmpGroup->usersLabel."\"><img src='app/img/dotW.png'> ".$tmpGroup->title."</div>";} ?>
				</div>
			</div>
			<?php } ?>
			<hr>
			<!--SELECTION D'UTILISATEURS / TYPE D'AFFICHAGE / TRI D'AFFICHAGE-->
			<?= MdlUser::menuSelectObjects().MdlUser::menuDisplayMode().MdlUser::menuSort() ?>
			<!--FILTRAGE ALPHABET-->
			<div class="moduleMenuLine sLink">
				<div class="moduleMenuIcon"><img src="app/img/alphabet.png"></div>
				<div class="moduleMenuTxt">
					<div class="menuContext sBlock" id="vMenuAlphabet">
						<?php foreach($alphabetList as $tmpLetter){ ?><a href="?ctrl=user&alphabet=<?= $tmpLetter ?>" class="vMenuAlphabet <?= Req::getParam("alphabet")==$tmpLetter?'sLinkSelect':'sLink' ?>"><?= $tmpLetter ?></a><?php } ?>
						<a href="?ctrl=user" class="vMenuAlphabet <?= Req::isParam("alphabet")==false?'sLinkSelect':'sLink' ?>"><?= Txt::trad("tout_afficher") ?></a>
					</div>
					<span class="sLink menuContextLauncher" for="vMenuAlphabet"><?= Txt::trad("alphabet_filtre").(strlen(Req::getParam("alphabet"))?" : ".Req::getParam("alphabet"):null) ?></span>
				</div>
			</div>
			<!--NB D'UTILISATEURS & "TOUS LES USERS SONT AFFECTES A CET ESPACE"-->
			<div class="moduleMenuLine" title="<?= $displayedUsersAllAffected ?>"><div class="moduleMenuIcon"><img src="app/img/info.png"></div><div class="moduleMenuTxt"><?= $displayedUsersTotalNb." ".Txt::trad("USER_users").(!empty($displayedUsersAllAffected)?"*":null) ?></div></div>
		</div>
	</div>
	<div class="pageFullContent">
		<!--CHEMIN DU DOSSIER & LISTE DES DOSSIERS & LISTE DES CONTACTS-->
		<?php foreach($displayedUsers as $tmpUser){ ?>
			<div class="sBlock objScrollContent <?= (MdlUser::getDisplayMode()=="line"?"objDLine":"objDBlock") ?>" <?= $tmpUser->blockIdForMenuContext() ?>>
				<?= $tmpUser->menuContext(); ?>
				<div class="objTable objUser">
					<div class="objLabelIcon"><?= $tmpUser->getImg(true) ?></div>
					<div class="objLabelMain">
						<div class="personLabelDetails">
							<div class="personLabel"><a href="javascript:lightboxOpen('<?= $tmpUser->getUrl("vue") ?>');"><?= $tmpUser->display("all") ?></a></div>
							<div class="personDetails"><?= $tmpUser->getFields(MdlUser::getDisplayMode()) ?></div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<!--AUCUN CONTENU-->
		<?php if(empty($displayedUsers)){ ?><div class="pageEmptyContent"><?= Txt::trad("USER_aucun_utilisateur") ?></div><?php } ?>
		<!--MENU DE PAGINATION-->
		<?= MdlUser::menuPagination($displayedUsersTotalNb,"alphabet") ?>
	</div>
</div>