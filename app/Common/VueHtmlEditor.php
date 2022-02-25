<script type="text/javascript" src="app/js/tinymce/tinymce.min.js"></script>

<script type="text/javascript">
////	Initialise l'editeur html
tinymce.init({
	//parametrage général
	width: "100%",
	language: "<?= Txt::trad("HTML_EDITOR") ?>",
	selector: "textarea[name='<?= $fieldName ?>']",//selecteur du textarea
	content_style: "p {margin:0px; padding:2px; font-size:110%;}",//style des balises <p> : cf. 'app/css/common.css'
	entity_encoding: "raw",//"All characters will not be stored as html entities, except : &amp; &lt; &gt; &quot;"
	statusbar: false,
	//forced_root_block: "div",//Remplace les balises "<p>"
	//charge les plugins (print preview hr anchor pagebreak wordcount fullscreen insertdatetime)
	plugins: ["autoresize advlist autolink lists link image charmap searchreplace visualblocks visualchars code media nonbreaking table contextmenu directionality emoticons paste textcolor colorpicker textpattern"],
	//barres de menu (Attention a l'affichage sur un width minimum. cf. modTask)
	menubar: false,
	toolbar1: "undo redo | alignleft aligncenter bullist numlist| table media image emoticons",//code
	toolbar2: "bold italic underline fontsizeselect forecolor link | removeformat",//fontselect
	//parametrage des plugins
	fontsize_formats: "100% 120% 140% 160% 200% 250%",//Taille des caractères
	media_alt_source: false,//désactive le champ alternatif de saisie de source dans la boîte de dialogue des médias
	media_poster: false,//désactive l'envoi de fichier dans la boîte de dialogue des médias
	setup: function(editor){
		//Init : Modif le style du menu && Focus sur l'éditeur?
		editor.on('init',function(){
			$(".mce-btn button").css("box-shadow","none");
			$(".mce-toolbar:first-child").css("border-bottom","1px solid #ccc");
			//Focus sur l'éditeur s'il est visible et aucun input n'a déjà le focus
			if($(editor.getBody()).is(":visible") && $("input:focus").length==0)	{editor.focus();}
			//Si une fonction "htmlEditorHeight" existe (modMail ou autre) : Calcul la hauteur de l'éditeur
			if(typeof htmlEditorHeight=="function")  {setTimeout(function(){ htmlEditorHeight(); },200);}
			//Init la hauteur du lightbox si besoin
			lightboxHeightResize();
		});
		//Resize le lightbox si le contenu agrandit l'éditeur
		editor.on("change keyup",function(){
			lightboxHeightResize();
			$(document).find("body").trigger("click");//cf. "confirmCloseLightbox"
		});
	}
});

////	Contenu de l'editeur est vide ?
function isEmptyEditor()
{
	var content=tinymce.activeEditor.getContent({format:'text'});
	if($.trim(content).length==0)	{return true;}
}
</script>