/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {

    showID();
});

function setFindbyTable() {
    var myform = document.getElementById('form0');
    var mytable = document.getElementById("dataTable0");
    var idpersona = getElement(myform, 'id_persona');
    var nombre1 = getElement(myform, 'nombre1_persona');
    var apellido1 = getElement(myform, 'apellido1_persona');
    var sexo = getElement(myform, 'sexo_persona');
    var tipo = getElement(myform, 'tipo_persona');
    if (idpersona !== null && idpersona.value !== '') {
        mytable.setAttribute('findby1', idpersona.id);
        mytable.setAttribute('findbyvalue1', idpersona.value);
    }else{
        mytable.removeAttribute('findby1');
        mytable.removeAttribute('findbyvalue1');
    }
    if (nombre1 !== null && nombre1.value !== '') {
        mytable.setAttribute('findby2', nombre1.id);
        mytable.setAttribute('findbyvalue2', nombre1.value);
    }else{
        mytable.removeAttribute('findby2');
        mytable.removeAttribute('findbyvalue2');
    }
    if (apellido1 !== null && apellido1.value !== '') {
        mytable.setAttribute('findby3', apellido1.id);
        mytable.setAttribute('findbyvalue3', apellido1.value);
    }else{
        mytable.removeAttribute('findby3');
        mytable.removeAttribute('findbyvalue3');
    }
    if (sexo !== null && sexo.value !== '' && sexo.selected !== '') {
        mytable.setAttribute('findby4', sexo.id);
        mytable.setAttribute('findbyvalue4', sexo.value);
    }else{
        mytable.removeAttribute('findby4');
        mytable.removeAttribute('findbyvalue4');
    }
    if (tipo !== null && tipo.value !== '' && tipo.selected !== '') {
        mytable.setAttribute('findby5', tipo.id);
        mytable.setAttribute('findbyvalue5', tipo.value);
    }else{
        mytable.removeAttribute('findby5');
        mytable.removeAttribute('findbyvalue5');
    }
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    clearTableData(mytable);
    setFindbyTable();
    loadTableData(mytable, true);
}

function GrabarPersona(item) {
    var form = getForm(item);
    var idpersona = document.getElementById('id_persona');
    if (idpersona.value === '0' || idpersona.value === '') {
        newID();
        showID();
    }
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            var tipousuario = document.getElementById("id_tipousuario");
            if (getLastInsertId() !== null) {
                alert('El Codigo de Persona Asignado es: P' + getLastInsertId());
            }
            if (tipousuario.value === 'Student') {
                if (confirm('Desea Completar los Datos del Observador del Estudiante?')) {
                    window.location.href = 'FormEstudiantes.html?' + 'id_persona=P' + getLastInsertId();
                }
            }
            if (tipousuario.value === 'Teacher') {
                if (confirm('Desea Completar los Datos del Docente?')) {
                    window.location.href = 'FormDocentes.html?' + 'id_docente=P' + getLastInsertId();
                }
            }
            resetForm(form);
            LoadTable();
        });
    }
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            LoadTable();
        });
    }
}

function showID() {
    var idpersona = null;
    var showid = null;
    idpersona = document.getElementById("id_persona");
    showid = document.getElementById("show_id_persona");

    if (idpersona !== undefined && showid !== undefined) {
        showid.value = idpersona.value;
    }
}


function newID() {
    var idpersona = null;
    var nombre1 = null;
    var apellido1 = null;
    var tipodoc = null;
    var fecha = null;
    var nuevo = null;
    if (document.getElementById('id_persona') !== null && document.getElementById('id_persona') !== undefined) {
        idpersona = document.getElementById('id_persona');
    }
    if (document.getElementById('nombre1_persona') !== null && document.getElementById('nombre1_persona') !== undefined) {
        nombre1 = document.getElementById('nombre1_persona').value;
    }
    if (document.getElementById('apellido1_persona') !== null && document.getElementById('apellido1_persona') !== undefined) {
        apellido1 = document.getElementById('apellido1_persona').value;
    }
    if (document.getElementById('tipodoc_persona') !== null && document.getElementById('tipodoc_persona') !== undefined) {
        tipodoc = document.getElementById('tipodoc_persona');
        tipodoc = tipodoc.value;
    }
    fecha = new Date();
    if (nombre1 !== null && nombre1 !== "" && apellido1 !== null && apellido1 !== "") {
        if (document.getElementById('id_persona') !== undefined && (idpersona.value === "" || idpersona.value === "0")) {
            nuevo = fecha.getTime() + getRandomNumber(0, 99);
            console.log('Generando Id: ' + nuevo);
            document.getElementById('id_persona').value = nuevo;
            document.getElementById('show_id_persona').value = nuevo;
        }
    } else {
        alert("Debe ingresar Nombre y Apellido.");
        return false;
    }
    return true;
}

function Edit(item) {
    var myform = null;
    myform = document.getElementById('form0');
    resetForm(myform);
    sendValue(item, null, myform, null);
    showID();
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
