/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    setIdEstudiante();
    LoadTable();
});

function setIdEstudiante() {
    var mytable = document.getElementById("dataTable0");
    var idestudiante = null;
    if (mytable !== undefined && mytable !== null) {
        console.log('Seteando Id Estudiante.');
        console.log('TipoUsuario: '+getUserRoleLogin());
        if (getUserRoleLogin() === 'Student' && getUserIdLogin() !== null) {
            mytable.setAttribute('findby', 'id_estudiante');
            mytable.setAttribute('findbyvalue', getUserIdLogin());
        }
        if (getUserRoleLogin() !== 'Student') {
            if (GET('id_estudiante') !== null) {
                idestudiante = GET('id_estudiante');
                mytable.setAttribute('findby', 'id_estudiante');
                mytable.setAttribute('findbyvalue', idestudiante);
            }
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
        window.location.href = "FormEstudiantes.html?" + idestudiante.id + "=" + idestudiante.value + "";
    }
}

function VerNotas(item) {
    if (item !== undefined && item !== null) {
        var form = getForm(item);
        var idmat = getElement(form, 'id_matricula');
        setPOST('listingcalificaciones_id_matricula', idmat.value);
        window.location.href = "ListingCalificaciones.html?" + idmat.id + "=" + idmat.value + "";
    }
}