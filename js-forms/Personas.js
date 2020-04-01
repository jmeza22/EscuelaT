/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    LoadTable();
    showID();
});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, true);
}

function GrabarPersona(item) {
    var form = getForm(item);
    var idpersona = document.getElementById('id_persona');
    if (idpersona.value === '0' || idpersona.value === '') {
        newID();
        showID();
    }
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            var tipousuario = document.getElementById("id_tipousuario");
            if (getLastInsertId() !== null) {
                alert('El Codigo de Persona Asignado es: P' + getLastInsertId());
            }
            if (tipousuario.value === 'Student') {
                if (confirm('Desea Completar los Datos del Observador del Estudiante?')) {
                    window.location.href = 'FormEstudiantes.html?' + 'id_persona=P' + getLastInsertId();
                }
            }
            if (tipousuario.value === 'Teacher') {
                if (confirm('Desea Completar los Datos del Docente?')) {
                    window.location.href = 'FormDocentes.html?' + 'id_docente=P' + getLastInsertId();
                }
            }
            resetForm(form);
            LoadTable();
        });
    }
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            LoadTable();
        });
    }
}

function showID() {
    var idpersona = null;
    var showid = null;
    idpersona = document.getElementById("id_persona");
    showid = document.getElementById("show_id_persona");

    if (idpersona !== undefined && showid !== undefined) {
        showid.value = idpersona.value;
    }
}


function newID() {
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
            document.getElementById('show_id_persona').value = nuevo;
        }
    } else {
        alert("Debe ingresar Nombre y Apellido.");
        return false;
    }
    return true;
}

function Edit(item) {
    var myform = null;
    myform = document.getElementById('form0');
    resetForm(myform);
    sendValue(item, null, myform, null);
    showID();
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
