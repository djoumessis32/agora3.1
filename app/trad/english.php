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
		////	Header http / Editeurs Tinymce,DatePicker,etc / Dates formatées par PHP
		self::$trad["CURLANG"]="en";
		self::$trad["HEADER_HTTP"]="en";
		self::$trad["DATEPICKER"]="en";
		self::$trad["HTML_EDITOR"]="en_GB";
		self::$trad["UPLOADER"]="en";
		setlocale(LC_TIME, "en_US.utf8", "en_US.UTF-8", "en_US", "en", "english");

		////	Divers
		self::$trad["OK"]="OK";
		self::$trad["remplir_tous_champs"]="Thank you to specify all the fields";
		self::$trad["elem_inaccessible"]="Inaccessible Element";
		self::$trad["champs_obligatoire"]="Required Fields";
		self::$trad["oui"]="yes";
		self::$trad["non"]="no";
		self::$trad["aucun"]="no";
		self::$trad["et"]="and";
		self::$trad["aller_page"]="Go to the page";
		self::$trad["alphabet_filtre"]="Alphabetical Filter";
		self::$trad["tout_afficher"]="Display all";
		self::$trad["important"]="Important";
		self::$trad["afficher"]="display";
		self::$trad["masquer"]="mask";
		self::$trad["deplacer"]="move";
		self::$trad["garder"]="Keep";
		self::$trad["par_defaut"]="By default";
		self::$trad["localiser_carte"]="Localize on a map";
		self::$trad["bienvenue_agora"]="Welcome on the Agora!";
		self::$trad["mail_pas_valide"]="The email is not valid";
		self::$trad["element"]="element";
		self::$trad["elements"]="elements";
		self::$trad["dossier"]="folder";
		self::$trad["dossiers"]="folders";
		self::$trad["dossier_parent"]="parent folder";
		self::$trad["fermer"]="Close";
		self::$trad["visible_espaces"]="Spaces where it will be visible";
		self::$trad["visible_ts_espaces"]="Visible on all spaces";
		self::$trad["confirmCloseLightbox"]="Would you want to close the form?";
		
		////	Menu/Menu contextuel
		self::$trad["menu"]="menu";
		self::$trad["objNew"]="New element";
		self::$trad["acces_perso"]="Personal access";

		////	images
		self::$trad["picture"]="Picture";
		self::$trad["wallpaper"]="wallpaper";
		self::$trad["image_changer"]="change";
		self::$trad["pixels"]="pixels";
		
		////	Connexion
		self::$trad["specifierLoginPassword"]="Thank you to specify an identifier and a password";
		self::$trad["login"]="Login";
		self::$trad["login2"]="Login";
		self::$trad["placeholderLogin"]="Email / Login";
		self::$trad["password"]="Password";
		self::$trad["passwordToModify"]="Password (to change)";
		self::$trad["passwordVerif"]="Confirm password";
		self::$trad["passwordInfo"]="Leave blank if you want to keep your password";
		self::$trad["passwordVerifError"]="Your password confirmation is not valid";
		self::$trad["connexion"]="Connection";
		self::$trad["connexion_auto"]="stay connected";
		self::$trad["connexion_auto_info"]="Retain my login and password for an automatic connection";
		self::$trad["password_oublie"]="forgot password ?";
		self::$trad["password_oublie_info"]="Send my login and password to my email address (if specified)";
		self::$trad["acces_guest"]="Guest access";
		self::$trad["espace_password_erreur"]="Wrong password";
		self::$trad["version_ie"]="Your browser is too old and does not support all HTML standards : It is advisable to update it or use another browser";
		
		////	Affichage
		self::$trad["type_affichage"]="View";
		self::$trad["type_affichage_line"]="List";
		self::$trad["type_affichage_block"]="Block";
		self::$trad["type_affichage_arbo"]="Tree";
		
		////	Sélectionner / Déselectionner tous les éléments
		self::$trad["select"]="Select";
		self::$trad["select_deselect"]="Select / unselect";
		self::$trad["tout_selectionner"]="Select all";
		self::$trad["inverser_selection"]="Reverse the selection";
		self::$trad["suppr_elements"]="Remove the selected elements";
		self::$trad["deplacer_elements"]="Move in another folder";
		self::$trad["voir_sur_carte"]="Show on a map";
		self::$trad["selectionner_user"]="Thank you for selecting at least a user";
		self::$trad["selectionner_2users"]="Thank you for selecting at least 2 users";
		self::$trad["selectionner_espace"]="Thank you for selecting at least one space";
		
		////	Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
		self::$trad["de"]="of ";
		self::$trad["a"]="to";
		self::$trad["le"]="the";
		self::$trad["debut"]="Begin";
		self::$trad["fin"]="End";
		self::$trad["separateur_horaire"]=":";
		self::$trad["jours"]="days";
		self::$trad["jour_1"]="Monday";
		self::$trad["jour_2"]="Tuesday";
		self::$trad["jour_3"]="Wednesday";
		self::$trad["jour_4"]="Thursday";
		self::$trad["jour_5"]="Friday";
		self::$trad["jour_6"]="Saturday";
		self::$trad["jour_7"]="Sunday";
		self::$trad["mois_1"]="january";
		self::$trad["mois_2"]="february";
		self::$trad["mois_3"]="march";
		self::$trad["mois_4"]="april";
		self::$trad["mois_5"]="may";
		self::$trad["mois_6"]="june";
		self::$trad["mois_7"]="july";
		self::$trad["mois_8"]="august";
		self::$trad["mois_9"]="september";
		self::$trad["mois_10"]="october";
		self::$trad["mois_11"]="november";
		self::$trad["mois_12"]="december";
		self::$trad["aujourdhui"]="Today";
		self::$trad["aff_aujourdhui"]="Today";
		self::$trad["modif_dates_debutfin"]="The end date can't be before the start date";
		self::$trad["dates_mauvais_format"]="The date must be in the format dd/mm/YYYY";
		
		////	Nom & Description (pour les menus d'édition principalement)
		self::$trad["title"]="Title";
		self::$trad["name"]="Name";
		self::$trad["description"]="Description";
		self::$trad["specifyName"]="Thank you to specify a name";
		
		////	Validation des formulaires
		self::$trad["ajouter"]=" Add";
		self::$trad["modifier"]=" Modify";
		self::$trad["modifier_et_acces"]="Modify & define access";
		self::$trad["valider"]=" Validate";
		self::$trad["envoyer"]="Send";
		self::$trad["envoyer_a"]="Send to";
		
		////	Tri d'affichage. Tous les elements (dossier, tache, lien, etc...) ont par défaut une date, un auteur & une description
		self::$trad["trie_par"]="Sorted by";
		self::$trad["SORT_dateCrea"]="creation date";
		self::$trad["SORT_dateModif"]="change date";
		self::$trad["SORT_title"]="title";
		self::$trad["SORT_description"]="description";
		self::$trad["SORT__idUser"]="author";
		self::$trad["SORT_extension"]="type of file";
		self::$trad["SORT_octetSize"]="size";
		self::$trad["SORT_downloadsNb"]="downloads";
		self::$trad["SORT_civility"]="civility";
		self::$trad["SORT_name"]="name";
		self::$trad["SORT_firstName"]="first name";
		self::$trad["SORT_adress"]="adress";
		self::$trad["SORT_postalCode"]="zip code";
		self::$trad["SORT_city"]="city";
		self::$trad["SORT_country"]="country";
		self::$trad["SORT_function"]="function";
		self::$trad["SORT_companyOrganization"]="company / organization";
		self::$trad["tri_ascendant"]="Ascend";
		self::$trad["tri_descendant"]="Descend";
		
		////	Options de suppression
		self::$trad["confirmDelete"]="Confirm the delete ?";
		self::$trad["confirmDeleteBis"]="Are you sure ?!";
		self::$trad["confirmer_suppr_dossier"]="Caution ! certain sub-folders are not accessible for you : they will be deleted !";
		self::$trad["supprimer"]="Delete";
		
		////	Visibilité d'un Objet : auteur et droits d'accès
		self::$trad["auteur"]="Author";
		self::$trad["cree_par"]="Creation";
		self::$trad["modif_par"]="Changed";
		self::$trad["historique_element"]="History of the element";
		self::$trad["guest"]="guest";
		self::$trad["guests"]="guests";
		self::$trad["tous"]="all";
		self::$trad["inconnu"]="unknown person";
		self::$trad["lecture"]="read";
		self::$trad["lecture_infos"]="Access in reading";
		self::$trad["ecriture_limit"]="limited writing";
		self::$trad["ecriture_limit_infos"]="Limited access in writing: Ability to add elements (-ELEMENT-), without modify or delete those created by other users";
		self::$trad["ecriture"]="write";
		self::$trad["ecriture_infos"]="Access in writing";
		self::$trad["ecriture_infos_conteneur"]="Access in writing : Ability to add, modify or delete<br>all the elements (-ELEMENT-) of the -CONTENEUR-";
		self::$trad["ecriture_auteur_admin"]="Only the author and administrators can edit<br>the access rights or delete the -CONTENEUR-";
		self::$trad["contenu_dossier"]="content";
		
		////	Libellé des objets
		self::$trad["OBJECTcontainer"]="container";
		self::$trad["OBJECTelement"]="element";
		self::$trad["OBJECTfolder"]="folder";
		self::$trad["OBJECTdashboardNews"]="news";
		self::$trad["OBJECTfile"]="file";
		self::$trad["OBJECTcalendar"]="calendar";
		self::$trad["OBJECTcalendarEvent"]="event";
		self::$trad["OBJECTforumSubject"]="topic";
		self::$trad["OBJECTforumMessage"]="message";
		self::$trad["OBJECTcontact"]="contact";
		self::$trad["OBJECTlink"]="bookmark";
		self::$trad["OBJECTtask"]="task";
		self::$trad["OBJECTuser"]="user";
		
		////	Envoi d'un mail (nouvel utilisateur, notification de création d'objet, etc...)
		self::$trad["MAIL_noFooter"]="Do not sign the message";
		self::$trad["MAIL_noFooter_info"]="Do not sign the end of the message with the sender's name and a weblink to the space";
		self::$trad["MAIL_hideRecipients"]="Hide recipients";
		self::$trad["MAIL_hideRecipients_info"]="By default, email recipients are displayed in the message.";
		self::$trad["MAIL_receptionNotif"]="Delivery receipt";
		self::$trad["MAIL_receptionNotif_info"]="Warning! some email clients don't support delivery receipts";
		self::$trad["MAIL_envoye_par"]="Sent by";  // "Envoyé par" Mr trucmuche
		self::$trad["MAIL_envoye"]="The email was sent !";
		self::$trad["MAIL_envoye_notif"]="The notification email was sent !";
		self::$trad["MAIL_pas_envoye"]="The email could not be sent..."; // idem
		self::$trad["MAIL_element_cree"]="New element created by";//boby
		self::$trad["MAIL_element_modifie"]="Element modified by";//boby
		self::$trad["MAIL_element_sur_espace"]="on the space";//truc
		self::$trad["MAIL_element_acces_direct"]="Click here to access the element on your space";

		////	Dossier & fichier
		self::$trad["giga_octet"]="Gb";
		self::$trad["mega_octet"]="Mb";
		self::$trad["kilo_octet"]="Kb";
		self::$trad["dossier_racine"]="Root folder";
		self::$trad["rootFolderEditInfo"]="Open the the space settings<br> to change the access rights to the root folder";
		self::$trad["ajouter_dossier"]="add a folder";
		self::$trad["telecharger"]="Download file";
		self::$trad["telecharge_nb"]="Downloaded";
		self::$trad["telecharge_nb_bis"]="times"; // Téléchargé 'n' fois
		self::$trad["telecharger_dossier"]="Download the folder";
		self::$trad["espace_disque_utilise"]="Disk space used";
		self::$trad["espace_disque_utilise_mod_fichier"]="Disk space used for the File manager";
		self::$trad["download_alert"]="Downloading large files not available during the day";//..(9h=>19h)
		self::$trad["download_alert2"]="Maximum file  size during these hours";//..200Mo
		
		////	Infos sur une personne
		self::$trad["civility"]="Civility";
		self::$trad["name"]="Name";
		self::$trad["firstName"]="First name";
		self::$trad["adress"]="Address";
		self::$trad["postalCode"]="Zip code";
		self::$trad["city"]="City";
		self::$trad["country"]="country";
		self::$trad["telephone"]="Phone";
		self::$trad["telmobile"]="Mobile Phone";
		self::$trad["mail"]="Email";
		self::$trad["fax"]="Fax";
		self::$trad["website"]="Web site";
		self::$trad["skills"]="Skills";
		self::$trad["hobbies"]="Hobbies";
		self::$trad["function"]="Function";
		self::$trad["companyOrganization"]="Company /Organization";
		self::$trad["comment"]="Comment";
		
		////	Captcha
		self::$trad["captcha"]="Visual identification";
		self::$trad["captcha_info"]="Thank you to recopy the 4 characters for your identification";
		self::$trad["captcha_alert_specifier"]="Thank you to specify the visual identification";
		self::$trad["captcha_alert_erronee"]="The visual identification is false";
		
		////	Rechercher
		self::$trad["preciser_text"]="Thank you to specify key words of at least 3 characters";
		self::$trad["rechercher"]="Search";
		self::$trad["rechercher_dateCrea"]="Creation date";
		self::$trad["rechercher_dateCrea_jour"]="less than one day";
		self::$trad["rechercher_dateCrea_semaine"]="less than a week";
		self::$trad["rechercher_dateCrea_mois"]="less than one month";
		self::$trad["rechercher_dateCrea_annee"]="less than a year";
		self::$trad["rechercher_espace"]="Search on this space";
		self::$trad["recherche_avancee"]= "Advanced Search";
		self::$trad["recherche_avancee_mots_certains"]= "any word";
		self::$trad["recherche_avancee_mots_tous"]= "all words";
		self::$trad["recherche_avancee_expression_exacte"]= "exact phrase";
		self::$trad["keywords"]="Key words";
		self::$trad["liste_modules"]="Modules";
		self::$trad["liste_champs"]="Fields";
		self::$trad["liste_champs_elements"]="Elements involved";
		self::$trad["aucun_resultat"]="No result";
		
		////	Gestion des inscriptions d'utilisateur
		self::$trad["usersInscription"]="register on the site";
		self::$trad["usersInscription_info"]="create a new user account (validated by an administrator)";
		self::$trad["usersInscription_espace"]="register on the space";
		self::$trad["usersInscription_enregistre"]="Your registration was recorded : it will be validated as soon as possible by the administrator of the space";
		self::$trad["usersInscription_option_espace"]="Allow visitors to register on the space";
		self::$trad["usersInscription_option_espace_info"]="The registration is on the homepage of the site. Registration must then be validated by the administrator of the space.";
		self::$trad["usersInscription_validation"]="Registrations";
		self::$trad["usersInscription_validation_title"]="Validate user registrations on the site";
		self::$trad["usersInscription_invalider"]="invalidate";
		self::$trad["usersInscription_invalider_mail"]="Your account has not been validated on";

		////	Importer ou Exporter : Contact OU Utilisateurs
		self::$trad["exporter"]="Export";
		self::$trad["importer"]="Import";
		self::$trad["import_export_user"]="users";
		self::$trad["import_export_contact"]="contacts";
		self::$trad["export_format"]="format";
		self::$trad["specifier_fichier"]="Thank you to specify a file";
		self::$trad["extension_fichier"]="The file type is invalid. It must be of the type";
		self::$trad["import_infos"]="Select the Agora's fields to target, thanks to the dropdown of each column";
		self::$trad["import_alert"]="Thank you for selecting the name's column in the select boxes";
		self::$trad["import_alert2"]="Thank you for selecting at least a contact to import";
		self::$trad["import_alert3"]="this agora's field has already been selected in another column (each agora's fields can be selected only once)";

		////	Connexion à un serveur LDAP
		self::$trad["ldap_connexion_serveur"]="Connecting to an LDAP server";
		self::$trad["ldap_server"]="server address";
		self::$trad["ldap_server_port"]="Port server";
		self::$trad["ldap_server_port_infos"]="''389'' by default";
		self::$trad["ldap_admin_login"]="String connection for admin";
		self::$trad["ldap_admin_login_infos"]="for example ''uid=admin,ou=my_company''";
		self::$trad["ldap_admin_pass"]="Password of the admin";
		self::$trad["ldap_base_dn"]="Group / base DN";
		self::$trad["ldap_base_dn_infos"]="Location of directory users.<br> For example ''ou=users,o=my_company''";
		self::$trad["ldap_connexion_erreur"]="Error connecting to LDAP server !";
		self::$trad["ldap_crea_auto_users"]="Auto creation of users after identification";
		self::$trad["ldap_crea_auto_users_infos"]="Automatically create a user if it is missing from the Agora but present on the LDAP server: it will be assigned to areas accessible to ''all users of the Site''.<br>Otherwise, the user will not be created.";
		self::$trad["ldap_pass_cryptage"]="Passwords encrypted on the server";
		self::$trad["ldap_effacer_params"]="Delete LDAP setting?";
		self::$trad["ldap_pas_module_php"]="PHP module for connection to an LDAP server is not installed!";

		////	Messages d'alert ou d'erreur
		self::$trad["MSG_NOTIF_identification"]="Invalid login or password";
		self::$trad["MSG_NOTIF_dejapresent"]="Account currently used by another address IP... (Each account can be used only by one person at the same time)";
		self::$trad["MSG_NOTIF_adresseip"]="Address IP that you use is not authorized for this account";
		self::$trad["MSG_NOTIF_acces_aucun_espace"]="Access to the site is not authorized to you because currently, you are probably assigned to any space";
		self::$trad["MSG_NOTIF_acces_fichier_dossier"]="File or folder not accessible";
		self::$trad["MSG_NOTIF_espace_disque"]="Space for the storage of your files is insufficient, you cannot add file";
		self::$trad["MSG_NOTIF_type_interdit"]="File type not allowed";
		self::$trad["MSG_NOTIF_type_version"]="File type different from the original";
		self::$trad["MSG_NOTIF_deplacement_dossier"]="You cannot even move the folder inside him..!";
		self::$trad["MSG_NOTIF_duplicateName"]="An element with the same name already exists.";
		self::$trad["MSG_NOTIF_fileName"]="A file with the same name already exists (but not replaced with the current file)";
		self::$trad["MSG_NOTIF_chmod_DATAS"]="The ''DATAS'' folder is not accessible in writing. You need to give a read-write access to the owner and the group (''chmod 775'').";
		self::$trad["MSG_NOTIF_nb_users"]="You cannot add new user: limited to "; // "...limité à" 10
		self::$trad["MSG_NOTIF_update"]="Update completed. It is advisable to restart your browser before you reconnect";
		self::$trad["MSG_NOTIF_user_existdeja"]="The username already exists : the user has not been created";
		
		////	header menu
		self::$trad["HEADER_MENU_espaces_dispo"]="Other available spaces";
		self::$trad["HEADER_MENU_display_elem"]="Display";
		self::$trad["HEADER_MENU_display_normal"]="Elements assigned to me";
		self::$trad["HEADER_MENU_display_title_normal"]="This is the display normal / default";
		self::$trad["HEADER_MENU_display_autor"]="Elements i created";
		self::$trad["HEADER_MENU_display_title_autor"]="To display only the items that I created";
		self::$trad["HEADER_MENU_display_all"]="All the elements of the space (admin)";
		self::$trad["HEADER_MENU_display_title_all"]="For the administrators of the space : to display all the elements of space, even those who are not assigned to the administrator !";
		self::$trad["HEADER_MENU_recherche_elem"]="Search an element on the space";
		self::$trad["HEADER_MENU_documentation"]="Documentation";
		self::$trad["HEADER_MENU_sortie_agora"]="Log out from the Agora";
		self::$trad["HEADER_MENU_shortcuts"]="Shortcuts";
		self::$trad["HEADER_MENU_seul_utilisateur_connecte"]="Currently alone on the site";
		self::$trad["HEADER_MENU_en_ligne"]="Online";
		self::$trad["HEADER_MENU_connecte_a"]="connected to the site at";   // Mr bidule truc "connecté au site à" 12:45
		self::$trad["HEADER_MENU_messenger"]="Instant messaging";
		self::$trad["HEADER_MENU_envoye_a"]="Sent to";
		self::$trad["HEADER_MENU_ajouter_message"]="Add a message";
		self::$trad["HEADER_MENU_specifier_message"]="Thank you to specify a message";
		self::$trad["HEADER_MENU_enregistrer_conversation"]="Record this conversation";
		
		////	Footer
		self::$trad["FOOTER_page_generee"]="page generated in";
		
		////	Password_oublie
		self::$trad["PASS_OUBLIE_preciser_mail"]="Enter your email address to receive your login and password";
		self::$trad["PASS_OUBLIE_mail_inexistant"]="The email indicated does not exist in the database";
		self::$trad["PASS_OUBLIE_mail_objet"]="Connection to your space";
		self::$trad["PASS_OUBLIE_mail_contenu"]="Your login";
		self::$trad["PASS_OUBLIE_mail_contenu_bis"]="Click here to reset your password";
		self::$trad["PASS_OUBLIE_prompt_changer_pass"]="Specify your new password";
		self::$trad["PASS_OUBLIE_idNewPassword_expire"]="The weblink to regenerate the password has expired .. thank you to restart the procedure";
		self::$trad["PASS_OUBLIE_password_reinitialise"]="Your new password was registered !";
		
		////	menu_edit_objet
		self::$trad["EDIT_OBJET_alert_aucune_selection"]="You must select at least a person or a space";
		self::$trad["EDIT_OBJET_alert_pas_acces_perso"]="You are not assigned to the element. validate all the same ?";
		self::$trad["EDIT_OBJET_alert_ecriture_obligatoire"]="There must be at least a person or a space assigned in writing";
		self::$trad["EDIT_OBJET_alert_ecriture_deconseille"]="Warning! <br><br>The write access permit to delete ALL the messages of the Topic !<br><br>It is recommended to use limited write access";
		self::$trad["EDIT_OBJET_alert_guest"]="Thank you to specify a name or pseudo";
		self::$trad["EDIT_OBJET_accessRight"]="Access rights";
		self::$trad["EDIT_OBJET_accessRightContent"]="Access rights to the content";
		self::$trad["EDIT_OBJET_identification"]="Identification";
		self::$trad["EDIT_OBJET_espace_pas_module"]="The current module has not yet been added to this space";
		self::$trad["EDIT_OBJET_allUsers"]="All the users";
		self::$trad["EDIT_OBJET_allUsers_espaces"]="All the spaces";
		self::$trad["EDIT_OBJET_espace_guests"]="Guests of this public space";
		self::$trad["EDIT_OBJET_guest"]="Your Name/Pseudo";
		self::$trad["EDIT_OBJET_admin_espace"]="Administrator of this space:<br>write access to all the elements of this space";
		self::$trad["EDIT_OBJET_tous_espaces"]="Display all my spaces";
		self::$trad["EDIT_OBJET_notif_mail"]="Notify by email";
		self::$trad["EDIT_OBJET_notif_mail_label"]="Notify by email";
		self::$trad["EDIT_OBJET_notif_mail_joindre_fichiers"]="Attach files to the notification";
		self::$trad["EDIT_OBJET_notif_mail_info"]="Send a notification of creation/modification by email, to the persons who will have access to the element";
		self::$trad["EDIT_OBJET_notif_mail_selection"]="Manually select the recipients of notifications";
		self::$trad["EDIT_OBJET_notif_tous_users"]="Display more users";
		self::$trad["EDIT_OBJET_accessRightSubFolders"]="Assign the same access rights to the under-folders";
		self::$trad["EDIT_OBJET_accessRightSubFolders_info"]="Extend rights of access, to subfolders that you can edit";
		self::$trad["EDIT_OBJET_shortcut"]="Shortcut";
		self::$trad["EDIT_OBJET_shortcut_info"]="Put a shortcut on the main menu";
		self::$trad["EDIT_OBJET_fichier_joint"]="Add attached files";
		self::$trad["EDIT_OBJET_fichier_joint_info"]="Attach pictures, videos, Pdf, Word, etc to the current object.<br>Images and videos can be integrated directly to the text editor.";
		self::$trad["EDIT_OBJET_inserer_fichier"]="Display in the description";
		self::$trad["EDIT_OBJET_inserer_fichier_info"]="Display the image / video / mp3 player ... in the description above. The insertion is performed after form validation.";
		self::$trad["EDIT_OBJET_demandToConfirm"]="Your request has been registered. It will be confirmed soon.";
		
		////	Formulaire d'installations
		self::$trad["INSTALL_connexion_bdd"]="Connection to the database";
		self::$trad["INSTALL_db_host"]="Hostname of the databases server";
		self::$trad["INSTALL_db_name"]="Name of the database";
		self::$trad["INSTALL_db_login"]="User name";
		self::$trad["INSTALL_config_admin"]="Information about the administrator of the ";
		self::$trad["INSTALL_errorConnectSGBD"]="The connection to the MySQL database failed";
		self::$trad["INSTALL_errorConnectIdentification"]="The identification to the MySQL database failed";
		self::$trad["INSTALL_errorAppliInstalled"]="The installation has already been done. Thank you to remove the database whether to restart the installation.";
		self::$trad["INSTALL_errorConnectDbConfirmInstall"]="The connection to the database has not been established, still confirm ?";
		self::$trad["INSTALL_PhpOldVersion"]="Agora-Project requires a newer version of PHP";
		self::$trad["INSTALL_confirm_install"]="Confirm the installation ?";
		self::$trad["INSTALL_install_ok"]="Agora-Project was well installed !";
		self::$trad["INSTALL_spaceDescription"]="Space for sharing and collaborative work";

		////	MODULE_PARAMETRAGE
		////
		self::$trad["AGORA_headerModuleName"]="General settings";
		self::$trad["AGORA_description_module"]="Settings of the site";
		self::$trad["AGORA_sav"]="Backup the database and the files";
		self::$trad["AGORA_sav_alert"]="The creation of the backup file may take a few minute ... and download a few dozen minutes.";
		self::$trad["AGORA_sav_bdd"]="Backup the database";
		self::$trad["AGORA_espace_disque_invalide"]="The limiting disk space must be an entirety";
		self::$trad["AGORA_confirmez_modification_site"]="Confirm modifications ?";
		self::$trad["AGORA_name"]="Site name";
		self::$trad["AGORA_footerHtml"]="Footer html";
		self::$trad["AGORA_footerHtml_info"]="To include statistical tools for exemple";
		self::$trad["AGORA_lang"]="Language by default";
		self::$trad["AGORA_timezone"]="Timezone";
		self::$trad["AGORA_spaceName"]="Name of principal space";
		self::$trad["AGORA_limite_espace_disque"]="Space available for the storage of the files";
		self::$trad["AGORA_logsTimeOut"]="Shelf life of Logs";
		self::$trad["AGORA_skin"]="Color of the interface";
		self::$trad["AGORA_noir"]="Black";
		self::$trad["AGORA_blanc"]="White";
		self::$trad["AGORA_erreur_wallpaper_logo"]="The wallpaper and the logo must have a .jpg or .png extension";
		self::$trad["AGORA_suppr_wallpaper"]="Delete the wallpaper ?";
		self::$trad["AGORA_logo_footer"]="Logo at the bottom of each page";
		self::$trad["AGORA_logo_footer_url"]="URL";
		self::$trad["AGORA_messengerDisabled"]="Instant messenger enabled";
		self::$trad["AGORA_personalCalendarsDisabled"]="Personal calendars enabled by default";
		self::$trad["AGORA_personalCalendarsDisabled_infos"]="Add a personal calendar at the creation of a user. The calendar may, however, be disabled later, changing the user account.";
		self::$trad["AGORA_moduleLabelDisplay"]="Name of the modules in the main menu";
		self::$trad["AGORA_moduleLabelDisplay_masquer"]="hide";
		self::$trad["AGORA_moduleLabelDisplay_icones"]="above each module icon";
		self::$trad["AGORA_moduleLabelDisplay_page"]="only the name of the current module";
		self::$trad["AGORA_personsSort"]="Sort users and contacts";
		self::$trad["AGORA_versions"]="Versions";
		self::$trad["AGORA_version_agora_maj"]="update";
		self::$trad["AGORA_fonction_mail_desactive"]="PHP function to send email : disabled !";
		self::$trad["AGORA_fonction_mail_infos"]="Some hosters disable the PHP function for sending mails for security reasons or saturation servers (SPAM)";
		self::$trad["AGORA_fonction_image_desactive"]="Function of handling images and creation of thumbs (PHP GD2) : disabled !";

		////	MODULE_LOG
		////
		self::$trad["LOG_headerModuleName"]="Logs";
		self::$trad["LOG_description_module"]="Logs - Event Log";
		self::$trad["LOG_filtre"]="filter";
		self::$trad["LOG_date_heure"]="Date / Time";
		self::$trad["LOG_espace"]="space";
		self::$trad["LOG_module"]="module";
		self::$trad["LOG_objectType"]="Object type";
		self::$trad["LOG_action"]="Action";
		self::$trad["LOG_utilisateur"]="User";
		self::$trad["LOG_ipAdress"]="IP";
		self::$trad["LOG_comment"]="comment";
		self::$trad["LOG_no_logs"]="no log";
		self::$trad["LOG_filtre_a_partir"]="filtered from";
		self::$trad["LOG_chercher"]="search";
		self::$trad["LOG_connexion"]="connection";
		self::$trad["LOG_deconnexion"]="logout";
		self::$trad["LOG_consult"]="consult";
		self::$trad["LOG_consult2"]="download";
		self::$trad["LOG_add"]="add";
		self::$trad["LOG_delete"]="delete";
		self::$trad["LOG_modif"]="edit change";

		////	MODULE_ESPACE
		////
		// Menu principal
		self::$trad["SPACE_headerModuleName"]="Spaces";
		self::$trad["SPACE_description_module"]="Spaces of the site";
		self::$trad["SPACE_description_module_infos"]="The site (or main space) can be divided into several spaces";
		// Header_menu.inc.php
		self::$trad["SPACE_gerer_espaces"]="Manage spaces of the site";
		self::$trad["SPACE_parametrage"]="Settings of the space";
		self::$trad["SPACE_parametrage_infos"]="Settings of the space (description, modules, Users assigned, etc)";
		// Index.php
		self::$trad["SPACE_confirm_suppr_espace"]="Confirm the suppression ? Attention, the affected data only with this space will be definitively lost !!";
		self::$trad["SPACE_espace"]="space";
		self::$trad["SPACE_espaces"]="spaces";
		self::$trad["SPACE_definir_acces"]="To define !";
		self::$trad["SPACE_modules"]="Modules";
		self::$trad["SPACE_ajouter_espace"]="Add a space";
		self::$trad["SPACE_supprimer_espace"]="Delete the space?";
		self::$trad["MSG_NOTIF_suppr_espace_impossible"]="You can not delete the current space";
		// Espace_edit.php
		self::$trad["SPACE_gestion_acces"]="Users assigned to the space";
		self::$trad["SPACE_selectionner_module"]="You must select at least a module";
		self::$trad["SPACE_modules_espace"]="Modules of the space";
		self::$trad["SPACE_modules_rank"]="Move to set the display order of modules";
		self::$trad["SPACE_espace_public"]="Public space";
		self::$trad["SPACE_public_infos"]="Provides access to people who do not have accounts on the site : guests. Ability to specify a password to protect access.";
		self::$trad["SPACE_usersInvitation"]="Users can send invitations by mail";
		self::$trad["SPACE_usersInvitation_infos"]="All users can send email invitations to join the space";
		self::$trad["SPACE_allUsers"]="All the users on the site";
		self::$trad["SPACE_utilisation"]=" User";
		self::$trad["SPACE_utilisation_info"]="User of the space : <br> Normal access to the space";
		self::$trad["SPACE_administration"]="Administrator";
		self::$trad["SPACE_administration_info"]="Administrator of the space : Write access to all elements of the space + ability to send email invitations + ability to add users";
		self::$trad["SPACE_creer_agenda_espace"]="Create a shared agenda";
		self::$trad["SPACE_creer_agenda_espace_info"]="The calendar will have the same name than the space. This can be useful if the calendars of the users are disabled.";

		////	MODULE_UTILISATEUR
		////
		// Menu principal
		self::$trad["USER_headerModuleName"]="User";
		self::$trad["USER_description_module"]="Users of the space";
		self::$trad["USER_ajout_utilisateurs_groupe"]="Users can also create groups";
		// Index.php
		self::$trad["USER_utilisateurs_site"]="User of the site";
		self::$trad["USER_gerer_utilisateurs_site"]="Manage all the users";
		self::$trad["USER_utilisateurs_site_infos"]="All users of the site : from all spaces";
		self::$trad["USER_utilisateurs_espace"]="Users of the space";
		self::$trad["USER_confirm_desaffecter_utilisateur"]="Confirm the unassignment of the user to current space ?";
		self::$trad["USER_suppr_definitivement"]="Delete definitely";
		self::$trad["USER_desaffecter"]="Unassign to the space";
		self::$trad["USER_tous_user_affecte_espace"]="All the users of the site are affected to this space: no possible unassignment";
		self::$trad["USER_user"]="User";
		self::$trad["USER_users"]="users";
		self::$trad["USER_affecter_utilisateur"]="Add an existing user in this space";
		self::$trad["USER_ajouter_utilisateur"]="Add User";
		self::$trad["USER_ajouter_utilisateur_site"]="Create a user on the site: by default, assigned to any space!";
		self::$trad["USER_ajouter_utilisateur_espace"]="Create a user into the current space";
		self::$trad["USER_envoi_coordonnees"]="Send login and password";
		self::$trad["USER_envoi_coordonnees_info"]="Send to users (by mail) their login <br> and a new password";
		self::$trad["USER_envoi_coordonnees_info2"]="Send to each new user <br> an email with login information.";
		self::$trad["USER_envoi_coordonnees_confirm"]="Passwords will be renewed ! continue ?";
		self::$trad["USER_mail_coordonnees"]="Your login details to your space";
		self::$trad["USER_aucun_utilisateur"]="No user assigned to this space for the moment";
		self::$trad["USER_lastConnection"]="Last connection";
		self::$trad["USER_liste_espaces"]="Spaces of the user";
		self::$trad["USER_aucun_espace"]="No space";
		self::$trad["USER_adminGeneral"]="General administrator of the site";
		self::$trad["USER_adminSpace"]="Administrator of the space";
		self::$trad["USER_userSpace"]="User of the space";
		self::$trad["USER_pas_connecte"]="Not connected yet";
		self::$trad["USER_modifier"]="Modify user";
		self::$trad["USER_modifier_mon_profil"]="Modify my profil";
		// Invitation.php
		self::$trad["USER_envoi_invitation"]="Invite somebody to join the space";
		self::$trad["USER_envoi_invitation_info"]="The invitation will be sent by mail";
		self::$trad["USER_objet_mail_invitation"]="Invitation of "; // ..Jean DUPOND
		self::$trad["USER_admin_guest_espace"]="invites you to join "; // Jean DUPOND "vous invite à rejoindre l'espace" Mon Espace
		self::$trad["USER_confirmer_invitation"]="Click here to confirm the invitation";
		self::$trad["USER_invitation_a_confirmer"]="Invitations not confirmed yet";
		self::$trad["USER_idInvitation_expire"]="The weblink for your invitation has expired ...";
		self::$trad["USER_invitationConfirmPassword"]="Choose your password and click 'OK' to confirm your invitation";
		self::$trad["USER_invitation_valide"]="Your invitation has been validated !";
		// groupes.php
		self::$trad["USER_groupe_espace"]="groups of users of the space";
		self::$trad["USER_groupe_info"]="edit the groups of users";
		self::$trad["USER_droit_gestion_groupes"]="Each group can be modified by its author or the general administrator";
		// Utilisateur_affecter.php
		self::$trad["USER_preciser_recherche"]="Thank you to specify a name, a first name or an address of email";
		self::$trad["USER_affecter_user_confirm"]="Confirm assignements?";
		self::$trad["USER_rechercher_user"]="Search users to add to the current space";
		self::$trad["USER_tous_users_affectes"]="All the users of the site are already assigned to this space";
		self::$trad["USER_affecter_user"]="Assign users to the space :";
		self::$trad["USER_aucun_users_recherche"]="No user for this research";
		// Utilisateur_edit.php & CO
		self::$trad["USER_specifyName"]="Thank you to specify a name";
		self::$trad["USER_specifyFirstName"]="Thank you to specify a first name";
		self::$trad["USER_specifier_identifiant"]="Thank you to specify a login";
		self::$trad["USER_specifier_password"]="Thank you to specify a password";
		self::$trad["USER_specifyMailAsLogin"]="It is recommended to use an email as login. Still validate the form ?";
		self::$trad["USER_langs"]="Language";
		self::$trad["USER_agenda_perso_desactive"]="Personal calendar disabled";
		self::$trad["USER_agenda_perso_desactive_infos"]="By default, the personal calendar is always visible by the user, even if the Calendar module is not enabled in the space";
		self::$trad["USER_connectionSpace"]="Space displayed after connection";
		self::$trad["USER_notification_mail"]="Send a notification of creation by email";
		self::$trad["USER_alert_notification_mail"]="Think of specifying an address email!";
		self::$trad["USER_adresses_ip"]="list of control IP addresses";
		self::$trad["USER_info_ipAdress"]="When you specify one (or several) control IP addresses, the user can only connect if it uses the specified IP addresses";
		self::$trad["USER_identifiant_deja_present"]="The specified login already exists. Thank you to specify another";
		self::$trad["USER_mail_deja_present"]="The email already exists. Thank you to specify another";
		self::$trad["USER_mail_objet_nouvel_utilisateur"]="New account on";  // "...sur" l'Agora machintruc
		self::$trad["USER_mail_nouvel_utilisateur"]="Your user account has been created on";  // idem
		self::$trad["USER_mail_infos_connexion"]="Connect with the following login and password";
		self::$trad["USER_mail_infos_connexion2"]="Thank you to archive this email.";
		// Utilisateur_Messenger.php
		self::$trad["USER_gestion_messenger_livecounter"]="Manage the instant messenger";
		self::$trad["USER_visibilite_messenger_livecounter"]="Users who can see me online chat on instant messaging";
		self::$trad["USER_aucun_utilisateur_messenger"]="No user for the moment";
		self::$trad["USER_voir_aucun_utilisateur"]="Messenger disabled (no one can see me)";
		self::$trad["USER_voir_tous_utilisateur"]="All the users can see me";
		self::$trad["USER_voir_certains_utilisateur"]="Certain users can see me";

		////	MODULE_TABLEAU BORD
		////
		// Menu principal
		self::$trad["DASHBOARD_headerModuleName"]="News";
		self::$trad["DASHBOARD_description_module"]="News";
		self::$trad["DASHBOARD_ajout_actualite_admin"]="Only the admin can add News";
		// Index.php
		self::$trad["DASHBOARD_new_elems"]="new elements";
		self::$trad["DASHBOARD_new_elems_info"]="Elements created between <br>";
		self::$trad["DASHBOARD_new_elems_realises"]="currents";
		self::$trad["DASHBOARD_new_elems_realises_info"]="Events and tasks taking place between <br>";
		self::$trad["DASHBOARD_plugin_connexion"]="since last connection";
		self::$trad["DASHBOARD_plugin_jour"]="today";
		self::$trad["DASHBOARD_plugin_semaine"]="this Week";
		self::$trad["DASHBOARD_plugin_mois"]="this month";
		self::$trad["DASHBOARD_autre_periode"]="Another period";
		self::$trad["DASHBOARD_pas_nouveaux_elements"]="No elements for the selected period";
		self::$trad["DASHBOARD_actualites"]="News";
		self::$trad["DASHBOARD_actualite"]="new";
		self::$trad["DASHBOARD_actualites"]="news";
		self::$trad["DASHBOARD_ajout_actualite"]="Add a news";
		self::$trad["DASHBOARD_actualites_offline"]="Offline news";
		self::$trad["DASHBOARD_pas_actualites"]="No news";
		// Actualite_edit.php
		self::$trad["DASHBOARD_ala_une"]="In Focus";
		self::$trad["DASHBOARD_ala_une_info"]="Highlight this news";
		self::$trad["DASHBOARD_offline"]="Offline";
		self::$trad["DASHBOARD_offline_info"]="Offline news";
		self::$trad["DASHBOARD_dateOnline"]="Date of online";
		self::$trad["DASHBOARD_dateOnline_info"]="Select a date to put automatically the news online.<br>In the meantime, the news is offline";
		self::$trad["DASHBOARD_dateOnline_alerte"]="The news is offline in this expectation";
		self::$trad["DASHBOARD_dateOffline"]="Date of offline";
		self::$trad["DASHBOARD_dateOffline_info"]="Select a date to put automatically the news offline";

		////	MODULE_AGENDA
		////
		// Menu principal
		self::$trad["CALENDAR_headerModuleName"]="Calendar";
		self::$trad["CALENDAR_description_module"]="Personal and shared calendar";
		self::$trad["CALENDAR_ajout_agenda_ressource_admin"]="Only the admin can add resource calendars";
		self::$trad["CALENDAR_ajout_categorie_admin"]="Only the admin can add a category of event";
		// Index.php
		self::$trad["CALENDAR_afficher_tous_agendas"]="Show all calendars";
		self::$trad["CALENDAR_masquer_tous_agendas"]="Hide all calendars";
		self::$trad["CALENDAR_cocher_tous_agendas"]="Check/Uncheck all Calendars";
		self::$trad["CALENDAR_cocher_agendas_users"]="Check/Uncheck users";
		self::$trad["CALENDAR_cocher_agendas_ressources"]="Check/Uncheck resources";
		self::$trad["CALENDAR_imprimer_agendas"]="Print calendar(s)";
		self::$trad["CALENDAR_imprimer_agendas_infos"]="print in landscape mode";
		self::$trad["CALENDAR_ajouter_agenda_ressource"]="Add a chared calendar";
		self::$trad["CALENDAR_ajouter_agenda_ressource_bis"]="Add a chared calendar :<br>for the reservations of a room, vehicle, videoprojector, etc";
		self::$trad["CALENDAR_exporter_ical"]="Export the events (iCal format)";
		self::$trad["CALENDAR_exporter_ical_mail"]="Export the events by mail (iCal)";
		self::$trad["CALENDAR_exporter_ical_mail2"]="To integrate in an calendar IPHONE, ANDROID, OUTLOOK, GOOGLE CALENDAR...";
		self::$trad["CALENDAR_importer_ical"]="Import the events (iCal)";
		self::$trad["CALENDAR_importer_ical_etat"]="State";
		self::$trad["CALENDAR_importer_ical_deja_present"]="Already present";
		self::$trad["CALENDAR_importer_ical_a_importer"]="To import";
		self::$trad["CALENDAR_suppr_anciens_evt"]="Delete the past events";
		self::$trad["CALENDAR_suppr_anciens_evt_info"]="Delete the events preceding today";
		self::$trad["CALENDAR_confirm_suppr_anciens_evt"]="Are you sure you want to delete the events preceding today ?";
		self::$trad["CALENDAR_ajouter_evt_heure"]="Add an event at";
		self::$trad["CALENDAR_ajouter_evt_jour"]="Add an event to this day";
		self::$trad["CALENDAR_evt_jour"]="Day";
		self::$trad["CALENDAR_evt_semaine"]="Week";
		self::$trad["CALENDAR_evt_semaine_w"]="Working week";
		self::$trad["CALENDAR_evt_mois"]="Month";
		self::$trad["CALENDAR_voir_num_semaine"]="See the week n°";
		self::$trad["CALENDAR_periode_suivante"]="Next period";
		self::$trad["CALENDAR_periode_precedante"]="Preceding period";
		self::$trad["CALENDAR_affectations_evt"]="Event in the calendar of ";
		self::$trad["CALENDAR_affectationUnconfirmed"]="Confirmation on standby : ";
		self::$trad["CALENDAR_evenements_proposes_pour_agenda"]="Events proposed for"; // "Videoprojecteur" / "salle de réunion" / etc.
		self::$trad["CALENDAR_evenements_proposes_mon_agenda"]="Events proposed for my calendar";
		self::$trad["CALENDAR_evenement_propose_par"]="Proposed by";  // "Proposé par" Mr bidule truc
		self::$trad["CALENDAR_evenement_integrer"]="Integrate the event into the calendar ?";
		self::$trad["CALENDAR_evenement_pas_integrer"]="Delete the proposal of the event ?";
		self::$trad["CALENDAR_supprimer_evt_agenda"]="Delete for this calendar ?";
		self::$trad["CALENDAR_supprimer_evt_agendas"]="Delete for all the calendars ?";
		self::$trad["CALENDAR_supprimer_evt_date"]="Delete this date only ?";
		self::$trad["CALENDAR_evt_prive"]="Private event";
		self::$trad["CALENDAR_evt_proprio"]="Events which I created";
		self::$trad["CALENDAR_aucun_evt"]="No event";
		self::$trad["CALENDAR_proposer"]="Propose an event";
		self::$trad["CALENDAR_synthese"]="Calendars synthesis";
		self::$trad["CALENDAR_pourcent_agendas_occupes"]="Busy calendars";
		self::$trad["CALENDAR_aucun_agenda_visible"]="No calendars displayed";
		// Evenement.php
		self::$trad["CALENDAR_categorie"]="Category";
		self::$trad["CALENDAR_visibilite"]="Visibility";
		self::$trad["CALENDAR_visibilite_public"]="public";
		self::$trad["CALENDAR_visibilite_public_cache"]="public, but details masked";
		self::$trad["CALENDAR_visibilite_prive"]="private";
		// Agenda_edit.php
		self::$trad["CALENDAR_affichage_evt"]="Event display";
		self::$trad["CALENDAR_affichage_evt_border"]="Border with the color of the category";
		self::$trad["CALENDAR_affichage_evt_background"]="Background with the color of the category";
		self::$trad["CALENDAR_timeSlot"]="Time slot";
		// Evenement_edit.php
		self::$trad["CALENDAR_periodicite"]="Recurring Event";
		self::$trad["CALENDAR_period_weekDay"]="Every week";
		self::$trad["CALENDAR_period_monthDay"]="Every month, some days";
		self::$trad["CALENDAR_period_month"]="Every month";
		self::$trad["CALENDAR_period_month_xdumois"]="of the month"; // Le 21 du mois
		self::$trad["CALENDAR_period_year"]="Every year";
		self::$trad["CALENDAR_periodDateEnd"]="End of periodicity";
		self::$trad["CALENDAR_exception_periodicite"]="Exception de périodicité";
		self::$trad["CALENDAR_agendas_affectations"]="Assign to the following calendars";
		self::$trad["CALENDAR_verif_nb_agendas"]="Thank you to select at least a calendar";
		self::$trad["CALENDAR_input_proposer"]="Propose the event to the owner of the calendar";
		self::$trad["CALENDAR_input_affecter"]="Add the event to the calendar";
		self::$trad["CALENDAR_info_proposer"]="Only propose the event (you don't have access in writing to this calendar)";
		self::$trad["CALENDAR_info_pas_modif"]="Modification forbidden because you don't have access in writing to this calendar";
		self::$trad["CALENDAR_visibilite_info"]="<u>Public</u> : normal visibility.<br><br><u>Public, but masked details</u> : read-only access to the calendar, only displays the time period of the event.<br><br><u>Private</u> : visible only if it can edit the event.";
		self::$trad["CALENDAR_edit_limite"]="You are not the author of the event: you can only manage your calendars assignments";
		self::$trad["CALENDAR_creneau_occupe"]="The slot is already occupied on this calendar :";
		// Categories.php
		self::$trad["CALENDAR_gerer_categories"]="Manage event categories";
		self::$trad["CALENDAR_categories_evt"]="Event categories";
		self::$trad["CALENDAR_droit_gestion_categories"]="Each category can be modified by its author or the general administrator";

		////	MODULE_FICHIER
		////
		// Menu principal
		self::$trad["FILE_headerModuleName"]="File manager";
		self::$trad["FILE_description_module"]="File manager";
		self::$trad["FILE_AdminRootFolderAddContent"]="Only the administrator can add folders and files in the root folder";
		// Index.php
		self::$trad["FILE_ajouter_fichier"]="Add files";
		self::$trad["FILE_ajouter_fichier_alert"]="Folder on the server not accessible in writing! thank you to contact the administrator";
		self::$trad["FILE_telecharger_selection"]="Download Selection";
		self::$trad["FILE_nb_versions_fichier"]="versions of the file"; // n versions du fichier
		self::$trad["FILE_ajouter_versions_fichier"]="add a new file version";
		self::$trad["FILE_aucun_fichier"]="No file for the moment";
		// Fichier_edit.php  &  Dossier_edit.php  &  fichier_edit_ajouter.php  &  Versions_fichier.php
		self::$trad["FILE_limite_chaque_fichier"]="The files should not exceed"; // ...2 Mega Octets
		self::$trad["FILE_optimiser_images"]="Limit the size to "; // ..1024*768 pixels
		self::$trad["FILE_updatedName"]="The filename will be replaced by the new version";
		self::$trad["FILE_erreur_taille_fichier"]="File is too big";
		self::$trad["FILE_ajout_multiple_info"]="Button 'Shift' or 'Ctrl' to select multiple files";
		self::$trad["FILE_selectionner_fichier"]="Thank you to select at least a file";
		self::$trad["FILE_contenu"]="content";
		// Versions_fichier.php
		self::$trad["FILE_versions_de"]="Versions of"; // versions de fichier.gif
		self::$trad["FILE_confirmer_suppression_version"]="Confirm the removal of this version ?";

		////	MODULE_FORUM
		////
		// Menu principal
		self::$trad["FORUM_headerModuleName"]="Forum";
		self::$trad["FORUM_description_module"]="Forum";
		self::$trad["FORUM_ajout_sujet_admin"]="Only the administrator can add topics";
		self::$trad["FORUM_ajout_sujet_theme"]="Users can also add themes";
		// TRI
		self::$trad["SORT_dateLastMessage"]="last message";
		// Index.php & Sujet.php
		self::$trad["FORUM_sujet"]="topic";
		self::$trad["FORUM_sujets"]="topics";
		self::$trad["FORUM_message"]="message";
		self::$trad["FORUM_messages"]="messages";
		self::$trad["FORUM_dernier_message"]="last by";
		self::$trad["FORUM_ajouter_sujet"]="Add a topic";
		self::$trad["FORUM_voir_sujet"]="View topic";
		self::$trad["FORUM_ajouter_message"]="Add message";
		self::$trad["FORUM_repondre"]="Answer";
		self::$trad["FORUM_citer"]="Quote";
		self::$trad["FORUM_message_repondre"]="Answer this message";
		self::$trad["FORUM_message_citer"]="Answer and quote this message";
		self::$trad["FORUM_aucun_sujet"]="No topic for the moment";
		self::$trad["FORUM_aucun_message"]="No message for the moment";
		self::$trad["FORUM_confirme_suppr_message"]="Confirm the delete of the message (and sub-messages) ?";
		self::$trad["FORUM_notifier_dernier_message"]="Notify by email";
		self::$trad["FORUM_notifier_dernier_message_info"]="Send me a notification by mail to each new message";
		// Sujet_edit.php  &  Message_edit.php
		self::$trad["FORUM_accessRightInfos"]="To participate in the topic, you must have at least a ''limited write access''";
		self::$trad["FORUM_theme_espaces"]="The topic is available in the spaces";
		// Themes
		self::$trad["FORUM_theme_sujet"]="Theme";
		self::$trad["FORUM_accueil_forum"]="Forum index";
		self::$trad["FORUM_sans_theme"]="Without theme";
		self::$trad["FORUM_themes_gestion"]="Manage themes";
		self::$trad["FORUM_droit_gestion_themes"]="Each theme can be modified by its author or the general administrator";

		////	MODULE_TACHE
		////
		// Menu principal
		self::$trad["TASK_headerModuleName"]="Tasks";
		self::$trad["TASK_description_module"]="Tasks";
		self::$trad["TASK_AdminRootFolderAddContent"]="Only the administrator can add folders and tasks in the root folder";
		// TRI
		self::$trad["SORT_priority"]="Priority";
		self::$trad["SORT_advancement"]="Progress";
		self::$trad["SORT_dateBegin"]="Begin date";
		self::$trad["SORT_dateEnd"]="End date";
		// Index.php
		self::$trad["TASK_ajouter_tache"]="Add a task";
		self::$trad["TASK_aucune_tache"]="No task for the moment";
		self::$trad["TASK_advancement"]="Progress";
		self::$trad["TASK_advancementAverage"]="Average progress";
		self::$trad["TASK_priority"]="Priority";
		self::$trad["TASK_priority1"]="Low";
		self::$trad["TASK_priority2"]="Medium";
		self::$trad["TASK_priority3"]="High";
		self::$trad["TASK_priority4"]="Critical";
		self::$trad["TASK_responsiblePersons"]="Leaders";
		self::$trad["TASK_budgetAvailable"]="Budget available";
		self::$trad["TASK_budgetAvailable_total"]="Total Budget available";
		self::$trad["TASK_budgetEngaged"]="Budget committed";
		self::$trad["TASK_budgetEngaged_total"]="Total Budget committed";
		self::$trad["TASK_humanDayCharge"]="Day/man charge";
		self::$trad["TASK_humanDayCharge_info"]="Number of working days required for one person to accomplish this task";
		self::$trad["TASK_advancement_retard"]="Progress delayed";
		self::$trad["TASK_budgetExceeded"]="Budget exceeded";

		////	MODULE_CONTACT
		////
		// Menu principal
		self::$trad["CONTACT_headerModuleName"]="Contacts";
		self::$trad["CONTACT_description_module"]="Directory of contacts";
		self::$trad["CONTACT_AdminRootFolderAddContent"]="Only the administrator can add folders and contacts in the root folder";
		// Index.php
		self::$trad["CONTACT_ajouter_contact"]="Add a contact";
		self::$trad["CONTACT_aucun_contact"]="No contact for the moment";
		self::$trad["CONTACT_creer_user"]="Create a user in this space";
		self::$trad["CONTACT_creer_user_infos"]="Create a user in this space from this contact ?";
		self::$trad["CONTACT_creer_user_confirm"]="the user was created";

		////	MODULE_LIEN
		////
		// Menu principal
		self::$trad["LINK_headerModuleName"]="Bookmarks";
		self::$trad["LINK_description_module"]="Bookmarks";
		self::$trad["LINK_AdminRootFolderAddContent"]="Only the administrator can add folders and bookmarks in the root folder";
		// Index.php
		self::$trad["LINK_ajouter_lien"]="Add a bookmark";
		self::$trad["LINK_aucun_lien"]="No bookmark for the moment";
		// lien_edit.php & dossier_edit.php
		self::$trad["LINK_adress"]="bookmark";

		////	MODULE_MAIL
		////
		//  Menu principal
		self::$trad["MAIL_headerModuleName"]="Mailing";
		self::$trad["MAIL_description_module"]="Send emails in a click!";
		// Index.php
		self::$trad["MAIL_specifier_mail"]="Thank you to specify at least an address email";
		self::$trad["MAIL_title"]="Email of the title";
		self::$trad["MAIL_fichier_joint"]="Attached file";
		// Historique Mail
		self::$trad["MAIL_historique_mail"]="History of the emails sent";
		self::$trad["MAIL_aucun_mail"]="No email";
		self::$trad["MAIL_envoye_par"]="Email sent by";
		self::$trad["MAIL_recipients"]="Recipients";
	}

	/*
	 * Jours Fériés de l'année
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
			$dateList[$date]="Easter Monday";
		}

		//Fêtes fixes
		$dateList[$year."-01-01"]="New Year's Day";
		$dateList[$year."-12-25"]="Christmas";

		//Retourne le résultat
		return $dateList;
	}
}