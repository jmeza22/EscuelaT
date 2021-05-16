/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function clickImageButton(inputFile) {
    if (inputFile !== null && inputFile !== '') {
        if (inputFile.tagName === undefined) {
            inputFile = document.getElementById(inputFile);
        }
    }
    inputFile.click();
}

function showMyImage(inputFile, imageTag = null) {
    var files = inputFile.files;
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var imageType = /image.*/;
        if (!file.type.match(imageType)) {
            console.log('File not like Image!.');
            break;
        }
        console.log('File Type: ' + file.type);
        if (imageTag === null || imageTag === '') {
            imageTag = document.getElementById('image');
        }
        console.log('Image Tag Target: ' + imageTag.id);
        if (imageTag !== null || imageTag !== '') {
            if (imageTag.tagName !== undefined) {
                if (imageTag.tagName !== 'IMG' && imageTag.tagName !== 'IMAGE') {
                    return null;
                }
            } else {
                imageTag = document.getElementById(imageTag);
            }
        }
        imageTag.file = file;
        var reader = new FileReader();
        reader.onload = (function (aImg) {
            return function (e) {
                aImg.src = e.target.result;
            };
        })(imageTag);
        reader.readAsDataURL(file);
        return file.name;
    }
    return null;
}

function showMyImageAndGetName(inputFile, elementid) {
    var filename = null;
    if (showMyImage(inputFile) !== null) {
        var filename = showMyImage(inputFile);
    }
    var element = null;
    if (elementid !== null && elementid !== '') {
        element = document.getElementById(elementid);
    }
    if (element !== null && filename !== null) {
        element.value = filename;
        if (element.tagName === 'INPUT' || element.tagName === 'LABEL' || element.tagName === 'DIV') {
            element.innerHTML = filename;
        }
        console.log('Image Selected: ' + filename);
        return true;
    }
    return false;
}
