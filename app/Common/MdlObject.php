<?php
/*
 * Classe principale des Objects de l'Agora
 */
class MdlObject
{
	//Propriétés principales
	const moduleName=null;
	const objectType=null;
	const dbTable=null;
	const guestDefaultRight=1;
	//Propriétés de dépendance
	const MdlObjectContent=null;
	const MdlObjectContainer=null;
	const isFolder=false;
	const isFolderContent=false;
	//Propriésé bdd
	const hasAccessRight=true;
	const hasDateCrea=true;
	const hasAutor=true;
	const hasInfosModif=true;
	//Propriétés d'IHM
	const nbObjectsByPage=null;
	const isSelectable=false;
	const hasShortcut=true;
	const hasAttachedFiles=true;
	const hasNotifMail=true;
	const htmlEditorField=null;//Champ avec editeur Html
	public static $menuContextUniqId=null;
	//Type d'affichage des objets de la page (ligne/block/arborescence)
	public static $displayModeOptions=array();
	//Champs obligatoires, Champs de recherche et Champs de tri d'affichage
	public static $requiredFields=array();
	public static $searchFields=array();
	public static $sortFields=array();
	//Valeurs en cache (calculées qu'1 fois)
	private $_accessRight=null;
	private $_containerObj=null;
	private $_attachedFiles=null;
	private $_affectations=null;
	protected static $_displayMode=null;

	/*
	 * Init
	 */
	function __construct($objIdOrValues=null)
	{
		////	Par défaut
		$this->_id=0;
		////	Assigne des propriétés à l'objet : Objet déjà créé / Objet à créer
		if(!empty($objIdOrValues)){
			//Récupère les propriétés en bdd / propriétés déjà passées en paramètre
			$objValues=(is_numeric($objIdOrValues))  ?  Db::getLine("select * from ".static::dbTable." where _id=".(int)$objIdOrValues)  :  $objIdOrValues;
			//S'il y a des propriétés
			if(!empty($objValues)){
				foreach($objValues as $propertieKey=>$propertieVal)  {$this->$propertieKey=$propertieVal;}
			}
		}
		////	Identifiant + identifiant générique (exple : "fileFolder-19")
		$this->_id=(int)$this->_id;
		$this->_targetObjId=static::objectType."-".$this->_id;
	}

	/*
	 * Renvoie la valeur d'une propriété (ne renvoie rien si la propriete est inexistante)
	 */
	function __get($propertyName){
		if(!empty($this->$propertyName))	{return $this->$propertyName;}
	}

	/*
	 * Renvoi le type de l'objet
	 */
	public function getType()
	{
		return static::objectType;
	}

	/*
	 * Url d'accès à l'objet : "vue" de l'objet (lightbox) / Edition de l'objet / suppression de l'objet / conteneur de l'objet
	 */
	public function getUrl($display=null)
	{
		//Objet n'existe plus (supprimé depuis, par un autre user?). Ne pas utiliser "isNew()"
		if(empty($this->_id))	{return "?ctrl=".static::moduleName;}
		//Objet existe encore
		else
		{
			$urlBase="?ctrl=".static::moduleName."&targetObjId=";
			if($display=="vue")													{return $urlBase.$this->_targetObjId."&action=".static::objectType."Vue";}						//Affichage d'un objet : lightbox
			elseif($display=="edit" && static::isFolder==true)					{return "?ctrl=object&targetObjId=".$this->_targetObjId."&action=FolderEdit";}					//Edition d'un dossier : lightbox
			elseif($display=="edit")											{return $urlBase.$this->_targetObjId."&action=".static::objectType."Edit";}						//Edition d'un objet : lightbox
			elseif($display=="delete")											{return "?ctrl=object&targetObjects[".static::objectType."]=".$this->_id."&action=delete";}		//Suppression d'un objet : ctrl=object
			elseif($display=="container" && is_object($this->containerObj()))	{return $urlBase.$this->containerObj()->_targetObjId."&targetObjIdChild=".$this->_targetObjId;}	//Conteneur d'un objet (exple: dossier d'un fichier)
			else																{return $urlBase.$this->_targetObjId;}															//Cible l'objet lui même (exple : dossier)
		}
	}

	/*
	 * Url d'edition d'un nouvel objet
	 */
	public static function getUrlNew()
	{
		$url="?ctrl=".static::moduleName."&action=".static::objectType."Edit"."&targetObjId=".static::objectType;
		if(!empty(Ctrl::$curContainer))	{$url.="&_idContainer=".Ctrl::$curContainer->_id;}
		return $url;
	}

	/*
	 * VERIF : l'objet est un conteneur (dossier, agenda, sujet du forum) ?
	 */
	public static function isContainer(){
		return (static::MdlObjectContent!==null);
	}
	/*
	 * VERIF : l'objet est un contenu dans un conteneur (exple:fichier d'un dossier, evenement d'un agenda) ?
	 */
	public static function isContainersContent(){
		return (static::MdlObjectContainer!==null);
	}
	/*
	 * VERIF : l'objet se trouve dans une arborescence (exple: dossier ou fichier de dossier) ?
	 */
	public static function isInArbo(){
		return (static::isFolder==true || static::isFolderContent==true);
	}
	/*
	 * VERIF : l'objet est un dossier racine ?										(ex "is_dossier_racine()")
	 */
	public function isRootFolder(){
		return (static::isFolder==true && $this->_id==1);
	}
	/*
	 * VERIF : Objet indépendant avec ses propres droits d'accès (ne dépendant pas d'un "conteneur" || "contenu" dans un dossier racine)
	 */
	public function isIndependant(){
		return (static::MdlObjectContainer===null || (static::isFolderContent==true && $this->containerObj()->isRootFolder()));
	}
	/*
	 * VERIF : User courant est l'auteur de l'objet ?  (ex "is_auteur()")
	 */
	public function isAutor(){
		return ($this->_idUser==Ctrl::$curUser->_id && Ctrl::$curUser->isUser());
	}
	/*
	 * VERIF : Objet nouvellement créé (_id==0 || dateCrea==-2sec)
	 */
	public function isNew(){
		return ($this->_id==0 || time()-strtotime($this->dateCrea)<2);
	}

	/*
	 * Récupère les droits d'accès à l'objet  (conteneur=dossier,agenda,sujet,etc / contenu=actualité,fichier,taches,etc / ecriture=ajout-modif-suppr)
	 *		3	[total]					sur un conteneur -> ecriture du conteneur + ecriture du contenu de premier niveau(*) + suppression de TOUTE l'arborescence
	 *									sur un contenu	 -> ecriture
	 *		2	[ecriture]				sur un conteneur -> lecture du conteneur + ecriture du contenu de premier niveau(*)
	 *									sur un contenu	 -> ecriture
	 *		1.5	[ecriture limité]		sur un conteneur -> lecture du conteneur + ecriture du contenu qu'on a créé (pour les invités, on limite uniquement à l'ajout de nouveau contenu: pas de modif!)
	 *									sur un contenu	 -> -non disponible-
	 *		1	[lecture]				sur un conteneur -> lecture
	 *									sur un contenu	 -> lecture
	 *		(*) les éléments des sous-dossiers sont soumis au droit d'accès de leur dossier conteneur
	 */
	public function accessRight()
	{
		if($this->_accessRight===null)
		{
			//Init
			$this->_accessRight=0;
			//ACCES TOTAL  =>  Auteur de l'objet  ||  Nouvel objet et addRight()==True  ||  Admin général
			if($this->isAutor() || ($this->_id==0 && static::addRight()) || Ctrl::$curUser->isAdminGeneral())	{$this->_accessRight=3;}
			//DROITS NORMAL
			elseif(static::hasAccessRight==true)
			{
				//ACCES TOTAL  =>  Admin d'espace  &&  accessRightAdminSpacePrivilege()==true  &&  RootFolder()==false  (..et objet pas affecté à d'autres espaces)
				if(Ctrl::$curUser->isAdminCurSpace() && $this->accessRightAdminSpacePrivilege() && $this->isRootFolder()==false){
					$nbSpaceAffect=Db::getVal("SELECT count(distinct _idSpace) FROM ap_objectTarget WHERE objectType='".static::objectType."' AND _idObject=".$this->_id." AND (_idSpace!=".Ctrl::$curSpace->_id." or _idSpace is null)");
					if($nbSpaceAffect==0)	{$this->_accessRight=3;}
				}
				//ACCES A CALCULER  =>  User lambda
				if(empty($this->_accessRight))
				{
					//DROITS D'ACCES DU CONTENEUR => objet est dépendant d'un dossier, etc   ||   DROITS D'ACCES CALCULE  =>  DROIT D'AFFECTATION LE PLUS IMPORTANT !
					if(!empty($this->_idContainer) && $this->isIndependant()==false)	{$this->_accessRight=$this->containerObj()->accessRight();}
					else																{$this->_accessRight=Db::getVal("SELECT max(accessRight) FROM ap_objectTarget WHERE objectType='".static::objectType."' AND _idObject=".$this->_id." AND (_idSpace=".Ctrl::$curSpace->_id." or _idSpace is null) AND target IN (".static::sqlTargets().")");}
					//DROIT PAR DEFAUT DU DOSSIER RACINE, SI TOUJOURS PAS SPECIFIES
					if(empty($this->_accessRight) && $this->isRootFolder())  {$this->_accessRight=Ctrl::$curUser->isUser()?2:1;}
				}
			}
		}
		return $this->_accessRight;
	}

