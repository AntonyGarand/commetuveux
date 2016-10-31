//Login with facebook
window.fbAsyncInit = function() {
    FB.init({
        appId      : '115189402279734',
        xfbml      : true,
        version    : 'v2.7'
    });
    FB.getLoginStatus(function(response){
        if(response.status === 'connected'){
            //Logged in
        } else {
            //Not logged in
        }
    });
    FB.AppEvents.logPageView();
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/fr_CA/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//Shows the modal confirmation popup to add an item to the cart
function addToCart(id){
    let title = document.getElementById('serviceTitle' + id).innerText,
        description = document.getElementById('serviceDescription' + id).innerText,    
        price = document.getElementById('servicePrice' + id).innerText.slice(7), //Removing "Tarif: "
        modal = document.getElementById('modal'),
        titleText = document.getElementById('modalTitle'),
        descriptionText = document.getElementById('modalDescription'),
        priceText = document.getElementById('modalPrice');

    document.getElementById("cartItemId").value = id,
    //Show the modal 
    modal.style.display = 'inline';
    titleText.innerText = title;
    descriptionText.innerText = description;
    priceText.innerText = price;

    //Users clicks no/exit will return without adding the item to the cart
    /*if(!confirm("Voulez-vous ajouter " + title + " au panier?")){
        return;
    }*/

}

//Adds an item to the cart
function addCartItem(){
    //Adding the item ID to the cookies
    let id= document.getElementById("cartItemId").value,
        newItems = id,
        cartCount = 1,
        cart = getCookie("cart");

    //If the cart is currently empty, to perform checks on the items currently in it
    if( cart !== ""){
        let items = cart.split('|');
        cartCount = items.length;
        //Checking if item is already in cart
        if(items.includes(id+[])){
            //Hide the modal div
            document.getElementById("modal").style.display = "none";
            return;
        }
        items.push(id+[]);
        newItems = items.join('|');
    }
    //Update the cart items
    setCookie('cart', newItems);
    //Hide the modal div
    document.getElementById("modal").style.display = "none";
    //Update the cart text
    console.log(cartCount);
    document.getElementById("cart").innerText = "Mon Panier(" + cartCount + ")";
}

function setCookie(cname, cvalue) {
    document.cookie = cname + "=" + cvalue + ";";
}

function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.slice(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

const text = $(".sectionPromocode > span")[0];
function checkPromocode(){
    const code = document.getElementById("promoCode").value
    $.get("?promocode=" + code)
        .done(function(ans){
            //0 is error, invalid code
            if(ans != '0'){
                //Result is [promoId]|[discountValue]
                const values = ans.split('|');
                text.style.color = "green";
                text.innerHTML = "Activation réussie!";
                updatePromo(values[0],values[1], values[2], values[3], code);
            } else {
                //Show "Invalid promotion" message
                text.style.color = "red";
                text.innerHTML = "Erreur lors de l'activation! Veuillez réessayer.";
            }
    });
}

function updatePromo(serviceId, promotionName, discount, discountPercent, promocode){
    const promoArea = $("#servicePromotion" + serviceId)[0];
    promoArea.innerText = promotionName + " (" + discountPercent * 100 + "%)";
    document.getElementById("servicePromotionPrice" + serviceId).innerHTML = "- " + discount;
	document.getElementById("promoCodeValue").value = promocode;
    document.getElementById("orderSpecial").innerHTML = "Rabais additionnel: " + discount + "$";
    document.getElementById("orderTotal").innerHTML = "Total : " + (orderSubtotal - discount) + "$";
}
//Remove a service from its id from the cart
function removeService(serviceId){
    let cart = getCookie('cart').split('|');
    const indexOfOldValue = cart.indexOf(serviceId+[]);
    //If value is present, remove it
    if(indexOfOldValue != -1){
        //Removing old value
        cart.splice(indexOfOldValue, 1);
        console.log(cart);
        setCookie("cart",cart.join('|'));
    }
    //Refresh the page to see changes
    location.reload();
}
