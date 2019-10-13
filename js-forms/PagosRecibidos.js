/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    LoadTable();
    var form0 = document.getElementById("form0");
    var idpec = null;
    var tipodoc=null;
    var valor = null;
    idpec = getElement(form0, 'id_pecuniario');
    tipodoc = getElement(form0, 'tipodoc_pago');
    valor = getElement(form0, 'valor_pago');
    loadComboboxData(idpec);
    loadComboboxData(document.getElementById('lista_nombre_acudiente'));
    autoNameFromDataList(idpec, tipodoc.id, valor.id);
    autoLoadNameFromId('id_estudiante', 'nombrecompleto_estudiante', null, null);
    
});

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
    var itemform = null;
    myform = document.getElementById('form0');
    itemform=getForm(item);
    resetForm(myform);
    sendValue(itemform, null, myform, null);
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
                    deleteRowInTable(mytable);
                } else {
                    status.value = '1';
                }
            });
        }
    }
}