	/*
	 * Privilège de droits d'accès pour l'admin général (surchargé). Activé par défaut
	 */
	public function accessRightAdminSpacePrivilege()
	{
		return true;
	}

	/*
	 * Droit de lecture sur un objet
	 */
	public function readRight()
	{
		return ($this->accessRight()>0);
	}

	/*
	 * Droit d'ajouter un nouvel objet sur le module (surchargée). Interdit aux guests sur les conteneurs
	 */
	public static function addRight()
	{
		return (static::isContainer()==false || Ctrl::$curUser->isUser());
	}

	/*
	 * Droit d'ajouter/modifier le contenu d'un conteneur
	 */
	public function editContentRight()
	{
		return (static::isContainer() && $this->accessRight()>1);
	}

	/*
	 * Droit d'édition d'un objet (frequement surchargée)  =>  "fullRight()"  OU  droit sup. à "2" pour les objets qui ne sont pas des conteneurs
	 */
	public function editRight()
	{
		return ($this->fullRight() || (static::isContainer()==false && $this->accessRight()==2));
	}

	/*
	 * Droit de suppression d'un objet (surchargée). ne pas utiliser "isNew()"
	 */
	public function deleteRight()
	{
		return ($this->editRight() && !empty($this->_id));
	}

	/*
	 * Droit complet sur l'objet
	 */
	public function fullRight()
	{
		return ($this->accessRight()==3);
	}

	/*
	 * Accès en lecture? (sort du script sinon)
	 */
	public function controlRead()
	{
		if($this->accessRight()==false)	{Ctrl::noAccessExit();}
	}

	/*
	 * Accès en écriture? (sort du script sinon)
	 */
	public function controlEdit()
	{
		if($this->editRight()==false)	{Ctrl::noAccessExit();}
	}

	/*
	 * Recupère l'objet conteneur de l'objet courant (ex: dossier d'un fichier)
	 */
	public function containerObj()
	{
		if($this->_containerObj===null && static::isContainersContent() && !empty($this->_idContainer)){
			$MdlObjectContainer=static::MdlObjectContainer;
			$this->_containerObj=Ctrl::getObj($MdlObjectContainer::objectType,$this->_idContainer);
		}
		return $this->_containerObj;
	}

	/*
	 * Suppression d'un objet			(ex "suppr_objet()")
	 */
	public function delete()
	{
		if($this->deleteRight())
		{
			//Supprime les fichiers joints
			if(static::hasAttachedFiles==true){
				foreach($this->getAttachedFileList() as $tmpFile)	{$this->deleteAttachedFile($tmpFile);}
			}
			//Ajoute le log de suppression
			Ctrl::addLog("delete",$this);
			//Suppr les droits d'accès & l'objet lui-même!
			Db::query("DELETE FROM ap_objectTarget WHERE objectType='".static::objectType."' AND _idObject=".$this->_id);
			Db::query("DELETE FROM ".static::dbTable." WHERE _id=".$this->_id);
		}
	}

	/*
	 * Déplace un objet (dossier?) dans un autre dossier				(ex "deplacer_fichier()", etc)
	 */
	public function folderMove($newFolderId)
	{
		////	Ancien et nouveau dossier
		$oldFolder=$this->containerObj();
		$newFolder=Ctrl::getObj($oldFolder::objectType, $newFolderId);
		////	Objet pas dans une arbo? Droit d'accès pas ok? || dossier de destination inaccessible sur le disque? || Déplace un dossier à l'interieur de lui même?
		if(static::isInArbo()==false || $this->accessRight()<2 || $newFolder->accessRight()<2 || (static::objectType=="fileFolder" && is_dir($newFolder->folderPath("real"))==false))    {Ctrl::addNotif(Txt::trad("elem_inaccessible")." : ".$this->name.$this->title);}
		elseif(static::isFolder && $this->isInFolderTree($newFolderId))    {Ctrl::addNotif(Txt::trad("MSG_NOTIF_deplacement_dossier")." : ".$this->name);}
		else
		{
			////	Change le dossier conteneur
			Db::query("UPDATE ".static::dbTable." SET _idContainer=".(int)$newFolderId." WHERE _id=".$this->_id);
			//Contenu de dossier : change les droits d'accès?
			if(static::isFolder==false){
				//Réinitialise les droits d'accès
				Db::query("DELETE FROM ap_objectTarget WHERE objectType='".static::objectType."' AND _idObject=".$this->_id);
				//Déplace à la racine : récupère les droits d'accès de l'ancien dossier conteneur
				if($newFolder->isRootFolder()){
					foreach(Db::getTab("SELECT * FROM ap_objectTarget WHERE objectType='".$oldFolder::objectType."' AND _idObject=".$oldFolder->_id) as $oldFolderAccessRight){
						Db::query("INSERT INTO ap_objectTarget SET objectType='".static::objectType."', _idObject=".$this->_id.", _idSpace=".$oldFolderAccessRight["_idSpace"].", target='".$oldFolderAccessRight["target"]."', accessRight='".$oldFolderAccessRight["accessRight"]."'");
					}
				}
			}
			////	Reload l'objet (et du cache)
			$reloadedObj=Ctrl::getObj(static::objectType, $this->_id, true);
			////	Déplace un fichier sur le disque
			if(static::objectType=="file"){
				//Deplace chaque version du fichier
				foreach(Db::getTab("SELECT * FROM ap_fileVersion WHERE _idFile=".$this->_id) as $tmpFileVersion){
					rename($oldFolder->folderPath("real").$tmpFileVersion["realName"], $newFolder->folderPath("real").$tmpFileVersion["realName"]);
				}
				//Déplace la vignette
				if($this->hasThumb()){
					rename($oldFolder->folderPath("real").$this->getThumbName(), $newFolder->folderPath("real").$this->getThumbName());
				}
			}
			////	Déplace un dossier sur le disque (du chemin actuel : $this,  vers le nouveau chemin : $reloadedObj)
			elseif(static::objectType=="fileFolder"){
				rename($this->folderPath("real"), $reloadedObj->folderPath("real"));
			}
			////	Ajoute aux logs
			Ctrl::addLog("edit", $reloadedObj, Txt::trad("deplacer_elements"));
			return true;
		}
	}

