//toggle invoice detail on/off
function toggleDetail(elemId) {
			elem = document.getElementById(elemId);
			display = (getComputedStyle(elem, null).display);
			link = document.getElementById('href' + elemId);
			if (display === 'none') {
				elem.style.display = 'inline-block';
				link.innerHTML = 'Réduire';
			}
			else if (display === 'inline-block'){
				elem.style.display = 'none';
				link.innerHTML = 'Détail';
			}
		}
		
// Get the modal
var modal = document.getElementById('clientInfoModal');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

//gets client info for ajax info lookup
function getClientInfo(id) {
		jQuery.ajax({
		  url: "modals/clientInfo.php",
		  type: "POST",
		  data: {clientId:id},
		  success: function(json_data)
			{
			
				//fills data
				var data_array = $.parseJSON(json_data);
				$('#clientNom').html(data_array[0]['prenom'] + " " + data_array[0]['nom']);
				$('#clientTelephone').html(data_array[0]['telephone']);
				$('#clientAdresse').html(data_array[0]['no_civique'] + " " + data_array[0]['rue'] + ", " + data_array[0]['ville'] + ", " + data_array[0]['code_postal'] );
				
				//displays the modal
				modal.style.display = "block";
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
			console.log(errorThrown);//)
		 
			}
		});
	}