<?php
/*
 * Modele des Liens
 */
class MdlLink extends MdlObject
{
	const moduleName="link";
	const objectType="link";
	const dbTable="ap_link";
	const MdlObjectContainer="MdlLinkFolder";
	const isFolderContent=true;
	const isSelectable=true;
	public static $displayModeOptions=array("block","line");
	public static $requiredFields=array("adress");
	public static $searchFields=array("adress","description");
	public static $sortFields=array("dateCrea@@desc","dateCrea@@asc","dateModif@@desc","dateModif@@asc","_idUser@@asc","_idUser@@desc","description@@asc","description@@desc","adress@@asc","adress@@desc");
}