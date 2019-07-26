/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    loadComboboxData(document.getElementById("select_id_planestudio"));
    loadComboboxData(document.getElementById("id_programa"));
    loadComboboxData(document.getElementById("id_asignatura"));
    setIdEscuela();
    LoadTable();
});

function setIdEscuela() {
    var item = null;
    item = document.getElementById('id_escuela');
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Escuela.');
        if (getEnterpriseID() !== null) {
            item.value = getEnterpriseID();
        }
        item.focus();
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
    myform = document.getElementById('form1');
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

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    clearTableData(mytable);
    loadTableData(mytable, false).done(function () {
    });
    return mytable;
}

function setIdEscuelaForm1() {
    var form0 = null;
    var form1 = null;
    var idorigen = null;
    var iddestino = null;
    form0 = document.getElementById('form0');
    form1 = document.getElementById('form1');
    idorigen = getElement(form0, 'id_escuela');
    iddestino = getElement(form1, idorigen.id);
    if (idorigen !== null && idorigen !== undefined && iddestino !== null && iddestino !== undefined ) {
        iddestino.value = idorigen.value;
        console.log('Copiado '+iddestino.id+'='+iddestino.value);
    }
}

function setIdPlanEstudioForm1() {
    var form0 = null;
    var form1 = null;
    var idorigen = null;
    var iddestino = null;
    form0 = document.getElementById('form0');
    form1 = document.getElementById('form1');
    idorigen = getElement(form0, 'id_planestudio');
    iddestino = getElement(form1, idorigen.id);
    if (idorigen !== null && idorigen !== undefined && iddestino !== null && iddestino !== undefined ) {
        iddestino.value = idorigen.value;
        console.log('Copiado '+iddestino.id+'='+iddestino.value);
    }
}

function setIdProgramaForm1() {
    var form0 = null;
    var form1 = null;
    var idorigen = null;
    var iddestino = null;
    form0 = document.getElementById('form0');
    form1 = document.getElementById('form1');
    idorigen = getElement(form0, 'id_programa');
    iddestino = getElement(form1, idorigen.id);
    if (idorigen !== null && idorigen !== undefined && iddestino !== null && iddestino !== undefined ) {
        iddestino.value = idorigen.value;
        console.log('Copiado '+iddestino.id+'='+iddestino.value);
    }
}

function setIdDetalleForm1() {
    var form1 = null;
    var iddetalle = null;
    form1 = document.getElementById('form1');
    iddetalle = getElement(form1, 'id_planestudiodetalle');
    if (iddetalle !== null && iddetalle !== undefined && iddetalle.value === '') {
        iddetalle.value = 'DPE' + new Date().getTime();
    }
}

function setIds() {
    setIdEscuelaForm1();
    setIdProgramaForm1();
    setIdPlanEstudioForm1();
    setIdDetalleForm1();
}

function GrabarDetalle(item) {
    setIds();
    var form = getForm(item);
    if (validateForm(form)) {
        console.log('Tratando de Grabar Detalle.');
        submitForm(item, false).done(function () {
            LoadTable();
            form.reset();
        });
    }
}

function RefreshFormAndTable(item) {
    var form0 = null;
    var table0 = null;
    if (item !== null && item !== undefined) {
        form0 = document.getElementById('form0');
        sendValue(item, 'select_id_planestudio', form0, 'id_planestudio');
        getData(form0).done(function () {
            setIdEscuelaForm1();
            setIdProgramaForm1();
            setIdPlanEstudioForm1();
        });
        table0 = document.getElementById("dataTable0");
        table0.setAttribute('findby', 'id_planestudio');
        table0.setAttribute('findbyvalue', item.value);
        LoadTable();
    }
}



