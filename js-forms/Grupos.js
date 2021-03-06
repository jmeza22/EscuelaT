/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    LoadTable();
    LoadSede();
    LoadPrograma();
});

function LoadSede() {
    var sede = null;
    sede = document.getElementById("id_sede");
    sede.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(sede);
}

function LoadPrograma() {
    var programa = null;
    programa = document.getElementById("id_programa");
    programa.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(programa);
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
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
        escuela.innerHTML = '';
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

function GenerarCodigo() {
    var form0 = null;
    var idgrupo = null;
    var idescuela = null;
    var idprograma = null;
    var grado = null;
    var numero = null;

    form0 = document.getElementById('form0');
    if (form0 !== null && form0 !== undefined) {
        idgrupo = getElement(form0, 'id_grupo');
        idescuela = getElement(form0, 'id_escuela');
        idprograma = getElement(form0, 'id_programa');
        grado = getElement(form0, 'numgrado_programa');
        numero = getElement(form0, 'num_grupo');
        idgrupo.value=grado.value+''+numero.value+''+idprograma.value;
    }
}

function GrabarGrupo(item){
    Send(item);
    LoadTable();
}