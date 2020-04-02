/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    loadComboboxData(document.getElementById("id_area"));
    LoadTable();
});

function setImagenLectura() {
    var item = null;
    item = document.getElementById('imagen_lectura');
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Nombre de Imagen.');
        if (getEnterpriseID() !== null) {
            var fecha = new Date();
            item.value = 'Lectura' + getEnterpriseID() + fecha.getFullYear() + '' + (fecha.getMonth() + 1) + '' + fecha.getDate() + '' + fecha.getHours() + '' + fecha.getMinutes() + '' + fecha.getSeconds() + (getRandomNumber(1, 9) * getRandomNumber(1, 9)) + '.jpg';
            console.log('Value: ' + item.value);
        }
    }
}

function MostrarImagen() {
    setImagenLectura();
    var prefix = '';
    var id = ''
    var nameimage = null;
    nameimage = document.getElementById('imagen_lectura');
    window.open(getWSPath() + "UploadImageForm.html?prefix=" + prefix + "&id=" + id + "&img=" + nameimage.value + "", "Subir una Imagen al Servidor - EscuelaT", "width=600,height=600,scrollbars=NO");
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            LoadTable();
            if (getRowCount() !== '' && getRowCount() !== '0') {
                document.getElementById('reset').click();
            }
        });
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
