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
    var prefixImg = null;
    var idImg = null;
    var urlImg = null;
    var fullname = null;
    prefixImg = getElement(formUpload, 'prefix_image');
    idImg = getElement(formUpload, 'name_image');
    urlImg = getElement(formUpload, 'imagePhoto');
    fullname = getElement(formUpload, 'fullname_image');
    console.log('Image: '+prefix+id);
    if (prefix !== null && prefix !== '') {
        prefixImg.value = prefix;
    }else{
        prefixImg.value = 'NULL';
    }
    if (id !== null && id !== '') {
        idImg.value = id;
    }else{
        idImg.value = 'NULL';
    }
    fullname.value = img;
    urlImg.src = 'ImageFiles/' + img;
}

function validateImage(item) {
    var validate = true;
    var formUpload = document.getElementById('formUpload');
    var prefixImg = getElement(formUpload, 'prefix_image');
    var idImg = getElement(formUpload, 'name_image');
    var fileImg = getElement(formUpload, 'imageFile');
    if (prefixImg === null || prefixImg.value === "") {
        validate = false;
    }
    if (idImg === null || idImg.value === "") {
        validate = false;
    }
    if (validateForm(getForm(item)) === false) {
        validate = false;
    }
    if (fileImg.files.length <= 0) {
        validate = false;
    } else {
        console.log("Se encontró " + fileImg.files.length + " archivos para Subir al Sistema!.");
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
