function showMenu(id){
    showItem("#cornerMenu" + id);
}
function showPromo(id){
    showItem("#cornerPromo" + id);
}
function showItem(selector){
    var item = document.querySelector(selector);
    item.style.display="inline";
    item.focus();
}

function deleteItem(id) {
	if (confirm("Souhaitez-vous désactiver cette promotion?")) {
		//send ajax post request to delete date with service id
		jQuery.ajax({
		  url: "promos.php",
		  type: "POST",
		  data: {deletedID:id},
		  success: function(result, textStatus, jqXHR)
			{
			    console.log(result + "\n" + textStatus);//)
				window.location.replace("service.php");
				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
			console.log(errorThrown);//)
		 
			}
		});
	}
}

// Get the modal
var modal = document.getElementById('applyToAllModal');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
// set correct id and percent to promotion 
function openModal(promoId) {
    modal.style.display = "block";
	console.log('#promoName[value="' + promoId + '"]');
	$('#promoName').val(promoId).change();
	var percent = $('#promoName').val(promoId).data("percent");
	console.log(percent);
	$('#addPromoNb').html(percent + '%');
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

function applyToAll() {
		var id = document.getElementById('promoName').value;
		jQuery.ajax({
		  url: "promos.php",
		  type: "POST",
		  data: {applyPromoId:id},
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
