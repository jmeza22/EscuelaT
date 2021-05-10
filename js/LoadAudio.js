/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function clickAudioButton(inputFile) {
    if (inputFile !== null && inputFile !== '') {
        if (inputFile.tagName === undefined) {
            inputFile = document.getElementById(inputFile);
        }
    }
    inputFile.click();
}

function showMyAudio(inputFile, audioTag = null) {
    var files = inputFile.files;
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var audioType = /audio.*/;
        if (!file.type.match(audioType)) {
            continue;
        }
        console.log('File Type: '+file.type);
        if (audioTag === null || audioTag === '') {
            audioTag = document.getElementById('audio');
        }
        if (audioTag !== null || audioTag !== '') {
            if (audioTag.tagName !== undefined) {
                if (audioTag.tagName !== 'AUDIO') {
                    return null;
                }
            } else {
                audioTag = document.getElementById(audioTag);
            }
        }
        audioTag.file = file;
        var reader = new FileReader();
        reader.onload = (function (aAud) {
            return function (e) {
                aAud.src = e.target.result;
            };
        })(audioTag);
        reader.readAsDataURL(file);
        return file.name;
    }
    return null;
}

function showMyAudioAndGetName(inputFile, elementid) {
    var filename = null;
    if (showMyAudio(inputFile) !== null) {
        var filename = showMyAudio(inputFile);
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
        console.log('Audio Selected: ' + filename);
        return true;
    }
    return false;
}
