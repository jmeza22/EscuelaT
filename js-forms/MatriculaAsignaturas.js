/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    setIdMatricula();
    getMatricula();
    RefreshTable();
});

function setIdMatricula() {
    if (getPOST('id_matricula') !== null) {
        var form = document.getElementById('form0');
        var formauto = document.getElementById('formAuto');
        var idmat = null;
        idmat = getElement(form, 'id_matricula');
        if (idmat !== null && idmat !== undefined) {
            idmat.value = getPOST('id_matricula');
            unsetPOST('id_matricula');
            sendValue(form, 'id_matricula', formauto, 'id_matricula');
        }
    }
}

function getMatricula() {
    var form = document.getElementById('form0');
    if (validateForm(form)) {
        getFormData(form);
    }
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false).done(function () {
    });
    return mytable;
}

function RefreshTable() {
    var table0 = null;
    var idmat = null;
    table0 = document.getElementById("dataTable0");
    if (table0 !== null && table0 !== undefined) {
        idmat = document.getElementById("id_matricula");
        table0.setAttribute('findby', 'id_matricula');
        table0.setAttribute('findbyvalue', idmat.value);
        LoadTable();
    }
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false);
    }
}

function MatriculaAutomatica(item) {
    var form0 = document.getElementById('form0');
    var formauto = document.getElementById('formAuto');
    var id1 = getElement(form0, 'id_matricula');
    var id2 = getElement(formauto, 'id_matricula');
    id2.value = id1.value;
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            RefreshTable();
        });
    }
}

function EliminarMatasig(item) {
    if (confirm('Desea eliminar este Registro?')) {
        var form = getForm(item);
        if (validateForm(form)) {
            submitForm(item, false).done(function () {
                RefreshTable();
            });
        }
    }
}