function showMenu(id){
    showItem("#cornerMenu" + id);
}
function showPromo(id){
    showItem("#cornerPromo" + id);
}

function showItem(selector){
    let item = document.querySelector(selector);
    item.style.display="inline";
    item.focus();
}

function editService(id){
    let modalDiv = document.getElementsByClassName("modal")[0];
    $.get(
        "modifierService.php?serviceId=" + id,
        function(response){
            $( '#modalFrame' ).html( response ); 
        }
    );
    modalDiv.style.display = 'inline';
}
function disableService(id){
    $.get(
        "modifierService.php?serviceId=" + id + "&inactive=1"
    );
    document.getElementById('cornerMenu' + id).style.display = 'none';
    alert("Service désactivé avec succès!");
}


// Get the modal
var modal = document.getElementById('updatePromoModal');
var addModal = document.getElementById('addPromoToServiceModal');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal for updating a promotion
// set correct id and percent to promotion 
function openModal(data) {

	//change beginning and end inputs to date objects
	// Split timestamp into [ Y, M, D, h, m, s ]
	var d = data[0]['date_debut'].split(/[- :]/);
	var f = data[0]['date_fin'].split(/[- :]/);

	// Apply each element to the Date function
	var debut = new Date(Date.UTC(d[0], d[1]-1, d[2], d[3], d[4], d[5]));
	var fin = new Date(Date.UTC(f[0], f[1]-1, f[2], f[3], f[4], f[5]));

	//fill modal with correct data
	$('#updateServiceId').val(data[0]['fk_service']);
	$('#updatePromoServiceId').val(data[0]['pk_promotion_service']);
	document.getElementById("debut").valueAsDate = debut;
	document.getElementById("fin").valueAsDate = fin;
	$('#updateCodePromo').val(data[0]['code']);
	$('#updatePromoName').val(data[0]['fk_promotion']);
	
	//change percentage of box based on chosen promotion
	var e = document.getElementById('updatePromoName');
    var promo = e.children[e.selectedIndex];
    var percent = promo.getAttribute("data-percent");
    document.getElementById('updatePromoNb').innerHTML = percent * 100 + "%";
	
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == addModal) {
        addModal.style.display = "none";
    }
}


//deletes promotion
function deletePromotion(id) {
	if (confirm("Souhaitez-vous désactiver cette promotion?")) {
		jQuery.ajax({
		  url: "service.php",
		  type: "POST",
		  data: {promoID: id},
		  success: function(result, textStatus, jqXHR)
			{
			    console.log(result + "\n" + textStatus);//)
				window.location.replace("service.php");
				alert('Promotion désactivée avec succès!');
				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
			console.log(errorThrown);//)
		 
			}
		});
	}
}

//opens the modal to modify id element
function openUpdatePromo(promoId) {
	jQuery.ajax({
	  url: "service.php",
	  type: "GET",
	  data: {updatePromoId:promoId},
	  success: function(json_data)
		{
			//fills data
			var data_array = $.parseJSON(json_data);
			
			console.log(data_array);
			
			openModal(data_array);				
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			console.log(errorThrown);//)
		}
	});
}

//when the client sends a promo to update
function updatePromo() { }
		
		
	
//update d'une promotion via AJAX	
function updatePromoTest() {

	//stocks values in variables
	var id = document.getElementById('updatePromoServiceId').value;
	var dateDebut = document.getElementById('updateDebut').value;
	var dateFin = document.getElementById('updateFin').value;
	var code = document.getElementById('updateCodePromo').value;
	var promoId = document.getElementById('updatePromoName').value;
	
	//sends update via jQuery
	jQuery.ajax({
	  url: "service.php",
	  type: "POST",
	  data: {updateId:id, promoId:promoId, debut:dateDebut, fin:dateFin, code:code},
	  success: function(result/*, textStatus, jqXHR*/)
		{	
			//console.log(result + "\n" + textStatus);//)
			alert('Modification effectuée avec succès.');
			window.location.reload();
		},
		error: function (error)
		{
			alert(error);
			//console.log(error);//)
		}
	});
}

//opens the modal to add a given promo to a service
function openAddPromoModal(id) {
	$('#serviceId').val(id);
	addModal.style.display = "block";
}

function addPromotion() {
	//stocks values in variables
	var id = document.getElementById('serviceId').value;
	var dateDebut = document.getElementById('debut').value;
	var dateFin = document.getElementById('fin').value;
	var code = document.getElementById('codePromo').value;
	var promoId = document.getElementById('promoName').value;
	
	//sends update via jQuery
	jQuery.ajax({
	  url: "service.php",
	  type: "POST",
	  data: {addToServiceId:id, promoId:promoId, debut:dateDebut, fin:dateFin, code:code},
	  success: function(result/*, textStatus, jqXHR*/)
		{	
			//console.log(result + "\n" + textStatus);//)
			alert('Ajout effectué avec succès.');
			window.location.reload();
		},
		error: function (error)
		{
			alert(error);
			//console.log(error);//)
		}
	});
}

//shares on Facebook using a feed dialog	
function shareToFB() {
	FB.ui({
		method: 'feed',
		link: 'http://weba.cegepsherbrooke.qc.ca/~tia16001/service.php',
		caption: 'An example caption',
		}, function(response){});
}


