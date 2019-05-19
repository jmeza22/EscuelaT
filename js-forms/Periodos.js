/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    LoadTable();
    setAnoPeriodo();
    setIdEscuela();
});

function setIdEscuela() {
    var item = null;
    item = document.getElementById('id_escuela');
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Escuela.');
        if (getEnterpriseID() !== null) {
            item.value = getEnterpriseID();
        }
    }
}

function setAnoPeriodo() {
    var form = null;
    var anoperiodo = null;
    var fecha = new Date();
    form = document.getElementById('form0');
    if (form !== undefined && form !== null) {
        anoperiodo = getElement(form, 'anualidad_periodo');
        if (anoperiodo !== null) {
            anoperiodo.value = fecha.getFullYear();
        }
    }
}

function setIdPeriodo() {
    var form = null;
    var idperiodo = null;
    var anoperiodo = null;
    form = document.getElementById('form0');
    if (form !== undefined && form !== null) {
        idperiodo = getElement(form, 'id_periodo');
        anoperiodo = getElement(form, 'anualidad_periodo');
        if (idperiodo !== null && anoperiodo !== null) {
            if (idperiodo.value === '') {
                idperiodo.value = anoperiodo.value;
            }
            if (idperiodo.value !== '' && idperiodo.value.toString().lastIndexOf(anoperiodo.value)===-1) {
                idperiodo.value = anoperiodo.value;
            }
        }
    }
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            LoadTable();
        });
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
