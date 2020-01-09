/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    var idescuela = null;
    idescuela = document.getElementById('id_escuela');
    setDatosEncabezado();
    LoadTable();
});

function setDatosEncabezado() {
    var nombre = null;
    var idasignatura = null;
    var nomasignatura = null;
    var periodo = null;
    var grado = null;
    var grupo = null;
    var mytable = document.getElementById("dataTable0");
    nombre = document.getElementById('nombrecompleto_docente');
    idasignatura = document.getElementById('id_asignatura');
    nomasignatura = document.getElementById('nombre_asignatura');
    periodo = document.getElementById('id_periodo');
    grado = document.getElementById('numgrado_programa');
    grupo = document.getElementById('id_grupo');
    nombre.value = getFullnameLogin();
    idasignatura.value = GET('id_asignatura');
    nomasignatura.value = GET('nombre_asignatura');
    periodo.value = GET('id_periodo');
    grado.value = GET('numgrado_programa');
    grupo.value = GET('id_grupo');
    mytable.setAttribute('findby1','fecha_asistencia');
    mytable.setAttribute('findbyvalue1',getDateTimeString().toString());
    mytable.setAttribute('findby2','id_asignatura');
    mytable.setAttribute('findbyvalue2',idasignatura.value);
    mytable.setAttribute('findby3','numgrado_programa');
    mytable.setAttribute('findbyvalue3',grado.value);
    mytable.setAttribute('findby4','id_grupo');
    mytable.setAttribute('findbyvalue4',grupo.value);
    mytable.setAttribute('findby5','id_periodo');
    mytable.setAttribute('findbyvalue5',periodo.value);
    
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}