	/*
	 * Liste les affectations de l'objet (Espaces/groupes/users)    (ex "objet_affectations()")
	 */
	public function getAffectations()
	{
		if($this->_affectations===null)
		{
			$this->_affectations=array();
			$affectations=Db::getTab("SELECT * FROM ap_objectTarget WHERE objectType='".static::objectType."' AND _idObject=".$this->_id." ORDER BY target");
			foreach($affectations as $tmpAffect)
			{
				//Affectations détaillées
				if($tmpAffect["target"]=="allSpaces")			{$tmpAffect["targetType"]=$tmpAffect["target"];		$tmpAffect["targetId"]=null;											$tmpAffect["label"]=Txt::trad("EDIT_OBJET_allUsers_espaces");}											//"Tous les espaces"
				elseif($tmpAffect["target"]=="spaceGuests")		{$tmpAffect["targetType"]=$tmpAffect["target"];		$tmpAffect["targetId"]=$tmpAffect["_idSpace"];							$tmpAffect["label"]=Ctrl::getObj("space",$tmpAffect["targetId"])->name." (".Txt::trad("guests").")";}	//"Espace Bidule (invités)"
				elseif($tmpAffect["target"]=="spaceUsers")		{$tmpAffect["targetType"]=$tmpAffect["target"];		$tmpAffect["targetId"]=$tmpAffect["_idSpace"];							$tmpAffect["label"]=Ctrl::getObj("space",$tmpAffect["targetId"])->name." (".Txt::trad("tous").")";}		//"Espace Bidule (tous)"
				elseif(substr($tmpAffect["target"],0,1)=="G")	{$tmpAffect["targetType"]="group";					$tmpAffect["targetId"]=(int)str_replace('G','',$tmpAffect["target"]);	$tmpAffect["label"]=Ctrl::getObj("userGroup",$tmpAffect["targetId"])->title;}							//"Groupe Bidule"
				elseif(substr($tmpAffect["target"],0,1)=="U")	{$tmpAffect["targetType"]="user";					$tmpAffect["targetId"]=(int)str_replace('U','',$tmpAffect["target"]);	$tmpAffect["label"]=Ctrl::getObj("user",$tmpAffect["targetId"])->display();}							//"Jean Dupont"
				//Ajoute l'affectation
				$targetKey=(int)$tmpAffect["_idSpace"]."_".$tmpAffect["target"];
				$this->_affectations[$targetKey]=$tmpAffect;
			}
		}
		return $this->_affectations;
	}

	/*
	 * Envoi d'un mail de notification (cf. "menuEdit")
	 */
	public function sendMailNotif($message, $attachedFiles=null, $specificAffectUserIds=null, $addedUserIds=null)
	{
		//Notification demandé par l'auteur de l'objet  OU  users ajoutés automatiquement
		if(Req::isParam("notifMail") || !empty($addedUserIds))
		{
			////	Sujet ("Nouvel element créé par boby SMITH : Actualité")
			$tradObjectType=(static::isFolder) ? "OBJECTfolder" : "OBJECT".static::objectType;
			$subject=(strtotime($this->dateCrea)>(time()-10) || empty($this->dateCrea)) ? Txt::trad("MAIL_element_cree") : Txt::trad("MAIL_element_modifie");//"Element modifié par" / "Nouvel element créé par" (10 secondes max precedant la notif)
			$subject.=" ".Ctrl::$curUser->display()." (".Txt::trad($tradObjectType).")";//" boby SMITH (Actualité)"
			////	Message
			$targetObjUrl=Req::getSpaceUrl()."/?ctrl=offline&_idSpaceAccess=".Ctrl::$curSpace->_id."&notifSpaceAccess=1&targetObjUrl=".urlencode($this->getUrl("container"));
			$message=str_replace(PATH_DATAS, Req::getSpaceUrl()."/".PATH_DATAS, $message);//Remplace les chemins relatifs dans le message
			$message=$subject.", ".Txt::trad("MAIL_element_sur_espace")." <i>".Ctrl::$curSpace->name."</i> :<br><br>".$message."<br><br><a href=\"".$targetObjUrl."\" target='_blank'>".Txt::trad("MAIL_element_acces_direct")."</a>";//Ajoute "Créé sur l'espace 'Bidule'" ...
			////	Users (id) à destination de la notif
			$notifUsersIds=[];
			if(Req::isParam("notifMail"))
			{
				if(Req::isParam("notifMailUsers"))		{$notifUsersIds=Req::getParam("notifMailUsers");}	//Selection de l'auteur de l'objet 
				elseif(!empty($specificAffectUserIds))	{$notifUsersIds=$specificAffectUserIds;}			//Users spécifiquement affectées à l'objet (exple: evts dans plusieurs agendas)
				else{																						//Users affectées à l'objet
					$refObject=($this->isIndependant()) ? $this : $this->containerObj();//Objet de référence pour les affectations
					foreach($refObject->getAffectations() as $tmpAffect){
						if($tmpAffect["targetType"]=="spaceUsers")	{$notifUsersIds=array_merge($notifUsersIds, Ctrl::getObj("space",$tmpAffect["targetId"])->getUsers("ids"));}
						elseif($tmpAffect["targetType"]=="group")	{$notifUsersIds=array_merge($notifUsersIds, Ctrl::getObj("userGroup",$tmpAffect["targetId"])->userIds);}
						elseif($tmpAffect["targetType"]=="user")	{$notifUsersIds[]=$tmpAffect["targetId"];}
					}
				}
			}
			////	Ajoute des users à la notification (exple: notif automatiques des messages de forum) 
			if(!empty($addedUserIds)){
				if(Req::isParam("notifMail")==false)	{$addedOptions="noSendNotif";}//Pas de demande explicite de l'user courant : on affiche pas de rapport d'envoi
				$notifUsersIds=array_merge($notifUsersIds,$addedUserIds);
			}
			////	Envoi du message
			if(!empty($notifUsersIds))
			{
				$options="objectEditNotif";
				if(Req::isParam("hideRecipients"))	{$options.=",hideRecipients";}
				if(Req::isParam("receptionNotif"))	{$options.=",receptionNotif";}
				if(!empty($addedOptions))			{$options.=",".$addedOptions;}
				Tool::sendMail(array_unique($notifUsersIds), $subject, $message, $options, $attachedFiles);
			}
		}
	}

	/*
	 * Ajout/Modif d'objet
	 */
	public function createUpdate($sqlProperties)
	{
		if($this->editRight())
		{
			////	Enleve espace et virgule en fin
			$sqlProperties=trim(trim($sqlProperties),",");
			////	Date & Auteur (créa/modif)
			if($this->isNew() && static::hasDateCrea==true)				{$sqlProperties.=", dateCrea=".Db::dateNow();}
			if($this->isNew() && static::hasAutor==true)				{$sqlProperties.=", _idUser=".Db::format(Ctrl::$curUser->_id);}
			if($this->isNew()==false && static::hasInfosModif==true)	{$sqlProperties.=", dateModif=".Db::dateNow().", _idUserModif=".Ctrl::$curUser->_id;}
			////	Propriétés optionnelles "_idContainer", "guest", "shortcut" (attention au decochage)
			if(Req::isParam("_idContainer"))	{$sqlProperties.=", _idContainer=".Db::formatParam("_idContainer");}
			if(Req::isParam("guest"))			{$sqlProperties.=", guest=".Db::formatParam("guest");}
			if(static::hasShortcut==true)		{$sqlProperties.=", shortcut=".Db::formatParam("shortcut");}
			////	LANCE L'INSERT/UPDATE !!
			if($this->isNew())	{$_id=(int)Db::query("INSERT INTO ".static::dbTable." SET ".$sqlProperties, true);}
			else{
				Db::query("UPDATE ".static::dbTable." SET ".$sqlProperties." WHERE _id=".$this->_id);
				$_id=$this->_id;
			}
			////	Reload l'objet (update cache)  & Ajoute les droits d'accès (si ya)  & Ajoute les fichiers joints (si ya)  & Ajoute aux Logs
			$reloadedObj=Ctrl::getObj(static::objectType, $_id, true);
			$reloadedObj->setAccessRights();
			$reloadedObj->addAttachedFiles();
			Ctrl::addLog("edit",$reloadedObj);
			////	renvoie l'objet rechargé et remis en chache!
			return $reloadedObj;
		}
	}

