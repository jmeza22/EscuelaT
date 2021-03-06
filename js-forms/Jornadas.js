/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    RefreshTable();
    LoadSede();
});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false).done(function () {
    });
    return mytable;
}

function LoadSede() {
    var sede = null;
    sede = document.getElementById("id_sede");
    sede.innerHTML = '<option disabled="disabled" value="">Ninguna</option>';
    loadComboboxData(sede);
}

function GrabarJornada() {
    var form0 = null;
    form0 = document.getElementById('form0');
    if (validateForm(form0)) {
        submitForm(form0, false).done(function () {
            LoadTable();
        });
    }
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false);
    }
}

function Edit(item) {
    var myform = null;
    myform = document.getElementById('form0');
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

function setIdEscuela() {
    var escuela = null;
    var newoption = null;
    escuela = document.getElementById('id_escuela');
    if (escuela !== null && escuela !== undefined && escuela.tagName === 'SELECT') {
        escuela.innerHTML = "<option value='NULL'>Ninguna</option>";
        escuela.removeAttribute('selected');
        newoption = document.createElement('option');
        newoption.setAttribute('value', getEnterpriseID());
        newoption.setAttribute('selected', 'selected');
        newoption.innerHTML = '' + getEnterpriseName();
        if (getEnterpriseName() === null) {
            newoption.innerHTML = 'Escuela Actual';
        }
        escuela.appendChild(newoption);
    }
}

function RefreshTable() {
    var table0 = null;
    var idescuela = null;
    table0 = document.getElementById("dataTable0");
    if (table0 !== null && table0 !== undefined) {
        idescuela = document.getElementById("id_escuela");
        table0.setAttribute('findby', 'id_escuela');
        table0.setAttribute('findbyvalue', getEnterpriseID());
        LoadTable();
    }
}

function setIdJornada() {
    var form0 = null;
    var idjornada = null;
    var idescuela = null;
    var idsede = null;
    var nombre = null;
    form0 = document.getElementById('form0');
    idjornada = getElement(form0, 'id_jornada');
    idescuela = getElement(form0, 'id_escuela');
    idsede = getElement(form0, 'id_sede');
    nombre = getElement(form0, 'nombre_jornada');
    if (idjornada !== null && idjornada !== undefined) {
        idjornada.value = '' + nombre.value.replace("ñ", "n").replace('Ñ','N') + '_E' + getEnterpriseID() + 'S' + idsede.value + '';
    }
}

