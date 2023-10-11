function openCookieModal() {
    document.getElementById('cookie-modal').style.display = 'block';
}

function closeCookieModal() {
    document.getElementById('cookie-modal').style.display = 'none';
}

function insertGA4Script() {
    const head = document.head || document.getElementsByTagName('head')[0];
    const script = document.createElement('script');
    script.async = true;
    script.src = 'https://www.googletagmanager.com/gtag/js?id=YOUR-GA4-ID'; // Replace with your GA4 Tracking ID

    script.onload = function () {
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'YOUR-GA4-ID'); // Replace with your GA4 Tracking ID
    };

    head.appendChild(script);
}

window.onload = function () {
    if (document.cookie.indexOf('ga4_cookie_consent=allow') === -1) {
        openCookieModal();
    }
};

document.querySelector('.allow-button').addEventListener('click', function (e) {
    e.preventDefault();
    insertGA4Script();
    document.cookie = 'ga4_cookie_consent=allow; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/';
    closeCookieModal();
    
    // Reload the page to initialize the cookie
    location.reload();
});
