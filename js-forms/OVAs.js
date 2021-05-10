/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    loadComboboxData(document.getElementById("id_asignatura"));
    LoadTable();
});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}

function LoadPDF() {

}

function LoadImagen1() {
    var img1 = document.getElementById('imagetag1');
    var imagen1 = document.getElementById('imagen1_ova');
    if (img1 !== null && img1.tagName === 'IMG' && imagen1 !== null && imagen1.tagName === 'INPUT' && imagen1.value !== '') {
        img1.src = "ImageFiles/" + imagen1.value;
    }
}

function LoadImagen2() {
    var img2 = document.getElementById('imagetag2');
    var imagen2 = document.getElementById('imagen2_ova');
    if (img2 !== null && img2.tagName === 'IMG' && imagen2 !== null && imagen2.tagName === 'INPUT' && imagen2.value !== '') {
        img2.src = "ImageFiles/" + imagen2.value;
    }
}

function LoadImagen3() {
    var img3 = document.getElementById('imagetag3');
    var imagen3 = document.getElementById('imagen3_ova');
    if (img3 !== null && img3.tagName === 'IMG' && imagen3 !== null && imagen3.tagName === 'INPUT' && imagen3.value !== '') {
        img3.src = "ImageFiles/" + imagen3.value;
    }
}

function LoadImagen4() {
    var img4 = document.getElementById('imagetag4');
    var imagen4 = document.getElementById('imagen4_ova');
    if (img4 !== null && img4.tagName === 'IMG' && imagen4 !== null && imagen4.tagName === 'INPUT' && imagen4.value !== '') {
        img4.src = "ImageFiles/" + imagen4.value;
    }
}

function LoadAudio1() {
    var aud1 = document.getElementById('audiotag1');
    var audio1 = document.getElementById('audio1_ova');
    if (aud1 !== null && aud1.tagName === 'AUDIO' && audio1 !== null && audio1.tagName === 'INPUT' && audio1.value !== '') {
        aud1.src = "AudioFiles/" + audio1.value;
    }
}

function LoadAudio2() {
    var aud2 = document.getElementById('audiotag2');
    var audio2 = document.getElementById('audio2_ova');
    if (aud2 !== null && aud2.tagName === 'AUDIO' && audio2 !== null && audio2.tagName === 'INPUT' && audio2.value !== '') {
        aud2.src = "AudioFiles/" + audio2.value;
    }
}


function LoadVideo1() {

}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            LoadTable();
            getFormData(form);
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
            LoadImagen1();
            LoadImagen2();
            LoadImagen3();
            LoadImagen4();
            LoadAudio1();
            showMyEmbedVideo(getElementByName(myform, 'video1_ova'), 'video1');
            showMyEmbedVideo(getElementByName(myform, 'video2_ova'), 'video2');
            LoadPDF1('documentfile', 'pdf_ova', 'linkTag', 'buttonTag', 'DocumentFiles/');
            
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
