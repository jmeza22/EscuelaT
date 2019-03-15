/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function clickButton() {
    document.getElementById('imageFile').click();
    $('#imagePhoto').click(function () {
        $('#imageFile').click();
    });
}

function showMyImage(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var imageType = /image.*/;
        if (!file.type.match(imageType)) {
            continue;
        }
        var img = document.getElementById("imagePhoto");
        img.file = file;
        var reader = new FileReader();
        reader.onload = (function (aImg) {
            return function (e) {
                aImg.src = e.target.result;
            };
        })(img);
        reader.readAsDataURL(file);
        return file.name;
    }
    return null;
}

function showMyImageAndGetName(fileInput, elementid) {
    var filename = null;
    if (showMyImage(fileInput) !== null) {
        var filename = showMyImage(fileInput);
    }
    var element = null;
    if (elementid !== null && elementid !== '') {
        element = document.getElementById(elementid);
    }
    if (element !== null && filename !== null) {
        element.value = filename;
        return true;
    }
    return false;
}



