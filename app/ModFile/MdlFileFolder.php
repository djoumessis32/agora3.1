<?php
/*
 * Modele des dossiers de fichiers
 */
class MdlFileFolder extends MdlObjectFolder
{
	const moduleName="file";
	const objectType="fileFolder";
	const dbTable="ap_fileFolder";
	const MdlObjectContent="MdlFile";

	/*
	 * VUE : Surcharge du menu contextuel
	 */
	public function menuContext($options=null)
	{
		//Ajoute l'option "Telecharger le dossier"
		if($this->isRootFolder()==false && Ctrl::$curUser->isUser() && $this->readRight()){
			$options["specificOptions"][]=array(
				"actionJs"=>"window.open('?ctrl=".static::moduleName."&action=downloadArchive&targetObjects[fileFolder]=".$this->_id."')",
				"iconSrc"=>"app/img/download.png",
				"label"=>Txt::trad("telecharger_dossier")
			);
		}
		return parent::menuContext($options);
	}

	/*
	 * SURCHARGE : Ajout/Modif de dossier
	 */
	public function createUpdate($sqlProperties)
	{
		$reloadedObj=parent::createUpdate($sqlProperties);
		//Créé un nouveau dossier sur le disque?
		if(!file_exists($reloadedObj->folderPath("real"))){
			$isCreated=mkdir($reloadedObj->folderPath("real"));
			if($isCreated==false)	{self::noAccessExit(Txt::trad("MSG_NOTIF_acces_fichier_dossier"));}
			else					{File::setChmod($reloadedObj->folderPath("real"));}
		}
		////	Retourne l'objet rechargé
		return $reloadedObj;
	}
}