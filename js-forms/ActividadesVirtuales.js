/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    var link = document.getElementById("linkDocumento");
    var oldhref = link.href;
    link.href = getWSPath() + 'DocumentFiles/{{documento_actividad}}';
    setDatosEncabezado();
    LoadTable();
});

function setDatosEncabezado() {
    var nombre = null;
    var iddocente = null;
    var idprograma = null;
    var idasignatura = null;
    var nomasignatura = null;
    var grado = null;
    nombre = document.getElementById('nombrecompleto_docente');
    iddocente = document.getElementById('id_docente');
    idprograma = document.getElementById('id_programa');
    idasignatura = document.getElementById('id_asignatura');
    nomasignatura = document.getElementById('nombre_asignatura');
    grado = document.getElementById('numgrado_programa');
    nombre.value = GET('nombrecompleto_docente');
    iddocente.value = GET('id_docente');
    idprograma.value = GET('id_programa');
    idasignatura.value = GET('id_asignatura');
    nomasignatura.value = GET('nombre_asignatura');
    grado.value = GET('numgrado_programa');
    setFindBy();
}

function setFindBy() {
    var iddocente = null;
    var idprograma = null;
    var idasignatura = null;
    var grado = null;
    var table = document.getElementById("dataTable0");
    iddocente = document.getElementById('id_docente');
    idprograma = document.getElementById('id_programa');
    idasignatura = document.getElementById('id_asignatura');
    grado = document.getElementById('numgrado_programa');
    table.setAttribute('findby1', iddocente.id);
    table.setAttribute('findbyvalue1', iddocente.value);
    table.setAttribute('findby2', idprograma.id);
    table.setAttribute('findbyvalue2', idprograma.value);
    table.setAttribute('findby3', idasignatura.id);
    table.setAttribute('findbyvalue3', idasignatura.value);
    table.setAttribute('findby4', grado.id);
    table.setAttribute('findbyvalue4', grado.value);
}

function LoadTable() {
    setFindBy();
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}

function SendActividad() {
    var form = document.getElementById("form0");
    var file = document.getElementById("document-file");
    var doc = document.getElementById("documento_actividad");
    var fecha = null;
    if (file.files.length > 0) {
        fecha = new Date();
        doc.value = "A" + fecha.getFullYear() + (fecha.getMonth() + 1) + fecha.getDate() + fecha.getHours() + fecha.getMinutes() + fecha.getMilliseconds() + "_" + file.files[0].name;
        console.log(doc.value);
    }
    if (validateForm(form)) {
        submitForm(form, false).done(function () {
            LoadTable();
        });
    }
}

function Edit(item) {
    var myform = null;
    myform = document.getElementById('form0');
    resetForm(myform);
    sendValue(item, null, myform, null);
    getFormData(myform).done(function () {
        setTimeout(function () {
        }, 1);
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
                    deleteTableRow(mytable);
                } else {
                    status.value = '1';
                }
            });
        }
    }
}
