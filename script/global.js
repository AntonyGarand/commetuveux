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
