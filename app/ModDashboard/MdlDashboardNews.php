<?php
/*
 * Modele des actualites
 */
class MdlDashboardNews extends MdlObject
{
	const moduleName="dashboard";
	const objectType="dashboardNews";
	const dbTable="ap_dashboardNews";
	const hasShortcut=false;
	const htmlEditorField="description";
	public static $requiredFields=array("description");
	public static $searchFields=array("description");
	public static $sortFields=array("dateCrea@@desc","dateCrea@@asc","dateModif@@desc","dateModif@@asc","_idUser@@asc","_idUser@@desc","description@@asc","description@@desc");

	/*
	 * STATIC : Récupère les news, après avoir basculé les news "offline" ou "online" automatiquement
	 */
	public static function displayedNews($offlineNews, $getCount=false)
	{
		Db::query("UPDATE ".static::dbTable." SET offline=null WHERE UNIX_TIMESTAMP(dateOnline)>0 AND dateOnline<'".date("Y-m-d 00:00:00")."' AND (dateOffline is null or dateOffline>'".date("Y-m-d 00:00:00")."')");
		Db::query("UPDATE ".static::dbTable." SET offline='1' WHERE UNIX_TIMESTAMP(dateOffline)>0 AND dateOffline<'".date("Y-m-d 00:00:00")."'");
		$sqlOffline=($offlineNews==1) ? "AND offline=1" : "AND (offline=0 or offline is null)";
		if($getCount==true)	{return Db::getVal("SELECT count(*) FROM ".static::dbTable." WHERE ".static::sqlDisplayedObjects()." ".$sqlOffline);}
		else				{return Db::getObjTab(static::objectType, "SELECT * FROM ".static::dbTable." WHERE ".static::sqlDisplayedObjects()." ".$sqlOffline." ".static::sqlSort(null,"une desc,"));}
	}

	/*
	 * SURCHARGE : Droit d'ajouter une nouvelle news
	 */
	public static function addRight()
	{
		return (Ctrl::$curUser->isAdminCurSpace() || (Ctrl::$curUser->isUser() && Ctrl::$curSpace->moduleOptionEnabled("dashboard","ajout_actualite_admin")==false));
	}
}