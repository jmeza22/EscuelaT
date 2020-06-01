/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    var cuestionario = document.getElementById('formCuestionario');
    var intento = document.getElementById('formIntento');
    var idcuestionario = getElement(cuestionario, 'id_cuestionario');
    idcuestionario.value = GET('id_cuestionario');
    var idcuestionarioI = getElement(intento, 'id_cuestionario');
    idcuestionarioI.value = GET('id_cuestionario');
    getFormData(cuestionario, null);
    LoadTable();
});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    clearTableData(mytable);
    loadTableData(mytable, false).done(function () {
    });
    return mytable;
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false).done(function () {
            LoadTable();
        });
    }
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