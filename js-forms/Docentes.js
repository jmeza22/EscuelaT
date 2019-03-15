/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    loadComboboxData(document.getElementById("lista_id_docente")).done(function () {
        autoNameFromDataList('id_docente', 'nombrecompleto_docente', null);
    });
    Buscar();
});

function Send(item) {
    if (validateForm(item)) {
        submitForm(item, false).done(function () {
            LoadTable();
        });
    }
}

function Buscar() {
    var iddocente = document.getElementById("id_docente");
    if (iddocente !== undefined && iddocente !== null && iddocente.value !== '') {
        getData(iddocente);
    }
    LoadTable();
}

function Edit(item) {
    var myform = null;
    myform = document.getElementById('form0');
    resetForm(myform);
    sendValue(item, null, myform, null);
    getData(myform).done(function () {
        setTimeout(function () {
        }, 1);
    });
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
    return mytable;
}