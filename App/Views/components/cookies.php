<div class="cookie-modal" id="cookieConsentModal">
    <div class="p-4">
        <div id="cookieSimpleConsent">
            <div class="text-center mb-3">
                <i class="bi bi-shield-lock cookie-icon"></i>
            </div>
            <h5 class="mb-3">Deze website gebruikt cookies</h5>
            <p>Wij gebruiken cookies om uw ervaring te verbeteren en voor analyse doeleinden.</p>
            <p><strong>Als u de cookies weigert werken bepaalde functies niet!</strong></p>
            <div class="d-flex justify-content-between gap-2 mt-4">
                <button class="btn deny btn-danger" style="background-color: #f3b7b7; border-color: #f3b7b7;"
                    id="rejectAllCookies">
                    Weigeren
                </button>
                <button class="btn setting btn-outline-secondary" id="cookieSettings">
                    Instellingen
                </button>
                <button class="btn accept btn-primary" style="background-color: #b7f3c8; border-color: #b7f3c8;"
                    id="acceptAllCookies">
                    Alles accepteren
                </button>
            </div>
        </div>

        <div id="cookieDetailedSettings">
            <h5 class="mb-3">Cookie instellingen</h5>
            <div class="cookie-categories">
                <div class="cookie-category p-3">
                    <div class="form-check">
                        <input class="form-check-input cookie-checkbox" type="checkbox" checked disabled>
                        <label class="form-check-label fw-bold">
                            Noodzakelijke cookies
                        </label>
                        <p class="small mb-0 text-muted">
                            Deze cookies zijn noodzakelijk voor het functioneren van de website.
                        </p>
                    </div>
                </div>

                <div class="cookie-category p-3">
                    <div class="form-check">
                        <input class="form-check-input cookie-checkbox" type="checkbox" id="functionalCookies">
                        <label class="form-check-label fw-bold">
                            Functionele cookies
                        </label>
                        <p class="small mb-0 text-muted">
                            Voor een betere gebruikerservaring en het onthouden van voorkeuren.
                        </p>
                    </div>
                </div>

                <div class="cookie-category p-3">
                    <div class="form-check">
                        <input class="form-check-input cookie-checkbox" type="checkbox" id="analyticsCookies">
                        <label class="form-check-label fw-bold">
                            Analytische cookies
                        </label>
                        <p class="small mb-0 text-muted">
                            Om het websitegebruik te analyseren en te verbeteren.
                        </p>
                    </div>
                </div>

                <div class="cookie-category p-3">
                    <div class="form-check">
                        <input class="form-check-input cookie-checkbox" type="checkbox" id="marketingCookies">
                        <label class="form-check-label fw-bold">
                            Marketing cookies
                        </label>
                        <p class="small mb-0 text-muted">
                            Voor gepersonaliseerde advertenties en content.
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button class="btn btn-primary" id="saveCookiePreferences">
                    Voorkeuren opslaan
                </button>
                <button class="btn btn-sm btn-outline-secondary me-3" id="backToCookieConsent">
                    <i class="bi bi-arrow-left"></i> Terug
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast" id="cookieToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-check-circle text-success me-2"></i>
            <strong class="me-auto">Cookie voorkeuren</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Uw cookie voorkeuren zijn opgeslagen!
        </div>
    </div>
</div>