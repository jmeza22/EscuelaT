jQuery(document).ready(function () {
    resetLocalPOST();
    document.getElementById("mynickname").value = getUsernameLogin();
    document.getElementById("mypassword").value = getPasswordLogin();
    loadComboboxData(document.getElementById("id_tipousuario"));
    LoadEscuela();
    if (getUserIdLogin() !== null) {
        window.location.href = 'home.html';
    }
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
    if (role !== null && (role === "SuperAdmin" || role === "Admin" || role === "Management")) {
        window.location.href = 'home.html';
    }
    if (role !== null && role === "Teacher") {
        window.location.href = 'homeDocentes.html';
    }
    if (role !== null && role === "Student") {
        window.location.href = 'homeEstudiantes.html';
    }
    if (role !== null && role === "Visitor") {
        window.location.href = 'homeAcudientes.html';
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
    var form = document.getElementById("FormLogin");
    if (validateForm(form)) {
        sendLogin(form);
    }
}