/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    var idescuela = null;
    loadComboboxData(document.getElementById("lista_id_escuela")).done(function () {
        autoNameFromDataList('id_escuela', 'nombre_escuela', null);
    });
    idescuela = document.getElementById('id_escuela');
    setIdEscuela();
    
    if (idescuela !== undefined && idescuela !== null && idescuela.value !== '') {
        console.log('Buscando Datos de Configuracion.');
        getData(idescuela);
    }
    
    
});

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false);
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
        item.focus();
    }
}
