/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function viewFile(linkTag) {
    if (linkTag !== null && linkTag.tagName === 'A' && linkTag.href !== undefined && linkTag.href !== '') {
        console.log('Download File!');
        linkTag.click();
    }
}

function selectFile(file, documentNameTag) {
    if (file !== null && file !== '') {
        if (file.tagName === undefined) {
            file = document.getElementById(file);
        }
    } else {
        return false;
    }
    if (documentNameTag !== null && documentNameTag !== '') {
        if (documentNameTag.tagName === undefined) {
            documentNameTag = document.getElementById(documentNameTag);
        }
    } else {
        return false;
    }

    if (file !== null && file.tagName === 'INPUT' && documentNameTag.value === '') {
        console.log('Select File!');
        file.click();
    }
}

function showSelectedFile(file, buttonTag) {
    if (file !== null && file !== '') {
        if (file.tagName === undefined) {
            file = document.getElementById(file);
        }
    } else {
        return false;
    }
    if (buttonTag !== null && buttonTag !== '') {
        if (buttonTag.tagName === undefined) {
            buttonTag = document.getElementById(buttonTag);
        }
    } else {
        return false;
    }

    if (file !== null && file.files !== undefined && file.files.length > 0) {
        var text = "";
        text = "Archivo: " + file.files[0].name + "\r\n";
        text = text + "Tipo: " + file.files[0].type + "\r\n";
        showNotification('Archivo Seleccionado:', text, 10000);
        buttonTag.setAttribute('class', 'btn btn-block btn-info');
        buttonTag.setAttribute('title', 'Archivo: ' + file.files[0].name);
        buttonTag.innerHTML = '<i class="glyphicon glyphicon-file"></i> Seleccionado';
    }
}

function LoadPDF1(file, documentNameTag, linkTag, buttonTag, url) {
    if (file !== null && file !== '') {
        if (file.tagName === undefined) {
            file = document.getElementById(file);
        }
    } else {
        return false;
    }
    if (documentNameTag !== null && documentNameTag !== '') {
        if (documentNameTag.tagName === undefined) {
            documentNameTag = document.getElementById(documentNameTag);
        }
    } else {
        return false;
    }
    if (linkTag !== null && linkTag !== '') {
        if (linkTag.tagName === undefined) {
            linkTag = document.getElementById(linkTag);
        }
    } else {
        return false;
    }
    if (buttonTag !== null && buttonTag !== '') {
        if (buttonTag.tagName === undefined) {
            buttonTag = document.getElementById(buttonTag);
        }
    } else {
        return false;
    }
    if (documentNameTag.value !== '') {
        console.log('Has Document!');
        linkTag.href = url + documentNameTag.value;
        buttonTag.setAttribute('title', 'Ha cargado un Documento. Haga click para verlo.');
        buttonTag.setAttribute('class', 'btn btn-block btn-success');
        buttonTag.innerHTML = '<i class="glyphicon glyphicon-download"></i> Descargar';
        file.setAttribute('disabled', 'disabled');
        linkTag.setAttribute('disabled', 'disabled');
    } else {
        console.log('Hasnot Document!');
        buttonTag.setAttribute('title', 'No ha cargado Documento. Haga click para seleccionarlo.');
        buttonTag.setAttribute('class', 'btn btn-block btn-default');
        buttonTag.innerHTML = '<i class="glyphicon glyphicon-upload"></i> Cargar';
        file.removeAttribute('disabled');
        linkTag.removeAttribute('disabled');
    }
}

