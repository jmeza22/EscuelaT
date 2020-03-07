jQuery(document).ready(function () {
    LoadEscuela();
});

function LoadEscuela() {
    var escuela = null;
    escuela = document.getElementById("id_escuela");
    escuela.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(escuela);
}

function goHome() {
    var role = null;
    role = getUserRoleLogin();
    showNotification('Bienvenido a EscuelaT de ' + getEnterpriseName() + '!', 'Bienvenido ' + getFullnameLogin());
    if (role !== null && role === "Student" && getFullnameLogin() !== null) {
        alert("Bienvenido. Será redirigido al Sitio para Votar!.");
        window.location.href = 'FormVotosElecciones.html';      
    }
}

function sendLogin(form) {
    var enterprise = document.getElementById("id_escuela");
    login(form, null).done(function () {
        setTimeout(function () {
            console.log(getErrorMessage());
            setUsernameLogin(document.getElementById("mynickname").value);
            if (enterprise.selected !== undefined && enterprise.selected !== null) {
                setEnterpriseID(enterprise.selected);
                setEnterpriseName(enterprise.getAttribute('text'));
            } else {
                setEnterpriseID(enterprise.value);
                setEnterpriseName(enterprise.getAttribute('text'));
            }
            if (getUserIdLogin() !== null) {
                window.setTimeout(goHome(), 5000);
            }
        }, 0);
    });
}

function Send(item) {
    var form = document.getElementById("FormLoginTerminal");
    if (validateForm(form)) {
        sendLogin(form);
    }
}