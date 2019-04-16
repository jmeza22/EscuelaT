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
    loadTableData(mytable, false);
}

function Send(item) {
    var form = getForm(item);
    var idpersona = document.getElementById('id_persona');
    if (idpersona.value === '0' || idpersona.value === '') {
        newID();
        showID();
    }
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            resetForm(form);
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
    var nombre1 = null;
    var apellido1 = null;
    var tipodoc = null;
    var fecha = null;
    var nuevo = null;
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
    if (nombre1 !== null && nombre1 !== "" && apellido1 !== null && apellido1 !== "" && tipodoc !== null && fecha !== null) {
        if (document.getElementById('id_persona') !== undefined && (document.getElementById('id_persona').value === "" || document.getElementById('id_persona').value === "0")) {
            nuevo = fecha.getFullYear().toString() + (fecha.getMonth() + 1) + (fecha.getDate() * getRandomNumber(0, 100000));
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
