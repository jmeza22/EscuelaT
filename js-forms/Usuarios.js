/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {

    loadComboboxData(document.getElementById("lista_id_escuela")).done(function () {
        autoNameFromDataList('id_escuela', 'nombre_escuela', null);
    });

    loadComboboxData(document.getElementById("lista_id_tipousuario")).done(function () {
        autoNameFromDataList('id_tipousuario', 'nombre_tipousuario', null);
    });

    autoLoadNameFromId('id_persona', 'documento_persona', 'nombre1_persona', null);
    
    setIdEscuela();

});


function Send(item) {
    var username = document.getElementById('username_usuario');
    var password = document.getElementById('password_usuario');
    if (validateForm(item)) {
        if (username.value === '0' || username.value === '') {
            nuevoUsername();
        }
        if (password.value === ' ' || password.value === '') {
            nuevoPassword();
        }
        submitForm(item, false);
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
        if (getIdEnterprise() !== null) {
            item.value = getIdEnterprise();
        }
    }
}