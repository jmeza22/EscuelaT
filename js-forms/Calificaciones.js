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
    setDatosEncabezado();
});

function setDatosEncabezado() {
    var nombre = null;
    var idasignatura = null;
    var nomasignatura = null;
    var grado = null;
    var grupo = null;
    var listalogros=null;
    nombre = document.getElementById('nombrecompleto_docente');
    idasignatura = document.getElementById('id_asignatura');
    nomasignatura = document.getElementById('nombre_asignatura');
    grado = document.getElementById('numgrado_programa');
    grupo = document.getElementById('id_grupo');
    listalogros = document.getElementById('lista_id_logro');
    nombre.value = getFullnameLogin();
    idasignatura.value = GET('id_asignatura');
    nomasignatura.value = GET('nombre_asignatura');
    grado.value = GET('numgrado_programa');
    grupo.value = GET('id_grupo');
    listalogros.setAttribute('findby',idasignatura.id);
    listalogros.setAttribute('findbyvalue',idasignatura.value);
    listalogros.setAttribute('findby2','numgrado_logro');
    listalogros.setAttribute('findbyvalue2',grado.value);
    loadComboboxData(listalogros);
}

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

