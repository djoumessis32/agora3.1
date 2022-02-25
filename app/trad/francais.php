<?php
/*
 * Classe de gestion d'une langue
 */
class Trad extends Txt
{
	/*
	 * Chargement les elements de traduction
	 */
	public static function loadTradsLang()
	{
		////	Langue courante / Header http / Editeurs Tinymce,DatePicker,etc / Dates formatées par PHP
		self::$trad["CURLANG"]="fr";
		self::$trad["HEADER_HTTP"]="fr";
		self::$trad["DATEPICKER"]="fr";
		self::$trad["HTML_EDITOR"]="fr_FR";
		self::$trad["UPLOADER"]="fr";
		setlocale(LC_TIME, "fr_FR.utf8", "fr_FR.UTF-8", "fr_FR", "fr", "french");

		////	Divers
		self::$trad["OK"]="OK";
		self::$trad["remplir_tous_champs"]="Merci de remplir tous les champs";
		self::$trad["elem_inaccessible"]="Element inaccessible";
		self::$trad["champs_obligatoire"]="Champ obligatoire";
		self::$trad["oui"]="oui";
		self::$trad["non"]="non";
		self::$trad["aucun"]="aucun";
		self::$trad["et"]="et";
		self::$trad["aller_page"]="Aller à la page";
		self::$trad["alphabet_filtre"]="Filtre alphabetique";
		self::$trad["tout_afficher"]="Tout afficher";
		self::$trad["important"]="Important";
		self::$trad["afficher"]="afficher";
		self::$trad["deplacer"]="déplacer";
		self::$trad["garder"]="Garder";
		self::$trad["par_defaut"]="Par défaut";
		self::$trad["localiser_carte"]="Localiser sur une carte";
		self::$trad["bienvenue_agora"]="Bienvenue sur l'Agora!";
		self::$trad["mail_pas_valide"]="L'e-mail n'est pas valide";
		self::$trad["element"]="élément";
		self::$trad["elements"]="éléments";
		self::$trad["dossier"]="dossier";
		self::$trad["dossiers"]="dossiers";
		self::$trad["dossier_parent"]="dossier parent";
		self::$trad["fermer"]="Fermer";
		self::$trad["visible_espaces"]="Espaces où il sera visible";
		self::$trad["visible_ts_espaces"]="Visible sur tous les espaces";
		self::$trad["confirmCloseLightbox"]="Fermer le formulaire ?";

		////	Menu/Menu contextuel
		self::$trad["menu"]="menu";
		self::$trad["objNew"]="Elément nouvellement créé";
		self::$trad["acces_perso"]="Accès personnel";

		////	images
		self::$trad["picture"]="Photo";
		self::$trad["wallpaper"]="Fond d'écran";
		self::$trad["image_changer"]="changer";
		self::$trad["pixels"]="pixels";

		////	Connexion
		self::$trad["specifierLoginPassword"]="Merci de spécifier un identifiant et un mot de passe";
		self::$trad["login"]="Email / Identifiant";//d'une manière plus generale => nom d'utilisateur OU email
		self::$trad["login2"]="Email / Identifiant de connexion";//d'une manière plus generale => nom d'utilisateur OU email
		self::$trad["placeholderLogin"]="Email / Identifiant";
		self::$trad["password"]="Mot de passe";
		self::$trad["passwordToModify"]="Mot de passe (à modifier au besoin)";
		self::$trad["passwordVerif"]="Confirmer mot de passe";
		self::$trad["passwordInfo"]="Remplir le champ seulement si vous souhaitez changer de mot de passe";
		self::$trad["passwordVerifError"]="Votre confirmation de mot de passe n'est pas valide";
		self::$trad["connexion"]="Connexion";
		self::$trad["connexion_auto"]="rester connecté";
		self::$trad["connexion_auto_info"]="Retenir mon identifiant et mot de passe pour une connexion automatique";
		self::$trad["password_oublie"]="mot de passe oublié ?";
		self::$trad["password_oublie_info"]="Envoyer mon identifiant et mot de passe à mon adresse mail";
		self::$trad["acces_guest"]="Accès invité";
		self::$trad["espace_password_erreur"]="Mot de passe erroné";
		self::$trad["version_ie"]="Votre navigateur est trop ancien et ne prend pas en charge les récents standards : ll est vivement conseillé de le mettre à jour ou d'utiliser un autre navigateur. Mise à jour à cette adresse : windows.microsoft.com/fr-fr/internet-explorer";
		
		////	Type d'affichage
		self::$trad["type_affichage"]="Affichage";
		self::$trad["type_affichage_line"]="Liste";
		self::$trad["type_affichage_block"]="Bloc";
		self::$trad["type_affichage_arbo"]="Arborescence";
		
		////	Sélectionner / Déselectionner tous les éléments
		self::$trad["select"]="Sélectionner";
		self::$trad["select_deselect"]="Sélectionner / déselectionner";
		self::$trad["tout_selectionner"]="Tout sélectionner";
		self::$trad["inverser_selection"]="Inverser la sélection";
		self::$trad["suppr_elements"]="Supprimer les éléments";
		self::$trad["deplacer_elements"]="Déplacer vers un autre dossier";
		self::$trad["voir_sur_carte"]="Voir les contacts sur une carte";
		self::$trad["selectionner_user"]="Merci de sélectionner au moins un utilisateur";
		self::$trad["selectionner_2users"]="Merci de sélectionner au moins 2 utilisateurs";
		self::$trad["selectionner_espace"]="Merci de sélectionner au moins un espace";
		
		////	Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
		self::$trad["de"]="de";
		self::$trad["a"]="à";
		self::$trad["le"]="le";
		self::$trad["debut"]="Début";
		self::$trad["fin"]="Fin";
		self::$trad["separateur_horaire"]="h";
		self::$trad["jours"]="jours";
		self::$trad["jour_1"]="Lundi";
		self::$trad["jour_2"]="Mardi";
		self::$trad["jour_3"]="Mercredi";
		self::$trad["jour_4"]="Jeudi";
		self::$trad["jour_5"]="Vendredi";
		self::$trad["jour_6"]="Samedi";
		self::$trad["jour_7"]="Dimanche";
		self::$trad["mois_1"]="janvier";
		self::$trad["mois_2"]="fevrier";
		self::$trad["mois_3"]="mars";
		self::$trad["mois_4"]="avril";
		self::$trad["mois_5"]="mai";
		self::$trad["mois_6"]="juin";
		self::$trad["mois_7"]="juillet";
		self::$trad["mois_8"]="aout";
		self::$trad["mois_9"]="septembre";
		self::$trad["mois_10"]="octobre";
		self::$trad["mois_11"]="novembre";
		self::$trad["mois_12"]="decembre";
		self::$trad["aujourdhui"]="aujourd'hui";
		self::$trad["aff_aujourdhui"]="Afficher aujourd'hui";
		self::$trad["modif_dates_debutfin"]="La date de fin ne peut pas être antérieure à la date de début";
		self::$trad["dates_mauvais_format"]="La date doit être au format jj/mm/AAAA";
		
		////	Nom & Description (pour les menus d'édition principalement)
		self::$trad["title"]="Titre";
		self::$trad["name"]="Nom";
		self::$trad["description"]="Description";
		self::$trad["specifyName"]="Merci de spécifier un nom";
		
		////	Validation des formulaires
		self::$trad["ajouter"]=" Ajouter";
		self::$trad["modifier"]=" Modifier";
		self::$trad["modifier_et_acces"]="Modifier et Gérer droits d'accès";
		self::$trad["valider"]=" Valider";
		self::$trad["envoyer"]="Envoyer";
		self::$trad["envoyer_a"]="Envoyer a";
		
		////	Tri d'affichage. Tous les éléments (dossier, tâche, lien, etc...) ont par défaut une date, un auteur & une description
		self::$trad["trie_par"]="Trié par";
		self::$trad["SORT_dateCrea"]="date de création";
		self::$trad["SORT_dateModif"]="date de modif";
		self::$trad["SORT_title"]="titre";
		self::$trad["SORT_description"]="description";
		self::$trad["SORT__idUser"]="auteur";
		self::$trad["SORT_extension"]="type de fichier";
		self::$trad["SORT_octetSize"]="taille";
		self::$trad["SORT_downloadsNb"]="nb de téléchargements";
		self::$trad["SORT_civility"]="civilité";
		self::$trad["SORT_name"]="nom";
		self::$trad["SORT_firstName"]="prénom";
		self::$trad["SORT_adress"]="adresse";
		self::$trad["SORT_postalCode"]="code postal";
		self::$trad["SORT_city"]="ville";
		self::$trad["SORT_country"]="pays";
		self::$trad["SORT_function"]="fonction";
		self::$trad["SORT_companyOrganization"]="société / organisme";
		self::$trad["tri_ascendant"]="Ascendant";
		self::$trad["tri_descendant"]="Descendant";
		
		////	Options de suppression
		self::$trad["confirmDelete"]="Confirmer la suppression ? Attention : action irréversible!";
		self::$trad["confirmDeleteBis"]="Êtes-vous sûr ?";
		self::$trad["confirmer_suppr_dossier"]="Attention! certains sous-dossiers ne vous ont pas été affectés en lecture ou ecriture (mais à d'autres utilisateurs) : ils seront également effacés !";
		self::$trad["supprimer"]="Supprimer";
		
		////	Visibilité d'un Objet : auteur et droits d'accès
		self::$trad["auteur"]="Auteur";
		self::$trad["cree_par"]="Création";
		self::$trad["modif_par"]="Modif.";
		self::$trad["historique_element"]="Historique de l'element";
		self::$trad["guest"]="invité";
		self::$trad["guests"]="invités";
		self::$trad["tous"]="tous";
		self::$trad["inconnu"]="personne indéfinie";
		self::$trad["lecture"]="lecture";
		self::$trad["lecture_infos"]="Accès en lecture";
		self::$trad["ecriture_limit"]="écriture limitée";
		self::$trad["ecriture_limit_infos"]="Possibilité d'ajouter des elements (<i>-ELEMENT-</i>),<br>mais pas de modifier/supprimer les éléments créés par d'autres utilisateurs";
		self::$trad["ecriture"]="écriture";
		self::$trad["ecriture_infos"]="Accès en écriture";
		self::$trad["ecriture_infos_conteneur"]="Accès en écriture : possibilité d'ajouter, modifier ou supprimer<br>tous les elements (-ELEMENT-) du -CONTENEUR-";
		self::$trad["ecriture_auteur_admin"]="Seul l'auteur et les administrateurs peuvent<br>modifier les droits d'accès ou supprimer le -CONTENEUR-";
		self::$trad["contenu_dossier"]="contenu";
		
		////	Libellé des objets (cf. "MdlObject::objectType")
		self::$trad["OBJECTcontainer"]="conteneur";
		self::$trad["OBJECTelement"]="element";
		self::$trad["OBJECTfolder"]="dossier";
		self::$trad["OBJECTdashboardNews"]="actualité";
		self::$trad["OBJECTfile"]="fichier";
		self::$trad["OBJECTcalendar"]="agenda";
		self::$trad["OBJECTcalendarEvent"]="evenement";
		self::$trad["OBJECTforumSubject"]="sujet";
		self::$trad["OBJECTforumMessage"]="message";
		self::$trad["OBJECTcontact"]="contact";
		self::$trad["OBJECTlink"]="favori";
		self::$trad["OBJECTtask"]="tache";
		self::$trad["OBJECTuser"]="utilisateur";
	
		////	Envoi d'un mail (nouvel utilisateur, notification de création d'objet, etc...)
		self::$trad["MAIL_noFooter"]="Ne pas signer le message";
		self::$trad["MAIL_noFooter_info"]="Ne pas signer la fin du message <br>avec le nom de l'expéditeur et un lien vers l'espace";
		self::$trad["MAIL_hideRecipients"]="Masquer les destinataires";
		self::$trad["MAIL_hideRecipients_info"]="Par défaut, les destinataires sont visibles dans le message.<br>Attention car si l'option est coché et qu'il y a trop de destinataires,<br>le mail peut arriver en Spam";
		self::$trad["MAIL_receptionNotif"]="Accusé de reception";
		self::$trad["MAIL_receptionNotif_info"]="Attention! Certaines messageries ne prennent pas en charge<br>les accusés de réception.";
		self::$trad["MAIL_envoye_par"]="Envoyé par";  // "Envoyé par" M. Trucmuche
		self::$trad["MAIL_envoye"]="L'e-mail a bien été envoyé !";
		self::$trad["MAIL_envoye_notif"]="L'e-mail de notification a bien été envoyé !";
		self::$trad["MAIL_pas_envoye"]="L'e-mail n'a pas pu être envoyé...";//idem
		self::$trad["MAIL_element_cree"]="Nouvel élément créé par";//boby
		self::$trad["MAIL_element_modifie"]="Elément modifié par";//boby
		self::$trad["MAIL_element_sur_espace"]="sur l'espace";//truc
		self::$trad["MAIL_element_acces_direct"]="Cliquez ici pour accéder à l'élément sur votre espace";
		
		////	Dossier & fichier
		self::$trad["giga_octet"]="Go";
		self::$trad["mega_octet"]="Mo";
		self::$trad["kilo_octet"]="Ko";
		self::$trad["dossier_racine"]="Dossier racine";
		self::$trad["rootFolderEditInfo"]="Ouvrez le parametrage de l'espace<br>pour pouvoir modifier les droits d'accès au dossier racine";
		self::$trad["ajouter_dossier"]="Ajouter un dossier";
		self::$trad["telecharger"]="Télécharger le fichier";
		self::$trad["telecharge_nb"]="Téléchargé";
		self::$trad["telecharge_nb_bis"]="fois"; // Téléchargé 'n' fois
		self::$trad["telecharger_dossier"]="Télécharger le dossier";
		self::$trad["espace_disque_utilise"]="Espace disque utilisé";
		self::$trad["espace_disque_utilise_mod_fichier"]="Espace disque utilisé sur le module fichier";
		self::$trad["download_alert"]="Telechargement de grosse archive indisponible entre";//..(9h=>19h)
		self::$trad["download_alert2"]="Taille maximum sur cette plage horaire";//..200Mo
		
		////	Infos sur une personne
		self::$trad["civility"]="Civilité";
		self::$trad["name"]="Nom";
		self::$trad["firstName"]="Prénom";
		self::$trad["adress"]="Adresse";
		self::$trad["postalCode"]="Code postal";
		self::$trad["city"]="Ville";
		self::$trad["country"]="Pays";
		self::$trad["telephone"]="Téléphone";
		self::$trad["telmobile"]="Tél. mobile";
		self::$trad["mail"]="Email";
		self::$trad["fax"]="Fax";
		self::$trad["website"]="Site Web";
		self::$trad["skills"]="Compétences";
		self::$trad["hobbies"]="Centres d'intérêt";
		self::$trad["function"]="Fonction";
		self::$trad["companyOrganization"]="Organisme / Société";
		self::$trad["comment"]="Commentaire";
		
		////	Captcha
		self::$trad["captcha"]="Identification visuelle";
		self::$trad["captcha_info"]="Merci de recopier les 4 caractères pour votre identification";
		self::$trad["captcha_alert_specifier"]="Merci de spécifier l'identification visuelle";
		self::$trad["captcha_alert_erronee"]="L'identification visuelle est erronée";
		
		////	Rechercher
		self::$trad["preciser_text"]="Merci de préciser des mots clés d'au moins 3 caractères";
		self::$trad["rechercher"]="Rechercher";
		self::$trad["rechercher_dateCrea"]="Date de création";
		self::$trad["rechercher_dateCrea_jour"]="moins d'un jour";
		self::$trad["rechercher_dateCrea_semaine"]="moins d'une semaine";
		self::$trad["rechercher_dateCrea_mois"]="moins d'un mois";
		self::$trad["rechercher_dateCrea_annee"]="moins d'un an";
		self::$trad["rechercher_espace"]="Rechercher sur l'espace";
		self::$trad["recherche_avancee"]= "Recherche avancée";
		self::$trad["recherche_avancee_mots_certains"]= "n'importe quel mot";
		self::$trad["recherche_avancee_mots_tous"]= "tous les mots";
		self::$trad["recherche_avancee_expression_exacte"]= "expression exacte";
		self::$trad["keywords"]="Mots clés";
		self::$trad["liste_modules"]="Modules";
		self::$trad["liste_champs"]="Champs";
		self::$trad["liste_champs_elements"]="Elements concernés";
		self::$trad["aucun_resultat"]="Aucun résultat";
		
		////	Gestion des inscriptions d'utilisateur
		self::$trad["usersInscription"]="m'inscrire sur le site";
		self::$trad["usersInscription_info"]="créer un nouveau compte utilisateur (validé par un administrateur)";
		self::$trad["usersInscription_espace"]="m'inscrire à l'espace";//.."trucmuche"
		self::$trad["usersInscription_enregistre"]="votre inscription a bien été enregistrée : elle sera validé dès que possible par l'administrateur de l'espace";
		self::$trad["usersInscription_option_espace"]="Les visiteurs peuvent s'incrire sur l'espace";
		self::$trad["usersInscription_option_espace_info"]="L'inscription d'un utilisateur se fait sur la page d'accueil du site. Elle doit ensuite être validée par l'administrateur de l'espace !";
		self::$trad["usersInscription_validation"]="Inscriptions";
		self::$trad["usersInscription_validation_title"]="Valider l'inscription d'utilisateurs sur le site";
		self::$trad["usersInscription_invalider"]="Invalider";
		self::$trad["usersInscription_invalider_mail"]="Votre compte n'a pas été validé sur";
		
		////	Importer ou Exporter : Contact OU Utilisateurs
		self::$trad["exporter"]="Exporter";
		self::$trad["importer"]="Importer";
		self::$trad["import_export_user"]="des utilisateurs";
		self::$trad["import_export_contact"]="des contacts";
		self::$trad["export_format"]="format";
		self::$trad["specifier_fichier"]="Merci de spécifier un fichier";
		self::$trad["extension_fichier"]="Le type de fichier n'est pas valide. Il doit être de type";
		self::$trad["import_infos"]="Sélectionnez les champs Agora à cibler grâce aux listes déroulantes de chaque colonne.";
		self::$trad["import_alert"]="Merci de sélectionner la colonne nom dans les listes déroulante";
		self::$trad["import_alert2"]="Merci de sélectionner au moins un element à importer";
		self::$trad["import_alert3"]="Le champ agora à déjà été sélectionné sur une autre colonne (chaque champs agora ne peut être sélectionné qu'une fois)";

		////	Connexion à un serveur LDAP
		self::$trad["ldap_connexion_serveur"]="Connexion à un serveur LDAP";
		self::$trad["ldap_server"]="Adresse sur serveur (hostname)";
		self::$trad["ldap_server_port"]="Port sur serveur";
		self::$trad["ldap_server_port_infos"]="''389'' par défaut";
		self::$trad["ldap_admin_login"]="Chaine de connexion pour l'admin";
		self::$trad["ldap_admin_login_infos"]="par exemple ''uid=admin,ou=mon_entreprise''";
		self::$trad["ldap_admin_pass"]="Mot de passe de l'admin";
		self::$trad["ldap_base_dn"]="Groupe d'utilisateurs / base DN";
		self::$trad["ldap_base_dn_infos"]="Emplacement des utilisateurs dans l'annuaire.<br>Par exemple ''ou=users,o=mon_organisme''";
		self::$trad["ldap_connexion_erreur"]="Erreur de connexion au serveur LDAP !";
		self::$trad["ldap_crea_auto_users"]="Création auto d'utilisateurs, si identification";
		self::$trad["ldap_crea_auto_users_infos"]="Créer automatiquement un utilisateur s'il est absent de l'Agora mais présent sur le serveur LDAP : il sera alors affecté aux espaces accessibles à ''tous les utilisateurs du site''.<br>Dans le cas contraire, l'utilisateur ne sera pas créé.";
		self::$trad["ldap_pass_cryptage"]="Password cryptés sur le serveur";
		self::$trad["ldap_effacer_params"]="Effacer le parametrage LDAP ?";
		self::$trad["ldap_pas_module_php"]="Le module PHP de connexion à un serveur LDAP n'est pas installé !"; 

		////	Messages d'alerte / d'erreur
		self::$trad["MSG_NOTIF_identification"]="Identifiant ou mot de passe invalide";
		self::$trad["MSG_NOTIF_dejapresent"]="Compte actuellement utilisé avec une autre adresse ip... (un compte ne peut être utilisé que sur un seul poste en même temps)";
		self::$trad["MSG_NOTIF_adresseip"]="L'Adresse IP que vous utilisez n'est pas autorisée pour ce compte";
		self::$trad["MSG_NOTIF_acces_aucun_espace"]="L'accès au site ne vous est pas autorisé car actuellement, vous n'êtes probablement affecté à aucun espace.";
		self::$trad["MSG_NOTIF_acces_fichier_dossier"]="Fichier ou dossier inaccessible";
		self::$trad["MSG_NOTIF_espace_disque"]="L'espace pour le stockage de vos fichiers est insuffisant, vous ne pouvez pas ajouter de fichier";
		self::$trad["MSG_NOTIF_type_version"]="Type de fichier différent de l'original";
		self::$trad["MSG_NOTIF_type_interdit"]="Type de fichier non autorisé";
		self::$trad["MSG_NOTIF_deplacement_dossier"]="Vous ne pouvez pas déplacer le dossier à l'intérieur de lui-même..!";
		self::$trad["MSG_NOTIF_duplicateName"]="Un element portant le même nom existe déjà.";
		self::$trad["MSG_NOTIF_fileName"]="Un fichier avec le même nom existe déjà (sans être remplacé par le fichier courant)";
		self::$trad["MSG_NOTIF_chmod_DATAS"]="Le dossier ''DATAS'' n'est pas accessible en écriture. Vous devez y donner un accès en lecture-ecriture, au proprietaire et au groupe (''chmod 775'')";
		self::$trad["MSG_NOTIF_nb_users"]="Vous ne pouvez pas créer de nouveau compte utilisateur : nombre limité à "; // "...limité à" 10
		self::$trad["MSG_NOTIF_update"]="Mise à jour terminée. <br><br>Il est conseillé de redémarrer votre navigateur avant de vous reconnecter !";
		self::$trad["MSG_NOTIF_user_existdeja"]="L'identifiant existe déjà : l'utilisateur n'a donc pas été créé";
		
		////	header menu / Footer
		self::$trad["HEADER_MENU_espaces_dispo"]="Autres espaces disponibles";
		self::$trad["HEADER_MENU_display_elem"]="Afficher les éléménts";
		self::$trad["HEADER_MENU_display_normal"]="qui me sont affectés";
		self::$trad["HEADER_MENU_display_title_normal"]="C'est l'affichage normal / par défaut";
		self::$trad["HEADER_MENU_display_autor"]="que j'ai créés";
		self::$trad["HEADER_MENU_display_title_autor"]="Pour afficher uniquement les éléments que j'ai créé";
		self::$trad["HEADER_MENU_display_all"]="Tous les éléments de l'espace (admin)";
		self::$trad["HEADER_MENU_display_title_all"]="Réservé à l'administrateur de l'espace : permet d'afficher tous les éléments sur l'espace, même ceux qui ne sont pas affectés à l'administrateur !";
		self::$trad["HEADER_MENU_recherche_elem"]="Rechercher un element sur l'espace";
		self::$trad["HEADER_MENU_documentation"]="Guide d'utilisation";
		self::$trad["HEADER_MENU_sortie_agora"]="Déconnexion";
		self::$trad["HEADER_MENU_shortcuts"]="Raccourcis";
		self::$trad["HEADER_MENU_seul_utilisateur_connecte"]="Vous êtes actuellement le seul connecté";
		self::$trad["HEADER_MENU_en_ligne"]="En ligne";
		self::$trad["HEADER_MENU_connecte_a"]="connecté au site à";   // M. Bidule truc "connecté au site à" 12:45
		self::$trad["HEADER_MENU_messenger"]="Messagerie instantanée";
		self::$trad["HEADER_MENU_envoye_a"]="Envoyé à";
		self::$trad["HEADER_MENU_ajouter_message"]="Ajouter un message";
		self::$trad["HEADER_MENU_specifier_message"]="Merci de spécifier un message";
		self::$trad["HEADER_MENU_enregistrer_conversation"]="Enregistrer cette conversation";
		self::$trad["FOOTER_page_generee"]="page générée en";

		////	Password_oublie
		self::$trad["PASS_OUBLIE_preciser_mail"]="Indiquez votre adresse e-mail pour recevoir vos coordonnées de connexion";
		self::$trad["PASS_OUBLIE_mail_inexistant"]="L'e-mail indiqué n'existe pas dans la base";
		self::$trad["PASS_OUBLIE_mail_objet"]="Connexion à votre espace";
		self::$trad["PASS_OUBLIE_mail_contenu"]="Votre identifiant de connexion";
		self::$trad["PASS_OUBLIE_mail_contenu_bis"]="Cliquer ici pour réinitialiser votre mot de passe";
		self::$trad["PASS_OUBLIE_prompt_changer_pass"]="Spécifier ici le nouveau mot de passe";
		self::$trad["PASS_OUBLIE_idNewPassword_expire"]="Le lien pour régénérer le mot de passe a expiré.. merci de recommencer la procédure";
		self::$trad["PASS_OUBLIE_password_reinitialise"]="Votre nouveau mot de passe a été enregistré !";
		
		////	menu_edit_objet
		self::$trad["EDIT_OBJET_alert_aucune_selection"]="Vous devez sélectionner au moins une personne ou un espace";
		self::$trad["EDIT_OBJET_alert_pas_acces_perso"]="Vous n'êtes pas affecté à l'élément. valider tout de même ?";
		self::$trad["EDIT_OBJET_alert_ecriture_obligatoire"]="Il doit y avoir au moins une personne, groupe ou un espace avec un accès en écriture";
		self::$trad["EDIT_OBJET_alert_ecriture_deconseille"]="Attention ! <br><br>Le droit en écriture permet d'effacer tous les messages du sujet : modérateur du sujet.<br><br>Il est préférable de mettre un accès en écriture limitée";
		self::$trad["EDIT_OBJET_alert_guest"]="Merci de préciser un nom ou un pseudo";
		self::$trad["EDIT_OBJET_accessRight"]="Droits d'accès";
		self::$trad["EDIT_OBJET_accessRightContent"]="Droits d'accès au contenu";
		self::$trad["EDIT_OBJET_identification"]="Identification";
		self::$trad["EDIT_OBJET_espace_pas_module"]="Le module courant n'a pas encore été ajouté à cet espace";
		self::$trad["EDIT_OBJET_allUsers"]="Tous les utilisateurs de l'espace";
		self::$trad["EDIT_OBJET_allUsers_espaces"]="Tous les espaces";
		self::$trad["EDIT_OBJET_espace_guests"]="Invités de l'espace";
		self::$trad["EDIT_OBJET_guest"]="Votre Nom / Pseudo";
		self::$trad["EDIT_OBJET_admin_espace"]="Administrateur de l'espace :<br>accède en écriture à tous les elements de l'espace";
		self::$trad["EDIT_OBJET_tous_espaces"]="Afficher tous mes espaces";
		self::$trad["EDIT_OBJET_notif_mail"]="Notifier par email";
		self::$trad["EDIT_OBJET_notif_mail_label"]="Envoyer une notification par email";
		self::$trad["EDIT_OBJET_notif_mail_joindre_fichiers"]="Joindre les fichiers à la notification";
		self::$trad["EDIT_OBJET_notif_mail_info"]="Envoyer une notification de création/modification par email<br>aux personnes affectées à cet élément ou sélectionnées dans le sous-menu";
		self::$trad["EDIT_OBJET_notif_mail_selection"]="Sélectionner manuellement les destinataires des notifications";
		self::$trad["EDIT_OBJET_notif_tous_users"]="Afficher + d'utilisateurs";
		self::$trad["EDIT_OBJET_accessRightSubFolders"]="Donner les mêmes droits aux sous-dossiers";
		self::$trad["EDIT_OBJET_accessRightSubFolders_info"]="Etendre les droits d'accès aux sous-dossiers auquels vous avez accès en écriture";
		self::$trad["EDIT_OBJET_shortcut"]="Raccourci";
		self::$trad["EDIT_OBJET_shortcut_info"]="Afficher un raccourci dans la barre de menu (en haut de page)";
		self::$trad["EDIT_OBJET_fichier_joint"]="Joindre des fichiers";
		self::$trad["EDIT_OBJET_fichier_joint_info"]="Attacher des images, videos, Pdf, etc.<br>Les images et videos peuvent être intégrées au texte";
		self::$trad["EDIT_OBJET_inserer_fichier"]="Afficher dans la description";
		self::$trad["EDIT_OBJET_inserer_fichier_info"]="Afficher l'image / video / Lecteur Mp3... dans la description ci-dessus. L'insertion sera réalisé après validation du formulaire.";
		self::$trad["EDIT_OBJET_demandToConfirm"]="Votre demande a bien été enregistrée. Elle sera prise en compte prochainement.";

		////	Formulaire d'installation
		self::$trad["INSTALL_connexion_bdd"]="Connexion à la base de données";
		self::$trad["INSTALL_db_host"]="Nom d'Hote du serveur (Hostname)";
		self::$trad["INSTALL_db_name"]="Nom de la Base de Données";
		self::$trad["INSTALL_db_login"]="Nom d'utilisateur";
		self::$trad["INSTALL_config_admin"]="Administrateur de l'Agora";
		self::$trad["INSTALL_PhpOldVersion"]="Agora-Project necessite une version plus recente de PHP";
		self::$trad["INSTALL_errorConnectSGBD"]="La connexion au serveur de base de données à échoué";
		self::$trad["INSTALL_errorConnectIdentification"]="L'identification au serveur de base de données à échoué";
		self::$trad["INSTALL_errorAppliInstalled"]="L'application a déjà été installée sur cette base de données. Merci de supprimer la BDD si vous souhaitez relancer l'installation.";
		self::$trad["INSTALL_errorConnectDbConfirmInstall"]="La base de données n'existe pas : confirmer l'install et créer la Bdd ?";
		self::$trad["INSTALL_confirm_install"]="Confirmer l'installation ?";
		self::$trad["INSTALL_install_ok"]="Agora-Project a bien été installé !";
		self::$trad["INSTALL_spaceDescription"]="Espace de partage et de travail collaboratif";

		////	MODULE_PARAMETRAGE DE L'AGORA
		////
		self::$trad["AGORA_headerModuleName"]="Paramétrage général";
		self::$trad["AGORA_description_module"]="Paramétrage général";
		self::$trad["AGORA_sav"]="Sauvegarder la base de données et les fichiers";
		self::$trad["AGORA_sav_alert"]="La création du fichier de sauvegarde peut durer quelques minutes... et son téléchargement quelques dizaines de minutes.";
		self::$trad["AGORA_sav_bdd"]="Sauvegarder la base de données";
		self::$trad["AGORA_espace_disque_invalide"]="L'espace disque limite doit être un entier";
		self::$trad["AGORA_confirmez_modification_site"]="Confirmez-vous les modifications ?";
		self::$trad["AGORA_name"]="Nom du site";
		self::$trad["AGORA_footerHtml"]="Footer / pied de page html";
		self::$trad["AGORA_footerHtml_info"]="Pour inclure des outils de statistique par exemple";
		self::$trad["AGORA_lang"]="Langue par défaut";
		self::$trad["AGORA_timezone"]="Fuseau horaire";
		self::$trad["AGORA_spaceName"]="Nom de l'espace principal";
		self::$trad["AGORA_limite_espace_disque"]="Espace disque pour les fichiers";
		self::$trad["AGORA_logsTimeOut"]="Durée de conservation des Logs d'événements";
		self::$trad["AGORA_skin"]="Couleur de l'interface";
		self::$trad["AGORA_noir"]="Noir";
		self::$trad["AGORA_blanc"]="Blanc";
		self::$trad["AGORA_erreur_wallpaper_logo"]="L'image de fond d'écran et le logo doivent être au format .jpg ou .png";
		self::$trad["AGORA_suppr_wallpaper"]="Supprimer le fond d'écran ?";
		self::$trad["AGORA_logo_footer"]="Logo en bas de page";
		self::$trad["AGORA_logo_footer_url"]="URL";
		self::$trad["AGORA_messengerDisabled"]="Messagerie instantanée activée";
		self::$trad["AGORA_personalCalendarsDisabled"]="Agendas personnels activés par défaut";
		self::$trad["AGORA_personalCalendarsDisabled_infos"]="Ajouter par défaut un agenda personnel à la création d'un utilisateur. L'agenda pourra toutefois être désactivé par la suite, en modifiant le compte de l'utilisateur.";
		self::$trad["AGORA_moduleLabelDisplay"]="Nom des modules dans le menu principal";
		self::$trad["AGORA_moduleLabelDisplay_masquer"]="masquer";
		self::$trad["AGORA_moduleLabelDisplay_icones"]="au dessus de chaque icône de module";
		self::$trad["AGORA_moduleLabelDisplay_page"]="uniquement pour le module courant";
		self::$trad["AGORA_personsSort"]="Trier les utilisateurs et contacts par";
		self::$trad["AGORA_versions"]="Versions";
		self::$trad["AGORA_version_agora_maj"]="mis à jour";
		self::$trad["AGORA_fonction_mail_desactive"]="Fonction PHP pour envoyer des e-mails : désactivée !";
		self::$trad["AGORA_fonction_mail_infos"]="Certains hébergeurs désactivent la fonction PHP d'envoi de mails pour des raisons de sécurité ou de saturation des serveurs (SPAM)";
		self::$trad["AGORA_fonction_image_desactive"]="Fonction de manipulation d'images et de vignettes (PHP GD2) : désactivée !";

		////	MODULE_LOG
		////
		self::$trad["LOG_headerModuleName"]="Logs";
		self::$trad["LOG_description_module"]="Logs - Journal des événements";
		self::$trad["LOG_filtre"]="Filtre";
		self::$trad["LOG_date_heure"]="Date/Heure";
		self::$trad["LOG_espace"]="Espace";
		self::$trad["LOG_module"]="Module";
		self::$trad["LOG_objectType"]="type d'objet";
		self::$trad["LOG_action"]="Action";
		self::$trad["LOG_utilisateur"]="Utilisateur";
		self::$trad["LOG_ipAdress"]="IP";
		self::$trad["LOG_comment"]="Commentaire";
		self::$trad["LOG_no_logs"]="Aucun log";
		self::$trad["LOG_filtre_a_partir"]="filtré à partir des";
		self::$trad["LOG_chercher"]="Chercher";
		self::$trad["LOG_connexion"]="connexion";
		self::$trad["LOG_deconnexion"]="déconnexion";
		self::$trad["LOG_consult"]="consultation";
		self::$trad["LOG_consult2"]="telechargement";
		self::$trad["LOG_add"]="ajout";
		self::$trad["LOG_delete"]="suppression";
		self::$trad["LOG_modif"]="modification";

		////	MODULE_ESPACE
		////
		// Menu principal
		self::$trad["SPACE_headerModuleName"]="Espaces";
		self::$trad["SPACE_description_module"]="Espaces du site";
		self::$trad["SPACE_description_module_infos"]="Le site (ou espace principal) peut être subdivisé en plusieurs espaces";
		// Header_menu.inc.php
		self::$trad["SPACE_gerer_espaces"]="Gérer les espaces du site";
		self::$trad["SPACE_parametrage"]="Paramétrage de l'espace";
		self::$trad["SPACE_parametrage_infos"]="Paramétrage de l'espace (description, modules, utilisateurs, etc)";
		// Index.php
		self::$trad["SPACE_confirm_suppr_espace"]="Confirmer la suppression ? Attention, les données affectées uniquement à cet espace seront définitivement perdues !!";
		self::$trad["SPACE_espace"]="espace";
		self::$trad["SPACE_espaces"]="espaces";
		self::$trad["SPACE_definir_acces"]="A définir !";
		self::$trad["SPACE_modules"]="Modules";
		self::$trad["SPACE_ajouter_espace"]="Ajouter un espace";
		self::$trad["SPACE_supprimer_espace"]="Supprimer l'espace?";
		self::$trad["MSG_NOTIF_suppr_espace_impossible"]="Vous ne pouvez pas supprimer l'espace courant";
		// Espace_edit.php
		self::$trad["SPACE_gestion_acces"]="Utilisateurs affectés à l'espace";
		self::$trad["SPACE_selectionner_module"]="Vous devez sélectionner au moins un module";
		self::$trad["SPACE_modules_espace"]="Modules de l'espace";
		self::$trad["SPACE_modules_rank"]="Déplacer pour définir l'ordre d'affichage des modules";
		self::$trad["SPACE_espace_public"]="Espace public";
		self::$trad["SPACE_public_infos"]="Donne accès aux personnes qui n'ont pas de compte sur le site : ''invités''. Possibilité de spécifier un mot de passe pour protéger l'accès.";
		self::$trad["SPACE_usersInvitation"]="Les utilisateurs peuvent envoyer des invitations par mail";
		self::$trad["SPACE_usersInvitation_infos"]="Tous les utilisateurs peuvent envoyer des invitations par mail pour rejoindre l'espace";
		self::$trad["SPACE_allUsers"]="Tous les utilisateurs du site";
		self::$trad["SPACE_utilisation"]=" Utilisateur";
		self::$trad["SPACE_utilisation_info"]="Accès normal à l'espace";
		self::$trad["SPACE_administration"]="Administrateur";
		self::$trad["SPACE_administration_info"]="Administrateur de l'espace : Accès en écriture à tous les éléments de l'espace + envoi d'invitations par mail + création d'utilisateurs sur l'espace";
		self::$trad["SPACE_creer_agenda_espace"]="Créer un agenda partagé";
		self::$trad["SPACE_creer_agenda_espace_info"]="L'agenda portera le même nom que l'espace<br>(option utile si les agendas des utilisateurs sont désactivés)";

		////	MODULE_UTILISATEUR
		////
		// Menu principal
		self::$trad["USER_headerModuleName"]="Utilisateurs";
		self::$trad["USER_description_module"]="Utilisateurs de l'espace";
		self::$trad["USER_ajout_utilisateurs_groupe"]="Tous les utilisateurs peuvent créer des groupes";
		// Index.php
		self::$trad["USER_utilisateurs_site"]="Tous les utilisateurs";
		self::$trad["USER_gerer_utilisateurs_site"]="Gérer tous les utilisateurs";
		self::$trad["USER_utilisateurs_site_infos"]="Tous les utilisateurs :<br>Ensemble des utilisateurs du site, tous espaces confondus";
		self::$trad["USER_utilisateurs_espace"]="Utilisateurs de l'espace";
		self::$trad["USER_confirm_desaffecter_utilisateur"]="Confirmer la désaffectation de l'utilisateur à l'espace courant ?";
		self::$trad["USER_suppr_definitivement"]="Supprimer définitivement";
		self::$trad["USER_desaffecter"]="Désaffecter de l'espace";
		self::$trad["USER_tous_user_affecte_espace"]="Tous les utilisateurs du site sont affectés à cet espace : pas de désaffectation possible";
		self::$trad["USER_user"]="Utilisateur";
		self::$trad["USER_users"]="utilisateurs";
		self::$trad["USER_affecter_utilisateur"]="Ajouter un utilis. existant à l'espace";
		self::$trad["USER_ajouter_utilisateur"]="Ajouter un utilisateur";
		self::$trad["USER_ajouter_utilisateur_site"]="Créer un utilisateur sur le site : affecté par défaut à aucun espace !";
		self::$trad["USER_ajouter_utilisateur_espace"]="Créer un utilisateur pour l'espace courant";
		self::$trad["USER_envoi_coordonnees"]="Envoyer login et mot de passe";
		self::$trad["USER_envoi_coordonnees_info"]="Renvoyer par mail à des utilisateurs<br>leur identifiant et <u>un nouveau mot de passe</u>";
		self::$trad["USER_envoi_coordonnees_info2"]="Envoyer à chaque nouvel utilisateur<br>un mail avec ses coordonnées de connexion.";
		self::$trad["USER_envoi_coordonnees_confirm"]="Attention : les mots de passe seront réinitialisés ! confirmer tout de même ?";
		self::$trad["USER_mail_coordonnees"]="Vos coordonnées de connexion à votre espace";
		self::$trad["USER_aucun_utilisateur"]="Aucun utilisateur affecté à cet espace pour le moment";
		self::$trad["USER_lastConnection"]="Dernière connexion";
		self::$trad["USER_liste_espaces"]="Espaces de l'utilisateur";
		self::$trad["USER_aucun_espace"]="aucun espace";
		self::$trad["USER_adminGeneral"]="Administrateur général";
		self::$trad["USER_adminSpace"]="Administrateur de l'espace";
		self::$trad["USER_userSpace"]="Utilisateur de l'espace";
		self::$trad["USER_pas_connecte"]="Pas encore connecté";
		self::$trad["USER_modifier"]="Modifier l'utilisateur";
		self::$trad["USER_modifier_mon_profil"]="Modifier mon profil";
		// Invitations
		self::$trad["USER_envoi_invitation"]="Inviter quelqu'un à rejoindre l'espace";
		self::$trad["USER_envoi_invitation_info"]="L'invitation sera envoyé par mail";
		self::$trad["USER_objet_mail_invitation"]="Invitation de "; // ..Jean DUPOND
		self::$trad["USER_admin_guest_espace"]="vous invite sur "; // Jean DUPOND "vous invite à rejoindre l'espace" Mon Espace
		self::$trad["USER_confirmer_invitation"]="Cliquer ici pour confirmer l'invitation";
		self::$trad["USER_invitation_a_confirmer"]="Invitation(s) en attente de confirmation";
		self::$trad["USER_idInvitation_expire"]="Le lien de votre invitation a expiré...";
		self::$trad["USER_invitationConfirmPassword"]="Choisissez votre mot de passe puis cliquez sur 'OK' pour confirmer votre invitation";
		self::$trad["USER_invitation_valide"]="Votre invitation a été validée !";
		// groupes.php
		self::$trad["USER_groupe_espace"]="groupes d'utilisateur de l'espace";
		self::$trad["USER_groupe_info"]="editer les groupes d'utilisateurs";
		self::$trad["USER_droit_gestion_groupes"]="Chaque groupe peut être modifié par son auteur ou par l'administrateur général";
		// Utilisateur_affecter.php
		self::$trad["USER_preciser_recherche"]="Merci de préciser un nom, un prénom ou une adresse e-mail";
		self::$trad["USER_affecter_user_confirm"]="Confirmer les affectations ?";
		self::$trad["USER_rechercher_user"]="Rechercher des utilisateurs pour les ajouter à l'espace";
		self::$trad["USER_tous_users_affectes"]="Tous les utilisateurs du site sont affectés à cet espace";
		self::$trad["USER_affecter_user"]="Affecter des utilisateurs à l'espace :";
		self::$trad["USER_aucun_users_recherche"]="Aucun utilisateur pour cette recherche";
		// Utilisateur_edit.php  & CO
		self::$trad["USER_specifyName"]="Merci de spécifier un nom";
		self::$trad["USER_specifyFirstName"]="Merci de spécifier un prénom";
		self::$trad["USER_specifier_identifiant"]="Merci de spécifier un identifiant";
		self::$trad["USER_specifier_password"]="Merci de spécifier un mot de passe";
		self::$trad["USER_specifyMailAsLogin"]="Il est recommandé d'utiliser une adresse mail comme identifiant de connexion. Valider tout de même le formulaire?";
		self::$trad["USER_langs"]="Langue";
		self::$trad["USER_agenda_perso_desactive"]="Agenda personnel désactivé";
		self::$trad["USER_agenda_perso_desactive_infos"]="Par défaut, l'agenda personnel reste toujours accessible à l'utilisateur, même si le module Agenda est désactivé sur l'espace";
		self::$trad["USER_connectionSpace"]="Espace affiché à la connexion";
		self::$trad["USER_notification_mail"]="Envoyer une notification de création par e-mail";
		self::$trad["USER_alert_notification_mail"]="Pensez à spécifier une adresse e-mail !";
		self::$trad["USER_adresses_ip"]="Adresses IP de contrôle";
		self::$trad["USER_info_ipAdress"]="Si vous spécifiez une adresse IP (ou+) :<br>l'utilisateur ne pourra se connecter que depuis celle-ci";
		self::$trad["USER_identifiant_deja_present"]="L'identifiant spécifié existe déjà. Merci d'en spécifier un autre";
		self::$trad["USER_mail_deja_present"]="L'adresse e-mail existe déjà. Merci d'en spécifier une autre";
		self::$trad["USER_mail_objet_nouvel_utilisateur"]="Bienvenue sur ";  //.."mon-espace"
		self::$trad["USER_mail_nouvel_utilisateur"]="Votre compte utilisateur vient d'être créé sur";  //.."mon-espace"
		self::$trad["USER_mail_infos_connexion"]="Connectez-vous avec les coordonnées suivantes";
		self::$trad["USER_mail_infos_connexion2"]="Merci de conserver cet e-mail dans vos archives.";
		// Utilisateur_Messenger.php
		self::$trad["USER_gestion_messenger_livecounter"]="Gérer la messagerie instantanée";
		self::$trad["USER_visibilite_messenger_livecounter"]="Utilisateurs qui me voient en ligne <br>et avec qui je peux discuter sur la messagerie instantanée";
		self::$trad["USER_aucun_utilisateur_messenger"]="Aucun utilisateur pour l'instant";
		self::$trad["USER_voir_aucun_utilisateur"]="Messagerie désactivée (aucun utilisateur ne peut me voir)";
		self::$trad["USER_voir_tous_utilisateur"]="Tous les utilisateurs peuvent me voir";
		self::$trad["USER_voir_certains_utilisateur"]="Seuls certains utilisateurs peuvent me voir";

		////	MODULE_TABLEAU BORD
		////
		// Menu principal + options du module
		self::$trad["DASHBOARD_headerModuleName"]="Actualités";
		self::$trad["DASHBOARD_description_module"]="Actualités & nouveaux éléments";
		self::$trad["DASHBOARD_ajout_actualite_admin"]="Seul l'administrateur peut ajouter des actualités";
		// Index.php
		self::$trad["DASHBOARD_new_elems"]="nouveautés";
		self::$trad["DASHBOARD_new_elems_info"]="Eléments créés entre <br>";
		self::$trad["DASHBOARD_new_elems_realises"]="elems. courants";
		self::$trad["DASHBOARD_new_elems_realises_info"]="Evenements et tâches ayant lieu entre <br>";
		self::$trad["DASHBOARD_plugin_connexion"]="depuis ma dernière connexion";
		self::$trad["DASHBOARD_plugin_jour"]="d'aujourd'hui";
		self::$trad["DASHBOARD_plugin_semaine"]="de la semaine";
		self::$trad["DASHBOARD_plugin_mois"]="du mois";
		self::$trad["DASHBOARD_autre_periode"]="Autre période";
		self::$trad["DASHBOARD_pas_nouveaux_elements"]="Aucun élément sur la période";
		self::$trad["DASHBOARD_actualites"]="Actualités";
		self::$trad["DASHBOARD_actualite"]="actualité";
		self::$trad["DASHBOARD_actualites"]="actualités";
		self::$trad["DASHBOARD_ajout_actualite"]="Ajouter une actualité";
		self::$trad["DASHBOARD_actualites_offline"]="Actualités hors ligne";
		self::$trad["DASHBOARD_pas_actualites"]="Aucune actualité";
		// Actualite_edit.php
		self::$trad["DASHBOARD_ala_une"]="Afficher à la une";
		self::$trad["DASHBOARD_ala_une_info"]="Afficher cette actualité en premier";
		self::$trad["DASHBOARD_offline"]="Hors ligne";
		self::$trad["DASHBOARD_offline_info"]="Actualité hors ligne";
		self::$trad["DASHBOARD_dateOnline"]="Date de mise en ligne";
		self::$trad["DASHBOARD_dateOnline_info"]="Définir une date de mise en ligne automatique.<br>Dans cette attente, l'actualité sera mise hors ligne";
		self::$trad["DASHBOARD_dateOnline_alerte"]="L'actualité est hors ligne dans l'attente de sa mise en ligne automatique";
		self::$trad["DASHBOARD_dateOffline"]="Date de mise hors ligne";
		self::$trad["DASHBOARD_dateOffline_info"]="Définir une date de mise hors ligne automatique";

		////	MODULE_AGENDA
		////
		// Menu principal
		self::$trad["CALENDAR_headerModuleName"]="Agendas";
		self::$trad["CALENDAR_description_module"]="Agendas personnel et agendas partagés";
		self::$trad["CALENDAR_ajout_agenda_ressource_admin"]="Seul l'administrateur peut ajouter des agendas de ressource";
		self::$trad["CALENDAR_ajout_categorie_admin"]="Seul l'administrateur peut ajouter des categories d'événement";
		// Index.php
		self::$trad["CALENDAR_afficher_tous_agendas"]="Afficher tous les agendas";
		self::$trad["CALENDAR_masquer_tous_agendas"]="Masquer tous les agendas";
		self::$trad["CALENDAR_cocher_tous_agendas"]="Cocher / décocher tous les agendas";
		self::$trad["CALENDAR_cocher_agendas_users"]="Cocher / décocher les utilisateurs";
		self::$trad["CALENDAR_cocher_agendas_ressources"]="Cocher / décocher les ressources";
		self::$trad["CALENDAR_imprimer_agendas"]="Imprimer l'agenda";
		self::$trad["CALENDAR_imprimer_agendas_infos"]="Imprimez la page en mode paysage";
		self::$trad["CALENDAR_ajouter_agenda_ressource"]="Ajouter un agenda partagé";
		self::$trad["CALENDAR_ajouter_agenda_ressource_bis"]="Ajouter un agenda partagé :<br>pour les réservation d'une salle, véhicule, vidéoprojecteur, etc.";
		self::$trad["CALENDAR_exporter_ical"]="Exporter les événements (format iCal)";
		self::$trad["CALENDAR_exporter_ical_mail"]="Envoyer les événements par mail (iCal)";
		self::$trad["CALENDAR_exporter_ical_mail2"]="Pour les intégrer dans un calendrier IPHONE, ANDROID, OUTLOOK, GOOGLE CALENDAR...";
		self::$trad["CALENDAR_importer_ical"]="Importer les événements (iCal)";
		self::$trad["CALENDAR_importer_ical_etat"]="Etat";
		self::$trad["CALENDAR_importer_ical_deja_present"]="Déjà présent";
		self::$trad["CALENDAR_importer_ical_a_importer"]="A importer";
		self::$trad["CALENDAR_suppr_anciens_evt"]="Supprimer les événements passés";
		self::$trad["CALENDAR_suppr_anciens_evt_info"]="Supprimer les événements qui précèdent la date d'aujourd'hui (uniquement pour cet agenda et en exclusion des événements périodiques non terminés)";
		self::$trad["CALENDAR_confirm_suppr_anciens_evt"]="Êtes-vous sûr de vouloir supprimer définitivement les événements qui précèdent la date d'aujourd'hui ?";
		self::$trad["CALENDAR_ajouter_evt_heure"]="Ajouter un événement à";
		self::$trad["CALENDAR_ajouter_evt_jour"]="Ajouter un événement à cette date";
		self::$trad["CALENDAR_evt_jour"]="Jour";
		self::$trad["CALENDAR_evt_semaine"]="Semaine";
		self::$trad["CALENDAR_evt_semaine_w"]="Semaine de travail";
		self::$trad["CALENDAR_evt_mois"]="Mois";
		self::$trad["CALENDAR_voir_num_semaine"]="Voir la semaine n°"; //...5
		self::$trad["CALENDAR_periode_suivante"]="Période suivante";
		self::$trad["CALENDAR_periode_precedante"]="Période précédente";
		self::$trad["CALENDAR_affectations_evt"]="Evenement dans les agendas :";
		self::$trad["CALENDAR_affectationUnconfirmed"]="Attente de confirmation pour les agendas : ";
		self::$trad["CALENDAR_evenements_proposes_pour_agenda"]="Proposé pour "; // "Videoprojecteur" / "salle de réunion" / etc.
		self::$trad["CALENDAR_evenements_proposes_mon_agenda"]="Proposé pour mon agenda perso";
		self::$trad["CALENDAR_evenement_propose_par"]="Proposé par";  // "Proposé par" M. Bidule
		self::$trad["CALENDAR_evenement_integrer"]="Intégrer l'événement à l'agenda ?";
		self::$trad["CALENDAR_evenement_pas_integrer"]="Supprimer la proposition d'événement ?";
		self::$trad["CALENDAR_supprimer_evt_agenda"]="Supprimer dans cet agenda?";
		self::$trad["CALENDAR_supprimer_evt_agendas"]="Supprimer dans tous les agendas?";
		self::$trad["CALENDAR_supprimer_evt_date"]="Supprimer uniquement à cette date?";
		self::$trad["CALENDAR_evt_prive"]="Évènement privé";
		self::$trad["CALENDAR_evt_proprio"]="Événements que j'ai créés";
		self::$trad["CALENDAR_aucun_evt"]="Aucun événement";
		self::$trad["CALENDAR_proposer"]="Proposer l'événement";
		self::$trad["CALENDAR_synthese"]="Synthèse des agendas";
		self::$trad["CALENDAR_pourcent_agendas_occupes"]="Agendas occupés";  // Agendas occupés : 2/5
		self::$trad["CALENDAR_aucun_agenda_visible"]="Aucun agenda affiché";
		// Evenement.php
		self::$trad["CALENDAR_categorie"]="Catégorie";
		self::$trad["CALENDAR_visibilite"]="Visibilité";
		self::$trad["CALENDAR_visibilite_public"]="public";
		self::$trad["CALENDAR_visibilite_public_cache"]="public, mais détails masqués";
		self::$trad["CALENDAR_visibilite_prive"]="privé";
		// Agenda_edit.php
		self::$trad["CALENDAR_affichage_evt"]="Affichage des événements";
		self::$trad["CALENDAR_affichage_evt_border"]="Bordure de la même couleur que la catégorie";
		self::$trad["CALENDAR_affichage_evt_background"]="Fond de la même couleur que la catégorie";
		self::$trad["CALENDAR_timeSlot"]="Plage horaire";
		// Evenement_edit.php
		self::$trad["CALENDAR_periodicite"]="Périodique";
		self::$trad["CALENDAR_period_weekDay"]="Toutes les semaines";
		self::$trad["CALENDAR_period_monthDay"]="Jours du mois";
		self::$trad["CALENDAR_period_month"]="Tous les mois";
		self::$trad["CALENDAR_period_month_xdumois"]="du mois"; // Le 21 du mois
		self::$trad["CALENDAR_period_year"]="Tous les ans";
		self::$trad["CALENDAR_periodDateEnd"]="Fin de périodicité";
		self::$trad["CALENDAR_exception_periodicite"]="Exception de périodicité";
		self::$trad["CALENDAR_agendas_affectations"]="Affectation aux agendas";
		self::$trad["CALENDAR_verif_nb_agendas"]="Merci de sélectionner au moins un agenda";
		self::$trad["CALENDAR_input_proposer"]="Proposer l'événement au propriétaire de l'agenda";
		self::$trad["CALENDAR_input_affecter"]="Ajouter l'événement à l'agenda";
		self::$trad["CALENDAR_info_proposer"]="Proposition seulement (vous n'avez pas accès en écriture à cet agenda)";
		self::$trad["CALENDAR_info_pas_modif"]="Modification non autorisé :<br> vous n'avez pas accès en écriture à cet agenda";
		self::$trad["CALENDAR_visibilite_info"]="<u>Public</u> : visibilité normale.<br><br><u>Public, mais détails masqués</u> : l'accès en lecture seule à l'agenda n'affichera que la plage horaire de l'événement.<br><br><u>Privé</u> : visible uniquement si l'on peut éditer l'événement.";
		self::$trad["CALENDAR_edit_limite"]="Vous n'êtes pas l'auteur de l'événement :<br>vous pouvez uniquement gérer les affectations à vos agendas";
		self::$trad["CALENDAR_creneau_occupe"]="Le créneau horaire est occupé ce jour là sur l'agenda(s) :";
		// Categories.php
		self::$trad["CALENDAR_gerer_categories"]="Gérer les catégories d'événements";
		self::$trad["CALENDAR_categories_evt"]="Catégories d'événement";
		self::$trad["CALENDAR_droit_gestion_categories"]="Chaque categorie peut être modifié par son auteur ou par l'administrateur général";

		////	MODULE_FICHIER
		////
		// Menu principal
		self::$trad["FILE_headerModuleName"]="Fichiers";
		self::$trad["FILE_description_module"]="Gestionnaire de fichiers";
		self::$trad["FILE_AdminRootFolderAddContent"]="Seul l'administrateur peut ajouter des dossiers et fichiers à la racine";
		// Index.php
		self::$trad["FILE_ajouter_fichier"]="Ajouter des fichiers";
		self::$trad["FILE_ajouter_fichier_alert"]="Dossier du serveur inaccessible en écriture!  merci de contacter l'administrateur";
		self::$trad["FILE_telecharger_selection"]="télécharger la sélection";
		self::$trad["FILE_nb_versions_fichier"]="versions du fichier"; // n versions du fichier
		self::$trad["FILE_ajouter_versions_fichier"]="Ajouter nouvelle version du fichier";
		self::$trad["FILE_aucun_fichier"]="Aucun fichier pour le moment";
		// fichier_edit_ajouter.php  &  Fichier_edit.php
		self::$trad["FILE_limite_chaque_fichier"]="Les fichiers ne doivent pas dépasser"; // ...2 Mega Octets
		self::$trad["FILE_optimiser_images"]="Limiter la taille à "; // ..1024 pixels
		self::$trad["FILE_updatedName"]="Le nom du fichier sera remplacé par celui de la nouvelle version";
		self::$trad["FILE_erreur_taille_fichier"]="Fichier trop volumineux";
		self::$trad["FILE_ajout_multiple_info"]="Touche Ctrl pour sélectionner plusieurs fichiers";
		self::$trad["FILE_selectionner_fichier"]="Merci de sélectionner au moins un fichier";
		self::$trad["FILE_contenu"]="contenu";
		// Versions_fichier.php
		self::$trad["FILE_versions_de"]="Versions de"; // versions de fichier.gif
		self::$trad["FILE_confirmer_suppression_version"]="Confirmer la suppression de cette version ?";

		////	MODULE_FORUM
		////
		// Menu principal
		self::$trad["FORUM_headerModuleName"]="Forum";
		self::$trad["FORUM_description_module"]="Forum";
		self::$trad["FORUM_ajout_sujet_admin"]="Seul l'administrateur peut ajouter des sujets";
		self::$trad["FORUM_ajout_sujet_theme"]="Tous les utilisateurs peuvent ajouter des thèmes";
		// TRI
		self::$trad["SORT_dateLastMessage"]="dernier message";
		// Index.php & Sujet.php
		self::$trad["FORUM_sujet"]="sujet";
		self::$trad["FORUM_sujets"]="sujets";
		self::$trad["FORUM_message"]="message";
		self::$trad["FORUM_messages"]="messages";
		self::$trad["FORUM_dernier_message"]="dernier de";
		self::$trad["FORUM_ajouter_sujet"]="Ajouter un sujet";
		self::$trad["FORUM_voir_sujet"]="Voir le sujet";
		self::$trad["FORUM_ajouter_message"]="Ajouter un message";
		self::$trad["FORUM_repondre"]="Répondre";
		self::$trad["FORUM_citer"]="Citer";
		self::$trad["FORUM_message_repondre"]="Répondre à ce message";
		self::$trad["FORUM_message_citer"]="Citer ce message";
		self::$trad["FORUM_aucun_sujet"]="Pas de sujet pour le moment";
		self::$trad["FORUM_aucun_message"]="Aucun message pour le moment";
		self::$trad["FORUM_confirme_suppr_message"]="Confirmer la suppression du message et des messages associées?";
		self::$trad["FORUM_notifier_dernier_message"]="Me notifier par mail";
		self::$trad["FORUM_notifier_dernier_message_info"]="Me prévenir par mail à chaque nouveau message créé par un autre utilisateur";
		// Sujet_edit.php  &  Message_edit.php
		self::$trad["FORUM_accessRightInfos"]="Pour participer à la discussion, il faut au minimum un accès en ''ecriture limitée''";
		self::$trad["FORUM_theme_espaces"]="Le thème sélectionné est uniquement accessible aux espaces";
		// Themes
		self::$trad["FORUM_theme_sujet"]="Thème";
		self::$trad["FORUM_accueil_forum"]="Accueil du forum";
		self::$trad["FORUM_sans_theme"]="Sans thème";
		self::$trad["FORUM_themes_gestion"]="Editer les thèmes de sujet";
		self::$trad["FORUM_droit_gestion_themes"]="Chaque theme peut être modifié par son auteur ou par l'administrateur général";

		////	MODULE_TACHE
		////
		// Menu principal
		self::$trad["TASK_headerModuleName"]="Tâches";
		self::$trad["TASK_description_module"]="Tâches";
		self::$trad["TASK_AdminRootFolderAddContent"]="Seul l'administrateur peut ajouter des dossiers et tâches à la racine";
		// TRI
		self::$trad["SORT_priority"]="Priorité";
		self::$trad["SORT_advancement"]="Avancement";
		self::$trad["SORT_dateBegin"]="Date de debut";
		self::$trad["SORT_dateEnd"]="Date de fin";
		// Index.php
		self::$trad["TASK_ajouter_tache"]="Ajouter une tâche";
		self::$trad["TASK_aucune_tache"]="Aucune tâche pour le moment";
		self::$trad["TASK_advancement"]="Avancement";
		self::$trad["TASK_advancementAverage"]="Avancement moyen";
		self::$trad["TASK_priority"]="Priorité";
		self::$trad["TASK_priority1"]="Basse";
		self::$trad["TASK_priority2"]="Moyenne";
		self::$trad["TASK_priority3"]="Haute";
		self::$trad["TASK_priority4"]="Critique";
		self::$trad["TASK_responsiblePersons"]="Responsables";
		self::$trad["TASK_budgetAvailable"]="Budget disponible";
		self::$trad["TASK_budgetAvailable_total"]="Budget total disponible";
		self::$trad["TASK_budgetEngaged"]="Budget engagé";
		self::$trad["TASK_budgetEngaged_total"]="Budget total engagé";
		self::$trad["TASK_humanDayCharge"]="Charge jours/homme";
		self::$trad["TASK_humanDayCharge_info"]="Nombre de jours de travail necessaires à une seule personne pour accomplir cette tâche";
		self::$trad["TASK_advancement_retard"]="Avancement en retard";
		self::$trad["TASK_budgetExceeded"]="Budget dépassé";

		////	MODULE_CONTACT
		////
		// Menu principal
		self::$trad["CONTACT_headerModuleName"]="Contacts";
		self::$trad["CONTACT_description_module"]="Annuaire de contacts";
		self::$trad["CONTACT_AdminRootFolderAddContent"]="Seul l'administrateur peut ajouter des dossiers et contacts à la racine";
		// Index.php
		self::$trad["CONTACT_ajouter_contact"]="Ajouter un contact";
		self::$trad["CONTACT_aucun_contact"]="Aucun contact pour le moment";
		self::$trad["CONTACT_creer_user"]="Créer un utilisateur sur cet espace";
		self::$trad["CONTACT_creer_user_infos"]="Créer un utilisateur sur cet espace à partir de ce contact ?";
		self::$trad["CONTACT_creer_user_confirm"]="L'utilisateur a été créé";

		////	MODULE_LIEN
		////
		// Menu principal
		self::$trad["LINK_headerModuleName"]="Favoris";
		self::$trad["LINK_description_module"]="Favoris";
		self::$trad["LINK_AdminRootFolderAddContent"]="Seul l'administrateur peut ajouter des dossiers et liens à la racine";
		// Index.php
		self::$trad["LINK_ajouter_lien"]="Ajouter un lien";
		self::$trad["LINK_aucun_lien"]="Aucun lien pour le moment";
		// lien_edit.php & dossier_edit.php
		self::$trad["LINK_adress"]="Adresse web";

		////	MODULE_MAIL
		////
		// Menu principal
		self::$trad["MAIL_headerModuleName"]="Mailing";
		self::$trad["MAIL_description_module"]="Envoyer en toute simplicité des e-mails à vos collaborateurs, amis ou contacts pour informer des dernières actualités";
		// Index.php
		self::$trad["MAIL_specifier_mail"]="Merci de spécifier au moins un destinataire";
		self::$trad["MAIL_title"]="Titre de l'e-mail";
		self::$trad["MAIL_fichier_joint"]="Fichier joint";
		// Historique Mail
		self::$trad["MAIL_historique_mail"]="Historique des e-mails envoyés";
		self::$trad["MAIL_aucun_mail"]="Aucun e-mail";
		self::$trad["MAIL_envoye_par"]="E-mail envoyé par";
		self::$trad["MAIL_recipients"]="Destinataires";
	}

