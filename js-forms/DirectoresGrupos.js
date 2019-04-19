/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    //LoadEscuela();
    setIdEscuela();
    LoadGrupo();
    LoadPeriodo();
    LoadDocente();
    RefreshTable();
});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false).done(function () {
    });
    return mytable;
}

function LoadGrupo() {
    var grupo = null;
    grupo = document.getElementById("id_grupo");
    grupo.innerHTML = '<option disabled="disabled" value="">Ninguna</option>';
    loadComboboxData(grupo);
}

function LoadPeriodo() {
    var periodo = null;
    periodo = document.getElementById("id_periodo");
    periodo.innerHTML = '<option disabled="disabled" value="">Ninguna</option>';
    loadComboboxData(periodo);
}

function LoadDocente() {
    var docente = null;
    docente = document.getElementById("id_docente");
    docente.innerHTML = '<option disabled="disabled" value="">Ninguna</option>';
    loadComboboxData(docente);
}

function GrabarDirectorGrupo() {
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
        table0.setAttribute('findbyvalue', idescuela.value);
        LoadTable();
    }
}

function setIdDirector() {
    var form0 = null;
    var iddirector = null;
    var idescuela = null;
    var idgrupo = null;
    var idperiodo = null;
    var iddocente = null;
    form0 = document.getElementById('form0');
    iddirector = getElement(form0, 'id_director');
    idescuela = getElement(form0, 'id_escuela');
    idgrupo = getElement(form0, 'id_grupo');
    idperiodo = getElement(form0, 'id_periodo');
    iddocente = getElement(form0, 'id_docente');
    if (iddirector !== null && iddirector !== undefined && iddirector.value === '') {
        iddirector.value = '' + idescuela.value + '' + idgrupo.value + '' + iddocente.value + '';
        iddirector.value = 0;
    }

}