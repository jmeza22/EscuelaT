/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    getParameters();
});

function getParameters() {
    var formUpload = document.getElementById('formUpload');
    var prefix = null;
    var id = null;
    var img = null;
    prefix = GET('prefix');
    id = GET('id');
    img = GET('img');
    var prefix_image = getElement(formUpload, 'prefix_image');
    var name_image = getElement(formUpload, 'name_image');
    var fullname_image = getElement(formUpload, 'fullname_image');
    var image = getElement(formUpload, 'image');
    var panelfields = document.getElementById('fields');
    if (prefix !== null && prefix !== '') {
        prefix_image.value = prefix;
    } else {
        prefix_image.value = '';
    }
    if (id !== null && id !== '') {
        name_image.value = id;
    } else {
        name_image.value = '';
    }
    if (img !== null && img !== '') {
        fullname_image.value = img;
        image.src = 'ImageFiles/' + img;
        panelfields.setAttribute('style', 'display:none;');
    }
}

function validateImage(item) {
    var validate = true;
    var formUpload = document.getElementById('formUpload');
    var imagefile = getElement(formUpload, 'image-file');
    if (validateForm(getForm(item)) === false) {
        validate = false;
    }
    if (imagefile.files.length <= 0) {
        validate = false;
    } else {
        console.log("Se encontró " + imagefile.files.length + " archivos para Subir al Sistema!.");
    }
    if (validate === false) {
        alert("Debe completar los Datos y Seleccionar la Imagen!.");
    }
    return validate;
}

function submitImage(item) {
    if (validateImage(item) === true) {
        var myform = getForm(item);
        if (myform !== undefined && myform !== null) {
            myform.submit();
        }
    }
}
