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
    var actividad = GET('id_actividad');
    var estudiante = GET('id_estudiante');
    idactividadA.value = actividad;
    idactividadS.value = actividad;
    idestudiante.value = estudiante;
    if (idactividadA !== undefined && idactividadA !== null && idactividadA.value !== null && idactividadA.value !== '') {
        getFormData(formAct).done(function () {
            var documento = document.getElementById("documento_actividad");
            var link = document.getElementById("linkDocumento");
            var btn = document.getElementById("VerDocumento");
            if (documento.value !== '') {
                link.href = getWSPath() + "DocumentFiles/" + documento.value;
                btn.setAttribute('class', 'btn btn-lg btn-success');
            } else {
                link.setAttribute('disabled', 'disabled');
                btn.setAttribute('disabled', 'disabled');
                btn.setAttribute('class', 'btn btn-lg btn-default');
            }
        });
    }
    if (idactividadS !== undefined && idactividadS !== null && idactividadS.value !== null && idactividadS.value !== ''
            && idestudiante !== undefined && idestudiante !== null && idestudiante.value !== null && idestudiante.value !== '') {
        getFormData(formSol).done(function () {
        });
    }
}

function getFormDataSoluciones() {
    var formSol = document.getElementById('formSoluciones');
    getFormData(formSol).done(function () {
    });
}

function SendSolucion() {
    var form = document.getElementById("formSol");
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
                alert('Bien hecho. Se ha guardado tu solucion!');
                getFormDataSoluciones();
            } else {
                alert('Upss. Hubo un error al intentar guardar!');
            }
        });
    }
}

