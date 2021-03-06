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
    var formE = document.getElementById("formE");
    var formR = document.getElementById("formRep");
    var idpersona = getElement(formP, 'id_persona');
    var idestudiante = getElement(formE, 'id_estudiante');
    var idestudianteR = getElement(formR, 'id_estudiante');
    idestudiante.value = idpersona.value;
    idestudianteR.value = idpersona.value;
}

function CargarNombres() {
    var formE = document.getElementById("formE");
    var idestudiante = null;
    if (formE !== undefined && formE !== null) {
        idestudiante = getElement(formE, 'id_estudiante');
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
    if (getUserRoleLogin() !== null && getUserRoleLogin() !== 'Student') {
        CopiarCodigo();
        var formP = document.getElementById("formP");
        var formE = document.getElementById("formE");
        var idpersona = null;
        var idestudiante = null;
        var idaux = null;
        if (formE !== undefined && formE !== null) {
            idpersona = getElement(formP, 'id_persona');
            idestudiante = getElement(formE, 'id_estudiante');
            if (idpersona !== undefined && idpersona.value !== '') {
                idaux = idpersona.value;
                resetForm(formE);
                idpersona.value = idaux;
                idestudiante.value = idaux;
                document.getElementById('id_tipousuario').value = 'Student';
                getFormData(formP);
                getFormData(formE).done(function () {
                    CopiarCodigoEstudianteAnotacion();
                    CopiarCodigoEstudianteCitacion();
                    DocumentoIdentidad();
                    CargarFrameFoto();
                    LoadTableAnotaciones();
                    LoadTableCitaciones();
                });
            }
        }
    }
}

function BuscarEstudianteActivo() {
    var formE = document.getElementById("formE");
    var idestudiante = null;
    console.log('Tipo de Usuario: ' + getUserRoleLogin());
    if (getUserRoleLogin() !== null && getUserRoleLogin() === 'Student') {
        formE.setAttribute('url', 'Base/Controllers/FindEstudiantesController.php');
        idestudiante = getElement(formE, 'id_estudiante');
        if (idestudiante !== undefined && idestudiante !== null) {
            idestudiante.value = getUserIdLogin();
            idestudiante.setAttribute('readonly', 'readonly');
            if (idestudiante.value !== '') {
                getFormData(formE).done(function () {
                    CargarFrameFoto();
                    DocumentoIdentidad();
                    document.getElementById("panelPersona").setAttribute('style', 'display: none;');
                    document.getElementById("panelCitaciones").setAttribute('style', 'display: none;');
                    document.getElementById("panelAnotaciones").setAttribute('style', 'display: none;');
                    document.getElementById("save").setAttribute('style', 'display: none;');
                    document.getElementById("reset").setAttribute('style', 'display: none;');
                    document.getElementById("generate").setAttribute('style', 'display: none;');
                    document.getElementById("btAnotacion").setAttribute('disabled', 'disabled');
                    document.getElementById("btCitacion").setAttribute('disabled', 'disabled');
                });
            }
        }
    }
}

function DocumentoIdentidad() {
    var file = document.getElementById("document-file");
    var documento = document.getElementById("imgdocumento_estudiante");
    var link = document.getElementById("linkDocumento");
    var btn = document.getElementById("VerDocumento");
    if (documento.value !== '') {
        console.log('Tiene documento');
        link.href = getWSPath() + "IDFiles/" + documento.value;
        btn.setAttribute('title', 'Ha cargado una copia del Documento de Identidad. Haga click para verlo.');
        btn.setAttribute('class', 'btn btn-block btn-success');
        btn.innerHTML = '<i class="glyphicon glyphicon-download"></i> Descargar';
        file.setAttribute('disabled', 'disabled');
        link.setAttribute('disabled', 'disabled');
    } else {
        console.log('No tiene documento');
        btn.setAttribute('title', 'No ha cargado copia del Documento de Identidad. Haga click para seleccionarlo.');
        btn.setAttribute('class', 'btn btn-block btn-default');
        btn.innerHTML = '<i class="glyphicon glyphicon-upload"></i> Cargar';
        file.removeAttribute('disabled');
        link.removeAttribute('disabled');
    }
}

function viewFile() {
    var link = document.getElementById("linkDocumento");
    var documento = document.getElementById("imgdocumento_estudiante");
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
    var documento = document.getElementById("imgdocumento_estudiante");
    var file = document.getElementById("document-file");
    if (documento !== null && documento.value !== undefined && documento.value !== '') {
        console.log('Eliminar Archivo');
        file.files = null;
        documento.value = "";
        GrabarEstudiante();
    }
}

function showSelectedFile() {
    var file = document.getElementById("document-file");
    var btn = document.getElementById("VerDocumento");
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

function CargarFrameFoto() {
    var foto = document.getElementById("foto_estudiante");
    var image = document.getElementById("image");
    image.src = "";
    if (foto !== undefined && foto.value !== '') {
        console.log('Cargando Foto.');
        image.src = getWSPath() + 'ImageFiles/' + foto.value;
    }
}

function EditPersona(item) {
    var myform = null;
    myform = document.getElementById('formP');
    resetForm(myform);
    sendValue(item, null, myform, null);
    BuscarEstudiante();
    CargarFrameFoto();
}

function EditAnotacion(item) {
    var myform = null;
    myform = document.getElementById('formAN');
    resetForm(myform);
    sendValue(item, null, myform, null);
    getFormData(myform).done(function () {
        setTimeout(function () {
        }, 1);
    });
}

function EditCitacion(item) {
    var myform = null;
    myform = document.getElementById('formCI');
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
            deleteTableRow(mytable);
        }
    }
}

