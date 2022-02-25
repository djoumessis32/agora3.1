<div class="navContainer">
	<div class="navMenu sBlock">
		<!--PRECEDENT-->
		<a <?= $previousAttr ?>><img src="app/img/navPrevious.png"></a>
		<!--NUMÉROS DE PAGE-->
		<?php for($pageNbTmp=1; $pageNbTmp<=$pageNbTotal; $pageNbTmp++){ ?>
			<a href="<?= $hrefBase.$pageNbTmp ?>" class="<?= $pageNb==$pageNbTmp?"sLinkSelect":"sLink" ?>" title="<?= Txt::trad("aller_page")." ".$pageNbTmp ?>"><?= $pageNbTmp ?></a>
		<?php } ?>
		<!--PRECEDENT-->
		<a <?= $nextAttr ?>><img src="app/img/navNext.png"></a>
	</div>
</div>