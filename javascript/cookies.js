function checkCookieConsent() {
    var consent = getCookie('cookieConsent');
    if (!consent) {
        document.getElementById('cookie-banner').style.display = 'block';
    }
}

function acceptCookies() {
    setCookie('cookieConsent', 'true', 365);
    document.getElementById('cookie-banner').style.display = 'none';
}

// Hergebruik de cookie-functies
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

// Controleer op cookie-toestemming bij het laden van de pagina
window.onload = checkCookieConsent;

// Voorbeeld van het lezen van een cookie
var username = getCookie('username');
if (username) {
    console.log('Welkom terug, ' + username);
} else {
    console.log('Gebruiker niet gevonden');
}

// Voorbeeld van het verwijderen van een cookie
function eraseCookie(name) {   
    document.cookie = name + '=; Max-Age=-99999999;';  
}
eraseCookie('username');

// Voorbeeld van het instellen van een cookie
setCookie('username', 'JohnDoe', 7); // Cookie 'username' wordt ingesteld voor 7 dagen
