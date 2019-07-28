/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    CargarNombres();
    BuscarEstudiante();
    BuscarEstudianteActivo();
});

function CargarNombres() {
    var form0 = document.getElementById("form0");
    var idestudiante = null;
    if (form0 !== undefined && form0 !== null) {
        idestudiante = getElement(form0, 'id_estudiante');
        if (idestudiante !== undefined && idestudiante !== null) {
            loadComboboxData(document.getElementById("lista_id_persona")).done(function () {
                autoNameFromDataList(idestudiante, 'nombrecompleto_estudiante', null);
                autoNameFromDataList('idacudiente1_estudiante', 'nombreacudiente1_estudiante', null);
                autoNameFromDataList('idacudiente2_estudiante', 'nombreacudiente2_estudiante', null);
            });
            loadComboboxData(document.getElementById("lista_acudientes"));
        }
    }
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false);
    }
}

function BuscarEstudiante() {
    var form0 = document.getElementById("form0");
    var idestudiante = null;
    var idaux = null;
    if (form0 !== undefined && form0 !== null) {
        idestudiante = getElement(form0, 'id_estudiante');
        if (idestudiante !== undefined && idestudiante.value !== '') {
            idaux = idestudiante.value;
            resetForm(form0);
            idestudiante.value = idaux;
            getData(form0);
            LoadTableAnotaciones();
            CopiarCodigoEstudiante();
        }
    }
}

function BuscarEstudianteActivo() {
    var form0 = document.getElementById("form0");
    var idestudiante = null;
    console.log('Tipo de Usuario: ' + getUserRoleLogin());
    if (getUserRoleLogin() !== null && getUserRoleLogin() === 'Student') {
        form0.setAttribute('url', 'Base/Controllers/FindEstudiantesController.php');
        idestudiante = getElement(form0, 'id_estudiante');
        if (idestudiante !== undefined && idestudiante !== null) {
            idestudiante.value = getUserIdLogin();
            idestudiante.setAttribute('readonly', 'readonly');
            if (idestudiante.value !== '') {
                getData(form0).done(function () {
                    CopiarCodigoEstudiante();
                    LoadTableAnotaciones();
                    document.getElementById("save").setAttribute('disabled','disabled');
                    document.getElementById("reset").setAttribute('disabled','disabled');
                    document.getElementById("btAnotacion").setAttribute('disabled','disabled');
                });
            }
        }
    }
}

function EditEstudiante(item) {
    var myform = null;
    myform = document.getElementById('form0');
    resetForm(myform);
    sendValue(item, null, myform, null);
    BuscarEstudiante();
}

function EditAnotacion(item) {
    var myform = null;
    myform = document.getElementById('formAN');
    resetForm(myform);
    sendValue(item, null, myform, null);
    getData(myform).done(function () {
        setTimeout(function () {
        }, 1);
    });
}

