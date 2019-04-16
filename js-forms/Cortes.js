/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    setIdEscuela();
    loadComboboxData(document.getElementById("select_id_periodo"));
    LoadTable();
});

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
    var item = null;
    item = document.getElementById('select_id_escuela');
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Escuela.');
        if (getIdEnterprise() !== null) {
            item.value = getIdEnterprise();
        }
        item.focus();
    }
}

function RefreshFormAndTable(item) {
    var form0 = null;
    var table0 = null;
    if (item !== null && item !== undefined) {
        form0 = document.getElementById('form0');
        sendValue(item, 'select_id_periodo', form0, 'id_periodo');
        sendValue(item, 'select_id_escuela', form0, 'id_escuela');
        table0 = document.getElementById("dataTable0");
        table0.setAttribute('findby', 'id_periodo');
        table0.setAttribute('findbyvalue', item.value);
        LoadTable();
    }
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false).done(function () {
    });
    return mytable;
}

function setIdCorte() {
    var form0 = null;
    var idcorte = null;
    var idescuela = null;
    var idperiodo = null;
    var numero = null;
    form0 = document.getElementById('form0');
    idcorte = getElement(form0, 'id_corte');
    idescuela = getElement(form0, 'id_escuela');
    idperiodo = getElement(form0, 'id_periodo');
    numero = getElement(form0, 'numero_corte');
    if (idcorte !== null && idcorte !== undefined) {
        idcorte.value = idescuela.value + '' + idperiodo.value + '' + numero.value;
    }
}

function GrabarCorte() {
    var form0 = null;
    form0 = document.getElementById('form0');
    if (validateForm(form0)) {
        submitForm(form0, false).done(function () {
            LoadTable();
        });
    }
}