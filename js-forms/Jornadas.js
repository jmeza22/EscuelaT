/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    //LoadEscuela();
    setIdEscuela();
    RefreshSede(document.getElementById("id_escuela"));
    RefreshTable();
});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false).done(function () {
    });
    return mytable;
}

function LoadEscuela() {
    var escuela = null;
    escuela = document.getElementById("id_escuela");
    escuela.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(escuela);
}

function LoadSede() {
    var sede = null;
    sede = document.getElementById("id_sede");
    sede.innerHTML = '<option disabled="disabled" value="">Ninguna</option>';
    loadComboboxData(sede);
}

function setFinbySede(findby) {
    if (findby !== null && findby !== '') {
        var sede = document.getElementById("id_sede");
        sede.setAttribute('findby', findby);
    }
}

function setFinbyValueSede(findbyvalue) {
    if (findbyvalue !== null && findbyvalue !== '') {
        var sede = document.getElementById("id_sede");
        sede.setAttribute('findbyvalue', findbyvalue);
    }
}

function RefreshSede(item) {
    if (item !== undefined && item !== null) {
        console.log('Filtrando por ' + item.id);
        setFinbySede('id_escuela');
        if (item.selected !== undefined && item.selected !== null) {
            setFinbyValueSede(item.selected);
        } else {
            setFinbyValueSede(item.value);
        }
        LoadSede();
    }
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

function setIdEscuela() {
    var escuela = null;
    var newoption = null;
    escuela = document.getElementById('id_escuela');
    if (escuela !== null && escuela !== undefined && escuela.tagName === 'SELECT') {
        escuela.innerHTML = '';
        escuela.removeAttribute('selected');
        newoption = document.createElement('option');
        newoption.setAttribute('id', 'id_escuela');
        newoption.setAttribute('value', getIdEnterprise());
        newoption.setAttribute('selected', 'selected');
        newoption.innerHTML = '' + getNameEnterprise();
        if (getNameEnterprise() === null) {
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
        table0.setAttribute('findbyvalue', idescuela.value);
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
        idjornada.value = '' + nombre.value + '_E' + idescuela.value + 'S' + idsede.value + '';
    }
}

