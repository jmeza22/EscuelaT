jQuery(document).ready(function () {
    var idescuela = null;
    var periodo = null;
    var corte = null;
    idescuela = document.getElementById('enterpriseid');
    periodo = document.getElementById("id_periodo");
    corte = document.getElementById("id_corte");

    loadComboboxData(document.getElementById("lista_id_escuela")).done(function () {
        autoNameFromDataList('enterpriseid', 'enterprisename', null);
    });
    setDatosUsuario();

    loadComboboxData(periodo);
    VisibilidadSecciones();

    console.log('Buscando Datos de Configuracion.');
    getFormData(idescuela).done(function () {
        setComboboxFindby('id_corte', periodo.id, periodo.value);
        setComboboxValue(corte);
        loadComboboxData(corte);
    });
    autoNameFromDataList('id_corte', 'numero_corte', 'estado_corte');

});

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false).done(function () {
            var role = null;
            role = getUserRoleLogin();
            if (window.location.href.indexOf('FormVariablesSesion.html') > -1) {
                if (role !== null && role === "Teacher") {
                    window.location.href = 'FormHomeDocentes.html';
                }
                if (role !== null && role === "Student") {
                    window.location.href = 'FormHomeEstudiantes.html';
                }
                if (role !== null && role === "Visitor") {
                    window.location.href = 'FormHomeAcudientes.html';
                }
            }
        });
    }
}

function setDatosUsuario() {
    var iduser = null;
    var nickname = null;
    var usertype = null;
    var fullname = null;
    iduser = document.getElementById('userid');
    nickname = document.getElementById('nickname');
    usertype = document.getElementById('usertype');
    fullname = document.getElementById('fullname');
    iduser.value = getUserIdLogin();
    nickname.value = getUsernameLogin();
    usertype.value = getUserRoleLogin();
    fullname.value = getFullnameLogin();
}

function VisibilidadSecciones() {
    var seccionescuela = null;
    var seccionperiodocorte = null;
    seccionescuela = document.getElementById('seccionescuela');
    if (seccionescuela !== undefined && seccionescuela !== null && getUserRoleLogin() !== 'SuperAdmin' && getUserRoleLogin() !== 'Admin') {
        seccionescuela.style = 'display:none; visibility:hidden;';
    }
}

function VerSesion(item) {
    if (getUserRoleLogin() === 'SuperAdmin') {
        window.open('Base/Controllers/ShowSessionDataController.php', "Datos de Sesion - EscuelaT", "width=600,height=600,scrollbars=NO");
    } else {
        alert('Operacion No Permitida!');
    }
}