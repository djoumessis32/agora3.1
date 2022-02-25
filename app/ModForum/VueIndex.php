<script>
$(function(){
	//Hauteur de l'image de l'user = hauteur du conteneur
	$(".vSubMessCellAutorImg img").css("max-height", $(".objDLine").css("height"));

	//Affichage des messages en arborescence
	$(".vMessageBlock").each(function(){
		var curLevel=parseInt($(this).attr("data-treeLevel"));//les messages de premier niveau sont à "1" (et non "0")
		if(curLevel>1)	{$(this).css("margin-left",((curLevel-1)*15)+"px").css("margin-top","3px");}//Ajoute 15px de marge gauche et réduit la marge supérieure à 3px au lieu de 8px
	});

	//Active/désactive les notifications des messages par mail
	<?php if($displayForum=="subjectMessages"){ ?>
		$("#notifyLastMessage").on("click",function(){
			$.ajax("?ctrl=forum&action=notifyLastMessage&targetObjId=<?= $curSubject->_targetObjId ?>").done(function(ajaxResult){
				if(ajaxResult=="addUser")	{$("#notifyLastMessage").addClass("vNotifyLastMessageSelect");}
				else						{$("#notifyLastMessage").removeClass("vNotifyLastMessageSelect");}
			});
		});
		//Selectionne "Me notifier par email"?
		if("<?= (int)$curSubject->curUserNotifyLastMessage() ?>"=="1")	{$("#notifyLastMessage").addClass("vNotifyLastMessageSelect");}
	<?php } ?>
});
</script>

