/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {

    loadComboboxData(document.getElementById("lista_id_escuela")).done(function () {
        autoNameFromDataList('id_escuela', 'nombre_escuela', null);
    });
    autoNameFromDataList('id_tipousuario', 'nombre_tipousuario', null);
    autoLoadNameFromId('id_persona', 'nombre1_persona', 'apellido1_persona', null);

    setIdEscuela();
    LoadTable();

});

function setNombreCompleto() {
    var nombre1 = document.getElementById("nombre1_persona");
    var apellido1 = document.getElementById("apellido1_persona");
    if (nombre1 !== undefined && apellido1 !== undefined && nombre1.value !== '' && apellido1.value !== '') {
        document.getElementById("nombrecompleto_persona").value = nombre1.value + ' ' + apellido1.value;
    }
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
    return mytable;
}

function Send(item) {
    var myform = null;
    myform = getForm(item);
    if (validateForm(myform)) {
        submitForm(myform, false).done(function () {
            setTimeout(function () {
                LoadTable();
                resetForm(myform);
            }, 100);
        });
    }
}

function SendUsuario(item) {
    var username = document.getElementById('username_usuario');
    var password = document.getElementById('password_usuario');
    var myform = null;
    myform = getForm(item);
    if (username.value === '0' || username.value === '') {
        nuevoUsername();
    }
    if (password.value === ' ' || password.value === '') {
        nuevoPassword();
    }
    if (validateForm(myform)) {
        setNombreCompleto();
        Send(myform);
    }
}

function Edit(item) {
    var myform = null;
    myform = document.getElementById('form0');
    resetForm(myform);
    sendValue(item, null, myform, null);
    getData(myform).done(function () {
        setTimeout(function () {
            myform.focus();
        }, 100);
    });
}

function DeleteItem(item) {
    if (confirm('Desea eliminar este Registro?')) {
        var form = getForm(item);
        var tr = getParentTR(item);
        var mytable = getParentTable(item);
        var id = null;
        var status = null;
        var rowcount = 0;
        if (tr !== null && tr !== undefined) {
            id = getElement(tr, getFindBy(form));
            status = getElement(tr, getStatusFieldName(form));
        }
        if (id !== null && status !== null && mytable !== null) {
            console.log('Tratando de Eliminar id: ' + id);
            addAttributeDisabled(mytable);
            removeAttributeDisabled(tr);
            status.value = '0';
            submitForm(form, false).done(function () {
                rowcount = window.sessionStorage.getItem('rowCount');
                rowcount = parseFloat(rowcount);
                if (rowcount !== undefined && rowcount !== null && rowcount > 0) {
                    deleteRowInTable(mytable);
                } else {
                    status.value = '1';
                }
            });
        }
    }
}

function nuevoUsername() {
    var idpersona = document.getElementById('id_persona');
    var username = document.getElementById('username_usuario');
    if (idpersona !== null && idpersona.value !== "") {
        username.value = idpersona.value;
    }
}

function nuevoPassword() {
    var idpersona = document.getElementById('id_persona');
    var password = document.getElementById('password_usuario');
    if (idpersona !== null && idpersona.value !== "") {
        password.value = idpersona.value;
    }
}

function setIdEscuela() {
    var item = null;
    item = document.getElementById('id_escuela');
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Escuela.');
        if (getEnterpriseID() !== null) {
            item.value = getEnterpriseID();
        }
    }
}