/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
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

function RefreshFormAndTable(item) {
    var form0 = null;
    var theform = null;
    var table0 = null;
    if (item !== null && item !== undefined) {
        theform = getForm(item);
        form0 = document.getElementById('form0');
        sendValue(theform, 'select_id_periodo', form0, 'id_periodo');
        table0 = document.getElementById("dataTable0");
        table0.setAttribute('findby', 'id_periodo');
        table0.setAttribute('findbyvalue', item.value);
        LoadTable();
    }
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    clearTableData(mytable);
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
        idcorte.value = getEnterpriseID() + '' + idperiodo.value + '' + numero.value;
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