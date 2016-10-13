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

