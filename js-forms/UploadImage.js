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
    prefixImg = getElement(formUpload, 'prefix_image');
    idImg = getElement(formUpload, 'name_image');
    urlImg = getElement(formUpload, 'imagePhoto');
    prefixImg.value=prefix;
    idImg.value=id;
    urlImg.src='ImageFiles/'+img;
}
