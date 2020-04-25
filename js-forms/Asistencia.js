/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    setDatosEncabezado();
    setFecha();
    LoadTable();
});

function setDatosEncabezado() {
    var nombre = null;
    var idprograma = null;
    var idasignatura = null;
    var nomasignatura = null;
    var periodo = null;
    var corte = null;
    var ncorte = null;
    var grado = null;
    var grupo = null;
    var mytable = document.getElementById("dataTable0");
    var fecha = document.getElementById("fecha");
    nombre = document.getElementById('nombrecompleto_docente');
    idasignatura = document.getElementById('id_asignatura');
    nomasignatura = document.getElementById('nombre_asignatura');
    periodo = document.getElementById('id_periodo');
    corte = document.getElementById('id_corte');
    ncorte = document.getElementById('num_corte');
    grado = document.getElementById('numgrado_programa');
    grupo = document.getElementById('id_grupo');
    nombre.value = getFullnameLogin();
    idprograma = GET('id_programa');
    idasignatura.value = GET('id_asignatura');
    nomasignatura.value = GET('nombre_asignatura');
    periodo.value = GET('id_periodo');
    corte.value = GET('id_corte');
    ncorte.value = GET('num_corte');
    grado.value = GET('numgrado_programa');
    grupo.value = GET('id_grupo');
    mytable.setAttribute('findby1', 'id_programa');
    mytable.setAttribute('findbyvalue1', idprograma);
    mytable.setAttribute('findby2', 'id_asignatura');
    mytable.setAttribute('findbyvalue2', idasignatura.value);
    mytable.setAttribute('findby3', 'numgrado_programa');
    mytable.setAttribute('findbyvalue3', grado.value);
    mytable.setAttribute('findby4', 'id_grupo');
    mytable.setAttribute('findbyvalue4', grupo.value);
    mytable.setAttribute('findby5', 'id_periodo');
    mytable.setAttribute('findbyvalue5', periodo.value);
    fecha.value = getCurrentDate();
}

function setFecha() {
    var fecha = document.getElementById("fecha");
    var mytable = document.getElementById("dataTable0");
    mytable.setAttribute('findby', 'fecha_asistencia');
    mytable.setAttribute('findbyvalue', fecha.value);
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false).done(function () {

    });
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        var checks = convertCheckboxesToTexts(form);
        console.log(checks);
        submitForm(form, false).done(function () {
            convertTextsToCheckboxes(checks);
            if (parseInt(getRowCount()) > 0) {
                LoadTable();
            }
        });
    }
}

function validarInsigniasSanciones(item) {
    if (item !== null) {
        var row = getParentTR(item);
        var icomp = getElementByName(row, 'insigniacomportamiento_asistencia[]');
        var scomp = getElementByName(row, 'sancionmalcomportamiento_asistencia[]');
        var iresp = getElementByName(row, 'insigniaresponsabilidad_asistencia[]');
        var sresp = getElementByName(row, 'sancionirresponsabilidad_asistencia[]');
        var iequi = getElementByName(row, 'insigniaequipo_asistencia[]');
        var sequi = getElementByName(row, 'sancionegocentrismo_asistencia[]');
        var iexe = getElementByName(row, 'insigniaexelencia_asistencia[]');
        var sexe = getElementByName(row, 'sancionbajorendimiento_asistencia[]');
        if (icomp.value === '1' && scomp.value === '1') {
            icomp.click();
        }
        if (iresp.value === '1' && sresp.value === '1') {
            iresp.click();
        }
        if (iequi.value === '1' && sequi.value === '1') {
            iequi.click();
        }
        if (iexe.value === '1' && sexe.value === '1') {
            iexe.click();
        }
    }
}
