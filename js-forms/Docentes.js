/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    loadComboboxData(document.getElementById("lista_id_persona")).done(function () {
        autoNameFromDataList('id_docente', 'nombrecompleto_docente', null);
    });
    Buscar();
    LoadTable();
});

function GrabarDocente() {
    var form = document.getElementById("form0");
    var iddocente = getElement(form, 'id_docente');
    var fileI = document.getElementById("image-file-photo");
    var foto = document.getElementById("foto_docente");
    var fileF = document.getElementById("image-file-sign");
    var firma = document.getElementById("firma_docente");
    if (validateForm(form)) {
        if (fileI.files.length > 0) {
            foto.value = "Docente" + iddocente.value + ".jpg";
        }
        if (fileF.files.length > 0) {
            firma.value = "FirmaDocente" + iddocente.value + ".jpg";
        }
        submitForm(form, false).done(function () {
            if (getLastInsertId() !== null) {
                alert('El Codigo del Docente es: ' + getLastInsertId());
            }
            LoadTable();
        });
    }
}
function CargarFrameFoto() {
    var foto = document.getElementById("foto_docente");
    var image = document.getElementById("image");
    image.src = "";
    if (foto !== undefined && foto.value !== '') {
        console.log('Cargando Foto.');
        image.src = getWSPath() + 'ImageFiles/' + foto.value;
        console.log('Foto Cargada: ' + image.src);
    }
}

function Buscar() {
    var iddocente = document.getElementById("id_docente");
    var result = null;
    if (iddocente !== undefined && iddocente !== null && iddocente.value !== '') {
        result = getFormData(iddocente).done(function () {
            CargarFrameFoto();
        });
    }
    return result;
}

function Edit(item) {
    var myform = null;
    myform = document.getElementById('form0');
    resetForm(myform);
    sendValue(item, null, myform, null);
    Buscar();
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
    return mytable;
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