	/*
	 * Jours Fériés de l'année (sur quatre chiffre)
	 */
	public static function celebrationDays($year)
	{
		// Init
		$dateList=array();

		//Fêtes mobiles (si la fonction de récup' de paques existe)
		if(function_exists("easter_date"))
		{
			$daySecondes=86400;
			$paquesTime=easter_date($year);
			$date=date("Y-m-d", $paquesTime+$daySecondes);
			$dateList[$date]="Lundi de pâques";
			$date=date("Y-m-d", $paquesTime+($daySecondes*39));
			$dateList[$date]="Jeudi de l'ascension";
			$date=date("Y-m-d", $paquesTime+($daySecondes*50));
			$dateList[$date]="Lundi de pentecôte";
		}

		//Fêtes fixes
		$dateList[$year."-01-01"]="Jour de l'an";
		$dateList[$year."-05-01"]="Fête du travail";
		$dateList[$year."-05-08"]="Armistice 39-45";
		$dateList[$year."-07-14"]="Fête nationale";
		$dateList[$year."-08-15"]="Assomption";
		$dateList[$year."-11-01"]="Toussaint";
		$dateList[$year."-11-11"]="Armistice 14-18";
		$dateList[$year."-12-25"]="Noël";

		//Retourne le résultat
		return $dateList;
	}
}
