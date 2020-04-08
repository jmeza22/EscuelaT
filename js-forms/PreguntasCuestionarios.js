/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    LoadSelectCuestionario();
    loadComboboxData(document.getElementById("id_lectura"));
});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    clearTableData(mytable);
    loadTableData(mytable, false).done(function () {
    });
    return mytable;
}

function LoadSelectCuestionario() {
    var cuestionario = document.getElementById("select_id_cuestionario");
    loadComboboxData(cuestionario);
}

function LoadSelectPregunta() {
    var pregunta = document.getElementById("select_id_pregunta");
    loadComboboxData(pregunta);
}

function Send(item) {
    var form = getForm(item);
    var reset = null;
    if (validateForm(form)) {
        submitForm(form, false).done(function () {
            reset = getElement(form, 'reset');
            if (parseFloat(getRowCount()) > 0 && form.id === 'form0') {
                LoadSelectPregunta();
                reset.click();
            }
            if (parseFloat(getRowCount()) > 0 && form.id === 'form1') {
                LoadTable();
                reset.click();
            }
        });
    }
}

function setIdCuestionarioToPregunta() {
    var cuestionario = document.getElementById('select_id_cuestionario');
    if (cuestionario !== null) {
        var formPregunta = document.getElementById('form0');
        var idcuestionario = getElement(formPregunta, 'id_cuestionario');
        idcuestionario.value = cuestionario.value;
    }
}

function setIdPreguntaToOpcionRespuesta() {
    var pregunta = document.getElementById('select_id_pregunta');
    if (pregunta !== null) {
        var formPregunta = document.getElementById('form0');
        var formOpcion = document.getElementById('form1');
        var idpregunta = getElement(formPregunta, 'id_pregunta');
        var mytable = document.getElementById('dataTable0');
        idpregunta.value = pregunta.value;
        idpregunta = getElement(formOpcion, 'id_pregunta');
        idpregunta.value = pregunta.value;
        setFindByField(mytable.id, idpregunta.id, idpregunta.value);
        getFormData(formPregunta).done(function () {
            idpregunta.value = pregunta.value;
            document.getElementById('nombre_pregunta').value = document.getElementById('nombrecorto_pregunta').value;
        });

        LoadTable();
    }
}

function setIdOpcionRespuesta() {
    var formOpcion = document.getElementById('form1');
    var idopcion = document.getElementById('id_opcionrespuesta');
    var idpregunta = getElement(formOpcion, 'id_pregunta');
    if (idopcion.value === '' && idpregunta.value !== '0') {
        var fecha = new Date();
        idopcion.value = idpregunta.value + fecha.getFullYear() + '' + (fecha.getMonth() + 1) + '' + fecha.getDate() + '' + fecha.getHours() + '' + fecha.getMinutes() + '' + fecha.getSeconds();
    }
}

function MostrarImagenPregunta() {
    var imagen = document.getElementById('imagen_pregunta');
    if (imagen !== null) {
        var prefix = '';
        var id = ''
        var fecha = new Date();
        if (imagen.value === '') {
            imagen.value = 'Pregunta' + fecha.getFullYear() + '' + (fecha.getMonth() + 1) + '' + fecha.getDate() + '' + fecha.getHours() + '' + fecha.getMinutes() + '' + fecha.getSeconds() + '.jpg';
        }
        window.open(getWSPath() + "UploadImageForm.html?prefix=" + prefix + "&id=" + id + "&img=" + imagen.value + "", "Subir una Imagen al Servidor - EscuelaT", "width=600,height=600,scrollbars=NO");
    }
}

function MostrarImagenOpcionRespuesta() {
    var imagen = document.getElementById('imagen_opcionrespuesta');
    if (imagen !== null) {
        var prefix = '';
        var id = ''
        var fecha = new Date();
        if (imagen.value === '') {
            imagen.value = 'OpcionRespuesta' + fecha.getFullYear() + '' + (fecha.getMonth() + 1) + '' + fecha.getDate() + '' + fecha.getHours() + '' + fecha.getMinutes() + '' + fecha.getSeconds() + '.jpg';
        }
        window.open(getWSPath() + "UploadImageForm.html?prefix=" + prefix + "&id=" + id + "&img=" + imagen.value + "", "Subir una Imagen al Servidor - EscuelaT", "width=600,height=600,scrollbars=NO");
    }
}

function Edit(item) {
    var myform = null;
    myform = document.getElementById('form1');
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
