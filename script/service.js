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

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
// set correct id and percent to promotion 
function openModal(data) {

	//fill modal with correct data
	$('#serviceId').val(data[0]['fk_service']);
	$('#promoServiceId').val(data[0]['pk_promotion_service']);
	$('#debut').val(data[0]['date_debut']);
	$('#fin').val(data[0]['date_fin']);
	$('#codePromo').val(data[0]['code']);
	
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
			openModal(data_array);				
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
		console.log(errorThrown);//)
	 
		}
	});
}

//when the client sends
function updatePromo(oldPromoId) {
		var id = document.getElementById('promoName').value;
		var dateDebut = document.getElementById('debut').value;
		var dateFin = document.getElementById('fin').value;
		var code = document.getElementById('codePromo').value;
		jQuery.ajax({
		  url: "service.php",
		  type: "POST",
		  data: {oldPromoId:oldPromoId, debut:dateDebut, fin:dateFin, code:code},
		  success: function(result, textStatus, jqXHR)
			{
			    console.log(result + "\n" + textStatus);//)
				window.location.reload();
				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
			console.log(errorThrown);//)
		 
			}
		});
	}


