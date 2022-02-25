<div class="moduleMenuLine sLink">
	<div class="moduleMenuIcon"><img src="app/img/display<?= ucfirst($displayMode) ?>.png"></div>
	<div class="moduleMenuTxt">
		<div class="menuContext sBlock" id="vMenuDisplayMode">
			<?php foreach($displayModeOptions as $tmpDisplay){ ?>
			<div class="menuContextLine <?= $displayMode==$tmpDisplay?"sLinkSelect":"sLink" ?>" onclick="redir('<?= $displayModeUrl.$tmpDisplay ?>')">
				<div class="menuContextIcon"><img src="app/img/display<?= ucfirst($tmpDisplay) ?>.png"></div>
				<div class="menuContextTxt"><?= Txt::trad("type_affichage_".$tmpDisplay) ?></div>
			 </div>
			<?php } ?>
		</div>
		<span class="sLink menuContextLauncher" for="vMenuDisplayMode"><?= Txt::trad("type_affichage")." ".Txt::trad("type_affichage_".$displayMode) ?></span>
	</div>
</div>