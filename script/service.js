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

function editService(id){
    var modalDiv = document.getElementsByClassName("modal")[0];
    $.get(
        "modifierService.php?serviceId=" + id,
        function(response){
            $( '#modalFrame' ).html( response ); 
        }
    );
    modalDiv.style.display = 'inline';

}
