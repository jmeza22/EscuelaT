/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    setDatosEncabezado();
});

function getFormDataActividades() {
    var form = document.getElementById('formActividades');
    getFormData(form).done(function () {
        LoadTable();
    });
}

function setDatosEncabezado() {
    var form = document.getElementById('formActividades');
    var idactividad = null;
    idactividad = getElement(form, 'id_actividad');
    idactividad.value = GET('id_actividad');
    getFormDataActividades();
}

function setFindBy() {
    var form = document.getElementById('formActividades');
    var idactividad = null;
    idactividad = getElement(form, 'id_actividad');
    var table = document.getElementById("dataTable0");
    table.setAttribute('findby1', idactividad.id);
    table.setAttribute('findbyvalue1', idactividad.value);
}

function LoadTable() {
    setFindBy();
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}
