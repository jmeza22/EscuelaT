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

function CopiarCodigo() {
    var formP = document.getElementById("formP");
    var formE = document.getElementById("form0");
    var formR = document.getElementById("formRep");
    var idpersona = getElement(formP, 'id_persona');
    var idestudiante = getElement(formE, 'id_estudiante');
    var idestudianteR = getElement(formR, 'id_estudiante');
    idestudiante.value = idpersona.value;
    idestudianteR.value = idpersona.value;
}

function CargarNombres() {
    var form0 = document.getElementById("form0");
    var idestudiante = null;
    if (form0 !== undefined && form0 !== null) {
        idestudiante = getElement(form0, 'id_estudiante');
        if (idestudiante !== undefined && idestudiante !== null) {
            loadComboboxData(document.getElementById("lista_acudientes"));
            autoNameFromDataList('idacudiente1_estudiante', 'nombreacudiente1_estudiante', null);
            autoNameFromDataList('idacudiente2_estudiante', 'nombreacudiente2_estudiante', null);
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
    CopiarCodigo();
    var formP = document.getElementById("formP");
    var form0 = document.getElementById("form0");
    var idpersona = null;
    var idestudiante = null;
    var idaux = null;
    if (form0 !== undefined && form0 !== null) {
        idpersona = getElement(formP, 'id_persona');
        idestudiante = getElement(form0, 'id_estudiante');
        if (idpersona !== undefined && idpersona.value !== '') {
            idaux = idpersona.value;
            resetForm(form0);
            idpersona.value = idaux;
            idestudiante.value = idaux;
            document.getElementById('id_tipousuario').value = 'Student';
            getData(formP);
            getData(form0);
            LoadTableAnotaciones();
            LoadTableCitaciones();
            CopiarCodigoEstudianteAnotacion();
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
                    CopiarCodigoEstudianteAnotacion();
                    LoadTableAnotaciones();
                    LoadTableCitaciones();
                    document.getElementById("save").setAttribute('disabled', 'disabled');
                    document.getElementById("reset").setAttribute('disabled', 'disabled');
                    document.getElementById("btAnotacion").setAttribute('disabled', 'disabled');
                });
            }
        }
    }
}

function CargarFrameFoto() {
    var prefix="Estudiante";
    var form0 = document.getElementById("form0");
    var idestudiante = getElement(form0, "id_estudiante");
    var frameFoto = document.getElementById("frameFoto");
    var foto = document.getElementById("foto_estudiante");
    foto.value = prefix + idestudiante.value + ".jpg";
    frameFoto.src = "UploadImageForm.html?prefix="+prefix+"&id=" + idestudiante.value + "&img=" + foto.value;
}

function EditPersona(item) {
    var myform = null;
    myform = document.getElementById('formP');
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
    var idestudiante = getElement(getForm('form0'), 'id_estudiante');
    clearTableData(mytable);
    mytable.setAttribute('findby', 'id_estudiante');
    mytable.setAttribute('findbyvalue', idestudiante.value);
    loadTableData(mytable, false);
    return mytable;
}

function LoadTableCitaciones() {
    var mytable = document.getElementById("dataTableCI");
    var idestudiante = getElement(getForm('form0'), 'id_estudiante');
    clearTableData(mytable);
    mytable.setAttribute('findby', 'id_estudiante');
    mytable.setAttribute('findbyvalue', idestudiante.value);
    loadTableData(mytable, false);
    return mytable;
}

function LoadTableEstudiantes() {
    var mytable = document.getElementById("dataTableE");
    clearTableData(mytable);
    loadTableData(mytable, true);
    return mytable;
}

function ClearTableAnotaciones() {
    var mytable = document.getElementById("dataTableAN");
    clearTableData(mytable);
}
function ClearTableCitaciones() {
    var mytable = document.getElementById("dataTableCI");
    clearTableData(mytable);
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

function GenerarIdPersona() {
    var idpersona = null;
    var nombre1 = null;
    var apellido1 = null;
    var tipodoc = null;
    var fecha = null;
    var nuevo = null;
    if (document.getElementById('id_persona') !== null && document.getElementById('id_persona') !== undefined) {
        idpersona = document.getElementById('id_persona');
    }
    if (document.getElementById('nombre1_persona') !== null && document.getElementById('nombre1_persona') !== undefined) {
        nombre1 = document.getElementById('nombre1_persona').value;
    }
    if (document.getElementById('apellido1_persona') !== null && document.getElementById('apellido1_persona') !== undefined) {
        apellido1 = document.getElementById('apellido1_persona').value;
    }
    if (document.getElementById('tipodoc_persona') !== null && document.getElementById('tipodoc_persona') !== undefined) {
        tipodoc = document.getElementById('tipodoc_persona');
        tipodoc = tipodoc.value;
    }
    fecha = new Date();
    if (nombre1 !== null && nombre1 !== "" && apellido1 !== null && apellido1 !== "") {
        if (document.getElementById('id_persona') !== undefined && (idpersona.value === "" || idpersona.value === "0")) {
            nuevo = fecha.getTime() + getRandomNumber(0, 99);
            console.log('Generando Id: ' + nuevo);
            document.getElementById('id_persona').value = nuevo;
        }
    } else {
        alert("Debe ingresar Nombre y Apellido.");
        return false;
    }
    return true;
}


function GrabarAnotacion(item) {
    GenerarFechaAnotacion();
    GenerarCodigoAnotacion();
    if (validateForm(getForm(item))) {
        submitForm(item, false).done(function () {
            LoadTableAnotaciones();
        });
    } else {
        showNotification('Verifique los datos de la Anotacion!');
    }
}

function GrabarCitacion(item) {
    GenerarFechaCitacion();
    GenerarCodigoCitacion();
    if (validateForm(getForm(item))) {
        submitForm(item, false).done(function () {
            LoadTableCitaciones();
        });
    } else {
        showNotification('Verifique los datos de la Citacion!');
    }
}

function GrabarEstudiante(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false).done(function () {
            if (getLastInsertId() !== null) {
                alert('El Codigo del Estudiante es: ' + getLastInsertId());
            }
            LoadTableEstudiantes();
        });
    }
}

function GrabarPersona(item) {
    var formP = getForm(item);
    var formE = document.getElementById('form0');
    var idpersona = getElement(formP, 'id_persona');
    var idestudiante = getElement(formE, 'id_estudiante');
    if (idpersona.value === '0' || idpersona.value === '') {
        GenerarIdPersona();
    }
    if (validateForm(formP)) {
        submitForm(formP, false).done(function () {
            if (getLastInsertId() !== null) {
                idpersona.value = 'P' + getLastInsertId();
                idestudiante.value = 'P' + getLastInsertId();
                BuscarEstudiante();
                alert('El Codigo de Persona Asignado es: P' + getLastInsertId() + '. Ahora debe completar las secciones Acudiente 1, Acudiente 2, Salud y Crecimiento.\n * Use las secciones de Anotaciones y Citaciones cuando se presente alguna novedad.');
            }
        });
    }
}

function resetEstudiante() {
    var formE = document.getElementById('form0');
    resetForm(formE);
    ClearTableAnotaciones();
    ClearTableCitaciones();
}

function resetAnoacion() {

}

function VerHistorialMatriculas() {
    var formE = document.getElementById('form0');
    var idestudiante = getElement(formE, 'id_estudiante');
    if (idestudiante !== null) {
        window.location.href = 'FormHomeEstudiantes.html?id_estudiante=' + idestudiante.value;
        setPOST('id_estudiante', idestudiante.value);
    }
}