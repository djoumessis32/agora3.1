<?php
/*
 * Modele de la config de l'Agora
 */
class MdlAgora extends MdlObject
{
	const moduleName="agora";
	const objectType="agora";
	const dbTable="ap_agora";
	//Propriésé bdd
	const hasDateCrea=false;
	const hasAutor=false;
	const hasInfosModif=false;
	const hasAccessRight=false;
	//Propriétés d'IHM
	const hasShortcut=false;
	const hasAttachedFiles=false;
	const hasNotifMail=false;

	/*
	 * SURCHARGE : Constructeur
	 */
	function __construct()
	{
		parent::__construct(Db::getLine("select * from ap_agora"));
		if(!empty($this->personsSort))	{$this->personsSort="name";}
	}
}