	/*
	 * Affectation des droits d'accès provenant (cf."menuEditValidate()"). Par défaut : accès en lecture à l'espace courant
	 */
	public function setAccessRights($specificAccessRight=null)
	{
		if($this->isIndependant())
		{
			//Init
			$sqlInsertBase="INSERT INTO ap_objectTarget SET objectType='".static::objectType."', _idObject=".$this->_id.", ";
			////	Objet créé par un "Guest"
			if(Ctrl::$curUser->isUser()==false){
				Db::query($sqlInsertBase." _idSpace=".Ctrl::$curSpace->_id.", target='spaceGuests', accessRight=".Db::format(static::guestDefaultRight));
				Db::query($sqlInsertBase." _idSpace=".Ctrl::$curSpace->_id.", target='spaceUsers', accessRight=".Db::format(static::guestDefaultRight));
			}
			////	Objet créé par un user && droit d'accès spécifiés
			elseif(Req::isParam("objectRight") || !empty($specificAccessRight))
			{
				//Réinitialise les droits, uniquement sur les espaces auxquels l'user courant a accès
				if($this->isNew()==false){
					$sqlSpaces="_idSpace IN (".implode(",",Ctrl::$curUser->getSpaces("ids")).")";
					if(Ctrl::$curUser->isAdminGeneral())	{$sqlSpaces="(".$sqlSpaces." OR _idSpace is null)";}
					Db::query("DELETE FROM ap_objectTarget WHERE objectType='".static::objectType."' AND _idObject=".$this->_id." AND ".$sqlSpaces);
				}
				//Ajoute les nouveaux droits d'accès : passés en paramètre / provenant du formulaire
				$newAccessRight=(Req::isParam("objectRight"))  ?  Req::getParam("objectRight")  :  $specificAccessRight;
				foreach($newAccessRight as $tmpRight){
					$tmpRight=explode("_",$tmpRight);//exple :  "5_U3_2"  devient ["_idSpace"=>"5","target"=>"U3","accessRight"=>"2"]  correspond à droit "2" sur l'user "3" de l'espace "5"
					Db::query($sqlInsertBase." _idSpace=".Db::format($tmpRight[0]).", target=".Db::format($tmpRight[1]).", accessRight=".Db::format($tmpRight[2]));
				}
			}
		}
	}

	/*
	 * STATIC SQL : Selection d'objets en fonction de la "target"			(ex "objet_targets()")
	 * "target" => "allSpaces" / "spaceGuests" / "spaceUsers" / "U1" / "G1"
	 */
	public static function sqlTargets()
	{
		// Objets des invités ou des utilisateurs
		$sqlTarget="'spaceGuests'";
		if(Ctrl::$curUser->isUser()){
			$sqlTarget.=",'spaceUsers','allSpaces','U".Ctrl::$curUser->_id."'";
			foreach(Ctrl::$curUserCurSpaceGroups as $tmpGroup)	{$sqlTarget.=",'G".$tmpGroup->_id."'";}
		}
		return $sqlTarget;
	}

	/*
	 * STATIC SQL : Selection d'objets à afficher																	(ex "sql_affichage()")
	 * En fonction de l'espace courant, de "displayObjects" et eventuellement du conteneur conteneur
	 * "displayObjects" => "normal": droit d'accès/affectation à l'user || "autor": crées par l'user courant || "all": tous ceux de l'espace (admin uniquement!)
	 */
	public static function sqlDisplayedObjects($containerObj=null, $keyId="_id")
	{
		$sqlConditions=[];
		////	Objets créés par l'user courant?  Objets dans un conteneur particulier?
		if($_SESSION["displayObjects"]=="autor")    {$sqlConditions[]="_idUser=".Ctrl::$curUser->_id;}
		if(is_object($containerObj))				{$sqlConditions[]="_idContainer=".$containerObj->_id;}
		////	Objets indépendants : récupère ceux affectés à l'espace ET éventuellement affectés à l'user ($sqlDisplayNormal)
		if(static::isContainersContent()==false || (static::isFolderContent && empty($containerObj)) || (is_object($containerObj) && $containerObj->isRootFolder())){
			$sqlDisplayNormal=($_SESSION["displayObjects"]=="normal")  ?  "and target in (".static::sqlTargets().")"  :  null;//inutile si "displayObjects" => "autor" ou "all"
			$sqlConditions[]=$keyId." IN  (select _idObject as ".$keyId." from ap_objectTarget where objectType='".static::objectType."' and (_idSpace=".Ctrl::$curSpace->_id." or _idSpace is null) ".$sqlDisplayNormal.")";
		}
		////	Finalise la sélection : liées par des "AND"
		$sqlDisplayedObjects=(!empty($sqlConditions))  ?  "(".implode(" AND ",$sqlConditions).")"  :  $keyId." is null";
		////	Au besoin, récupère aussi le contenu des conteneurs que l'on peut afficher (dossiers / sujets du forum / etc)
		if(empty($containerObj) && static::isContainersContent()){
			$MdlObjectContainer=static::MdlObjectContainer;
			$sqlDisplayedObjects="(".$sqlDisplayedObjects." OR ".$MdlObjectContainer::sqlDisplayedObjects(null,"_idContainer").")";//Appel récursif avec "_idContainer" comme $keyId. Cf. "getPluginObjects()"
		}
		return $sqlDisplayedObjects;
	}

	/*
	 * STATIC SQL : Recupère les objets pour un affichage "plugins" ("dashboard"/"shortcut"/"search")
	 */
	public static function getPluginObjects($pluginParams)
	{
		$returnObjects=[];
		if(isset($pluginParams["type"]))
		{
			//Recupere les elements du plugin!
			$returnObjects=Db::getObjTab(static::objectType, "SELECT * FROM ".static::dbTable." WHERE ".static::sqlPluginObjects($pluginParams)." AND ".static::sqlDisplayedObjects()." ORDER BY dateCrea desc");
			//Ajoute si besoin les plugins "current" du Dashboard (ayant lieu entre aujourd'hui et la fin de la periode selectionné)
			if($pluginParams["type"]=="dashboard" && (static::objectType=="calendarEvent" || static::objectType=="task"))
			{
				$pluginParams["type"]="current";
				$returnObjectsCurrent=Db::getObjTab(static::objectType, "SELECT * FROM ".static::dbTable." WHERE ".static::sqlPluginObjects($pluginParams)." AND ".static::sqlDisplayedObjects()." ORDER BY dateCrea desc");
				foreach($returnObjectsCurrent as $tmpObj){
					$tmpObj->pluginIsCurrent=true;
					$returnObjects[$tmpObj->_id]=$tmpObj;//écrase / ajoute l'objet du tableau
				}
			}
		}
		return $returnObjects;
	}

	/*
	 * STATIC SQL : Selection d'objets en fonction du type de plugin
	 * $pluginParams["type"] => "dashboard": cree dans la periode selectionné / "shortcut": ayant un raccourci / "search": issus d'une recherche
	 */
	public static function sqlPluginObjects($pluginParams)
	{
		if($pluginParams["type"]=="current")		{return "((dateBegin between ".Db::format($pluginParams["dateTimeBegin"])." and ".Db::format($pluginParams["dateTimeEnd"]).")  OR  (dateEnd between ".Db::format($pluginParams["dateTimeBegin"])." and ".Db::format($pluginParams["dateTimeEnd"]).")  OR  (dateBegin < ".Db::format($pluginParams["dateTimeBegin"])." and dateEnd > ".Db::format($pluginParams["dateTimeEnd"])."))";}
		elseif($pluginParams["type"]=="dashboard")	{return "dateCrea between '".$pluginParams["dateTimeBegin"]."' AND '".$pluginParams["dateTimeEnd"]."'";}
		elseif($pluginParams["type"]=="shortcut")	{return "shortcut=1";}
		elseif($pluginParams["type"]=="search")
		{
			$sqlReturned="";
			//Recherche dans tous les champs de l'objet ou uniquement ceux demandés
			$objectSearchFields=(!empty($pluginParams["searchFields"])) ? array_intersect(static::$searchFields,$pluginParams["searchFields"]) : static::$searchFields;
			//Recherche l'expression exacte  (title LIKE 'mot1 mot2')
			if($pluginParams["searchMode"]=="exactPhrase"){
				foreach($objectSearchFields as $tmpField)	{$sqlReturned.=$tmpField." LIKE ".Db::format($pluginParams["searchText"])." OR "; }
			}
			//Recherche un des mots || tous les mots  ("title like '%mot1%' or title like '%mot2%'"  <=>  "title like '%mot1%' and title like '%mot2%'")
			else{
				$searchWords=explode(" ",$pluginParams["searchText"]);
				$linkOperator=($pluginParams["searchMode"]=="allWords") ? "and " : "or ";//garder l'espace après..
				foreach($objectSearchFields as $tmpField){
					$sqlSubSearch="";
					foreach($searchWords as $tmpWord)	{$sqlSubSearch.=$tmpField." like ".Db::format($tmpWord,"likeSearch")." ".$linkOperator;}
					$sqlReturned.="(".trim($sqlSubSearch,$linkOperator).") OR ";
				}
			}
			//Sélection de base
			$sqlReturned="(".trim($sqlReturned," OR ").")";
			//Recherche aussi sur la date de creation
			if($pluginParams["creationDate"]!="all"){
				$nbDays=array("day"=>1,"week"=>7,"month"=>31,"year"=>365);
				$beginDate=time()-(86400*$nbDays[$pluginParams["creationDate"]]);
				$sqlReturned="(".$sqlReturned." AND dateCrea BETWEEN '".date("Y-m-d 00:00",$beginDate)."' and '".date("Y-m-d 23:59")."')";
			}
			//retourne le résultat
			return $sqlReturned;
		}
	}

