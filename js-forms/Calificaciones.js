/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    var idescuela = null;
    getIdCargaFromPOST();
    LoadTable();
    idescuela = document.getElementById('id_escuela');
    
});

function getIdCargaFromPOST() {
    var idcarga = null;
    idcarga = GET('id_carga');
    var mytable = document.getElementById("dataTable0");
    mytable.setAttribute('findby', 'id_carga');
    mytable.setAttribute('findbyvalue', idcarga);
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            LoadTable();
        });
    }
}

