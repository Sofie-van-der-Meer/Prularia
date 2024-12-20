document.addEventListener('DOMContentLoaded', function() {
    // Check if consent exists and is not expired
    const consent = localStorage.getItem('cookieConsent');
    if (consent) {
        const preferences = JSON.parse(consent);
        if (preferences.expiryDate && new Date(preferences.expiryDate) < new Date()) {
            localStorage.removeItem('cookieConsent');
            showCookieModal();
        }
    } else {
        showCookieModal();
    }

    // Handle settings button
    document.getElementById('cookieSettings').addEventListener('click', function() {
        document.getElementById('cookieSimpleConsent').style.display = 'none';
        document.getElementById('cookieDetailedSettings').style.display = 'block';
    });

    // Handle reject all cookies
    document.getElementById('rejectAllCookies').addEventListener('click', function() {
        const preferences = {
            necessary: false,
            functional: false,
            analytics: false,
            marketing: false,
            expiryDate: new Date(Date.now() + (24 * 60 * 60 * 1000)).toISOString() // 1 dag
        };
        localStorage.setItem('cookieConsent', JSON.stringify(preferences));
        document.getElementById('cookieConsentModal').classList.remove('show');
        
        const toast = new bootstrap.Toast(document.getElementById('cookieToast'));
        document.querySelector('#cookieToast .toast-body').textContent = 
            'Alle cookies zijn geweigerd voor 24 uur.';
        toast.show();
    });

    // Handle accept all cookies
    document.getElementById('acceptAllCookies').addEventListener('click', function() {
        const preferences = {
            necessary: true,
            functional: true,
            analytics: true,
            marketing: true,
            expiryDate: new Date(Date.now() + (24 * 60 * 60 * 1000)).toISOString() // 1 dag
        };
        localStorage.setItem('cookieConsent', JSON.stringify(preferences));
        document.getElementById('cookieConsentModal').classList.remove('show');
        
        const toast = new bootstrap.Toast(document.getElementById('cookieToast'));
        document.querySelector('#cookieToast .toast-body').textContent = 
            'Alle cookies zijn geaccepteerd voor 24 uur.';
        toast.show();
    });

    // Handle save preferences
    document.getElementById('saveCookiePreferences').addEventListener('click', function() {
        saveCookiePreferences();
    });

    // Handle back button
    document.getElementById('backToCookieConsent').addEventListener('click', function() {
        document.getElementById('cookieDetailedSettings').style.display = 'none';
        document.getElementById('cookieSimpleConsent').style.display = 'block';
    });
});

function showCookieModal() {
    setTimeout(() => {
        document.getElementById('cookieConsentModal').classList.add('show');
    }, 1000);
}

function saveCookiePreferences() {
    const preferences = {
        necessary: true,
        functional: document.getElementById('functionalCookies').checked,
        analytics: document.getElementById('analyticsCookies').checked,
        marketing: document.getElementById('marketingCookies').checked,
        expiryDate: new Date(Date.now() + (24 * 60 * 60 * 1000)).toISOString() // 1 dag
    };

    localStorage.setItem('cookieConsent', JSON.stringify(preferences));
    document.getElementById('cookieConsentModal').classList.remove('show');
    
    const toast = new bootstrap.Toast(document.getElementById('cookieToast'));
    document.querySelector('#cookieToast .toast-body').textContent = 
        'Uw cookie voorkeuren zijn opgeslagen voor 24 uur.';
    toast.show();
}

// Check every hour for expired cookies
setInterval(() => {
    const consent = localStorage.getItem('cookieConsent');
    if (consent) {
        const preferences = JSON.parse(consent);
        if (preferences.expiryDate && new Date(preferences.expiryDate) < new Date()) {
            localStorage.removeItem('cookieConsent');
            showCookieModal();
        }
    }
}, 3600000); // Check every hour