	/*
	 * Infos sur un fichier joint
	 */
	public static function getAttachedFile($fileInfos)
	{
		//Récup les infos en bdd (si besoin)
		if(is_numeric($fileInfos))
			{$fileInfos=Db::getLine("SELECT * FROM ap_objectAttachedFile WHERE _id=".(int)$fileInfos);}
		//Ajoute le nom, le chemin et l'objet conteneur
		$fileInfos["path"]=PATH_OBJECT_ATTACHMENT.$fileInfos["_id"].".".File::extension($fileInfos["name"]);
		$fileInfos["name"]=$fileInfos["name"];
		$fileInfos["containerObj"]=Ctrl::getObj($fileInfos["objectType"],$fileInfos["_idObject"]);
		return $fileInfos;
	}

	/*
	 * Fichiers joints de l'objet
	 */
	public function getAttachedFileList()
	{
		if($this->_attachedFiles===null){
			$this->_attachedFiles=array();
			if(static::hasAttachedFiles==true){
				$attachedFiles=Db::getTab("SELECT * FROM ap_objectAttachedFile WHERE objectType='".static::objectType."' AND _idObject=".$this->_id);
				foreach($attachedFiles as $fileKey=>$tmpFile)    {$this->_attachedFiles[$fileKey]=self::getAttachedFile($tmpFile);}//ajoute le "path"+"name"
			}
		}
		return $this->_attachedFiles;
	}

	/*
	 * Menu des fichiers joints de l'objet (menu contextuel ou description)
	 */
	public function menuAttachedFiles()
	{
		if(count($this->getAttachedFileList())>0){
			$menuAttachedFiles="<hr>";
			foreach($this->getAttachedFileList() as $tmpFile)	{$menuAttachedFiles.="<div class='menuAttachedFile sLink' title=\"".Txt::trad("telecharger")."\" onclick=\"redir('?ctrl=object&action=downloadAttachedFile&_id=".$tmpFile["_id"]."');\"><img src='app/img/download.png'> ".$tmpFile["name"]."</div>";}
			return $menuAttachedFiles;
		}
	}

	/*
	 * Ajoute un fichier joint dans la description (image/video/audio/flash) : via une requete Sql OU l'editeur html
	 */
	public static function attachedFileInsert($_idFile, $editorInsert=true)
	{
		//Récupère l'image ou le player du média
		$insertText=null;
		$curFile=self::getAttachedFile($_idFile);
		$displayPath="?ctrl=object&action=DisplayAttachedFile&_id=".$curFile["_id"]."&name=".$curFile["name"];
		if(File::controlType("imageBrowser",$curFile["path"]))		{$insertText="<img src='".$displayPath."' style='max-width:100%;'>";}//garder le "style" pour tinyMce..
		elseif(File::controlType("mp3",$curFile["path"]))			{$insertText=File::getMediaPlayer($displayPath);}
		elseif(File::controlType("videoBrowser",$curFile["path"]))	{$insertText=File::getMediaPlayer($curFile["path"]);}//affichage direct
		//Retourne le résultat
		if(!empty($insertText)){
			$insertText="<div id='tagAttachedFile".$curFile["_id"]."' title='".str_replace("'",null,$curFile["name"])."'>".$insertText."</div>";
			return ($editorInsert==true)  ?  "onclick=\"tinymce.activeEditor.setContent(tinymce.activeEditor.getContent()+'".addslashes(str_replace("\"","'",$insertText))."');\""  :  $insertText;
		}
	}

	/*
	 * Ajoute les fichiers joints (cf. "menuEditValidate()")
	 */
	public function addAttachedFiles()
	{
		if(static::hasAttachedFiles==true)
		{
			$curDatasFolderSize=File::datasFolderSize();
			foreach($_FILES as $inputId=>$curFile)
			{
				if(stristr($inputId,"newAttachedFile"))
				{
					////	Pas assez d'espace disque? Sinon fichier bien telechargé?
					if(($curDatasFolderSize+$curFile["size"]) > limite_espace_disque)	{Ctrl::addNotif("MSG_NOTIF_espace_disque");  break;}//sort de la boucle..
					elseif($curFile["error"]==0)
					{
						//Ajoute le fichier en Bdd et dans le dossier de destination
						$attachedFileId=Db::query("INSERT INTO ap_objectAttachedFile SET name=".Db::format($curFile["name"]).", objectType='".static::objectType."', _idObject=".$this->_id, true);
						$fileDestPath=PATH_OBJECT_ATTACHMENT.$attachedFileId.".".File::extension($curFile["name"]);
						$isMoved=move_uploaded_file($curFile["tmp_name"], $fileDestPath);
						if($isMoved!=false)
						{
							//Optimise le fichier
							if(File::controlType("imageResize",$fileDestPath))	{File::imageResize($fileDestPath,$fileDestPath,1200);}
							File::setChmod($fileDestPath);
							//Ajoute l'image/vidéo/Mp3 dans la description
							$insertCheckboxId=str_replace("newAttachedFile","newAttachedFileInsert",$inputId);
							if(static::htmlEditorField!=null && Req::isParam($insertCheckboxId) && File::controlType("attachedFile",$curFile["name"])){
								$insertedText=self::attachedFileInsert($attachedFileId,false);
								Db::query("UPDATE ".static::dbTable." SET ".static::htmlEditorField."=CONCAT(".static::htmlEditorField.",".Db::format($insertedText,"editor").") WHERE _id=".$this->_id);
							}
							$curDatasFolderSize+=$curFile["size"];
						}
					}
				}
			}
			File::datasFolderSize(true);//Recalcule $_SESSION["datasFolderSize"]
		}
	}

	/*
	 * Supprime un fichier joint
	 */
	public function deleteAttachedFile($curFile)
	{
		if($this->editRight() && is_array($curFile)){
			File::rm($curFile["path"]);
			if(!is_file($curFile["path"])){
				Db::query("DELETE FROM ap_objectAttachedFile WHERE _id=".(int)$curFile["_id"]);
				return true;
			}
		}
	}

	/*
	 * Affiche l'auteur de l' objet
	 */
	public function displayAutor($creaAutor=true, $tradAutor=false)
	{
		$labelAutor=($tradAutor==true) ? Txt::trad("auteur")." : ": null;
		if(!empty($this->guest))			{return $labelAutor.$this->guest." (".Txt::trad("guest").")";}				//Invité
		elseif($creaAutor==true)			{return $labelAutor.Ctrl::getObj("user",$this->_idUser)->display();}		//Créateur
		elseif(!empty($this->_idUserModif))	{return $labelAutor.Ctrl::getObj("user",$this->_idUserModif)->display();}	//Dernier user modif.
	}

