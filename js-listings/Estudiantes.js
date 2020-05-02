/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    loadComboboxData(document.getElementById('id_periodo'));
    loadComboboxData(document.getElementById('id_programa'));
});

function setFindbyTable() {
    var myform = document.getElementById('form0');
    var mytable = document.getElementById("dataTable0");
    var idprograma = getElement(myform, 'id_programa');
    var idperiodo = getElement(myform, 'id_periodo');
    var numgrado = getElement(myform, 'numgrado_programa');
    var idgrupo = getElement(myform, 'id_grupo');
    if (idprograma !== null && idprograma.value !== '') {
        mytable.setAttribute('findby1', idprograma.id);
        mytable.setAttribute('findbyvalue1', idprograma.value);
    }
    if (idperiodo !== null && idperiodo.value !== '') {
        mytable.setAttribute('findby3', idperiodo.id);
        mytable.setAttribute('findbyvalue3', idperiodo.value);
    }
    if (numgrado !== null && numgrado.value !== '') {
        mytable.setAttribute('findby4', numgrado.id);
        mytable.setAttribute('findbyvalue4', numgrado.value);
    }
    if (idgrupo !== null && idgrupo.value !== '') {
        mytable.setAttribute('findby5', idgrupo.id);
        mytable.setAttribute('findbyvalue5', idgrupo.value);
    }
}

function LoadTable() {
    var myform = document.getElementById('form0');
    var mytable = document.getElementById("dataTable0");
    clearTableData(mytable);
    if (validateForm(myform)) {
        setFindbyTable();
        loadTableData(mytable, true);
    }
}

function VerEstudiante(item) {
    if (item !== undefined && item !== null) {
        var form = getForm(item);
        var idestudiante = getElement(form, 'id_estudiante');
        setPOST('observador_id_persona', idestudiante.value);
        window.location.href = "FormEstudiantes.html?id_persona=" + idestudiante.value + "";
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