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
		self::$trad["CURLANG"]="pt";
		self::$trad["HEADER_HTTP"]="pt";
		self::$trad["DATEPICKER"]="pt";
		self::$trad["HTML_EDITOR"]="pt_PT";
		self::$trad["UPLOADER"]="pt";
		setlocale(LC_TIME, "pt_PT.utf8", "pt_PT.UTF-8", "pt_PT", "pt", "portuguese");

		////	Divers
		self::$trad["OK"]="OK";
		self::$trad["remplir_tous_champs"]="Favor preencher todos os campos";
		self::$trad["elem_inaccessible"]="Elemento inacessível";
		self::$trad["champs_obligatoire"]="Campo Obrigatório";
		self::$trad["oui"]="sim";
		self::$trad["non"]="não";
		self::$trad["aucun"]="não";
		self::$trad["et"]="e";
		self::$trad["aller_page"]="Ir para a página";
		self::$trad["alphabet_filtre"]="Filtro alfabética";
		self::$trad["tout_afficher"]="Tudo";
		self::$trad["important"]="Importante";
		self::$trad["afficher"]="mostrar";
		self::$trad["deplacer"]="mover";
		self::$trad["garder"]="Manter";
		self::$trad["par_defaut"]="Por defeito";
		self::$trad["localiser_carte"]="Localizar num mapa";
		self::$trad["bienvenue_agora"]="Bem-vindo na Ágora!";
		self::$trad["mail_pas_valide"]="O e-mail não é válido";
		self::$trad["element"]="elemento";
		self::$trad["elements"]="elementos";
		self::$trad["dossier"]="pasta";
		self::$trad["dossiers"]="pastas";
		self::$trad["dossier_parent"]="pasta superior";
		self::$trad["fermer"]="Fechar";
		self::$trad["visible_espaces"]="Espaços onde será visível";
		self::$trad["visible_ts_espaces"]="Visível em todos os espaços";
		self::$trad["confirmCloseLightbox"]="fechar o formulário ?";

		////	Menu/Menu contextuel
		self::$trad["menu"]="menu";
		self::$trad["objNew"]="Novo elemento";
		self::$trad["acces_perso"]="Acesso pessoal";
		
		////	images
		self::$trad["picture"]="Foto";
		self::$trad["wallpaper"]="Ecrã de fundo";
		self::$trad["image_changer"]="Mudar";
		self::$trad["pixels"]="pixels";
		
		////	Connexion
		self::$trad["specifierLoginPassword"]="Favor especificar um login e uma senha";
		self::$trad["login"]="login";
		self::$trad["login2"]="login";
		self::$trad["placeholderLogin"]="Email / login";
		self::$trad["password"]="Senha";
		self::$trad["passwordToModify"]="Senha (a alteração)";
		self::$trad["passwordVerif"]="Confirmar senha";
		self::$trad["passwordInfo"]="Deixe em branco se você quiser manter a senha";
		self::$trad["passwordVerifError"]="Sua senha de confirmação é inválida";
		self::$trad["connexion"]="Conexão";
		self::$trad["connexion_auto"]="fique ligado";
		self::$trad["connexion_auto_info"]="Lembrar meu login e senha para conexão automática";
		self::$trad["password_oublie"]="esqueceu a senha ?";
		self::$trad["password_oublie_info"]="Enviar o meu login e senha para o meu e-mail (se especificado)";
		self::$trad["acces_guest"]="Acesso para convidado";
		self::$trad["espace_password_erreur"]="senha incorreta";
		self::$trad["version_ie"]="Seu navegador é muito antigo e não suporta todos os padrões de HTML: É recomendado atualizar";
		
		////	Affichage
		self::$trad["type_affichage"]="Visualização";
		self::$trad["type_affichage_line"]="Lista";
		self::$trad["type_affichage_block"]="Bloco";
		self::$trad["type_affichage_arbo"]="árvore";
		
		////	Sélectionner / Déselectionner tous les éléments
		self::$trad["select"]="Selecionar";
		self::$trad["select_deselect"]="Selecionar / deselecionar";
		self::$trad["tout_selectionner"]="Selecionar Tudo";
		self::$trad["inverser_selection"]="Inverter Seleção";
		self::$trad["suppr_elements"]="Apagar os elementos";
		self::$trad["deplacer_elements"]="Mover para outra pasta";
		self::$trad["voir_sur_carte"]="Mostrar no mapa";
		self::$trad["selectionner_user"]="Favor selecionar pelo menos um usuário";
		self::$trad["selectionner_2users"]="Obrigado por escolher pelo menos 2 usuários";
		self::$trad["selectionner_espace"]="Favor Selecionar pelo menos um espaço";
		
		////	Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
		self::$trad["de"]="de";
		self::$trad["a"]="a";
		self::$trad["le"]="o";
		self::$trad["debut"]="Início";
		self::$trad["fin"]="Fim";
		self::$trad["separateur_horaire"]="h";
		self::$trad["jours"]="dias";
		self::$trad["jour_1"]="Segunda";
		self::$trad["jour_2"]="Terça";
		self::$trad["jour_3"]="Quarta";
		self::$trad["jour_4"]="Quinta";
		self::$trad["jour_5"]="Sexta";
		self::$trad["jour_6"]="Sábado";
		self::$trad["jour_7"]="Domingo";
		self::$trad["mois_1"]="Janeiro";
		self::$trad["mois_2"]="Fevereiro";
		self::$trad["mois_3"]="Março";
		self::$trad["mois_4"]="Abril";
		self::$trad["mois_5"]="Maio";
		self::$trad["mois_6"]="Junho";
		self::$trad["mois_7"]="Julho";
		self::$trad["mois_8"]="Agosto";
		self::$trad["mois_9"]="Setembro";
		self::$trad["mois_10"]="Outubro";
		self::$trad["mois_11"]="Novembro";
		self::$trad["mois_12"]="Dezembro";
		self::$trad["aujourdhui"]="hoje";
		self::$trad["aff_aujourdhui"]="Visualizar hoje";
		self::$trad["modif_dates_debutfin"]="A data de fim não pode ser anterior à data de início";
		self::$trad["dates_mauvais_format"]="A data deve estar no formato dd/mm/aaaa";
		
		////	Nom & Description (pour les menus d'édition principalement)
		self::$trad["title"]="Título";
		self::$trad["name"]="Nome";
		self::$trad["description"]="Descrição";
		self::$trad["specifyName"]="Favor especificar um nome";
		
		////	Validation des formulaires
		self::$trad["ajouter"]=" Adicionar";
		self::$trad["modifier"]=" Modificar";
		self::$trad["modifier_et_acces"]="Modificar + gestão dos direitos de acesso";
		self::$trad["valider"]=" Validar";
		self::$trad["envoyer"]="Enviar";
		self::$trad["envoyer_a"]="Enviar a";
		
		////	Tri d'affichage. Tous les éléments (dossier, tâche, lien, etc...) ont par défaut une date, un auteur & une description
		self::$trad["trie_par"]="Ordenar por";
		self::$trad["SORT_dateCrea"]="data de criação";
		self::$trad["SORT_dateModif"]="data de modificação";
		self::$trad["SORT_title"]="título";
		self::$trad["SORT_description"]="descrição";
		self::$trad["SORT__idUser"]="autor";
		self::$trad["SORT_extension"]="Tipo de arquivo";
		self::$trad["SORT_octetSize"]="tamanho";
		self::$trad["SORT_downloadsNb"]="downloads";
		self::$trad["SORT_civility"]="civilidade";
		self::$trad["SORT_name"]="sobrenome";
		self::$trad["SORT_firstName"]="nome";
		self::$trad["SORT_adress"]="endereço";
		self::$trad["SORT_postalCode"]="CEP";
		self::$trad["SORT_city"]="Cidade";
		self::$trad["SORT_country"]="país";
		self::$trad["SORT_function"]="função";
		self::$trad["SORT_companyOrganization"]="sociedade / organismo";
		self::$trad["tri_ascendant"]="Ascendente";
		self::$trad["tri_descendant"]="Descendente";
		
		////	Options de suppression
		self::$trad["confirmDelete"]="Confirmar apagar?";
		self::$trad["confirmDeleteBis"]="Tem certeza ?";
		self::$trad["confirmer_suppr_dossier"]="Cuidado! Você pode não ter acesso a algumas sub-pastas: elas serão apagadas também!";
		self::$trad["supprimer"]="Apagar";
		
		////	Visibilité d'un Objet : auteur et droits d'accès
		self::$trad["auteur"]="Autor";
		self::$trad["cree_par"]="Criação";
		self::$trad["modif_par"]="Mudança";
		self::$trad["historique_element"]="histórico";
		self::$trad["guest"]="convidado";
		self::$trad["guests"]="convidados";
		self::$trad["tous"]="todos";
		self::$trad["inconnu"]="pessoa desconhecida";
		self::$trad["lecture"]="leitura";
		self::$trad["lecture_infos"]="Acesso à leitura";
		self::$trad["ecriture_limit"]="escrito limitada";
		self::$trad["ecriture_limit_infos"]="Acesso limitado de escrita : habilidade para adicionar elementos (-ELEMENT-), sem modificar o remover aqueles criados por outros usuários";
		self::$trad["ecriture"]="escrita";
		self::$trad["ecriture_infos"]="Acesso de escrita";
		self::$trad["ecriture_infos_conteneur"]="Acesso de escrita : habilidade para adicionar, editar o remover<br>todos os elementos (-ELEMENT-) da -CONTENEUR-";
		self::$trad["ecriture_auteur_admin"]="Somente o proprietário e os administradores podem alterar<br>os direitos de acesso ou remover este -CONTENEUR-";
		self::$trad["contenu_dossier"]="conteúdo";

		////	Libellé des objets
		self::$trad["OBJECTcontainer"]="contêiner";
		self::$trad["OBJECTelement"]="elemento";
		self::$trad["OBJECTfolder"]="arquivo";
		self::$trad["OBJECTdashboardNews"]="actualidade";
		self::$trad["OBJECTfile"]="arquivo";
		self::$trad["OBJECTcalendar"]="agenda";
		self::$trad["OBJECTcalendarEvent"]="evento";
		self::$trad["OBJECTforumSubject"]="assunto";
		self::$trad["OBJECTforumMessage"]="mensagem";
		self::$trad["OBJECTcontact"]="contato";
		self::$trad["OBJECTlink"]="favorito";
		self::$trad["OBJECTtask"]="tarefa";
		self::$trad["OBJECTuser"]="usuário";

		////	Envoi d'un mail (nouvel utilisateur, notification de création d'objet, etc...)
		self::$trad["MAIL_noFooter"]="Não assinar a mensagem";
		self::$trad["MAIL_noFooter_info"]="Não assinar o final da mensagem com o nome do remetente e uma ligação para o espaço";
		self::$trad["MAIL_hideRecipients"]="Ocultar destinatários";
		self::$trad["MAIL_hideRecipients_info"]="Por defeito, os destinatários de e-mail são exibidas na mensagem.";
		self::$trad["MAIL_receptionNotif"]="Pedir um recibo de entrega";
		self::$trad["MAIL_receptionNotif_info"]="Atenção! alguns clientes de e-mail não aceitam o recibo de entrega";
		self::$trad["MAIL_envoye_par"]="Enviado por";  // "Envoyé par" M. Trucmuche
		self::$trad["MAIL_envoye"]="O e-mail foi enviado!";
		self::$trad["MAIL_envoye_notif"]="O e-mail de notificação foi enviado!";
		self::$trad["MAIL_pas_envoye"]="O e-mail não pôde ser enviada ..."; // idem
		self::$trad["MAIL_element_cree"]="Nova elemento criado por";//boby
		self::$trad["MAIL_element_modifie"]="Elemento modificado por";//boby
		self::$trad["MAIL_element_sur_espace"]="no espaço";//truc
		self::$trad["MAIL_element_acces_direct"]="Clique aqui para acessar o elemento no seu espaço";

		////	Dossier & fichier
		self::$trad["giga_octet"]="Go";
		self::$trad["mega_octet"]="Mo";
		self::$trad["kilo_octet"]="Ko";
		self::$trad["dossier_racine"]="Pasta raiz";
		self::$trad["rootFolderEditInfo"]="Abra a configuração do espaço para mudar os direitos de acesso para a pasta raiz";
		self::$trad["ajouter_dossier"]="adicionar pasta";
		self::$trad["telecharger"]="Baixar arquivo";
		self::$trad["telecharge_nb"]="baixado";
		self::$trad["telecharge_nb_bis"]="vezes"; // Téléchargé '3' fois
		self::$trad["telecharger_dossier"]="Baixe a pasta";
		self::$trad["espace_disque_utilise"]="espaço em disco utilizado";
		self::$trad["espace_disque_utilise_mod_fichier"]="espaço em disco utilizado";
		self::$trad["download_alert"]="Download de arquivos grandes não estão disponíveis durante o dia";//..(9h=>19h)
		self::$trad["download_alert2"]="Tamanho máximo do arquivo durante estas horas";//..200Mo
		
		////	Infos sur une personne
		self::$trad["Civilité"]="Civilidade";
		self::$trad["name"]="Sobrenome";
		self::$trad["firstName"]="Nome";
		self::$trad["adress"]="Endereço";
		self::$trad["postalCode"]="CEP";
		self::$trad["city"]="Cidade";
		self::$trad["country"]="País";
		self::$trad["telephone"]="Telefone";
		self::$trad["telmobile"]="Celular";
		self::$trad["mail"]="Email";
		self::$trad["fax"]="Fax";
		self::$trad["website"]="Site";
		self::$trad["skills"]="Competências";
		self::$trad["hobbies"]="Interesses";
		self::$trad["function"]="Função";
		self::$trad["companyOrganization"]="Organismo / Sociedade";
		self::$trad["comment"]="Comentario";
		
		////	Captcha
		self::$trad["captcha"]="Identificação visual";
		self::$trad["captcha_info"]="Favor digitar os 4 caracteres para a sua identificação";
		self::$trad["captcha_alert_specifier"]="Favor especificar identificação visual";
		self::$trad["captcha_alert_erronee"]="A identificação visual é errada";
		
		////	Rechercher
		self::$trad["preciser_text"]="Favor especificar as palavras-chave de pelo menos 3 caracteres";
		self::$trad["rechercher"]="Buscar";
		self::$trad["rechercher_dateCrea"]="Data de criação";
		self::$trad["rechercher_dateCrea_jour"]="menos de um dia";
		self::$trad["rechercher_dateCrea_semaine"]="menos de uma semana";
		self::$trad["rechercher_dateCrea_mois"]="menos de um mês";
		self::$trad["rechercher_dateCrea_annee"]="menos de um ano";
		self::$trad["rechercher_espace"]="Buscar no espaço";
		self::$trad["recherche_avancee"]= "Busca avançada";
		self::$trad["recherche_avancee_mots_certains"]= "qualquer palavra";
		self::$trad["recherche_avancee_mots_tous"]= "todas as palavras";
		self::$trad["recherche_avancee_expression_exacte"]= "frase exata";
		self::$trad["keywords"]="Palavras-chave";
		self::$trad["liste_modules"]="Módulos";
		self::$trad["liste_champs"]="Campos";
		self::$trad["liste_champs_elements"]="Elementos envolvidos";
		self::$trad["aucun_resultat"]="Nenhum resultado encontrado";
		
		////	Gestion des inscriptions d'utilisateur
		self::$trad["usersInscription"]="cadastre-se no site";
		self::$trad["usersInscription_info"]="criar uma nova conta de usuário (validada por um administrador)";
		self::$trad["usersInscription_espace"]="registar-se o espaço";
		self::$trad["usersInscription_enregistre"]="O seu registo foi gravado : será validado o mais rapidamente possível pelo administrador do espaço";
		self::$trad["usersInscription_option_espace"]="Permitir que os visitantes se cadastrar no espaço";
		self::$trad["usersInscription_option_espace_info"]="A inscrição é na página inicial do site. A inscrição deverá, então, ser validados pelo administrador do espaço.";
		self::$trad["usersInscription_validation"]="Registros";
		self::$trad["usersInscription_validation_title"]="Validar registros de usuários o espaço";
		self::$trad["usersInscription_invalider"]="Invalidar";
		self::$trad["usersInscription_invalider_mail"]="Sua conta não foi validado no";
		
		////	Importer ou Exporter : Contact OU Utilisateurs
		self::$trad["exporter"]="Exportar";
		self::$trad["importer"]="Importar";
		self::$trad["import_export_user"]="usuários";
		self::$trad["import_export_contact"]="contatos";
		self::$trad["export_format"]="formato";
		self::$trad["specifier_fichier"]="Favor especificar um arquivo";
		self::$trad["extension_fichier"]="O tipo de arquivo é inválido. Deve ser do tipo";
		self::$trad["import_infos"]="Selecione os campos Ágora apontados através das listas de cada coluna.";
		self::$trad["import_alert"]="Favor selecionar a coluna nome nas listas";
		self::$trad["import_alert2"]="Favor selecionar pelo menos um contato para importar";
		self::$trad["import_alert3"]="O campo ágora já foi selecionado em outra coluna (cada campo ágora pode ser selecionado somente uma vez)";
		
		////	Connexion à un serveur LDAP
		self::$trad["ldap_connexion_serveur"]="Conectar a um servidor LDAP";
		self::$trad["ldap_server"]="endereço do servidor";
		self::$trad["ldap_server_port"]="porta do servidor";
		self::$trad["ldap_server_port_infos"]="''389'' por padrão";
		self::$trad["ldap_admin_login"]="Cadeia de conexão para admin";
		self::$trad["ldap_admin_login_infos"]="por exemplo ''uid=admin,ou=my_company''";
		self::$trad["ldap_admin_pass"]="Senha do administrador";
		self::$trad["ldap_base_dn"]="Grupo / base DN";
		self::$trad["ldap_base_dn_infos"]="Localização de usuários de diretório.<br> por exemplo ''ou=users,o=my_company''";
		self::$trad["ldap_connexion_erreur"]="Erro ao conectar ao servidor LDAP!";
		self::$trad["ldap_crea_auto_users"]="Criação automática de usuários após identificação";
		self::$trad["ldap_crea_auto_users_infos"]="Criar automaticamente um usuário se ele estiver ausente da Ágora, mas presente no servidor LDAP : ele vai ser atribuído a áreas acessíveis a ''todos os usuários do site''.<br>Caso contrário, o usuário. não será criado.";
		self::$trad["ldap_pass_cryptage"]="Criptografia de senhas no servidor";
		self::$trad["ldap_effacer_params"]="Excluir configuração LDAP?";
		self::$trad["ldap_pas_module_php"]="PHP módulo para conexão a um servidor LDAP não está instalado!";
		
		////	Messages d'alert ou d'erreur
		self::$trad["MSG_NOTIF_identification"]="Login ou senha inválido";
		self::$trad["MSG_NOTIF_dejapresent"]="Conta atualmente usado com um endereço IP diferente ... (uma conta pode ser utilizada em um único computador ao mesmo tempo)";
		self::$trad["MSG_NOTIF_adresseip"]="O endereço IP que você usa não é permitido para esta conta";
		self::$trad["MSG_NOTIF_acces_aucun_espace"]="O acesso ao site não é permitido para você, porque provavelmente nenhum espaço foi atribuído para você.";
		self::$trad["MSG_NOTIF_acces_fichier_dossier"]="Arquivo/Pasta não disponível";
		self::$trad["MSG_NOTIF_espace_disque"]="O espaço para armazenar seus arquivos não é suficiente, você não pode adicionar arquivo";
		self::$trad["MSG_NOTIF_type_interdit"]="Tipo de arquivo não permitido";
		self::$trad["MSG_NOTIF_type_version"]="Tipo de arquivo diferente do original";
		self::$trad["MSG_NOTIF_deplacement_dossier"]="Você não pode mover a pasta dentro de si ...!";
		self::$trad["MSG_NOTIF_duplicateName"]="Um elemento com o mesmo nome já existe.";
		self::$trad["MSG_NOTIF_fileName"]="Um arquivo com o mesmo nome já existe (não foi substituído)";
		self::$trad["MSG_NOTIF_chmod_DATAS"]="A pasta ''DATAS'' não está acessível por escrito. Você precisa dar um acesso de leitura e gravação ao proprietário e do grupo (''chmod 775'').";
		self::$trad["MSG_NOTIF_nb_users"]="Você não pode adicionar um novo usuário: limitado a";
		self::$trad["MSG_NOTIF_update"]="Atualização concluída. É aconselhável reiniciar o navegador antes de reconectar-se.";
		self::$trad["MSG_NOTIF_user_existdeja"]="Login já existe: o usuário não foi criado";
		
		////	header menu
		self::$trad["HEADER_MENU_espaces_dispo"]="Outros espaços disponíveis";
		self::$trad["HEADER_MENU_display_elem"]="Mostrar os elementos";
		self::$trad["HEADER_MENU_display_normal"]="atribuído a mim";
		self::$trad["HEADER_MENU_display_title_normal"]="Este é o display / padrão normal";
		self::$trad["HEADER_MENU_display_autor"]="que eu criei";
		self::$trad["HEADER_MENU_display_title_autor"]="Para exibir apenas os itens que eu criei";
		self::$trad["HEADER_MENU_display_all"]="Todos os elementos do espaço (admin)";
		self::$trad["HEADER_MENU_display_title_all"]="Para o administrador do espaço : para mostrar todos os elementos do espaço, mesmo aqueles que não são atribuídos para o administrador !";
		self::$trad["HEADER_MENU_recherche_elem"]="Buscar um elemento no espaço";
		self::$trad["HEADER_MENU_documentation"]="Documentação";
		self::$trad["HEADER_MENU_sortie_agora"]="Sair da Ágora";
		self::$trad["HEADER_MENU_shortcuts"]="Atalhos";
		self::$trad["HEADER_MENU_seul_utilisateur_connecte"]="Atualmente somente você está conectado";
		self::$trad["HEADER_MENU_en_ligne"]="Conectados";
		self::$trad["HEADER_MENU_connecte_a"]="conectado ao Site a";   // M. Bidule truc "connecté au site à" 12:45
		self::$trad["HEADER_MENU_messenger"]="Mensagem Instantânea";
		self::$trad["HEADER_MENU_envoye_a"]="Enviado a";
		self::$trad["HEADER_MENU_ajouter_message"]="Adicionar uma mensagem";
		self::$trad["HEADER_MENU_specifier_message"]="Favor especificar uma mensagem";
		self::$trad["HEADER_MENU_enregistrer_conversation"]="Salvar a conversa";
		
		////	Footer
		self::$trad["FOOTER_page_generee"]="página gerada em";
		
		////	Password_oublie
		self::$trad["PASS_OUBLIE_preciser_mail"]="Digite seu endereço de e-mail para receber seu nome de usuário e senha";
		self::$trad["PASS_OUBLIE_mail_inexistant"]="O e-mail não existe no banco de dados";
		self::$trad["PASS_OUBLIE_mail_objet"]="Conexão com o espaço";
		self::$trad["PASS_OUBLIE_mail_contenu"]="o seu login";
		self::$trad["PASS_OUBLIE_mail_contenu_bis"]="Clique aqui para redefinir a sua senha";
		self::$trad["PASS_OUBLIE_prompt_changer_pass"]="Especificar a sua nova senha";
		self::$trad["PASS_OUBLIE_idNewPassword_expire"]="O link para regenerar a senha expirou .. obrigado a reiniciar o procedimento";
		self::$trad["PASS_OUBLIE_password_reinitialise"]="Sua nova senha foi registrado !";
		
		////	menu_edit_objet
		self::$trad["EDIT_OBJET_alert_aucune_selection"]="Você deve selecionar pelo menos uma pessoa ou um espaço";
		self::$trad["EDIT_OBJET_alert_pas_acces_perso"]="Você não está atribuído ao elemento. validar todos os mesmos ?";
		self::$trad["EDIT_OBJET_alert_ecriture_obligatoire"]="Deve ter pelo menos uma pessoa ou um espaço alocado para a escrita";
		self::$trad["EDIT_OBJET_alert_ecriture_deconseille"]="Atenção! <br><br>Com acesso de escrita, TODAS as mensagens podem ser apagadas! <br><br>Portanto, é recomendável para limitar o acesso de escrita";
		self::$trad["EDIT_OBJET_alert_guest"]="Favor especificar um nome ou apelido";
		self::$trad["EDIT_OBJET_accessRight"]="Direitos de acesso";
		self::$trad["EDIT_OBJET_accessRightContent"]="Direitos de acesso ao conteúdo";
		self::$trad["EDIT_OBJET_identification"]="Identificação";
		self::$trad["EDIT_OBJET_espace_pas_module"]="O módulo atual ainda não tenha sido adicionado a este espaço";
		self::$trad["EDIT_OBJET_allUsers"]="Todos os usuários";
		self::$trad["EDIT_OBJET_allUsers_espaces"]="todos os espaços";
		self::$trad["EDIT_OBJET_espace_guests"]="Os convidados deste espaço público";
		self::$trad["EDIT_OBJET_guest"]="Seu nome/apelido";
		self::$trad["EDIT_OBJET_admin_espace"]="Administrador do Espaço:<br>tem acesso de escrita a todos os elementos atribuídos ao espaço";
		self::$trad["EDIT_OBJET_tous_espaces"]="Visualizar todos os meus espaços";
		self::$trad["EDIT_OBJET_notif_mail"]="Enviar email de notificação";
		self::$trad["EDIT_OBJET_notif_mail_label"]="Enviar email de notificação";
		self::$trad["EDIT_OBJET_notif_mail_joindre_fichiers"]="Anexar arquivos à notificação";
		self::$trad["EDIT_OBJET_notif_mail_info"]="Enviar e-mail de notificação de criação/modificação para aqueles que têm acesso ao elemento";
		self::$trad["EDIT_OBJET_notif_mail_selection"]="Selecionar manualmente os destinatários de notificações";
		self::$trad["EDIT_OBJET_notif_tous_users"]="Visualizar mais usuários";
		self::$trad["EDIT_OBJET_accessRightSubFolders"]="dar direitos iguais a todas as subpastas";
		self::$trad["EDIT_OBJET_accessRightSubFolders_info"]="Alargar os direitos de acesso, para as subpastas que você pode editar";
		self::$trad["EDIT_OBJET_shortcut"]="Atalho";
		self::$trad["EDIT_OBJET_shortcut_info"]="Mostrar um atalho no menu principal";
		self::$trad["EDIT_OBJET_fichier_joint"]="Adicionar arquivos";
		self::$trad["EDIT_OBJET_fichier_joint_info"]="Adicionar imagens, vídeos, PDF, Word.. ao objeto atual.<br>Imagens e vídeos podem ser integrados diretamente no editor de texto.";
		self::$trad["EDIT_OBJET_inserer_fichier"]="Mostrar na descrição";
		self::$trad["EDIT_OBJET_inserer_fichier_info"]="Mostrar imagem / vídeo / leitor de mp3 ... na descrição acima. A inserção é realizada após a validação do formulário.";
		self::$trad["EDIT_OBJET_demandToConfirm"]="O seu pedido foi registrado. Irá ser confirmada em breve.";
		
		////	Formulaire d'installation
		self::$trad["INSTALL_connexion_bdd"]="Conexão para o banco de dados";
		self::$trad["INSTALL_db_host"]="Hostname do servidor";
		self::$trad["INSTALL_db_name"]="Nome da Base de Dados";
		self::$trad["INSTALL_db_login"]="Nome de usuário";
		self::$trad["INSTALL_config_admin"]="Administrator do Ágora";
		self::$trad["INSTALL_errorConnectSGBD"]="Sem conexão com o banco de dados MySQL";
		self::$trad["INSTALL_errorConnectIdentification"]="Identificação falhou ao banco de dados MySQL";
		self::$trad["INSTALL_errorAppliInstalled"]="A instalação já foi executada neste banco de dados. Obrigado simplesmente remover o banco de dados se para reiniciar a instalação.";
		self::$trad["INSTALL_errorConnectDbConfirmInstall"]="Conexão com o banco de dados não foi estabelecida, confirme mesmo assim?";
		self::$trad["INSTALL_PhpOldVersion"]="Agora-Project requer uma versão mais recente do PHP";
		self::$trad["INSTALL_confirm_install"]="Confirmar instalação?";
		self::$trad["INSTALL_install_ok"]="Ágora-Project foi instalado.";
		self::$trad["INSTALL_spaceDescription"]="Espaço de partilha e de trabalho colaborativo";

		////	MODULE_PARAMETRAGE
		////
		self::$trad["AGORA_headerModuleName"]="Parametrização geral";
		self::$trad["AGORA_description_module"]="Parametrização geral";
		self::$trad["AGORA_sav"]="Salvar banco de dados e arquivos";
		self::$trad["AGORA_sav_alert"]="A criação do arquivo de backup pode demorar alguns minutos ... e baixar algumas dezenas de minutos.";
		self::$trad["AGORA_sav_bdd"]="Salvar banco de dados";
		self::$trad["AGORA_espace_disque_invalide"]="O limite de espaço em disco deve ser um inteiro";
		self::$trad["AGORA_confirmez_modification_site"]="confirmar as alterações?";
		self::$trad["AGORA_name"]="Nome do site";
		self::$trad["AGORA_footerHtml"]="Rodapé";
		self::$trad["AGORA_footerHtml_info"]="Para incluir instrumentos estatísticos por exemplo";
		self::$trad["AGORA_lang"]="Idioma padrão";
		self::$trad["AGORA_timezone"]="Fuso horário";
		self::$trad["AGORA_spaceName"]="Nome do espaço principal";
		self::$trad["AGORA_limite_espace_disque"]="espaço em disco para los arquivos";
		self::$trad["AGORA_logsTimeOut"]="Vida de prateleira de LOGS";
		self::$trad["AGORA_skin"]="Cor da interface";
		self::$trad["AGORA_noir"]="Preto";
		self::$trad["AGORA_blanc"]="Branco";
		self::$trad["AGORA_erreur_wallpaper_logo"]="A imagem do papel de parede e o logo deve estar no formato JPG ou PNG.";
		self::$trad["AGORA_suppr_wallpaper"]="Apagar o papel de parede?";
		self::$trad["AGORA_logo_footer"]="Logo no rodapé";
		self::$trad["AGORA_logo_footer_url"]="URL";
		self::$trad["AGORA_messengerDisabled"]="Mensagens instantâneas ativadas";
		self::$trad["AGORA_personalCalendarsDisabled"]="Calendários pessoais ativado por padrão";
		self::$trad["AGORA_personalCalendarsDisabled_infos"]="Adicionar uma agenda pessoal para a criação de um utilizador. O calendário pode, contudo, ser desactivado depois, quando se muda a conta do utilizador.";
		self::$trad["AGORA_moduleLabelDisplay"]="Nome dos módulos na barra de menu";
		self::$trad["AGORA_moduleLabelDisplay_masquer"]="Esconder";
		self::$trad["AGORA_moduleLabelDisplay_icones"]="acima de cada ícone do módulo";
		self::$trad["AGORA_moduleLabelDisplay_page"]="uapenas para o módulo atual";
		self::$trad["AGORA_personsSort"]="Ordenar usuários e contatos com";
		self::$trad["AGORA_versions"]="Versões";
		self::$trad["AGORA_version_agora_maj"]="atualizado";
		self::$trad["AGORA_fonction_mail_desactive"]="função do PHP para enviar e-mails! desativada!";
		self::$trad["AGORA_fonction_mail_infos"]="Alguns hospedagens de site desativam a função PHP para enviar e-mails por razões de segurança ou por saturação de servidores (SPAM)";
		self::$trad["AGORA_fonction_image_desactive"]="Função de manipulação de imagens e miniaturas (PHP GD2): desativada!";

		////	MODULE_LOG
		////
		self::$trad["LOG_headerModuleName"]="Logs";
		self::$trad["LOG_description_module"]="Logs - Log de ​​eventos";
		self::$trad["LOG_filtre"]="Filtro";
		self::$trad["LOG_date_heure"]="Data / Hora";
		self::$trad["LOG_espace"]="Space";
		self::$trad["LOG_module"]="Módulo";
		self::$trad["LOG_objectType"]="typo de objeto";
		self::$trad["LOG_action"]="Action";
		self::$trad["LOG_utilisateur"]="Usuário";
		self::$trad["LOG_ipAdress"]="IP";
		self::$trad["LOG_comment"]="comentário";
		self::$trad["LOG_no_logs"]="log Não";
		self::$trad["LOG_filtre_a_partir"]="filtrada da";
		self::$trad["LOG_chercher"]="Pesquisar";
		self::$trad["LOG_connexion"]="conexão";
		self::$trad["LOG_deconnexion"]="sair";
		self::$trad["LOG_consult"]="consulta";
		self::$trad["LOG_consult2"]="download";
		self::$trad["LOG_add"]="adicionado";
		self::$trad["LOG_delete"]="supressão";
		self::$trad["LOG_modif"]="mudança";

		////	MODULE_ESPACE
		////
		// Menu principal
		self::$trad["SPACE_headerModuleName"]="Espaços";
		self::$trad["SPACE_description_module"]="Espaços do site";
		self::$trad["SPACE_description_module_infos"]="O site (ou espaço principal) pode ser subdividido em vários espaços";
		// Header_menu.inc.php
		self::$trad["SPACE_gerer_espaces"]="Geranciar os espaços do site";
		self::$trad["SPACE_parametrage"]="Parametrização do espaço";
		self::$trad["SPACE_parametrage_infos"]="Parametrização do espaço (descrição, módulos, usuários, etc)";
		// Index.php
		self::$trad["SPACE_confirm_suppr_espace"]="Confirmar apagar? Nota, os dados atribuído a esse espaço só serão perdidos para sempre!";
		self::$trad["SPACE_espace"]="espaço";
		self::$trad["SPACE_espaces"]="espaços";
		self::$trad["SPACE_definir_acces"]="Para ser definido!";
		self::$trad["SPACE_modules"]="Módulos";
		self::$trad["SPACE_ajouter_espace"]="Adicionar um espaço";
		self::$trad["SPACE_supprimer_espace"]="Apagar espaço?";
		self::$trad["MSG_NOTIF_suppr_espace_impossible"]="Você não pode apagar o espaço atual";
		// Espace_edit.php
		self::$trad["SPACE_gestion_acces"]="Usuários atribuídos ao espaço ";
		self::$trad["SPACE_selectionner_module"]="Você deve selecionar pelo menos um módulo";
		self::$trad["SPACE_modules_espace"]="Módulos no espaço";
		self::$trad["SPACE_modules_rank"]="Mover-se para definir a ordem de exibição dos módulos";
		self::$trad["SPACE_espace_public"]="Espaço público";
		self::$trad["SPACE_public_infos"]="Fornece acesso a pessoas que não tenham contas no site: ''Convidados''. Possibilidade de especificar uma senha para proteger o acesso.";
		self::$trad["SPACE_usersInvitation"]="Os usuários podem enviar convites por e-mail";
		self::$trad["SPACE_usersInvitation_infos"]="Todos os usuários podem enviar e-mail convites para se juntar ao espaço";
		self::$trad["SPACE_allUsers"]="Todos os usuários do site";
		self::$trad["SPACE_utilisation"]=" Usuários";
		self::$trad["SPACE_utilisation_info"]="Acesso normal ao espaço";
		self::$trad["SPACE_administration"]="Administrator";
		self::$trad["SPACE_administration_info"]="Administrador do espaço: Acceso em escrita a todos os elementos do espaço + enviar convites por email + criação de usuários no espaço";
		self::$trad["SPACE_creer_agenda_espace"]="Criar um calendário compartilhado para o espaço";
		self::$trad["SPACE_creer_agenda_espace_info"]="O calendário irá ter o mesmo nome que o espaço. Isto pode ser útil se os calendários dos usuários estão desativados.";

		////	MODULE_UTILISATEUR
		////
		// Menu principal
		self::$trad["USER_headerModuleName"]="Usuários";
		self::$trad["USER_description_module"]="Usuários do espaço";
		self::$trad["USER_ajout_utilisateurs_groupe"]="Os usuários também podem criar grupos";
		// Index.php
		self::$trad["USER_utilisateurs_site"]="Usuários do site";
		self::$trad["USER_gerer_utilisateurs_site"]="Geranciar os Usuários do site";
		self::$trad["USER_utilisateurs_site_infos"]="Todos os usuários do site : todos os espaços combinados";
		self::$trad["USER_utilisateurs_espace"]="Usuários do espaço";
		self::$trad["USER_confirm_desaffecter_utilisateur"]="Confirmar a exclusão do espaço do usuário atual?";
		self::$trad["USER_suppr_definitivement"]="Apagar definitivamente";
		self::$trad["USER_desaffecter"]="Excluir do espaço";
		self::$trad["USER_tous_user_affecte_espace"]="Todos os usuários do site são atribuídos a esse espaço: não há exclusão possível";
		self::$trad["USER_user"]="Usuários";
		self::$trad["USER_users"]="Usuários";
		self::$trad["USER_affecter_utilisateur"]="Adicionar um usuário existente no espaço";
		self::$trad["USER_ajouter_utilisateur"]="Adicionar um usuário";
		self::$trad["USER_ajouter_utilisateur_site"]="Criar um usuário no site: nenhum espaço atribuído por defeito!";
		self::$trad["USER_ajouter_utilisateur_espace"]="Criar um usuário e adicioná-lo ao espaço atual";
		self::$trad["USER_envoi_coordonnees"]="Enviar login e senha";
		self::$trad["USER_envoi_coordonnees_info"]="Enviar a usuários (via email)<br> seu login e uma <u>nova</u> senha";
		self::$trad["USER_envoi_coordonnees_info2"]="Enviar a cada novo usuário <br> um e-mail com informações de login.";
		self::$trad["USER_envoi_coordonnees_confirm"]="Cuidado! Senhas serão reinicializadas ! Confirme mesmo assim?";
		self::$trad["USER_mail_coordonnees"]="As suas informações de login para o seu espaço";
		self::$trad["USER_aucun_utilisateur"]="Nenhum usuário atribuído a esse espaço por enquanto";
		self::$trad["USER_lastConnection"]="Última conexão";
		self::$trad["USER_liste_espaces"]="Espaços do usuário";
		self::$trad["USER_aucun_espace"]="Nenhum espaço";
		self::$trad["USER_adminGeneral"]="Administrador geral do site";
		self::$trad["USER_adminSpace"]="Administrador do espaço";
		self::$trad["USER_userSpace"]="Usuários do espaço";
		self::$trad["USER_pas_connecte"]="Ainda não conectado";
		self::$trad["USER_modifier"]="Modificar o usuário";
		self::$trad["USER_modifier_mon_profil"]="Modificar meu perfil";
		// Invitation.php
		self::$trad["USER_envoi_invitation"]="Convidar alguém para juntar no espaço";
		self::$trad["USER_envoi_invitation_info"]="O convite será enviado por e-mail";
		self::$trad["USER_objet_mail_invitation"]="Convite de "; // ..Jean DUPOND
		self::$trad["USER_admin_guest_espace"]="convida você em "; // Jean DUPOND "vous invite à rejoindre l'espace" Mon Espace
		self::$trad["USER_confirmer_invitation"]="Clique aqui para confirmar o convite";
		self::$trad["USER_invitation_a_confirmer"]="aguardando confirmação de convites";
		self::$trad["USER_idInvitation_expire"]="O link para o seu convite expirou...";
		self::$trad["USER_invitationConfirmPassword"]="Escolha sua senha e clique em 'OK' para confirmar o seu convite";
		self::$trad["USER_invitation_valide"]="Seu convite foi validado !";
		// groupes.php
		self::$trad["USER_groupe_espace"]="grupos de usuários do espaço";
		self::$trad["USER_groupe_info"]="editar os grupos de usuários";
		self::$trad["USER_droit_gestion_groupes"]="Cada grupo pode ser modificado por seu autor ou pelo administrador geral";
		// Utilisateur_affecter.php
		self::$trad["USER_preciser_recherche"]="Favor indicar um sobrenome, um nome ou um endereço de e-mail";
		self::$trad["USER_affecter_user_confirm"]="Confirme a atribuiçãos?";
		self::$trad["USER_rechercher_user"]="Buscar usuários para adicioná-los ao espaço";
		self::$trad["USER_tous_users_affectes"]="Todos os usuários do site já estam atribuídos a este espaço";
		self::$trad["USER_affecter_user"]="Atribuir usuários ao espaço:";
		self::$trad["USER_aucun_users_recherche"]="Nenhum usuário encontrado";
		// Utilisateur_edit.php & CO
		self::$trad["USER_specifyName"]="Favor especificar um sobrenome";
		self::$trad["USER_specifyFirstName"]="Favor especificar um nome";
		self::$trad["USER_specifier_identifiant"]="Favor especificar um usuário";
		self::$trad["USER_specifier_password"]="Favor especificar uma senha";
		self::$trad["USER_specifyMailAsLogin"]="Recomenda-se usar um e-mail como login. Ainda validar o formulário?";
		self::$trad["USER_langs"]="Idioma";
		self::$trad["USER_agenda_perso_desactive"]="Agenda pessoal desactivado";
		self::$trad["USER_agenda_perso_desactive_infos"]="Por defeito, a agenda pessoal permanece sempre acessível ao usuário, mesmo que o módulo Agenda do espaço está desactivado.";
		self::$trad["USER_connectionSpace"]="Espaço visualizado quando conecta-se";
		self::$trad["USER_notification_mail"]="Enviar e-mail de notificação da criação";
		self::$trad["USER_alert_notification_mail"]="Lembre-se de especificar um e-mail!";
		self::$trad["USER_adresses_ip"]="Endereço IP de Controle";
		self::$trad["USER_info_ipAdress"]="Se você especificar uma (ou mais) endereços IP, o usuário poderá se conectar apenas se utiliza os endereços IP especificados";
		self::$trad["USER_identifiant_deja_present"]="O login já existe. Favor especificar outro.";
		self::$trad["USER_mail_deja_present"]="O endereço de email já existe. Favor especificar outro.";
		self::$trad["USER_mail_objet_nouvel_utilisateur"]="Nova conta em";  // "...sur" l'Agora machintruc
		self::$trad["USER_mail_nouvel_utilisateur"]="Sua conta de usuário foi criado em";  // idem
		self::$trad["USER_mail_infos_connexion"]="Conexão com o seu login e senha a abaixo contras";
		self::$trad["USER_mail_infos_connexion2"]="Obrigado a manter este e-mail para seus registros.";
		// Utilisateur_Messenger.php
		self::$trad["USER_gestion_messenger_livecounter"]="Geranciar Mensagens Instantâneas";
		self::$trad["USER_visibilite_messenger_livecounter"]="Usuários que podem me ver online e discutir comigo em mensagens instantâneas";
		self::$trad["USER_aucun_utilisateur_messenger"]="Nenhum usuário nesse momento";
		self::$trad["USER_voir_aucun_utilisateur"]="Mensagens desativado (nenhum usuário pode ver-me)";
		self::$trad["USER_voir_tous_utilisateur"]="Todos os usuários podem ver-me";
		self::$trad["USER_voir_certains_utilisateur"]="Alguns usuários poderão me ver";

		////	MODULE_TABLEAU BORD
		////
		// Menu principal + options du module
		self::$trad["DASHBOARD_headerModuleName"]="Actualidades";
		self::$trad["DASHBOARD_description_module"]="Actualidades & elementos novos";
		self::$trad["DASHBOARD_ajout_actualite_admin"]="Apenas o administrador pode adicionar actualidades";
		// Index.php
		self::$trad["DASHBOARD_new_elems"]="novidades";
		self::$trad["DASHBOARD_new_elems_info"]="Elementos criados entre <br>";
		self::$trad["DASHBOARD_new_elems_realises"]="atuais";
		self::$trad["DASHBOARD_new_elems_realises_info"]="Eventos e tarefas que ocorrem entre <br>";
		self::$trad["DASHBOARD_plugin_connexion"]="desde o minha última conexão";
		self::$trad["DASHBOARD_plugin_jour"]="hoje";
		self::$trad["DASHBOARD_plugin_semaine"]="esta semana";
		self::$trad["DASHBOARD_plugin_mois"]="este mês";
		self::$trad["DASHBOARD_autre_periode"]="Otro período";
		self::$trad["DASHBOARD_pas_nouveaux_elements"]="Nenhum elemento selecionado para o período";
		self::$trad["DASHBOARD_actualites"]="Actualidades";
		self::$trad["DASHBOARD_actualite"]="actualidade";
		self::$trad["DASHBOARD_actualites"]="actualidades";
		self::$trad["DASHBOARD_ajout_actualite"]="Adicionar uma actualidade";
		self::$trad["DASHBOARD_actualites_offline"]="Actualidades arquivadas";
		self::$trad["DASHBOARD_pas_actualites"]="Nenhuma actualidade";
		// Actualite_edit.php
		self::$trad["DASHBOARD_ala_une"]="Mostrar em primeira página";
		self::$trad["DASHBOARD_ala_une_info"]="Realce esta notícia";
		self::$trad["DASHBOARD_offline"]="Arquivado";
		self::$trad["DASHBOARD_offline_info"]="Arquivar esta notícia (Offline)";
		self::$trad["DASHBOARD_dateOnline"]="On-line na";
		self::$trad["DASHBOARD_dateOnline_info"]="Defina uma data automática online (Online). O novo será ''Arquivado'', entretanto";
		self::$trad["DASHBOARD_dateOnline_alerte"]="A notícia foi arquivada na expectativa de sua automática on-line";
		self::$trad["DASHBOARD_dateOffline"]="Arquivar a";
		self::$trad["DASHBOARD_dateOffline_info"]="Definir uma data de arquivamento automático (off-line)";

		////	MODULE_AGENDA
		////
		// Menu principal
		self::$trad["CALENDAR_headerModuleName"]="Agendas";
		self::$trad["CALENDAR_description_module"]="Agendas pessoais e agendas partilhadas";
		self::$trad["CALENDAR_ajout_agenda_ressource_admin"]="Apenas o administrador pode adicionar agendas de recursos";
		self::$trad["CALENDAR_ajout_categorie_admin"]="Apenas o administrador pode adicionar categorias de evento";
		// Index.php
		self::$trad["CALENDAR_afficher_tous_agendas"]="Mostrar todos os calendários";
		self::$trad["CALENDAR_masquer_tous_agendas"]="Esconder todos os calendários";
		self::$trad["CALENDAR_cocher_tous_agendas"]="Confira/atirar todos os calendários";
		self::$trad["CALENDAR_cocher_agendas_users"]="Confira/atirar usuários";
		self::$trad["CALENDAR_cocher_agendas_ressources"]="Confira/atirar os recursos";
		self::$trad["CALENDAR_imprimer_agendas"]="Imprimir a/as agendas";
		self::$trad["CALENDAR_imprimer_agendas_infos"]="imprimir de modo paisagem";
		self::$trad["CALENDAR_ajouter_agenda_ressource"]="Adicionar uma agenda compartilhado";
		self::$trad["CALENDAR_ajouter_agenda_ressource_bis"]="Adicionar uma agenda compartilhado : quarto, carro, vídeo, etc.";
		self::$trad["CALENDAR_exporter_ical"]="Exportar os eventos (formato iCal)";
		self::$trad["CALENDAR_exporter_ical_mail"]="Exportar os eventos por e-mail (iCal)";
		self::$trad["CALENDAR_exporter_ical_mail2"]="Para integrar o calendário IPHONE, ANDROID, OUTLOOK, GOOGLE CALENDAR...";
		self::$trad["CALENDAR_importer_ical"]="Importar os eventos (iCal)";
		self::$trad["CALENDAR_importer_ical_etat"]="Estado";
		self::$trad["CALENDAR_importer_ical_deja_present"]="Para importar";
		self::$trad["CALENDAR_importer_ical_a_importer"]="Te importeren";
		self::$trad["CALENDAR_suppr_anciens_evt"]="Apagar os eventos anteriores";
		self::$trad["CALENDAR_suppr_anciens_evt_info"]="Apagar os eventos que precederam hoje";
		self::$trad["CALENDAR_confirm_suppr_anciens_evt"]="Tem certeza de que deseja apagar permanentemente os eventos que precedem hoje?";
		self::$trad["CALENDAR_ajouter_evt_heure"]="Adicionar um evento a";
		self::$trad["CALENDAR_ajouter_evt_jour"]="Adicionar um evento nesta data";
		self::$trad["CALENDAR_evt_jour"]="Dia";
		self::$trad["CALENDAR_evt_semaine"]="Semana";
		self::$trad["CALENDAR_evt_semaine_w"]="Semana de Trabalho";
		self::$trad["CALENDAR_evt_mois"]="Mês";
		self::$trad["CALENDAR_voir_num_semaine"]="Ver la semana n°"; //...5
		self::$trad["CALENDAR_periode_suivante"]="período seguinte";
		self::$trad["CALENDAR_periode_precedante"]="Período anterior";
		self::$trad["CALENDAR_affectations_evt"]="Eventos na agenda de ";
		self::$trad["CALENDAR_affectationUnconfirmed"]="A aguardar confirmação : ";
		self::$trad["CALENDAR_evenements_proposes_pour_agenda"]="Eventos propostos para"; // "Videoprojecteur" / "salle de réunion" / etc.
		self::$trad["CALENDAR_evenements_proposes_mon_agenda"]="Eventos propostos para a minha agenda";
		self::$trad["CALENDAR_evenement_propose_par"]="Proposto por";  // "Proposé par" M. Bidule
		self::$trad["CALENDAR_evenement_integrer"]="Incorporar o evento para a agenda ?";
		self::$trad["CALENDAR_evenement_pas_integrer"]="Apagar o evento proposto ?";
		self::$trad["CALENDAR_supprimer_evt_agenda"]="Apagar ?";
		self::$trad["CALENDAR_supprimer_evt_agendas"]="Apagar eventos de todas as agendas ?";
		self::$trad["CALENDAR_supprimer_evt_date"]="Apagar essa data ?";
		self::$trad["CALENDAR_evt_prive"]="Evento privado";
		self::$trad["CALENDAR_evt_proprio"]="Eventos que criei";
		self::$trad["CALENDAR_aucun_evt"]="Não há eventos";
		self::$trad["CALENDAR_proposer"]="Propor um evento";
		self::$trad["CALENDAR_synthese"]="Síntese de agendas";
		self::$trad["CALENDAR_pourcent_agendas_occupes"]="Agendas ocupadas";  // Agendas occupés : 2/5
		self::$trad["CALENDAR_aucun_agenda_visible"]="Nenhuma agenda";
		// Evenement.php
		self::$trad["CALENDAR_categorie"]="Categoria";
		self::$trad["CALENDAR_visibilite"]="Visibilidade";
		self::$trad["CALENDAR_visibilite_public"]="público";
		self::$trad["CALENDAR_visibilite_public_cache"]="público mas com detalhes ocultos";
		self::$trad["CALENDAR_visibilite_prive"]="privado";
		//  Agenda_edit.php
		self::$trad["CALENDAR_affichage_evt"]="Mostrar eventos";
		self::$trad["CALENDAR_affichage_evt_border"]="Fronteira com a cor da categoria";
		self::$trad["CALENDAR_affichage_evt_background"]="Fundo com a cor da categoria";
		self::$trad["CALENDAR_timeSlot"]="Intervalo de tempo";
		// Evenement_edit.php
		self::$trad["CALENDAR_periodicite"]="Evento periódico";
		self::$trad["CALENDAR_period_weekDay"]="Todas as semanas";
		self::$trad["CALENDAR_period_monthDay"]="Dias do mês";
		self::$trad["CALENDAR_period_month"]="Todos os meses";
		self::$trad["CALENDAR_period_month_xdumois"]="do mês"; // Le 21 du mois
		self::$trad["CALENDAR_period_year"]="Todos os anos";
		self::$trad["CALENDAR_periodDateEnd"]="Fim da periodicidade";
		self::$trad["CALENDAR_exception_periodicite"]="Exceção de periodicidade";
		self::$trad["CALENDAR_agendas_affectations"]="Atribuição de agendas";
		self::$trad["CALENDAR_verif_nb_agendas"]="Favor selecionar ao menos uma agenda";
		self::$trad["CALENDAR_input_proposer"]="Enviar o evento ao proprietário da agenda";
		self::$trad["CALENDAR_input_affecter"]="Adicionar o evento para a agenda";
		self::$trad["CALENDAR_info_proposer"]="Enviar o evento (você não tem acesso de gravação para a agenda)";
		self::$trad["CALENDAR_info_pas_modif"]="Modificação não autorizada, porque você não tem acesso de gravação para esta agenda";
		self::$trad["CALENDAR_visibilite_info"]="<u>Público</u>: visibilidade normal.<br><br><u>Público, mas os detalhes ocultos</u>: acesso somente leitura o calendário, exibe apenas o período de tempo do evento.<br><br><u>Privado</u>: visível somente se ele pode editar Evento.";
		self::$trad["CALENDAR_edit_limite"]="Você não é o autor do evento: você só pode gerenciar seus calendários atribuições";
		self::$trad["CALENDAR_creneau_occupe"]="O slot já está ocupado na agenda :";
		// Categories.php
		self::$trad["CALENDAR_gerer_categories"]="Geranciar categorias de eventos";
		self::$trad["CALENDAR_categories_evt"]="Categorias de eventos";
		self::$trad["CALENDAR_droit_gestion_categories"]="Cada categoria pode ser modificado por seu autor ou pelo administrador geral";

		////	MODULE_FICHIER
		////
		// Menu principal
		self::$trad["FILE_headerModuleName"]="Arquivos";
		self::$trad["FILE_description_module"]="Gerenciador de arquivos";
		self::$trad["FILE_AdminRootFolderAddContent"]="Apenas o administrador pode adicionar elementos na pasta raiz";
		// Index.php
		self::$trad["FILE_ajouter_fichier"]="Adicionar arquivos";
		self::$trad["FILE_ajouter_fichier_alert"]="Arquivos do servidor não gravável! Obrigado para manter contato com o administrador";
		self::$trad["FILE_telecharger_selection"]="Selecção de downloads";
		self::$trad["FILE_nb_versions_fichier"]="versões do arquivo"; // n versions du fichier
		self::$trad["FILE_ajouter_versions_fichier"]="adicionar nova versão do arquivo";
		self::$trad["FILE_aucun_fichier"]="Nenhum arquivo no momento";
		// fichier_edit_ajouter.php  &  Fichier_edit.php
		self::$trad["FILE_limite_chaque_fichier"]="Os arquivos não devem ultrapassar"; // ...2 Mega Octets
		self::$trad["FILE_optimiser_images"]="Limite de tamanho a "; // ..1024*768 pixels
		self::$trad["FILE_updatedName"]="O nome do arquivo será substituído pela nova versão";
		self::$trad["FILE_erreur_taille_fichier"]="Arquivo grande demais";
		self::$trad["FILE_ajout_multiple_info"]="Pressione o 'Shift' or 'Ctrl' para selecionar vários arquivos";
		self::$trad["FILE_selectionner_fichier"]="Favor Selecione pelo menos um arquivo";
		self::$trad["FILE_contenu"]="conteúdo";
		// Versions_fichier.php
		self::$trad["FILE_versions_de"]="Versões de"; // versions de fichier.gif
		self::$trad["FILE_confirmer_suppression_version"]="Confirmar apagar esta versão?";

		////	MODULE_FORUM
		////
		// Menu principal
		self::$trad["FORUM_headerModuleName"]="Fórum";
		self::$trad["FORUM_description_module"]="Fórum";
		self::$trad["FORUM_ajout_sujet_admin"]="Apenas o administrador pode adicionar assuntos";
		self::$trad["FORUM_ajout_sujet_theme"]="Os usuários também podem adicionar temas";
		// TRI
		self::$trad["SORT_dateLastMessage"]="última mensagem";
		// Index.php & Sujet.php
		self::$trad["FORUM_sujet"]="assunto";
		self::$trad["FORUM_sujets"]="assuntos";
		self::$trad["FORUM_message"]="mensagem";
		self::$trad["FORUM_messages"]="mensagens";
		self::$trad["FORUM_dernier_message"]="último de";
		self::$trad["FORUM_ajouter_sujet"]="Adicionar um assunto";
		self::$trad["FORUM_voir_sujet"]="Mostrar o assunto";
		self::$trad["FORUM_ajouter_message"]="Adicionar uma mensagem";
		self::$trad["FORUM_repondre"]="Responder";
		self::$trad["FORUM_citer"]="Citar";
		self::$trad["FORUM_message_repondre"]="Responder a esta mensagem";
		self::$trad["FORUM_message_citer"]="Responder e citar esta mensagem";
		self::$trad["FORUM_aucun_sujet"]="Nenhum assunto por enquanto";
		self::$trad["FORUM_aucun_message"]="Nenhuma mensagem";
		self::$trad["FORUM_confirme_suppr_message"]="Confirmar apagar a mensagem (e respectivas sub-mensagens)?";
		self::$trad["FORUM_notifier_dernier_message"]="Avise-me por e-mail";
		self::$trad["FORUM_notifier_dernier_message_info"]="Avise-me por e-mail a cada nova mensagem";
		// Sujet_edit.php  &  Message_edit.php
		self::$trad["FORUM_accessRightInfos"]="Para participar do tópico, você deve ter acesso de escrita";
		self::$trad["FORUM_theme_espaces"]="Áreas onde o tema está disponível";
		// Themes
		self::$trad["FORUM_theme_sujet"]="Assunto";
		self::$trad["FORUM_accueil_forum"]="Índice do Fórum";
		self::$trad["FORUM_sans_theme"]="sem assunto";
		self::$trad["FORUM_themes_gestion"]="Gerenciar os assuntos de tema";
		self::$trad["FORUM_droit_gestion_themes"]="Cada assunto pode ser modificado por seu autor ou pelo administrador geral";

		////	MODULE_TACHE
		////
		// Menu principal
		self::$trad["TASK_headerModuleName"]="Tarefas";
		self::$trad["TASK_description_module"]="Tarefas";
		self::$trad["TASK_AdminRootFolderAddContent"]="Apenas o administrador pode adicionar elementos na pasta raiz";
		// TRI
		self::$trad["SORT_priority"]="Prioridade";
		self::$trad["SORT_advancement"]="Progresso";
		self::$trad["SORT_dateBegin"]="Data de início";
		self::$trad["SORT_dateEnd"]="Data final";
		// Index.php
		self::$trad["TASK_ajouter_tache"]="Adicionar tarefa";
		self::$trad["TASK_aucune_tache"]="Nenhuma tarefa por enquanto";
		self::$trad["TASK_advancement"]="Progresso";
		self::$trad["TASK_advancementAverage"]="Progresso médio";
		self::$trad["TASK_priority"]="Prioridade";
		self::$trad["TASK_priority1"]="Baixa";
		self::$trad["TASK_priority2"]="Média";
		self::$trad["TASK_priority3"]="Alta";
		self::$trad["TASK_priority4"]="Crítica";
		self::$trad["TASK_responsiblePersons"]="Responsáveis";
		self::$trad["TASK_budgetAvailable"]="Orçamento disponível";
		self::$trad["TASK_budgetAvailable_total"]="Orçamento total disponível";
		self::$trad["TASK_budgetEngaged"]="Orçamento cometido";
		self::$trad["TASK_budgetEngaged_total"]="Orçamento total cometido";
		self::$trad["TASK_humanDayCharge"]="carga dias/homem";
		self::$trad["TASK_humanDayCharge_info"]="Número de dias de trabalho necessário para uma pessoa só para realizar essa tarefa";
		self::$trad["TASK_advancement_retard"]="Progresso em atraso";
		self::$trad["TASK_budgetExceeded"]="Excedeu orçamento";

		////	MODULE_CONTACT
		////
		// Menu principal
		self::$trad["CONTACT_headerModuleName"]="Contatos";
		self::$trad["CONTACT_description_module"]="Lista de contatos";
		self::$trad["CONTACT_AdminRootFolderAddContent"]="Apenas o administrador pode adicionar elementos na pasta raiz";
		// Index.php
		self::$trad["CONTACT_ajouter_contact"]="Adicionar um contato";
		self::$trad["CONTACT_aucun_contact"]="Nenhum contato por enquanto";
		self::$trad["CONTACT_creer_user"]="Criar um usuário neste espaço";
		self::$trad["CONTACT_creer_user_infos"]="Criar um usuário neste espaço a partir desse contato?";
		self::$trad["CONTACT_creer_user_confirm"]="O usuário foi criado";

		////	MODULE_LIEN
		////
		// Menu principal
		self::$trad["LINK_headerModuleName"]="Favoritos";
		self::$trad["LINK_description_module"]="Favoritos";
		self::$trad["LINK_AdminRootFolderAddContent"]="Apenas o administrador pode adicionar elementos na pasta raiz";
		// Index.php
		self::$trad["LINK_ajouter_lien"]="Adicionar uma ligação";
		self::$trad["LINK_aucun_lien"]="Nenhuma ligação por enquanto";
		// lien_edit.php & dossier_edit.php
		self::$trad["LINK_adress"]="Endereço web";

		////	MODULE_MAIL
		////
		// Menu principal
		self::$trad["MAIL_headerModuleName"]="Mailing";
		self::$trad["MAIL_description_module"]="Enviar e-mail com um clique!";
		// Index.php
		self::$trad["MAIL_specifier_mail"]="Favor especificar pelo menos um destinatário";
		self::$trad["MAIL_title"]="Título do e-mail";
		self::$trad["MAIL_fichier_joint"]="Arquivo ligado";
		// Historique Mail
		self::$trad["MAIL_historique_mail"]="Histórico de emails enviados";
		self::$trad["MAIL_aucun_mail"]="Nenhum e-mail";
		self::$trad["MAIL_envoye_par"]="E-mail enviado por";
		self::$trad["MAIL_recipients"]="destinatários";
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
			$dateList[$date]="Segunda-feira de Páscoa";
			$date=date("Y-m-d", $paquesTime + ($daySecondes*39));
			$dateList[$date]="Quinta-feira de Ascensão";
			$date=date("Y-m-d", $paquesTime + ($daySecondes*50));
			$dateList[$date]="Segunda-feira de Pentecostes";
		}

		//Fêtes fixes
		$dateList[$year."-01-01"]="Ano Novo";
		$dateList[$year."-05-01"]="Dia do Trabalhador";
		$dateList[$year."-06-10"]="Feriado nacional";
		$dateList[$year."-08-15"]="Assunção";
		$dateList[$year."-11-01"]="Todos os Santos";
		$dateList[$year."-12-25"]="Natal";

		//Retourne le résultat
		return $dateList;
	}
}