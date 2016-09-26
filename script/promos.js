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
alert("test");
	if (confirm("Souhaitez-vous désactiver cette promotion?")) {
		//send ajax post request to delete date with service id
	}
}
