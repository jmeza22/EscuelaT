jQuery(document).ready(function () {
    window.setTimeout(logoutSystem(), 3000);
});

function logoutSystem() {
    logout("Base/Controllers/LogoutController.php","login.html",getTokenLogin());
}