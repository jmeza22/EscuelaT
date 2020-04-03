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
    var doc = null;
    prefix = GET('prefix');
    id = GET('id');
    doc = GET('doc');
    var prefix_document = getElement(formUpload, 'prefix_document');
    var name_document = getElement(formUpload, 'name_document');
    var fullname_document = getElement(formUpload, 'fullname_document');
    var document = getElement(formUpload, 'document');
    var panelfields = document.getElementById('fields');
    if (prefix !== null && prefix !== '') {
        prefix_document.value = prefix;
    } else {
        prefix_document.value = '';
    }
    if (id !== null && id !== '') {
        name_document.value = id;
    } else {
        name_document.value = '';
    }
    if (doc !== null && doc !== '') {
        fullname_document.value = doc;
        document.src = 'DocumentFiles/' + doc;
        panelfields.setAttribute('style', 'display:none;');
    }
}

function validateImage(item) {
    var validate = true;
    var formUpload = document.getElementById('formUpload');
    var documentfile = getElement(formUpload, 'document-file');
    if (validateForm(getForm(item)) === false) {
        validate = false;
    }
    if (documentfile.files.length <= 0) {
        validate = false;
    } else {
        console.log("Se encontró " + documentfile.files.length + " archivos para Subir al Sistema!.");
    }
    if (validate === false) {
        alert("Debe completar los Datos y Seleccionar el Documento!.");
    }
    return validate;
}

function submitDocument(item) {
    var myform = getForm(item);
    if (validateImage(myform) === true) {
        if (myform !== undefined && myform !== null) {
            submitForm(myform, false).done(function () {
            });
        }
    }
}
