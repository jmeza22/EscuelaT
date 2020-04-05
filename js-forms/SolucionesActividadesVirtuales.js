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
    var idactividadA = getElement(formAct, 'id_actividad');
    var idactividadS = getElement(formSol, 'id_actividad');
    var idestudiante = getElement(formSol, 'id_estudiante');
    var idsolucion = getElement(formSol, 'id_solucion');
    var actividad = GET('id_actividad');
    var estudiante = GET('id_estudiante');
    var solucion = GET('id_solucion');
    idactividadA.value = actividad;
    idactividadS.value = actividad;
    idestudiante.value = estudiante;
    idsolucion.value = solucion;
    if (idactividadA !== undefined && idactividadA !== null && idactividadA.value !== null && idactividadA.value !== '') {
        getFormDataActividades();
    }
    if (idactividadS !== undefined && idactividadS !== null && idactividadS.value !== null && idactividadS.value !== ''
            && idestudiante !== undefined && idestudiante !== null && idestudiante.value !== null && idestudiante.value !== '') {
        getFormDataSoluciones();
    }
}

function getFormDataActividades() {
    var formAct = document.getElementById('formActividades');
    getFormData(formAct).done(function () {
        DocumentoActividad();
    });

}

function getFormDataSoluciones() {
    var formSol = document.getElementById('formSoluciones');
    getFormData(formSol).done(function () {
        DocumentoSolucion();
    });
}

function DocumentoActividad() {
    var documento = document.getElementById("documento_actividad");
    var link = document.getElementById("linkDocumento");
    var btn = document.getElementById("VerDocumento");
    if (documento.value !== '') {
        link.href = getWSPath() + "DocumentFiles/" + documento.value;
        link.setAttribute('title', 'Haga click para descargar la Guia.');
        link.removeAttribute('disabled');
        btn.removeAttribute('disabled');
        btn.setAttribute('class', 'btn btn-lg btn-success');
    } else {
        console.log('No hay Documento.');
        link.setAttribute('title', 'El Docente no incluyó un archivo o Guia.');
        link.setAttribute('disabled', 'disabled');
        btn.setAttribute('disabled', 'disabled');
        btn.setAttribute('class', 'btn btn-lg btn-default');
    }
}

function DocumentoSolucion() {
    var file = document.getElementById("document-file");
    var documento = document.getElementById("documento_solucion");
    var link = document.getElementById("linkSolucion");
    var btn = document.getElementById("VerSolucion");
    if (documento.value !== '') {
        link.href = getWSPath() + "DocumentFiles/" + documento.value;
        btn.setAttribute('title', 'Ha cargado un archivo. Haga click para verlo.');
        btn.setAttribute('class', 'btn btn-block btn-success');
        btn.innerHTML = '<i class="glyphicon glyphicon-download"></i> Descargar';
        file.setAttribute('disabled', 'disabled');
        link.setAttribute('disabled', 'disabled');
    } else {
        btn.setAttribute('title', 'No ha cargado archivos de Solucion. Haga click para seleccionarlo.');
        btn.setAttribute('class', 'btn btn-block btn-default');
        btn.innerHTML = '<i class="glyphicon glyphicon-upload"></i> Cargar';
        file.removeAttribute('disabled');
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

function selectFile() {
    var file = document.getElementById("document-file");
    console.log('Seleccionar Archivo');
    file.click();
}

function deleteFile() {
    var save = document.getElementById("save");
    save.setAttribute('action', 'delete');
    console.log('Eliminar Archivo');
    SendSolucion();
    save.setAttribute('action', 'insertorupdate');
}

function showSelectedFile() {
    var file = document.getElementById("document-file");
    var btn = document.getElementById("VerSolucion");
    if (file !== null && file.files !== undefined && file.files.length > 0) {
        var text = "";
        text = "Archivo: " + file.files[0].name + "\r\n";
        text = text + "Tipo: " + file.files[0].type + "\r\n";
        showNotification('Archivo Seleccionado:', text, 10000);
        btn.setAttribute('class', 'btn btn-block btn-info');
        btn.setAttribute('title', 'Archivo: ' + file.files[0].name);
        btn.innerHTML = '<i class="glyphicon glyphicon-file"></i> Seleccionado';
    }
}

function SendSolucion() {
    var form = document.getElementById("formSoluciones");
    var file = document.getElementById("document-file");
    var doc = document.getElementById("documento_solucion");
    var idestudiante = document.getElementById("id_estudiante");
    var fecha = null;
    if (file.files.length > 0) {
        fecha = new Date();
        doc.value = "Solucion" + idestudiante.value + file.files[0].name;
        console.log(doc.value);
    }
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

