/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    setDatosEncabezado();
    LoadTable();
});

function setDatosEncabezado() {
    var idestudiante = null;
    var idprograma = null;
    var nomprograma = null;
    var grado = null;
    idestudiante = document.getElementById('id_estudiante');
    idprograma = document.getElementById('id_programa');
    nomprograma = document.getElementById('nombre_programa');
    grado = document.getElementById('numgrado_programa');
    idestudiante.value = GET('id_estudiante');
    idprograma.value = GET('id_programa');
    nomprograma.value = GET('nombre_programa');
    grado.value = GET('numgrado_programa');
    setFindBy();
}

function setFindBy() {
    var idprograma = null;
    var grado = null;
    var table = document.getElementById("dataTable0");
    idprograma = document.getElementById('id_programa');
    grado = document.getElementById('numgrado_programa');
    table.setAttribute('findby1', idprograma.id);
    table.setAttribute('findbyvalue1', idprograma.value);
    table.setAttribute('findby2', grado.id);
    table.setAttribute('findbyvalue2', grado.value);
}

function LoadTable() {
    setFindBy();
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}