	/*
	 * Affiche la date de création OU modif
	 */
	public function displayDate($getDateCrea=true, $format="normal")
	{
		if($getDateCrea==true)	{return Txt::displayDate($this->dateCrea,$format);}
		else					{return Txt::displayDate($this->dateModif,$format);}
	}

	/*
	 * Traduction avec changement des libelles -ELEMENT- et -CONTENEUR- par ceux des objets concernés
	 */
	public function tradObject($tradKey)
	{
		//Traduction de base
		$tradBase=Txt::trad($tradKey);
		//Traduction des "-ELEMENT-"
		if(static::isContainer()==false)	{$tradBase=str_replace("-ELEMENT-",Txt::trad("OBJECTelement"),$tradBase);}
		else{
			$MdlObjectContent=static::MdlObjectContent;
			if(Txt::isTrad("OBJECT".$MdlObjectContent::objectType))	{$tradBase=str_replace("-ELEMENT-",Txt::trad("OBJECT".$MdlObjectContent::objectType),$tradBase);}
		}
		//Traduction des "-CONTENEUR-"
		if(static::isFolder)								{$tradBase=str_replace("-CONTENEUR-", Txt::trad("OBJECTfolder"), $tradBase);}
		elseif(Txt::isTrad("OBJECT".static::objectType))	{$tradBase=str_replace("-CONTENEUR-", Txt::trad("OBJECT".static::objectType), $tradBase);}
		else												{$tradBase=str_replace("-CONTENEUR-", Txt::trad("OBJECTcontainer"), $tradBase);}
		// Retour
		return $tradBase;
	}



	/**************************************************************************************************************************
	 ******************************************		VUES/MENUS DE L'OBJET      ************************************************
	 **************************************************************************************************************************/



	/*
	 * Identifiant du menu contextuel : pour le "block" ou pour le "menu"
	 */
	public function menuContextId($for, $reloadUniqid=false)
	{
		//Initialise l'identifiant unique du menu
		if(static::$menuContextUniqId==null || $reloadUniqid==true)  {static::$menuContextUniqId=uniqid();}
		//Préfixe de l'identifiant
		$prefix=($for=="block") ? "objBlock_" : "objMenuContext_";
		return $prefix.$this->_targetObjId."_".static::$menuContextUniqId;
	}

	/*
	 * Attributs du block d'objet (cf. "menuContext()" ci-après)
	 * Le block de l'objet précède toujours l'affichage du menu contextuel correspondant : $reloadUniqid=true
	 */
	public function blockIdForMenuContext()
	{
		//Id du block (initialisé!)  &  Id du menu Contextuel & Url du  l'édition de l'objet (dblClick)
		return  'id="'.$this->menuContextId('block',true).'" for="'.$this->menuContextId('menu').'" '.($this->editRight()?'data-urlEdit="'.$this->getUrl('edit').'"':null);
	}

	/*
	 * VUE : Menu contextuel (édition, droit d'accès, etc)
	 * $options => "deleteLabel" / "deleteLabelConfirm" / "specificOptions"
	 */
	public function menuContext($options=null)
	{
		////	INIT
		$vDatas["curObj"]=$this;
		$vDatas["inlineLauncher"]=(!empty($options["inlineLauncher"])) ? true : false;
		$vDatas["isSelectable"]=(static::isSelectable==true && $vDatas["inlineLauncher"]==false) ? true : false;
		////	DIVERSES OPTIONS (exple: "array('actionJs'=>'?ctrl=file&action=monAction','iconSrc'=>'app/img/plus.png','label'=>'mon option spécifique','tooltip'=>'mon tooltip','inMainMenu'=>true)")
		$vDatas["specificOptions"]=(!empty($options["specificOptions"])) ? $options["specificOptions"] : array();
		////	MODIFIER
		if($this->editRight()){
			$vDatas["editLabel"]=(static::hasAccessRight==true && $this->isIndependant()) ? Txt::trad("modifier_et_acces") : Txt::trad("modifier");
			$vDatas["editObjUrl"]=$this->getUrl("edit");
		}
		////	SUPPRIMER
		if($this->deleteRight()){
			$vDatas["deleteLabel"]=(!empty($options["deleteLabel"])) ? $options["deleteLabel"] : Txt::trad("supprimer");
			$deleteConfirm=(!empty($options["deleteLabelConfirm"])) ? $options["deleteLabelConfirm"] : Txt::trad("confirmDelete");
			$deleteConfirmDetails=(static::isFolder==true)  ?  ", '?ctrl=object&action=SubFoldersDeleteControl&targetObjId=".$this->_targetObjId."', '".Txt::trad("confirmer_suppr_dossier",true)."'"  :  null;
			$vDatas["deleteConfirmRedir"]="confirmRedir('".addslashes($deleteConfirm)."', '".$this->getUrl("delete")."' ".$deleteConfirmDetails.")";
		}
		////	Changer de dossier & Logs/historique (auteur/admin espace)
		if($this->editRight()){
			if($this::isInArbo())										{$vDatas["moveObjectUrl"]="?ctrl=object&action=FolderMove&targetObjId=".$this->containerObj()->_targetObjId."&targetObjects[".static::objectType."]=".$this->_id;}
			if($this->isAutor() || Ctrl::$curUser->isAdminCurSpace())	{$vDatas["logUrl"]="?ctrl=object&action=logs&targetObjId=".$this->_targetObjId;}
		}
		////	INFOS DES DROITS D'ACCESS
		if(static::hasAccessRight==true)
		{
			////	Contenu d'un dossier (sauf racine) : nombre d'elements + taille (module fichiers)
			if(static::isFolder==true)	 {$vDatas["folderContentDescription"]=$this->folderContentDescription();}
			////	Affectation de l'objet (aux espaces, users, etc)
			if(Ctrl::$curUser->isUser() && $this->isIndependant())
			{
				$vDatas["isPersoAccess"]=($vDatas["inlineLauncher"]==false) ? true : false;
				$vDatas["affectLabels"]=$vDatas["affectTooltips"]=array("1"=>null,"1.5"=>null,"2"=>null);
				////	Libellés d'affectations & Icone d'element Perso?
				foreach($this->getAffectations() as $tmpAffect){
					if($tmpAffect["targetType"]!="user" || ($tmpAffect["targetType"]=="user" && Ctrl::$curUser->_id!=$tmpAffect["targetId"]) && $vDatas["inlineLauncher"]==false)	{$vDatas["isPersoAccess"]=false;}
					$vDatas["affectLabels"][$tmpAffect["accessRight"]].=$tmpAffect["label"].($tmpAffect["targetType"]=="user"?", ":"<br>");
				}
				foreach($vDatas["affectLabels"] as $affectRight=>$affectLabel)	{$vDatas["affectLabels"][$affectRight]=trim($affectLabel,", ");}//Enlève dernières virgules pour les affectation d'users
				////	Tooltip sur les droits d'accès
				$affectTooltipsAutor=(static::isContainer()) ? $affectTooltipsAutor="<hr>".$this->tradObject("ecriture_auteur_admin") : null;//"Seul l'auteur peut modifier les droits d'accès à ce dossier"
				if(!empty($vDatas["affectLabels"]["1.5"]))	{$vDatas["affectTooltips"]["1.5"]=$this->tradObject("ecriture_limit_infos").$affectTooltipsAutor;}
				if(!empty($vDatas["affectLabels"]["2"]) && static::isContainer())	{$vDatas["affectTooltips"]["2"]=$this->tradObject("ecriture_infos_conteneur").$affectTooltipsAutor;}//ex: "possibilité de modifier tous les elements du dossier"
				elseif(!empty($vDatas["affectLabels"]["2"]))						{$vDatas["affectTooltips"]["2"]=Txt::trad("ecriture_infos");}//"acces ecriture"
			}
		}
		////	Auteur et date
		//Auteur/invité + date création (optionnelle)
		$vDatas["infosCrea"]["autor"]=$this->displayAutor();
		$vDatas["infosCrea"]["autorLightbox"]=(!empty($this->_idUser)) ? Ctrl::getObj("user",$this->_idUser)->displayUserVue() : null;
		$vDatas["infosCrea"]["date"]=(!empty($this->dateCrea)) ? "<br>".$this->displayDate(true,"normal") : null;
		//Auteur + date Modif
		if(!empty($this->_idUserModif)){
			$vDatas["infosModif"]["autor"]=$this->displayAutor(false);
			$vDatas["infosModif"]["autorLightbox"]=Ctrl::getObj("user",$this->_idUserModif)->displayUserVue();
			$vDatas["infosModif"]["date"]="<br>".$this->displayDate(false,"normal");
		}
		////	Nouvel objet (créé depuis la dernière connexion)
		$vDatas["newObjectSinceConnection"]=(Ctrl::$curUser->isUser() && strtotime($this->dateCrea) > Ctrl::$curUser->previousConnection && !empty(Ctrl::$curUser->previousConnection))  ?  true  :  false;
		////	Fichiers joints
		$vDatas["menuAttachedFiles"]=$this->menuAttachedFiles();
		////	renvoie DU MENU
		return Ctrl::getVue(Req::commonPath."VueObjMenuContext.php",$vDatas);
	}