function LoadTableAnotaciones() {
    var mytable = document.getElementById("dataTableAN");
    var idestudiante = getElement(getForm('formE'), 'id_estudiante');
    clearTableData(mytable);
    mytable.setAttribute('findby', 'id_estudiante');
    mytable.setAttribute('findbyvalue', idestudiante.value);
    loadTableData(mytable, false);
    return mytable;
}

function LoadTableCitaciones() {
    var mytable = document.getElementById("dataTableCI");
    var idestudiante = getElement(getForm('formE'), 'id_estudiante');
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
    var formE = null;
    var formAN = null;
    var idestudiante = null;
    var idestudianteanotacion = null;
    var mytable = document.getElementById("dataTableAN");
    formE = document.getElementById('formE');
    formAN = document.getElementById('formAN');
    idestudiante = getElement(formE, "id_estudiante");
    idestudianteanotacion = getElement(formAN, "id_estudiante");
    if (idestudianteanotacion !== undefined && idestudianteanotacion !== null) {
        console.log('Copiando Datos de Estudiante a Panel de Anotacion.');
        idestudianteanotacion.value = idestudiante.value;
    }
    mytable.setAttribute('findby', 'id_estudiante');
    mytable.setAttribute('findbyvalue', idestudiante.value);
}

function CopiarCodigoEstudianteCitacion() {
    var formE = null;
    var formCI = null;
    var idestudiante = null;
    var idestudiantecitacion = null;
    var mytable = document.getElementById("dataTableCI");
    formE = document.getElementById('formE');
    formCI = document.getElementById('formCI');
    idestudiante = getElement(formE, "id_estudiante");
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


function GrabarAnotacion() {
    var form = document.getElementById("formAN");
    GenerarFechaAnotacion();
    GenerarCodigoAnotacion();
    if (validateForm(getForm(form))) {
        submitForm(form, false).done(function () {
            LoadTableAnotaciones();
        });
    } else {
        showNotification('Verifique los datos de la Anotacion!');
    }
}

function GrabarCitacion() {
    var form = document.getElementById("formCI");
    GenerarFechaCitacion();
    GenerarCodigoCitacion();
    if (validateForm(getForm(form))) {
        submitForm(form, false).done(function () {
            LoadTableCitaciones();
        });
    } else {
        showNotification('Verifique los datos de la Citacion!');
    }
}

function GrabarEstudiante() {
    var form = document.getElementById("formE");
    var idestudiante = getElement(form, 'id_estudiante');
    var fileI = document.getElementById("image-file");
    var fileD = document.getElementById("document-file");
    var foto = document.getElementById("foto_estudiante");
    var documento = document.getElementById("imgdocumento_estudiante");
    var auxF = foto.value;
    var auxD = documento.value;
    if (validateForm(form)) {
        if (fileI.files.length > 0) {
            foto.value = "Estudiante" + idestudiante.value + ".jpg";
        }
        if (fileD.files.length > 0) {
            documento.value = "DocId" + idestudiante.value + ".pdf";
        }
        submitForm(form, false).done(function () {
            if (parseInt(getRowCount()) > 0) {
                if (getLastInsertId() !== null) {
                    alert('El Codigo del Estudiante es: ' + getLastInsertId());
                }
                BuscarEstudiante();
            } else {
                foto.value = auxF;
                documento.value = auxD;
            }
        });
    }
}

function GrabarPersona() {
    var formP = document.getElementById('formP');
    var formE = document.getElementById('formE');
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
    var formE = document.getElementById('formE');
    resetForm(formE);
    ClearTableAnotaciones();
    ClearTableCitaciones();
}

function resetAnoacion() {

}

function VerHistorialMatriculas() {
    var formE = document.getElementById('formE');
    var idestudiante = getElement(formE, 'id_estudiante');
    if (idestudiante !== null) {
        window.location.href = 'FormHomeEstudiantes.html?id_estudiante=' + idestudiante.value;
        setPOST('id_estudiante', idestudiante.value);
    }
}