/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    LoadTable();
    setIdEscuela();
});

function setIdEscuela() {
    var item = null;
    item = document.getElementById('id_escuela');
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Escuela.');
        if (getEnterpriseID() !== null) {
            item.value = getEnterpriseID();
        }
    }
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, true);
}

function VerEstudiante(item) {
    if (item !== undefined && item !== null) {
        var form = getForm(item);
        var idestudiante = getElement(form, 'id_estudiante');
        setPOST('observador_id_estudiante', idestudiante.value);
        window.location.href = "FormEstudiantes.html?"+idestudiante.id+"="+idestudiante.value+"";
    }
}