	/*
	 * VUE : Menu d'édition (droits d'accès, fichiers joints, etc)
	 */
	public function menuEditValidate()
	{
		//Init
		$vDatas["curObj"]=$this;
		$vDatas["WriteLimitInfos"]=$this->tradObject("ecriture_limit_infos");
		$vDatas["extendToSubfolders"]=(static::isFolder==true && $this->_id>1 && Db::getVal("SELECT count(*) FROM ".static::dbTable." WHERE _idContainer=".$this->_id)>0) ? true : false;//dossier pas racine et pas nouveau, mais avec des sous-dossiers?
		////	Menu principal : droit d'accès / identification d'un invité
		$vDatas["mainMenu"]=false;
		if(static::hasAccessRight==true && Ctrl::$curUser->isUser() && $this->isIndependant() && empty($this->onlyNotifMenu)){
			$vDatas["mainMenu"]="accessRights";
			$vDatas["mainMenuLabel"]=(static::isContainer()) ? Txt::trad("EDIT_OBJET_accessRightContent") : Txt::trad("EDIT_OBJET_accessRight");
		}elseif(Ctrl::$curUser->isUser()==false){
			$vDatas["mainMenu"]="identification";
			$vDatas["mainMenuLabel"]=Txt::trad("EDIT_OBJET_identification");
		}
		////	Droits d'accès
		if($vDatas["mainMenu"]=="accessRights")
		{
			////	Droits d'accès pour chaque espace ("targets")
			$vDatas["blocksAccessRight"]=[];
			foreach(Ctrl::$curUser->getSpaces() as $tmpSpace)
			{
				//Init
				$preCheckObject=($tmpSpace->_id==Ctrl::$curSpace->_id && count($this->getAffectations())==0 && $this->type!="user");//Pré-check si espace courant + aucune affectations + pas un agenda perso (type 'user')
				$tmpSpace->curModuleEnabled=array_key_exists(static::moduleName,$tmpSpace->moduleList());//Module activé sur l'espaces?
				$tmpSpace->targetsLines=[];
				////	Tous les utilisateurs de l'espace
				$targetLine=["targetId"=>$tmpSpace->_id."_spaceUsers", "labelText"=>Txt::trad("EDIT_OBJET_allUsers"), "labelIcon"=>"user/icon.png"];
				if($preCheckObject==true)	{$targetLine["preCheckRight"]=(static::isContainer()) ? "1.5" : "1";}//Pré-check : écriture limité pour les conteneurs / lecture seule
				$tmpSpace->targetsLines[]=$targetLine;
				////	Invités
				if($tmpSpace->public=="1"){
					$targetLine=["targetId"=>$tmpSpace->_id."_spaceGuests", "labelText"=>Txt::trad("EDIT_OBJET_espace_guests"), "labelIcon"=>"public.png", "disableWriteAccess"=>true];
					if($preCheckObject==true)	{$targetLine["preCheckRight"]="1";}
					$tmpSpace->targetsLines[]=$targetLine;
				}
				////	Groupe d'utilisateurs de l'espace
				foreach(MdlUserGroup::getGroups($tmpSpace) as $tmpGroup){
					$targetLine=["targetId"=>$tmpSpace->_id."_G".$tmpGroup->_id, "labelText"=>$tmpGroup->title, "labelIcon"=>"user/userGroup.png", "tooltip"=>$tmpGroup->usersLabel];
					$tmpSpace->targetsLines[]=$targetLine;
				}
				////	Chaque user de l'espace
				foreach($tmpSpace->getUsers() as $tmpUser)
				{
					$curUserEditAccess=($tmpSpace->userAccessRight($tmpUser)==2 || $tmpUser->_id==Ctrl::$curUser->_id) ? true : false;
					$tmpSpace->targetsLines[]=array(
						"targetId"=>$tmpSpace->_id."_U".$tmpUser->_id,
						"labelText"=>$tmpUser->display(),
						"labelIconBis"=>($curUserEditAccess==true?'dotW.png':null),
						"tooltip"=>($tmpSpace->userAccessRight($tmpUser)==2 ? Txt::trad("EDIT_OBJET_admin_espace") : null),//(Ctrl::$curSpace->userAccessRight($this)==2)
						"disableLimitedAccess"=>$curUserEditAccess
					);
				}
				////	Ajoute l'espace
				$vDatas["blocksAccessRight"][]=$tmpSpace;
			}
			////	Prépare les targets de chaque espace
			foreach($vDatas["blocksAccessRight"] as $tmpKey=>$tmpSpace)
			{
				foreach($tmpSpace->targetsLines as $targetKey=>$targetLine)
				{
					//Init les propriétés des checkbox. Utilise des "id" pour une sélection rapide des checkboxes par jQuery
					$targetLine["boxProp"]["1"]		="value='".$targetLine["targetId"]."_1'		id='objectRightBox_".$targetLine["targetId"]."_1'";
					$targetLine["boxProp"]["1.5"]	="value='".$targetLine["targetId"]."_1.5'	id='objectRightBox_".$targetLine["targetId"]."_15'";//"_15" au lieu de "_1.5" (cf. selectors jQuery)
					$targetLine["boxProp"]["2"]		="value='".$targetLine["targetId"]."_2'		id='objectRightBox_".$targetLine["targetId"]."_2'";
					//Check les box?
					$objAffectations=$this->getAffectations();
					if(array_key_exists($targetLine["targetId"],$objAffectations)){
						$targetRight=$objAffectations[$targetLine["targetId"]]["accessRight"];
						$targetLine["boxProp"][$targetRight].=" checked";
					}elseif(!empty($targetLine["preCheckRight"])){
						$targetLine["boxProp"][$targetLine["preCheckRight"]].=" checked";
					}
					//Désactive les box?
					if(!empty($targetLine["disableLimitedAccess"]))	{$targetLine["boxProp"]["1"].=" disabled";  $targetLine["boxProp"]["1.5"].=" disabled";}
					if(!empty($targetLine["disableWriteAccess"]))	{$targetLine["boxProp"]["2"].=" disabled";}
					//Met à jour les propriétés de la target
					$vDatas["blocksAccessRight"][$tmpKey]->targetsLines[$targetKey]=$targetLine;
				}
			}
		}
		////	Option "Raccourci"
		if(static::hasShortcut==true && Ctrl::$curUser->isUser() && empty($this->onlyNotifMenu)){
			$vDatas["shortcut"]=true;
			$vDatas["shortcutChecked"]=(!empty($this->shortcut)) ? "checked" : null;
		}
		////	Option "Fichiers joints"
		if(static::hasAttachedFiles==true && Ctrl::$curUser->isUser() && empty($this->onlyNotifMenu)){
			$vDatas["attachedFiles"]=true;
			$vDatas["attachedFilesList"]=$this->getAttachedFileList();
		}
		////	Options notification par mail ET users visibles, autres que ceux de l'espace courant
		if(static::hasNotifMail==true && Ctrl::$curUser->isUser() && function_exists("mail")){
			$vDatas["notifMail"]=true;
			$vDatas["notifMailUsers"]=Ctrl::$curUser->usersVisibles("withCurUser,withMail");
			$vDatas["notifMailCurSpaceUsersIds"]=Ctrl::$curSpace->getUsers("ids");
		}
		//Affiche la vue
		return Ctrl::getVue(Req::commonPath."VueObjMenuEdit.php",$vDatas);
	}

