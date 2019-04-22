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
    setIdEscuela();
    setDatosUsuario();

    loadComboboxData(periodo);
    VisibilidadSecciones();

    if (idescuela !== undefined && idescuela !== null && idescuela.value !== '') {
        console.log('Buscando Datos de Configuracion.');
        getData(idescuela).done(function () {
            var listacorte = document.getElementById("lista_id_corte");
            setFindbyCombobox(listacorte.id, periodo.id, periodo.value);
            autoNameFromDataList('id_corte', 'numero_corte', null);
        });
    }

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

function setIdEscuela() {
    var item = null;
    item = document.getElementById('enterpriseid');
    var item2 = null;
    item2 = document.getElementById('enterprisename');
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Escuela.');
        if (getEnterpriseID() !== null) {
            item.value = getEnterpriseID();
        }
        if (getEnterpriseName() !== null) {
            item2.value = getEnterpriseName();
        }
        item.focus();
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