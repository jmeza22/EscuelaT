/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    setDatosEncabezado();
});

function setDatosEncabezado() {
    var formAct = document.getElementById('formActividades');
    var formSol = document.getElementById('formSoluciones');
    var idactividad = getElement(formAct, 'id_actividad');
    var idestudiante = getElement(formSol, 'id_estudiante');
    var idsolucion = getElement(formSol, 'id_solucion');
    var actividad = GET('id_actividad');
    var estudiante = GET('id_estudiante');
    var solucion = GET('id_solucion');
    idactividad.value = actividad;
    idestudiante.value = estudiante;
    idsolucion.value = solucion;
    if (idactividad !== undefined && idactividad !== null && idactividad.value !== null && idactividad.value !== '') {
        getFormDataActividades();
    }
    if (idsolucion !== undefined && idsolucion !== null && idsolucion.value !== null && idsolucion.value !== '') {
        getFormDataSoluciones();
    }
}

function getFormDataActividades() {
    var formAct = document.getElementById('formActividades');
    getFormData(formAct).done(function () {
    });

}

function getFormDataSoluciones() {
    var formSol = document.getElementById('formSoluciones');
    getFormData(formSol).done(function () {
        var nombre = getElement(formSol, 'nombrecompleto_estudiante');
        nombre.value = GET('nombrecompleto_estudiante');
        DocumentoSolucion();
    });
}

function DocumentoSolucion() {
    var documento = document.getElementById("documento_solucion");
    var link = document.getElementById("linkSolucion");
    var btn = document.getElementById("VerSolucion");
    if (documento.value !== '') {
        link.href = getWSPath() + "DocumentFiles/" + documento.value;
        btn.setAttribute('title', 'El Estudiante cargó un archivo. Haga click para verlo.');
        btn.setAttribute('class', 'btn btn-block btn-success');
        btn.innerHTML = '<i class="glyphicon glyphicon-download"></i> Descargar';
        link.setAttribute('disabled', 'disabled');
    } else {
        btn.setAttribute('title', 'El Estudiante no ha cargado ningun Documento.');
        btn.setAttribute('class', 'btn btn-block btn-default');
        btn.innerHTML = '<i class="glyphicon glyphicon-upload"></i> Cargar';
        link.removeAttribute('disabled');
    }
}

function viewFile() {
    var link = document.getElementById("linkSolucion");
    var documento = document.getElementById("documento_solucion");
    if (link.href !== undefined && link.href !== '' && documento.value !== '') {
        console.log('Descargar Archivo');
        link.click();
    }
}

function deleteFile() {
    var save = document.getElementById("save");
    save.setAttribute('action', 'delete');
    console.log('Eliminar Archivo');
    SendSolucion();
    save.setAttribute('action', 'insertorupdate');
}

function SendSolucion() {
    var form = document.getElementById("formSoluciones");
    if (validateForm(form)) {
        submitForm(form, false).done(function () {
            if (parseFloat(getRowCount()) > 0) {
                alert('Bien hecho. Se han guardado los cambios!');
                getFormDataSoluciones();
            } else {
                alert('Upss. Hubo un error!');
            }
        });
    }
}