<style>
/*Masque le menu de gauche si affiche les thèmes et que le menu est vide*/
<?php if($displayForum=="theme" && MdlForumTheme::addRight()==false){ ?>.pageMenu{display:none}<?php } ?>
/*Themes*/
.vThemeTable				{display:table; width:99%; margin-bottom:10px;}
.vThemeCell					{display:table-cell; vertical-align:middle;}
.vThemeCell:nth-child(1)		{padding-left:10px;}
.vThemeCell:nth-child(2)		{width:90px; font-size:90%; line-height:20px;}
.vThemeCell:nth-child(3)		{width:300px; font-size:90%; line-height:20px;}
.themeColor					{border:#777 solid 1px; border-radius:2px;}/*surcharge!*/
.vThemeTitle				{text-transform:uppercase;}
.vThemeDescription			{margin-top:10px; font-size:90%; font-weight:normal;}
/*Sujet & Message*/
.objDLine					{height:65px;}
.vSubMessTable				{display:table; width:100%; height:100%;}
[class^='vSubMessCell']		{display:table-cell; padding:10px;}
.vSubMessCellDetails		{width:240px; font-size:90%; line-height:20px; text-align:right;}
.vSubMessCellAutorImg		{width:70px; padding:0px; text-align:right;}
.vSubMessCellAutorImg img	{max-width:100%; max-height:80px;}
.vSubMessInfos				{box-shadow:0 6px 6px -6px #999;}
.vSubMessInfos>span			{margin:0px 8px 0px 8px; display:inline-block;}
.vSubMessAutorDate			{font-style:italic; font-size:95%; font-weight:normal;}
.vMessageReponse			{font-style:italic; font-size:95%;}
.vSubMessDescription		{margin-top:10px; font-size:95%; font-weight:normal;}
.objScrollContent .vSubMessDescription	{height:15px; overflow:hidden; text-overflow:ellipsis;}/*Liste de sujet : affiche la description sur une ligne (tronqué en fonction de la largeur)*/
.vSubjectBlock.sBlock		{margin-right:10px; margin-bottom:10px; box-shadow:0px 2px 6px 3px #ddd;}
.vMessageBlock				{margin-right:10px; margin-bottom:5px;}
.vMessageQuoted				{padding:5px; overflow:auto; background:#eee; border-radius:5px; max-height:50px;}
.vMessageQuoted [src*='quote2']	{float:right; opacity:0.7;}
.vNotifyLastMessageSelect	{color:#a00; font-style:italic;}
</style>

<div class="<?= (!empty($displayPageCenter))?"pageCenter":"pageFull" ?>">
	<div class="pageMenu">
		<div class="sBlock">
		<!--MENU DES SUJETS-->
		<?php if($displayForum=="subjects"){ ?>
			<?php if(MdlForumSubject::addRight()){ ?><div class="moduleMenuLine sLink" onclick="lightboxOpen('<?= MdlForumSubject::getUrlNew()."&_idTheme=".Req::getParam("_idTheme") ?>');"><div class="moduleMenuIcon"><img src="app/img/plus.png"></div><div class="moduleMenuTxt"><?= Txt::trad("FORUM_ajouter_sujet") ?></div></div><hr><?php } ?>
			<?= MdlForumSubject::menuSort() ?>
			<div class="moduleMenuLine"><div class="moduleMenuIcon"><img src="app/img/info.png"></div><div class="moduleMenuTxt"><?= $subjectsTotalNb." ".Txt::trad($subjectsTotalNb>1?"FORUM_sujets":"FORUM_sujet") ?></div></div>
		<?php } ?>
		<!--MENU DES THEMES-->
		<?php if(!empty($themeEditButton)){ ?>
			<div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=forum&action=ForumThemeEdit');"><div class="moduleMenuIcon"><img src="app/img/category.png"></div><div class="moduleMenuTxt"><?= Txt::trad("FORUM_themes_gestion") ?></div></div>
		<?php } ?>
		<!--MENU D'UN SUJET ET SES MESSAGES-->
		<?php if($displayForum=="subjectMessages"){ ?>
			<?php if(Ctrl::$curContainer->editContentRight()){ ?><div class="moduleMenuLine sLink" onclick="lightboxOpen('<?= MdlForumMessage::getUrlNew() ?>');"><div class="moduleMenuIcon"><img src="app/img/plus.png"></div><div class="moduleMenuTxt"><?= Txt::trad("FORUM_ajouter_message") ?></div></div><?php } ?>
			<?php if(!empty(Ctrl::$curUser->mail)){ ?><div class="moduleMenuLine sLink" id="notifyLastMessage" title="<?= Txt::trad("FORUM_notifier_dernier_message_info") ?>"><div class="moduleMenuIcon"><img src="app/img/mail.png"></div><div class="moduleMenuTxt"><?= Txt::trad("FORUM_notifier_dernier_message") ?></div></div><?php } ?>
			<hr>
			<?= MdlForumSubject::menuDisplayMode() ?>
			<?= ($displayMode=="line") ? MdlForumMessage::menuSort() : null ?>
			<div class="moduleMenuLine"><div class="moduleMenuIcon"><img src="app/img/info.png"></div><div class="moduleMenuTxt"><?= $messagesNb." ".Txt::trad($messagesNb>1?"FORUM_messages":"FORUM_message") ?></div></div>
		<?php } ?>
		</div>
	</div>

	<div class="<?= (!empty($displayPageCenter))?"pageCenterContent":"pageFullContent" ?>">
		<!--PATH DU FORUM (ACCUEIL FORUM > THEME COURANT > SUBJET COURANT)-->
		<?php if(!empty($curTheme) || !empty($curSubject)){ ?>
		<div class="forumPath sBlock">
			<a href="?ctrl=forum"><img src="app/img/forum/icon.png"> <?= Txt::trad("FORUM_accueil_forum") ?></a>
			<?php if(!empty($curTheme)){ ?><a href="?ctrl=forum&_idTheme=<?= $curTheme->idThemeUrl ?> "><img src='app/img/arrowRight.png'> <?= $curTheme->display() ?></a><?php } ?>
			<?php if(!empty($curSubject)){ ?><a><img src='app/img/arrowRight.png'> <?= Txt::reduce($curSubject->title,50) ?></a><?php } ?>
		</div>
		<?php } ?>

		<?php
		////	LISTE DES THEMES
		if($displayForum=="theme"){
			foreach($themeList as $tmpTheme){
		?>
			<div class="vThemeTable sBlock objDLine sLink" onClick="redir('?ctrl=forum&_idTheme=<?= $tmpTheme->idThemeUrl ?>')">
				<div class="vThemeCell">
					<div class="vThemeTitle"><?= $tmpTheme->display() ?></div>
					<div class="vThemeDescription"><?= $tmpTheme->description ?></div>
				</div>
				<div class="vThemeCell">
					<?= !empty($tmpTheme->subjectsNb)  ?  $tmpTheme->subjectsNb." ".Txt::trad($tmpTheme->subjectsNb>1?"FORUM_sujets":"FORUM_sujet")  :  null ?>
					<?= !empty($tmpTheme->messagesNb)  ?  "<br>".$tmpTheme->messagesNb." ".Txt::trad($tmpTheme->messagesNb>1?"FORUM_messages":"FORUM_message")  :  null ?>
				</div>
				<div class="vThemeCell">
					<?= !empty($tmpTheme->subjectLast)  ?  "<img src='app/img/arrowRight.png'> ".Txt::trad("FORUM_dernier_message")." ".$tmpTheme->subjectLast->displayAutor().", ".$tmpTheme->subjectLast->displayDate()  :  null ?>
					<?= !empty($tmpTheme->messageLast)  ?  "<br><img src='app/img/arrowRight.png'> ".Txt::trad("FORUM_dernier_message")." ".$tmpTheme->messageLast->displayAutor().", ".$tmpTheme->messageLast->displayDate()  :  null ?>
				</div>
			</div>
		<?php
			}
		}

		////	LISTE DES SUJETS
		if($displayForum=="subjects"){
			foreach($subjectsDisplayed as $tmpSubject){
		?>
			<div class="sBlock objDLine objScrollContent sLink" <?= $tmpSubject->blockIdForMenuContext() ?> data-onclickJs="redir('?ctrl=forum&targetObjId=<?= $tmpSubject->_targetObjId ?>')" title="<?= Txt::trad("FORUM_voir_sujet") ?>">
				<?= $tmpSubject->menuContext(); ?>
				<div class="vSubMessTable">
					<div class="vSubMessCellMain">
						<span class="vSubMessInfos">
							<span <?= $tmpSubject->curUserConsultLastMessage()==false?"class='sLinkSelect'":null ?>><?= $tmpSubject->title ?></span>
							<span class="vSubMessAutorDate"><?= $tmpSubject->displayAutor().", ".$tmpSubject->displayDate() ?></span>
						</span>
						<div class="vSubMessDescription"><?= strip_tags($tmpSubject->description) ?></div>
					</div>
					<div class="vSubMessCellDetails">
						<?= $tmpSubject->messagesNb." ".Txt::trad($tmpSubject->messagesNb>1?"FORUM_messages":"FORUM_message") ?>
						<?= !empty($tmpSubject->messagesNb) ? ". ".Txt::trad("FORUM_dernier_message")." :<br>".$tmpSubject->messageLast->displayAutor().", ".$tmpSubject->messageLast->displayDate() : null ?>
					</div>
					<div class="vSubMessCellAutorImg"><?= CtrlForum::autorImg($tmpSubject->_idUser) ?></div>
				</div>
			</div>
		<?php
			}
			////	AUCUN SUJET
			if(empty($subjectsDisplayed))  {echo "<div class='pageEmptyContent'>".Txt::trad("FORUM_aucun_sujet")."</div>";}
			////	MENU DE PAGINATION
			echo MdlForumSubject::menuPagination($subjectsTotalNb,"_idTheme");
		}

		////	SUJET CIBLE & SES MESSAGES
		if($displayForum=="subjectMessages"){ ?>
			<!--SUJET COURANT-->
			<div class="vSubjectBlock sBlock" <?= $curSubject->blockIdForMenuContext() ?>>
				<?= $curSubject->menuContext(); ?>
				<div class="vSubMessTable">
					<div class="vSubMessCellMain">
						<span class="vSubMessInfos">
							<span><?= $curSubject->title ?></span>
							<span class="vSubMessAutorDate"><?= $curSubject->displayAutor().", ".$curSubject->displayDate() ?></span>
						</span>
						<div class="vSubMessDescription"><?= $curSubject->description.$curSubject->menuAttachedFiles() ?></div>
					</div>
					<div class="vSubMessCellAutorImg"><?= CtrlForum::autorImg($curSubject->_idUser) ?></div>
				</div>
			</div>
			<!--LISTE DES MESSAGES-->
			<?php foreach($messagesList as $tmpMessage){ ?>
			<div class="vMessageBlock sBlock" <?= $tmpMessage->blockIdForMenuContext() ?> data-treeLevel="<?= $tmpMessage->treeLevel ?>">
				<?= $tmpMessage->menuContext(); ?>
				<div class="vSubMessTable">
					<div class="vSubMessCellMain">
						<span class="vSubMessInfos">
							<span><?= $tmpMessage->title ?></span>
							<span class="vSubMessAutorDate"><?= $tmpMessage->displayAutor().", ".$tmpMessage->displayDate() ?></span>
							<?php if($curSubject->editContentRight()){ ?><span class="vMessageReponse"><a href="javascript:lightboxOpen('<?= MdlForumMessage::getUrlNew()."&_idMessageParent=".$tmpMessage->_id ?>')" title="<?= $labelQuoteAnswerTitle ?>"><?= $labelQuoteAnswer ?> <img src="app/img/forum/<?= $displayMode=="line"?"quote":"answer" ?>.png"></a></span><?php } ?>
						</span>
						<div class="vSubMessDescription">
							<?php if($displayMode=="line" && !empty($tmpMessage->_idMessageParent)){ ?>
							<div class="vMessageQuoted">
								<img src="app/img/forum/quote2.png">
								<span class="vSubMessInfos"><?= Ctrl::getObj($tmpMessage::objectType,$tmpMessage->_idMessageParent)->title ?></span><br><?= Ctrl::getObj($tmpMessage::objectType,$tmpMessage->_idMessageParent)->description ?>
							</div>
							<?php } ?>
							<?= $tmpMessage->description.$tmpMessage->menuAttachedFiles() ?>
						</div>
					</div>
					<div class="vSubMessCellAutorImg"><?= CtrlForum::autorImg($tmpMessage->_idUser) ?></div>
				</div>
			</div>
			<?php } ?>
			<!--AUCUN SUJET-->
			<?php if(empty($messagesNb))  {echo "<div class='pageEmptyContent'>".Txt::trad("FORUM_aucun_message")."</div>";} ?>
		<?php } ?>
	</div>
</div>