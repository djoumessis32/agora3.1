<meta name="viewport" content="initial-scale=1.0, user-scalable=no"></meta>							<!-- Carte affiché en plein écran et sans redimensionnement possible -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>	<!-- API Google MAPS ("sensor=false" : pas de positionnement de l'user) -->
<div id="mapCanvas"></div>

<script type="text/javascript">
////	PERSONNES A AFFICHER (UTILISATEURS/CONTACTS)
adressTab=[];
<?php
foreach(Ctrl::getTargetObjects() as $tmpPerson)
{
	if($tmpPerson->readRight() && $tmpPerson->hasAdress()){
		$tmpAdress=trim($tmpPerson->adress.", ".$tmpPerson->postalCode." ".str_ireplace("cedex","",$tmpPerson->city)." ".$tmpPerson->country,  ", ");
		$tmpLabel=trim(htmlspecialchars($tmpPerson->display()." - ".$tmpPerson->function." - ".$tmpPerson->companyOrganization." - ".$tmpAdress)," - ");
		echo 'adressTab.push({ "adress":"'.$tmpAdress.'", "personLabel":"'.$tmpLabel.'", "personImg":"'.$tmpPerson->getImg(false,true,true).'" });';
	}
}
?>

////	INITIALISE LA CARTE GOOGLE MAP
$(function(){
	////	Charge la carte (avec options) + Instancie le gécodeur + Bornes/limites de la carte (latitude/longitude)
	map		=new google.maps.Map(document.getElementById("mapCanvas"), {zoom:8, mapTypeId:google.maps.MapTypeId.ROADMAP});
	geocoder=new google.maps.Geocoder();
	bounds	=new google.maps.LatLngBounds();
	////	Géocode et marque chaque adresse (dans une fonction annexe, pour que la géolocalisation se fasse indépendamment)
	for(var tmpKey in adressTab)	{geocodeMarkAddress(tmpKey);}
	////	redimensionne le conteneur "mapCanvas", puis le fancybox
	$("#mapCanvas").css("width",($(window.parent).width()-50)+"px").css("height",($(window.parent).height()-80)+"px");
	parent.$.fancybox.update();
});

////	GÉOCODE ET MARQUE UNE ADRESSE
function geocodeMarkAddress(tmpKey)
{
	geocoder.geocode({"address":adressTab[tmpKey]["adress"]}, function(results,status){
		//Géolocalisation OK
		if(status==google.maps.GeocoderStatus.OK)
		{
			// Récupère la latitude et longitude
			adressTab[tmpKey]["lat"]=results[0].geometry.location.lat();
			adressTab[tmpKey]["lng"]=results[0].geometry.location.lng();
			// Ajoute le marker avec l'image de la personne (url + position de la photo par rapport au point du marker = centre/bottom + dimension de la photo)
			adressTab[tmpKey]["personImg"]=new google.maps.MarkerImage(adressTab[tmpKey]["personImg"], null, null, new google.maps.Point(18,0), new google.maps.Size(35,35));
			adressTab[tmpKey]["marker"]=new google.maps.Marker({map:map, title:adressTab[tmpKey]["personLabel"], position:results[0].geometry.location, icon:adressTab[tmpKey]["personImg"]});
			// Infobulle du marqueur
			adressTab[tmpKey]["tooltipHtml"]=adressTab[tmpKey]["personLabel"]+"<div id='streetView"+tmpKey+"' class='personStreetView'>Street View loading ...</div>";
			adressTab[tmpKey]["tooltip"]=new google.maps.InfoWindow( {content:adressTab[tmpKey]["tooltipHtml"]} );
			google.maps.event.addListener(adressTab[tmpKey]["marker"], "click", function(){
				adressTab[tmpKey]["tooltip"].open(map, adressTab[tmpKey]["marker"]);
				//Appel de StreetView une fois l'infobulle chargee
				setTimeout(function(){
					StreetView=new google.maps.StreetViewPanorama(document.getElementById("streetView"+tmpKey));
					StreetView.setPosition(new google.maps.LatLng(adressTab[tmpKey]["lat"],adressTab[tmpKey]["lng"]));
				},200);
			});
			// Ajuste le Zoom de la carte en fonction des markers (personnes)
			bounds.extend(new google.maps.LatLng(adressTab[tmpKey]["lat"],adressTab[tmpKey]["lng"]));
			map.fitBounds(bounds);
			//Dézoom si besoin (>15)
			var listener = google.maps.event.addListener(map, "idle", function(){
				if(map.getZoom()>15)	{map.setZoom(15);}
				google.maps.event.removeListener(listener); 
			});
		}
	});
}
</script>