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
