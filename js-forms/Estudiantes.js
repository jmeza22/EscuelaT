/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    CargarNombres();
    BuscarEstudiante();
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
            form0.reset();
            idestudiante.value = idaux;
            getData(form0);
            LoadTableAnotaciones();
            CopiarCodigoEstudiante();
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
    myform = document.getElementById('form1');
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
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
    return mytable;
}

function LoadTableEstudiantes() {
    var mytable = document.getElementById("dataTableE");
    loadTableData(mytable, true);
    return mytable;
}

function CopiarCodigoEstudiante() {
    var form0 = null;
    var form1 = null;
    var idestudiante = null;
    var idestudianteanotacion = null;
    var mytable = document.getElementById("dataTable0");
    form0 = document.getElementById('form0');
    form1 = document.getElementById('form1');
    idestudiante = getElement(form0, "id_estudiante");
    idestudianteanotacion = getElement(form1, "id_estudiante");
    if (idestudianteanotacion !== undefined && idestudianteanotacion !== null) {
        console.log('Copiando Datos de Estudiante a Panel de Anotacion.');
        idestudianteanotacion.value = idestudiante.value;
        mytable.setAttribute('findby', 'id_estudiante');
        mytable.setAttribute('findbyvalue', idestudiante.value);
    }
}

function GenerarFecha() {
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
            mes = '0' + mes
        fechaanotacion.value = ano + "-" + mes + "-" + dia;
    }
}

function GenerarCodigoAnotacion() {
    var idanotacion = null;
    var fecha = null;
    var codigo = null;
    idanotacion = getElement(document.getElementById('form1'), 'id_anotacion');
    if (idanotacion !== undefined && (idanotacion.value === '' || idanotacion.value === ' ')) {
        fecha = new Date();
        codigo = 'AN' + fecha.getFullYear().toString() + (fecha.getMonth() + 1) + (fecha.getDate() * fecha.getMilliseconds());
        console.log('Generando Codigo Anotacion: ' + codigo);
        idanotacion.value = codigo;
    }
}

function GenerarCodigoFecha() {
    CopiarCodigoEstudiante();
    GenerarFecha();
    GenerarCodigoAnotacion();
}

function GrabarAnotacion(item) {
    GenerarFecha();
    GenerarCodigoAnotacion();
    if (validateForm(item)) {
        submitForm(item, false).done(function () {
            LoadTableAnotaciones();
        });
    }
}

function GrabarEstudiante(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false).done(function () {
            LoadTableEstudiantes();
        });
    }
}

function resetAnoacion(){
    
}