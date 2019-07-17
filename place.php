<?php
    if(isset($_GET["q"])){
	$loc = $_GET["q"];
	$str = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($loc)."&key=xxxxxx");
	echo $str;
				
	die();	 
    }
?>
			
<?php
    if(isset($_GET["a"])){
	$p = explode(",",$_GET["a"]);
	$place = file_get_contents("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$p[3].",".$p[4]."&radius=".$p[2]."&type=".$p[1]."&keyword=".$p[0]."&key=xxxxxxxxx");
	echo $place;
			
	die();
    }
?>
		
<?php
    if(isset($_GET["pl"])){
	$placeid = $_GET["pl"];
	//create directory if present
	if(file_exists("testing"))
	    $test = "hi";
	else
	    mkdir("testing");
			
	//delete older files
	$files = glob('testing/*'); // get all file names
	foreach($files as $file){ // iterate files
	    if(is_file($file))
   	        unlink($file); // delete file
	}
			
	$placeResponse = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=".$placeid."&key=xxxxxxx");
			
	$int1 = json_decode($placeResponse);
	if(isset($int1 -> result -> photos)){
	    $plen = count($int1->result->photos);
				
	    if($plen > 5){
		for($i = 0; $i < 5; $i++){
		    $res = $int1 -> result -> photos[$i] -> photo_reference;
		    $response1 = file_get_contents("https://maps.googleapis.com/maps/api/place/photo?maxwidth=750&photoreference=".$res."&key=xxxxxxxxxxxx");
		    file_put_contents("testing/".$i.$placeid.".png",$response1);
		}
	    }
	    else{
		for($i = 0; $i < $plen; $i++){
		    $res = $int1 -> result -> photos[$i] -> photo_reference;
		    $response1 = file_get_contents("https://maps.googleapis.com/maps/api/place/photo?maxwidth=750&photoreference=".$res."&key=xxxxxxxxxxxxxxxx");
		    file_put_contents("testing/".$i.$placeid.".png",$response1);
		}
	   }	
	}
	else
	    $error = "Not found";	
	    echo $placeResponse;
			
	    die();
    }
?>//indent done till here

<!DOCTYPE html>
<html>
	<head>
	<title>Homework 4</title>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=xxxxxxxxxxxxxx"
		type="text/javascript"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<script type="text/javascript">
		
			function generateHTML(jsonObj)
			{
				OBJ = jsonObj;
				firstcheck = jsonObj.results.length;
				tableBox = document.getElementById("part2");
				
				if(firstcheck!=0)
				{
				
					x = document.createElement("TABLE");
					x.setAttribute("id", "myTable");
					tableBox.appendChild(x);
					
					//x1, x2 and x3 are for columns name
					var x1 = document.createElement("TH");
					var t1 = document.createTextNode("Category");
					x1.appendChild(t1);
					x.appendChild(x1);
					
					var x2 = document.createElement("TH");
					var t2 = document.createTextNode("Name");
					x2.appendChild(t2);
					x.appendChild(x2);
					
					var x3 = document.createElement("TH");
					var t3 = document.createTextNode("Address");
					x3.appendChild(t3);
					x.appendChild(x3);
					
					var reslength = jsonObj.results.length; //to check the length of results google API might return
					if(jsonObj.results.length>20)
						var reslength = 20;
					else
						var reslength = jsonObj.results.length;
					
					for(i = 0; i < reslength; i++)  
					{
						var tr = document.createElement("TR");			// create a new row
						
						var td1 = document.createElement("TD");			//column 1
						var im = document.createElement("IMG");
						im.setAttribute("src", jsonObj.results[i].icon);
						im.setAttribute("class","categoryimages");
						td1.appendChild(im);
						tr.appendChild(td1);
						
						var td2 = document.createElement("TD");			//column 2
						var a = document.createElement("A");
						var at = document.createTextNode(jsonObj.results[i].name);
						a.setAttribute("href","#!");
						//pass = String(jsonObj.results[i].place_id);
						//console.log(pass);
						a.setAttribute("onclick", "getRandP(OBJ,this.text);");
						a.appendChild(at);
						td2.appendChild(a);
						tr.appendChild(td2);
						
						var td3 = document.createElement("TD");
						var a1 = document.createElement("A");
						var at1 = document.createTextNode(jsonObj.results[i].vicinity);
						a1.setAttribute("class","vicinityText");
						a1.setAttribute("href","#!");
						a1.setAttribute("onclick", "mapmarker(OBJ,this.text);");
						a1.appendChild(at1);
						var div = document.createElement("DIV");
							var div1 = document.createElement("DIV");
							div.setAttribute("id","mapbox"+i);
							div1.setAttribute("id","butt");
								var b1 = document.createElement("BUTTON");
								b1.setAttribute("id","walk");
								b1.setAttribute("type","button");
								b1.setAttribute("value","WALKING");
								b1.setAttribute("onclick","initRoute(this.id)");
								b1.appendChild(document.createTextNode("Walk there"));
								
								var b2 = document.createElement("BUTTON");
								b2.setAttribute("id","bike");
								b2.setAttribute("type","button");
								b2.setAttribute("value","BICYCLING");
								b2.setAttribute("onclick","initRoute(this.id);");
								b2.appendChild(document.createTextNode("Bike there"));

								var b3 = document.createElement("BUTTON");
								b3.setAttribute("id","drive");
								b3.setAttribute("type","button");
								b3.setAttribute("value","DRIVING");
								b3.setAttribute("onclick","initRoute(this.id);");
								b3.appendChild(document.createTextNode("Drive there"));

							div1.appendChild(b1);
							div1.appendChild(b2);
							div1.appendChild(b3);
							
							var div2 = document.createElement("DIV");
							div2.setAttribute("id","map"+i);
							
						div.appendChild(div1);
						div.appendChild(div2);
						td3.appendChild(a1);
						td3.appendChild(div);
						tr.appendChild(td3);
						
						x.appendChild(tr);
							
						document.getElementById("mapbox"+i).style.display = 'none';
						document.getElementById("mapbox"+i).style.marginLeft = "20px";
						document.getElementById("map"+i).style.width = "400px";
						document.getElementById("map"+i).style.height = "300px";
						document.getElementById("mapbox"+i).style.position="absolute";
						document.getElementById("map"+i).style.position="absolute";
						document.getElementById("map"+i).style.top = "0px";
					}
					
				}
				else
				{
					
					p = document.createElement("P");
					p.setAttribute("id", "norecord");
					pt = document.createTextNode("No Records has been found");
					p.appendChild(pt);
					tableBox.appendChild(p);
	
				}
			}
			
			//========================================================================================================================	
			
			function disRev(id)
			{
				var x = document.getElementById("revTable");
				document.getElementById("phTable").style.display = 'none';
				document.getElementById("p2").innerHTML="click to show photos";
				document.getElementById("downArrow2").src="http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png";
				
					if(x.style.display == 'block')
					{
						x.style.display = 'none';
						document.getElementById(id).src="http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png";
						document.getElementById("p1").innerHTML="click to show reviews";

					}
					else
					{
						x.style.display = 'block';
						document.getElementById(id).src="http://cs-server.usc.edu:45678/hw/hw6/images/arrow_up.png";
						document.getElementById("p1").innerHTML="click to hide reviews";
					}
			}
			
			//========================================================================================================================
			
			function disPhoto(id)
			{
				var x = document.getElementById("phTable");
				document.getElementById("revTable").style.display = 'none';
				document.getElementById("p1").innerHTML="click to show reviews";
				document.getElementById("downArrow1").src="http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png";
				
					if(x.style.display == 'block')
					{
						x.style.display = 'none';
						document.getElementById(id).src="http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png";
						document.getElementById("p2").innerHTML="click to show photos";

					}
					else
					{
						x.style.display = 'block';
						document.getElementById(id).src="http://cs-server.usc.edu:45678/hw/hw6/images/arrow_up.png";
						document.getElementById("p2").innerHTML="click to hide photos";
					}
			}
			
			//========================================================================================================================
			
			function generatePlace(placeObj,at,placeofid)
			{
				fulldiv = document.createElement("DIV");
				fulldiv.setAttribute("id","revandph");

				var nx = document.getElementById("part2");
				nx.replaceChild(fulldiv,document.getElementById("myTable"));
				
				head1 = document.createElement("P");
				head1.setAttribute("id","selectedName");
				texthead = document.createTextNode(at);
				head1.appendChild(texthead);
				fulldiv.appendChild(head1);
				
				var div1 = document.createElement("DIV");
				div1.setAttribute("id","revDiv");
				
					var revhead = document.createElement("P");
					var revheadT = document.createTextNode("click to show reviews");
					revhead.setAttribute("id","p1");
					revhead.appendChild(revheadT);
					
					var down1 = document.createElement("IMG");
					down1.setAttribute("src","http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png");
					down1.setAttribute("id","downArrow1");
					down1.setAttribute("onclick","disRev(this.id);");
					
					div1.appendChild(revhead);
					div1.appendChild(down1);
				
				fulldiv.appendChild(div1);
								
				var div2 = document.createElement("DIV");
				div2.setAttribute("id","phDiv");
				
					var phhead = document.createElement("P");
					var phheadT = document.createTextNode("click to show photos");
					phhead.setAttribute("id","p2");
					phhead.appendChild(phheadT);
					
					var down2 = document.createElement("IMG");
					down2.setAttribute("src","http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png");
					down2.setAttribute("id","downArrow2");
					down2.setAttribute("onclick","disPhoto(this.id);")
					
					div2.appendChild(phhead);
					div2.appendChild(down2);
				
				fulldiv.appendChild(div2);
				
				
				tableForRev = document.createElement("TABLE");	//table for reviews
				tableForRev.setAttribute("id", "revTable");
				div1.appendChild(tableForRev);
				//console.log(typeof placeObj.result.reviews[0]);

				if(("reviews" in placeObj.result))
				{

					revlen = placeObj.result.reviews.length;
					if(revlen>5)
					{
						for(var i=0;i<5;i++)
						{
							var trr = document.createElement("TR");	
							var trd = document.createElement("TD");
							var rdiv = document.createElement("DIV");
							
								if("profile_photo_url" in placeObj.result.reviews[i])
								{
									var authorimg = document.createElement("IMG");
									authorimg.setAttribute("src",placeObj.result.reviews[i].profile_photo_url);

									rdiv.appendChild(authorimg);
									trd.appendChild(rdiv);
									
								}
								if("author_name" in placeObj.result.reviews[i])
								{
									var authorname = document.createTextNode(placeObj.result.reviews[i].author_name);
									
									rdiv.appendChild(authorname);
									trd.appendChild(rdiv);
								}
							
							trr.appendChild(trd);
							tableForRev.appendChild(trr);
							
							var trr1 = document.createElement("TR");
							var trd1 = document.createElement("TD");
							if("text" in placeObj.result.reviews[i])
							{
								var tdT = document.createTextNode(placeObj.result.reviews[i].text);
								trd1.appendChild(tdT);
							}
							
							trr1.appendChild(trd1);
							tableForRev.appendChild(trr1);
							
						}
						
					}
					else
					{
						for(var i=0;i<revlen;i++)
						{
							var trr = document.createElement("TR");	
							var trd = document.createElement("TD");
							var rdiv = document.createElement("DIV");
							
								if("profile_photo_url" in placeObj.result.reviews[i])
								{
									var authorimg = document.createElement("IMG");
									authorimg.setAttribute("src",placeObj.result.reviews[i].profile_photo_url);

									rdiv.appendChild(authorimg);
									trd.appendChild(rdiv);
									
								}
								if("author_name" in placeObj.result.reviews[i])
								{
									var authorname = document.createTextNode(placeObj.result.reviews[i].author_name);
									
									rdiv.appendChild(authorname);
									trd.appendChild(rdiv);
								}

							trr.appendChild(trd);
							tableForRev.appendChild(trr);
							
							var trr1 = document.createElement("TR");
							var trd1 = document.createElement("TD");
							if("text" in placeObj.result.reviews[i])
							{
								var tdT = document.createTextNode(placeObj.result.reviews[i].text);
								trd1.appendChild(tdT);
							}
							
							trr1.appendChild(trd1);
							tableForRev.appendChild(trr1);							
						}
						
					}
				}
				else
				{
					//console.log("No reviews");
					var norev = document.createElement("P");
					norev.setAttribute("id","norev");
					
					var norevT = document.createTextNode("No reviews found");
					norev.appendChild(norevT);
					
					var trnr = document.createElement("TR");
					var trndr = document.createElement("TD");	//trndr = table row no data rev
					trndr.appendChild(norev);
					trnr.appendChild(trndr);
					tableForRev.appendChild(trnr);
					
					document.getElementById("norev").style.marginTop="0px";
					document.getElementById("norev").style.marginBottom="0px";
					document.getElementById("norev").style.textAlign="center";
					document.getElementById("norev").style.fontWeight="bold";
				
				}

				tableForPh = document.createElement("TABLE");  //table for photos
				tableForPh.setAttribute("id", "phTable");
				div2.appendChild(tableForPh);
				
				if(!("photos" in placeObj.result))
				{
					var nophoto = document.createElement("P");
					nophoto.setAttribute("id","nophoto");
					
					var nophotoT = document.createTextNode("No photos found");
					nophoto.appendChild(nophotoT);
					
					var trnp = document.createElement("TR");
					var trndp = document.createElement("TD");
					trndp.appendChild(nophoto);
					trnp.appendChild(trndp);
					tableForPh.appendChild(trnp);
					
					document.getElementById("nophoto").style.marginTop="0px";
					document.getElementById("nophoto").style.marginBottom="0px";
					document.getElementById("nophoto").style.textAlign="center";
					document.getElementById("nophoto").style.fontWeight="bold";
					
				}
				else
				{
					phlen = placeObj.result.photos.length;
					if(phlen>5)
					{
						for(var i=0;i<5;i++)
						{
							//console.log("Here!!");
							var trp = document.createElement("TR");	
							var tpd = document.createElement("TD");
							var tdim = document.createElement("IMG");
							tdim.setAttribute("src","testing/"+i+""+placeofid+".png");
							tdim.setAttribute("onclick","window.open(this.src);");
							tpd.appendChild(tdim);
							trp.appendChild(tpd);
							tableForPh.appendChild(trp);
						}
					}
					else
					{
						for(var i=0;i<phlen;i++)
						{
							
							var trp = document.createElement("TR");	
							var tpd = document.createElement("TD");
							var tdim = document.createElement("IMG");
							tdim.setAttribute("src","testing/"+i+""+placeofid+".png");
							tdim.setAttribute("onclick","window.open(this.src);");
							tpd.appendChild(tdim);
							trp.appendChild(tpd);
							tableForPh.appendChild(trp);
						}
						
					}
				}
				

			}
			
			//========================================================================================================================			
			
			function getRandP(obj,at)
			{

				idc = obj.results.length;
				var x = at;

				for(var j=0;j<20;j++)
				{
					if(obj.results[j].name==at)
					{
						var placeofid = obj.results[j].place_id;
						break;
					}
				}
				//alert(placeofid);

				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					placeObj = JSON.parse(this.responseText);
					//console.log(placeObj);
					placeObj.onload=generatePlace(placeObj,at,placeofid);				
					}
				};
					
				xmlhttp.open("GET", "place.php?pl=" + placeofid, true);
				xmlhttp.send(); 
				
			}
			//======================================================================================================================
			function mapmarker(obj,addr)
			{	
				var len = obj.results.length;
				for(j=0;j<20;j++)
				{
					if(obj.results[j].vicinity==addr)
					{
						dlat = obj.results[j].geometry.location.lat;
						dlon = obj.results[j].geometry.location.lng;
						index = j;
						break;
					}
				}

				var x = document.getElementById("mapbox"+index);

				if(x.style.display == 'block')
				{
					x.style.display = 'none';
				}
				else
				{
					x.style.display = 'block';
				}
		
				initMap(index)
					
			}
			
		function initMap(index) 
		{
			var uluru = {lat: dlat, lng: dlon};
			var map = new google.maps.Map(document.getElementById("map"+index), {
			  zoom: 13,
			  center: uluru
			});
			var marker = new google.maps.Marker({
			  position: uluru,
			  map: map
			});
		  }
		  
		  function initRoute(id)
		  {
				var directionsDisplay = new google.maps.DirectionsRenderer;
				var directionsService = new google.maps.DirectionsService;
				
				var map = new google.maps.Map(document.getElementById("map"+index), {
				  zoom: 16,
				  center: {lat: Number(latit), lng: Number(longit)}
				});
				directionsDisplay.setMap(map);
				calculateAndDisplayRoute(directionsService, directionsDisplay,id);

		  }
		  
		  function calculateAndDisplayRoute(directionsService, directionsDisplay,id)
		  {
				var selectedMode = document.getElementById(id).value;
				directionsService.route({
				  origin: {lat: Number(latit), lng: Number(longit)},  // Haight.
				  destination: {lat: dlat, lng: dlon},  // Ocean Beach.
				  // Note that Javascript allows us to access the constant
				  // using square brackets and a string value as its
				  // "property."
				  travelMode: google.maps.TravelMode[selectedMode]
				}, function(response, status) {
				  if (status == 'OK') {
					directionsDisplay.setDirections(response);
				  } else {
					window.alert('Directions request failed due to ' + status);
				  }
				});
			}
			//======================================================================================================================
			function main()
			{
				//====================================if it creates problem then remove===========================================
				var tabpart = document.getElementById("part2");
				if(tabpart.hasChildNodes())
				{	
					while(tabpart.hasChildNodes())
						tabpart.removeChild(tabpart.lastChild);
				}
				
				var inp1 = document.getElementById("keyword").value;
				var inp2 = document.getElementById("cat").value;
				var inp3 = document.getElementById("dist").value;
				var x="";
				if(inp3 == "")
					inp3 = 10;
				var dist = inp3*1609.34;
				//console.log(dist);
				if(document.getElementById("curLoc").checked)
				{
					latit = document.getElementById("latitude").value;
					longit = document.getElementById("longitude").value;
				}
				else
				{
					typedloc = document.getElementById("locText").value;
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						jsonObj = JSON.parse(this.responseText);
						latit = jsonObj.results[0].geometry.location.lat;
						longit = jsonObj.results[0].geometry.location.lng;
						}
					};
					xmlhttp.open("GET", "place.php?q=" + typedloc, false);
					xmlhttp.send();
				//console.log(latit+","+longit);
				}
				
				var urlArray = [inp1,inp2,dist,latit,longit];

					var xmlhttp1 = new XMLHttpRequest();
					xmlhttp1.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						jsObj = JSON.parse(this.responseText);
						jsObj.onload=generateHTML(jsObj);
						
						}
					};
					
					xmlhttp1.open("GET", "place.php?a=" + urlArray, true);
					xmlhttp1.send();
				
			}
			//========================================================================================================================
			function initial()
			{
				
				document.getElementById("curLoc").checked = true;
				document.getElementById("search").disabled=true;
				document.getElementById("locText").disabled=true;
				
				var url = "http://www.ip-api.com/json";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",url,false); 
				xmlhttp.send();
				jsonObj= JSON.parse(xmlhttp.responseText);
				j1 = jsonObj["lat"];
				j2 = jsonObj["lon"];
				document.getElementById("latitude").value=j1;
				document.getElementById("longitude").value=j2;
				//console.log(j1+","+j2);
				document.getElementById("search").disabled=false;
				
			
			}
			//========================================================================================================================
			function enableLoc()
			{
				document.getElementById("locText").disabled=false;
				document.getElementById("search").disabled=false;
			}
			//========================================================================================================================
			function clearfields()
			{
				
				document.getElementById("keyword").value="";
				document.getElementById("dist").value="";
				document.getElementById("locText").value="";
				document.getElementById("cat").selectedIndex ="0";
				var tabpart = document.getElementById("part2");
				if(tabpart.hasChildNodes())
				{
					while(tabpart.hasChildNodes())
						tabpart.removeChild(tabpart.lastChild);
				}
					
				initial();
			}			
		</script>
		
	</head>
	
		<style>
			table,th,td{
				border:2px solid #d4d4d4;
				border-collapse: collapse;
				z-index:1;
			}
			
			td a{
				text-decoration:none;
				color:black;
				margin-left:20px;
				
			}   
			.vicinityText:hover{
				color:#cfcfcf;
				-webkit-transition-duration:  0.5s;
				-moz-transition-duration:  0.5s;
				transition-duration:  0.5s;
				transition-timing-function: linear;
			}
			#bike, #walk, #drive{
				border:0px solid black;
				height:35px;
				display:block;
				width:100px;
				background-color:#e7e7e7	
			}
			
			#bike:hover{
				background-color:#cfcfcf;
				-webkit-transition-duration:  0.2s;
				-moz-transition-duration:  0.2s;
				transition-duration:  0.2s;
				cursor:pointer;
			}
			
			#walk:hover{
				background-color:#cfcfcf;
				-webkit-transition-duration:  0.2s;
				-moz-transition-duration:  0.2s;
				transition-duration:  0.2s;
				cursor:pointer;
			}
			
			#drive:hover{
				background-color:#cfcfcf;
				-webkit-transition-duration:  0.2s;
				-moz-transition-duration:  0.2s;
				transition-duration:  0.2s;
				cursor:pointer;
			}
			
			#butt{
				position:absolute;
				z-index:5;
			}
			
			#revTable, #phTable{
				display:none;
			}
			
			#travel{
				font-size:28px;
				margin-top:0px;
				margin-bottom:0px;
				text-align:center;
			}
			
			#search{
				margin-left:70px;
				border-radius:4px;
				box-shadow:0px;
				border: 1px solid #d4d4d4;
				background-color:white;
		   }

		   #clear{
				border-radius:4px;
				box-shadow:0px;
				border: 1px solid #d4d4d4;
				background-color:white;
		   }
		   #clear:active, #search:active{
			   background-color: #539ed4;
		   }
		   hr{
			   width:96%;
		   }
		   
		   #myForm{
			  margin-left:10px;
		   }
		   
		   .part1{
			  border:3px solid #d4d4d4;
			  height:220px;
			  width:650px;
			  margin:0 auto;
			  background-color:#fafafa;
		   }
		   
		   #part2{
				border:0px solid black;
				width:90%;
				margin:0 auto;
				margin-top:25px;
		   }
		   
			#myTable{
				width:100%;		
			}
			
			#selectedName{
				font-weight: bold;
				margin-top:0;
				text-align: center;
			}
			
			#revandph{
				border:0px solid black;
				width:650px;
				margin:0 auto;
			}
		
			#revDiv #p1{
				text-align:center;
			}
			
			#downArrow1,#downArrow2{
				width:40px;
				margin-left:300px;
				margin-top:-10px;
				padding-left:10px;
				padding-right:10px;
			}

			#downArrow1:hover{
				cursor:pointer;
			}
			
			#downArrow2:hover{
				cursor:pointer;
			}
			
			#phDiv #p2{
				text-align: center;
			}
			
			#revTable div{
				margin-left:270px;
				font-weight:bold;
			}
			
			#revTable div img{
				width:40px;
			}
			
			#phTable{
				border:0px;
				width:650px;
			}
			
			#revTable{
				border:0px;
				width:650px;
			}
			
			#revTable td{
				width:650px;
			}
			
			#phTable td{
				width:650px;
			}

			#phTable img{
				width:605px;
				padding:20px;
			}
			
			#phTable img:hover{
				cursor:pointer;
			}
			
			.categoryimages{
				width:50px;
				height:35px;
			}
			
			#keyword{
				margin-left:5px;
				margin-bottom:10px;
			}
			#keyword, #locText{
				box-shadow:none;
			}
			
			#cat{
				margin-bottom:10px;
			}
			
			input:focus{
				outline: none !important;
				border:2px solid #539ed4;
			}

			#norecord{
				border:1px solid #d4d4d4;
				width:900px;	
				margin:0 auto;
				text-align:center;
				background-color:#e7e7e7;
			}

		</style>
		
	<body onload="initial()">

		<div class="part1">
			<p id="travel"><i><strong>Travel and Entertainment Search</strong></i></p>
			<hr>
			<form action="Javascript:main()" id="myForm">
				<b>Keyword</b><input type="text" name="keyword" id="keyword" required>
				<br>
				<b>Category</b><select name="category" id="cat" style="margin-left:5px;">
							<option value="default">default</option>
							<option value="cafe">cafe</option>
							<option value="bakery">bakery</option>
							<option value="restaurant">restaurant</option>
							<option value="beauty_salon">beauty salon</option>
							<option value="casino">casino</option>
							<option value="movie_theater">movie theater</option>
							<option value="lodging">lodging</option>
							<option value="airport">airport</option>
							<option value="train_station">train station</option>
							<option value="subway_station">subway station</option>
							<option value="bus_station">bus station</option>
						</select>
				<br>

	  			<b>Distance (miles)</b><input type="text" name="distance" placeholder="10" id="dist" style="margin-left:5px;">

				<b>from </b><input type="radio" name="here" value="here" checked onclick="initial()" id="curLoc">Here<br>
								<input type="hidden" name="lati" id="latitude">
								<input type="hidden" name="long" id="longitude">
						
					 <input type="radio" name="here" value="locate" style="margin-left:303px;" id="typLoc" onclick="enableLoc()">
						<input type="text" name="loc" placeholder="location" id="locText" required disabled="true">
						
					 <br>
					 <br>
					 <input type="submit" value="Search" name="submit" id="search" >
					 <button type="button" id="clear" onclick="clearfields()">Clear</button>
			</form> 
		</div>
		
		<div id="part2">	
		</div>

	</body>

</html>
