/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    setIdEscuela();
    var idlogro = document.getElementById("id_logro");
    if (idlogro !== undefined && idlogro !== null && idlogro.value !== '' && idlogro.value !== 0) {
        getFormData(idlogro);
    }
    ObtenerConfiguracion();
    LoadTable();
    loadComboboxData(document.getElementById("id_asignatura"));
});

function setIdEscuela() {
    var item = null;
    item = document.getElementById("id_escuela");
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Escuela.');
        if (getEnterpriseID() !== null) {
            item.value = getEnterpriseID();
        }
    }
}

function setIdLogro() {
    var item = null;
    var escuela = null;
    var fecha = new Date();
    item = document.getElementById("id_logro");
    escuela = document.getElementById("id_escuela");
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Logro.');
        item.value = "L" + escuela.value + fecha.getSeconds() + getRandomNumber(0, 9);
        showNotification('Codigo', 'El codigo del Logro es: ' + item.value);
    }
}

function setFinbyTable(findby) {
    if (findby !== null && findby !== '') {
        var mytable = document.getElementById("dataTable0");
        mytable.setAttribute('findby', findby);
        console.log('Filtrando por Campo: ' + findby);
    }
}

function setFinbyValueTable(findbyvalue) {
    if (findbyvalue !== null && findbyvalue !== '') {
        var mytable = document.getElementById("dataTable0");
        mytable.setAttribute('findbyvalue', findbyvalue);
        console.log('Filtrando por Valor: ' + findbyvalue);
    }
}

function ObtenerConfiguracion() {
    var idescuela = null;
    var formconf = null;
    formconf = document.getElementById("formConfig");
    if (formconf !== undefined && formconf !== null) {
        idescuela = getElement(formconf, 'id_escuela');
        idescuela.value = getEnterpriseID();
        if (idescuela.value !== null && idescuela.value !== '') {
            getFormData(formconf).done(function () {
            });
        }else{
            showNotification('Error','Error al obtener la Configuracion de la Escuela.');
        }
    }
}

function setMinMaxLogro(item) {
    var valmin = document.getElementById('min_logro');
    var valmax = document.getElementById('max_logro');
    var vminsup = document.getElementById('valsupmin_configuracion');
    var vmaxsup = document.getElementById('valsupmax_configuracion');
    var vminalt = document.getElementById('valaltomin_configuracion');
    var vmaxalt = document.getElementById('valaltomax_configuracion');
    var vminbas = document.getElementById('valbasmin_configuracion');
    var vmaxbas = document.getElementById('valbasmax_configuracion');
    var vminbaj = document.getElementById('valbajomin_configuracion');
    var vmaxbaj = document.getElementById('valbajomax_configuracion');
    if (valmin !== undefined && valmin !== null && valmax !== undefined && valmax !== null && item !== undefined && item !== null) {
        if (item.value === 'R') {
            valmin.value = vminbaj.value;
            valmax.value = vmaxsup.value;
        }
        if (item.value === 'F') {
            valmin.value = vminbas.value;
            valmax.value = vmaxsup.value;
        }
        if (item.value === 'D') {
            valmin.value = vminbaj.value;
            valmax.value = vmaxbaj.value;
        }
        if (item.value === 'DI') {
            valmin.value = vminbaj.value;
            valmax.value = vmaxbaj.value;
        }
        if (item.value === 'DB') {
            valmin.value = vminbas.value;
            valmax.value = vmaxbas.value;
        }
        if (item.value === 'DA') {
            valmin.value = vminalt.value;
            valmax.value = vmaxalt.value;
        }
        if (item.value === 'DS') {
            valmin.value = vminsup.value;
            valmax.value = vmaxsup.value;
        }
    }
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, true);
    return mytable;
}

function RefreshTable(item) {
    if (item !== undefined && item !== null) {
        setFinbyTable('id_asignatura');
        if (item.value !== undefined && item.value !== null) {
            setFinbyValueTable(item.value);
        } else {
            setFinbyValueTable(item.selected);
        }
        LoadTable();
    }
}

function Send(item) {
    var myform = null;
    myform = getForm(item);
    if (validateForm(myform)) {
        submitForm(myform, false).done(function () {
            setTimeout(function () {
                LoadTable();
                resetForm(myform);
            }, 100);
        });
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
