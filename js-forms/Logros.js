/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    var idlogro = document.getElementById("id_logro");
    if (idlogro !== undefined && idlogro !== null && idlogro.value !== '') {
        getData(idlogro);
    }
    LoadTable();
    loadComboboxData(document.getElementById("id_asignatura"));
});

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

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
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

function setIdEscuela(item) {
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Escuela.');
        if (getEnterpriseID() !== null) {
            item.value = getEnterpriseID();
        }
    }
}