	/*
	 * STATIC : Clé de préférence en Bdd ($prefDbKey) : objet passé en parametre / conteneur ou dossier courant / module courant
	 */
	public static function getPrefDbKey($containerObj)
	{
		if(is_object($containerObj))		{return $containerObj->_targetObjId;}
		elseif(!empty(Ctrl::$curContainer))	{return Ctrl::$curContainer->_targetObjId;}
		else								{return static::moduleName;}
	}

	/*
	 * VUE : Menu de sélection d'objets (menu contextuel flottant)
	 */
	public static function menuSelectObjects($containerObj=null)
	{
		$vDatas["containerObj"]=($containerObj==null && !empty(Ctrl::$curContainer)) ? Ctrl::$curContainer : $containerObj;//Conteneur/Dossier courant en référence?
		return Ctrl::getVue(Req::commonPath."VueObjMenuSelection.php",$vDatas);
	}

	/*
	 * STATIC : Tri d'objets : Preference en Bdd / paramètre passé en Get
	 * exple: "firstName@@asc"
	 */
	private static function getSort($containerObj=null)
	{
		//Récupère la préférence en Bdd ou params GET/POST
		$objectsSort=Ctrl::prefUser("sort_".static::getPrefDbKey($containerObj), "sort");
		//Tri par défaut si aucune préférence n'est précisé ou le tri sélectionné n'est pas dispo pour l'objet courant 
		if(empty($objectsSort) || !in_array($objectsSort,static::$sortFields))    {$objectsSort=static::$sortFields[0];}
		//renvoie le tri
		return $objectsSort;
	}

	/*
	 * STATIC SQL : Tri Sql des objets (avec premier tri si besoin : news, subject, etc)			(ex "tri_sql()")
	 * exple: "ORDER BY firstName asc"
	 */
	public static function sqlSort($containerObj=null, $firstSort=null)
	{
		//Récupère la préférence de tri d'un objet (dossier ou autre)
		$curSortTab=Txt::txt2tab(static::getSort($containerObj));
		//Tri par extension (de fichier ou autre) ?
		$mainSort=($curSortTab[0]=="extension") ? "SUBSTRING_INDEX(name,'.',-1)" : $curSortTab[0];
		//Renvoie le tri Sql
		return "ORDER BY ".$firstSort." ".$mainSort." ".$curSortTab[1];
	}

	/*
	 * VUE : Menu de tri d'un type d'objet				(ex "menu_tri()")
	 */
	public static function menuSort($containerObj=null)
	{
		$vDatas["curSort"]=static::getSort($containerObj);
		$vDatas["curSortTab"]=Txt::txt2tab($vDatas["curSort"]);
		foreach(static::$sortFields as $tmpSort){
			$curSortTab=Txt::txt2tab($tmpSort);
			$vDatas["sortFields"][]=array("url"=>Tool::getParamsUrl("sort")."&sort=".$tmpSort, "sort"=>$tmpSort, "field"=>$curSortTab[0], "ascDesc"=>$curSortTab[1]);
		}
		return Ctrl::getVue(Req::commonPath."VueObjMenuSort.php",$vDatas);
	}

	/*
	 * STATIC : Récupère le type d'affichage de la page
	 */
	public static function getDisplayMode($containerObj=null)
	{
		if(static::$_displayMode===null){
			static::$_displayMode=Ctrl::prefUser("displayMode_".static::getPrefDbKey($containerObj), "displayMode");
			if(empty(static::$_displayMode))    {static::$_displayMode=static::$displayModeOptions[0];}//Affichage par défaut
		}
		return static::$_displayMode;
	}

	/*
	 * VUE : Menu d'affichage des objets dans une page : Blocks / Lignes / Arborescence de messages				(ex "menu_type_affichage()")
	 * $displayModeOptions=> "line" / "block" / "arbo"
	 */
	public static function menuDisplayMode($containerObj=null)
	{
		$vDatas["displayModeOptions"]=static::$displayModeOptions;
		$vDatas["displayMode"]=static::getDisplayMode($containerObj);
		$vDatas["displayModeUrl"]=Tool::getParamsUrl("displayMode")."&displayMode=";
		return Ctrl::getVue(Req::commonPath."VueObjMenuDisplayMode.php",$vDatas);
	}

	/*
	 * STATIC SQL : Filtrage de pagination											(ex "menu_pager()")
	 */
	public static function sqlPagination()
	{
		$offset=(Req::isParam("pageNb"))  ?  ((Req::getParam("pageNb")-1)*static::nbObjectsByPage)  :  "0";
		return "LIMIT ".static::nbObjectsByPage." OFFSET ".$offset;
	}

	/*
	 * VUE : Menu de filtre alphabétique (passe en parametre la requete sql pour récupérer les
	 */
	public static function menuPagination($displayedObjNb, $getParamKey=null)
	{
		$pageNbTotal=ceil($displayedObjNb/static::nbObjectsByPage);
		if($pageNbTotal>1)
		{
			//Nb de page et numéro de page courant
			$vDatas["pageNbTotal"]=$pageNbTotal;
			$vDatas["pageNb"]=$pageNb=Req::isParam("pageNb") ? Req::getParam("pageNb") : 1;
			//Url de redirection de base
			$vDatas["hrefBase"]="?ctrl=".Req::$curCtrl;
			if(!empty($getParamKey) && Req::isParam($getParamKey))	{$vDatas["hrefBase"].="&".$getParamKey."=".Req::getParam($getParamKey);}
			$vDatas["hrefBase"].="&pageNb=";
			//Page Précédente / Suivante
			$vDatas["previousAttr"]=($pageNb>1)  ?  "href=\"".$vDatas["hrefBase"].((int)$pageNb-1)."\""  :  "class='navMenuDisabled'";
			$vDatas["nextAttr"]=($pageNb<$pageNbTotal)  ?  "href=\"".$vDatas["hrefBase"].((int)$pageNb+1)."\""  :  "class='navMenuDisabled'";
			//Récupère le menu
			return Ctrl::getVue(Req::commonPath."VueObjMenuPagination.php",$vDatas);
		}
	}

	/*
	 * Menu d'affectation aux espaces														(ex "menu_affect_espaces()")
	 * Thème de forum (T1) / Categories d'evenement (C1) / Groupe d'utilisateur (G1)
	 */
	public function menuSpaceAffectation()
	{
		$vDatas["curObj"]=$this;
		////	Liste des espaces
		$vDatas["spaceList"]=Ctrl::$curUser->getSpaces();
		//Pour chaque espace : liste les users de l'espace & check si espace affecté à l'objet OU nouvel objet + espace=espace courant
		foreach($vDatas["spaceList"] as $tmpSpace){
			$tmpSpace->checked=(in_array($tmpSpace->_id,$this->spaceIds) || ($this->isNew() && $tmpSpace->_id==Ctrl::$curSpace->_id))  ?  "checked"  :  null;
		}
		////	pseudo Espace "Tous les espaces"
		if(Ctrl::$curUser->isAdminGeneral()){
			$spaceAllSpaces=new MdlSpace();
			$spaceAllSpaces->_id="all";
			$spaceAllSpaces->name=Txt::trad("visible_ts_espaces");
			$spaceAllSpaces->checked=(Ctrl::$curUser->isAdminGeneral() && $this->isNew()==false && empty($this->spaceIds))  ?  "checked"  :  null;//Check "tous les utilisateurs"?
			array_unshift($vDatas["spaceList"],$spaceAllSpaces);
		}
		//Affiche le menu
		$vDatas["displayMenu"]=(Ctrl::$curUser->isAdminGeneral() && count($vDatas["spaceList"])>1) ? true : false;
		return Ctrl::getVue(Req::commonPath."VueObjMenuSpaceAffectation.php",$vDatas);
	}
}