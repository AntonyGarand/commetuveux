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

function applyToAll(id) {
	if (confirm("Souhaitez-vous appliquer cette promotion à tous les services?")) {
		//send ajax post request to delete date with service id
		jQuery.ajax({
		  url: "promos.php",
		  type: "POST",
		  data: {applyPromoId:id},
		  success: function(result, textStatus, jqXHR)
			{
			    console.log(result + "\n" + textStatus);//)
				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
			console.log(errorThrown);//)
		 
			}
		});
	}
}
