<?php
/*
 * Classe Db : Gère les connexions à la base de données
 */
class Db
{
	private static $_objPDO=null;
	public static $readsNb=null;
	public static $writesNb=null;

	/*
	 * renvoie l'objet PDO initialisé qu'une seule fois
	 */
	private static function objPDO()
	{
		//Instancie PDO
		if(self::$_objPDO===null){
			//Connection PDO
			try{
				//Aucune bdd n'est spécifiée : dbInstall!  /  Sinon tente d'établir une connexion
				if(!defined("db_name") || !db_name)	{throw new Exception("dbInstall_dbNameUndefined");}
				else								{self::$_objPDO=new PDO("mysql:host=".db_host.";dbname=".db_name.";charset=utf8", db_login, db_password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));}
				//Pas de connexion, ni d'exception : dbInstall!
				if(!is_object(self::$_objPDO))	{throw new Exception("dbInstall_pdoIsNull");}
			}
			//Erreur envoyé par PDO : renvoi une exception de base, avec demande d'install
			catch(PDOException $exception){
				throw new Exception("dbInstall_".$exception);
			}
		}
		return self::$_objPDO;
	}

	/*
	 * Exécute une requête SQL (insert/update/etc)
	 */
	public static function query($sqlQuery, $returnLastInsertId=false)
	{
		$queryResult=self::objPDO()->query($sqlQuery);//(preg_match("/(update|insert|delete)/i",$sqlQuery)) ? self::$writesNb++ : self::$readsNb++;
		if($returnLastInsertId==true)	{return self::objPDO()->lastInsertId();}
		else							{return $queryResult;}
	}

	/*
	 * Retourne un tableau de résultat
	 */
	public static function getTab($sqlQuery)
	{
		$result=self::objPDO()->query($sqlQuery);//self::$readsNb++;
		return $result->fetchAll(PDO::FETCH_ASSOC);//faster than "fetch()"
	}

	/*
	 * Retourne un tableau d'objets (avec Id de l'objet en key)
	 */
	public static function getObjTab($objectType, $sqlQuery)
	{
		$returnTab=[];
		$result=self::objPDO()->query($sqlQuery);
		//Pas de "FETCH_CLASS", sinon pas de mise en cache des objets. NOTE: les champs des autres tables ne seront pas ajoutées à l'objet (cf. requetes avec jointure)
		foreach($result->fetchAll(PDO::FETCH_ASSOC) as $dbFields){
			$tmpObj=Ctrl::getObj($objectType, $dbFields);
			$returnTab[$tmpObj->_id]=$tmpObj;//Id de l'objet en key?
		}
		return $returnTab;
	}

	/*
	 * Retourne une ligne de resultat : premier enregistrement retourné avec ses champs
	 */
	public static function getLine($sqlQuery)
	{
		$result=self::objPDO()->query($sqlQuery);//self::$readsNb++;
		return $result->fetch(PDO::FETCH_ASSOC);
	}

	/*
	 * Retourne une colonne d'enregistrements : premier champ d'une liste d'enregistrements (liste d'identifiants par exemple)
	 */
	public static function getCol($sqlQuery)
	{
		$result=self::objPDO()->query($sqlQuery);//self::$readsNb++;
		return $result->fetchAll(PDO::FETCH_COLUMN,0);//que le premier champs
	}

	/*
	 * Retourne la valeur d'un champ : premier champs du premier résultat d'une requete
	 */
	public static function getVal($sqlQuery)
	{
		$result=self::objPDO()->query($sqlQuery);//self::$readsNb++;
		$record=$result->fetch(PDO::FETCH_NUM);
		return $record[0];
	}

	/*
	 * Sauvegarde la Bdd
	 */
	public static function getDump()
	{
		//Path
		$dumpPath=PATH_DATAS."BackupMysql_".db_name.".sql";
		//Linux/windows
		if(Tool::linuxEnv() && function_exists("exec"))  {exec("mysqldump --user=".db_login." --password=".db_password." --host=".db_host." ".db_name." > ".$dumpPath);}
		else
		{
			// Recupere chaque table
			$tabDump=[];
			foreach(self::getCol("SHOW TABLES FROM `".db_name."`") as $tableName)
			{
				// Structure de la table
				$createTable=self::getLine("SHOW CREATE TABLE ".$tableName);
				$tabDump[]=str_replace(array("\r","\n"),"",$createTable["Create Table"]).";";
				// Contenu de la table
				foreach(self::getTab("SELECT * FROM ".$tableName) as $record){
					$tmpInsert="INSERT INTO ".$tableName." VALUES(";
					foreach($record as $fieldRecord)	{$tmpInsert.=($fieldRecord=="") ? "NULL," : self::objPDO()->quote($fieldRecord).",";}//pas de "empty()" car doit enregistrer aussi "0"
					$tabDump[]=trim($tmpInsert,",").");";
				}
			}
			// Transforme le tableau en texte,  Enregistre le fichier sql,  Retourne le chemin du fichier
			$fp=fopen($dumpPath, "w");
			fwrite($fp, implode("\n", $tabDump));
			fclose($fp);
		}
		return $dumpPath;
	}
	
	/*
	 * Version de MySQL														(ex "db_version()")
	 */
	public static function dbVersion()
	{
		return self::objPDO()->getAttribute(PDO::ATTR_SERVER_VERSION);
	}

	/*
	 * Formate une valeur dans une requete (insert,update,etc)
	 */
	public static function format($text, $options=null)
	{
		$text=trim($text);
		if(empty($text))	{return "NULL";}
		else{
			//Filtre le résultat
			if(!stristr($options,"editor"))							{$text=htmlspecialchars(strip_tags($text));}//Enleve les balises (html/xml/etc)  &  Convertit les caractères spéciaux en HTML ("<" devient "&lt;") => complementaire au "strip_tags" precedent
			if(stristr($options,"float"))							{$text=str_replace(",",".",$text);}			//Remplace les virgules par des points (valeur flottante)
			if(stristr($options,"url") && !stristr($text,"http"))	{$text="http://".$text;}					//Ajoute "http://" dans l'url
			if(stristr($options,"likeSearch"))						{$text="%".$text."%";}						//Search : délimite par des "%"
			//Formate une date provenant d'un datepicker + timepicker?
			if(stristr($options,"datetime"))	{$text=Txt::formatDate($text,"inputDatetime","dbDatetime");}
			elseif(stristr($options,"date"))	{$text=Txt::formatDate($text,"inputDate","dbDate");}
			//renvoie le résultat filtré par pdo (trim, addslashes, délimite par des quotes, etc)
			return (stristr($options,"noquotes"))  ?  $text  :  self::objPDO()->quote($text);
		}
	}

	/*
	 * Formate une valeur GET/POST dans une requete (insert,update,etc)
	 */
	public static function formatParam($keyParam, $options=null)
	{
		return self::format(Req::getParam($keyParam),$options);
	}
	
	/*
	 * Format un tableau dans une requette
	 */
	public static function formatTab2txt($text)
	{
		return Db::format(Txt::tab2txt($text));
	}

	/*
	 * Formate la date actuelle d'un champ "datetime" ou "date", avec le timezone spécifié (équivalent à "now()" mais assure le formatage via "date_default_timezone_set()")
	 */
	public static function dateNow()
	{
		return "'".strftime("%Y-%m-%d %H:%M:%S")."'";
	}
}