function EditCitacion(item) {
    var myform = null;
    myform = document.getElementById('formCI');
    resetForm(myform);
    sendValue(item, null, myform, null);
    getData(myform).done(function () {
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
        if (tr !== null && tr !== undefined) {
            id = getElement(tr, getFindBy(form));
            status = getElement(tr, getStatusFieldName(form));
        }
        if (id !== null && status !== null && mytable !== null) {
            console.log('Tratando de Eliminar id: ' + id);
            addAttributeDisabled(mytable);
            removeAttributeDisabled(tr);
            status.value = '0';
            Send(item);
            deleteRowInTable(mytable);
        }
    }
}

function LoadTableAnotaciones() {
    var mytable = document.getElementById("dataTableAN");
    loadTableData(mytable, false);
    return mytable;
}

function LoadTableCitaciones() {
    var mytable = document.getElementById("dataTableCI");
    loadTableData(mytable, false);
    return mytable;
}

function LoadTableEstudiantes() {
    var mytable = document.getElementById("dataTableE");
    loadTableData(mytable, true);
    return mytable;
}

function CopiarCodigoEstudianteAnotacion() {
    var form0 = null;
    var formAN = null;
    var idestudiante = null;
    var idestudianteanotacion = null;
    var mytable = document.getElementById("dataTableAN");
    form0 = document.getElementById('form0');
    formAN = document.getElementById('formAN');
    idestudiante = getElement(form0, "id_estudiante");
    idestudianteanotacion = getElement(formAN, "id_estudiante");
    if (idestudianteanotacion !== undefined && idestudianteanotacion !== null) {
        console.log('Copiando Datos de Estudiante a Panel de Anotacion.');
        idestudianteanotacion.value = idestudiante.value;
    }
    mytable.setAttribute('findby', 'id_estudiante');
    mytable.setAttribute('findbyvalue', idestudiante.value);
}

function CopiarCodigoEstudianteCitacion() {
    var form0 = null;
    var formCI = null;
    var idestudiante = null;
    var idestudiantecitacion = null;
    var mytable = document.getElementById("dataTableCI");
    form0 = document.getElementById('form0');
    formCI = document.getElementById('formCI');
    idestudiante = getElement(form0, "id_estudiante");
    idestudiantecitacion = getElement(formCI, "id_estudiante");
    if (idestudiantecitacion !== undefined && idestudiantecitacion !== null) {
        console.log('Copiando Datos de Estudiante a Panel de Citacion.');
        idestudiantecitacion.value = idestudiante.value;
    }
    mytable.setAttribute('findby', 'id_estudiante');
    mytable.setAttribute('findbyvalue', idestudiante.value);
}

function GenerarFechaAnotacion() {
    var fechaanotacion = null;
    var fecha = new Date();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    var ano = fecha.getFullYear();
    fechaanotacion = document.getElementById('fecha_anotacion');
    if (fechaanotacion !== undefined && (fechaanotacion.value === '' || fechaanotacion.value === ' ')) {
        if (dia < 10)
            dia = '0' + dia;
        if (mes < 10)
            mes = '0' + mes;
        fechaanotacion.value = ano + "-" + mes + "-" + dia;
    }
}

function GenerarFechaCitacion() {
    var fechacitacion = null;
    var fecha = new Date();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    var ano = fecha.getFullYear();
    fechacitacion = document.getElementById('fechacita_citacion');
    if (fechacitacion !== undefined && (fechacitacion.value === '' || fechacitacion.value === ' ')) {
        if (dia < 10)
            dia = '0' + dia;
        if (mes < 10)
            mes = '0' + mes;
        fechacitacion.value = ano + "-" + mes + "-" + dia;
    }
}

function GenerarCodigoAnotacion() {
    var idanotacion = null;
    var fecha = null;
    var codigo = null;
    idanotacion = getElement(document.getElementById('formAN'), 'id_anotacion');
    if (idanotacion !== undefined && (idanotacion.value === '' || idanotacion.value === ' ')) {
        fecha = new Date();
        codigo = 'AN' + fecha.getFullYear().toString() + (fecha.getMonth() + 1) + (fecha.getDate() * fecha.getMilliseconds());
        console.log('Generando Codigo Anotacion: ' + codigo);
        idanotacion.value = codigo;
    }
}

function GenerarCodigoCitacion() {
    var idcitacion = null;
    var fecha = null;
    var codigo = null;
    idcitacion = getElement(document.getElementById('formCI'), 'id_citacion');
    if (idcitacion !== undefined && (idcitacion.value === '' || idcitacion.value === ' ')) {
        fecha = new Date();
        codigo = 'CI' + fecha.getFullYear().toString() + (fecha.getMonth() + 1) + (fecha.getDate() * fecha.getMilliseconds());
        console.log('Generando Codigo Citacion: ' + codigo);
        idcitacion.value = codigo;
    }
}

function GenerarCodigoFechaAnotacion() {
    CopiarCodigoEstudianteAnotacion();
    GenerarFechaAnotacion();
    GenerarCodigoAnotacion();
}

function GenerarCodigoFechaCitacion() {
    CopiarCodigoEstudianteCitacion();
    GenerarFechaCitacion();
    GenerarCodigoCitacion();
}

function GrabarAnotacion(item) {
    GenerarFechaAnotacion();
    GenerarCodigoAnotacion();
    if (validateForm(item)) {
        submitForm(item, false).done(function () {
            LoadTableAnotaciones();
        });
    }
}

function GrabarCitacion(item) {
    GenerarFechaCitacion();
    GenerarCodigoCitacion();
    if (validateForm(item)) {
        submitForm(item, false).done(function () {
            LoadTableCitaciones();
        });
    }
}

function GrabarEstudiante(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false).done(function () {
            if(getLastInsertId()!==null){
                alert('El Codigo del Estudiante es: '+getLastInsertId());
            }
            LoadTableEstudiantes();
        });
    }
}

function resetAnoacion() {

}