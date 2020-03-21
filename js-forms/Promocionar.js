/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    LoadPeriodo();
    LoadPrograma();
});

function setFindbyTable() {
    var mytable = document.getElementById("dataTable0");
    var periodo = null;
    periodo = document.getElementById("id_periodo_new");
    var programa = null;
    programa = document.getElementById("id_programa");
    mytable.setAttribute("findby1", 'id_periodo');
    mytable.setAttribute("findbyvalue1", periodo.value);
    mytable.setAttribute("findby2", programa.id);
    mytable.setAttribute("findbyvalue2", programa.value);
    mytable.setAttribute("findby3", 'usuariocrea_matricula');
    mytable.setAttribute("findbyvalue3", getUserIdLogin());
    mytable.setAttribute("findby4", 'fecha_matricula');
    mytable.setAttribute("findbyvalue4", getCurrentDate());
}

function LoadPeriodo() {
    var periodo = null;
    periodo = document.getElementById("id_periodo");
    periodo.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(periodo);
}

function LoadPrograma() {
    var programa = null;
    programa = document.getElementById("id_programa");
    programa.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(programa);
}

function LoadPeriodoSiguiente() {
    var periodo = null;
    var anualidadAnt = document.getElementById("anualidad_periodo");
    periodo = document.getElementById("id_periodo_new");
    periodo.setAttribute('findby', 'anualidad_periodo_old');
    periodo.setAttribute('findbyvalue', anualidadAnt.value);
    periodo.innerHTML = '<option value="" disabled="disabled">Ninguna</option>';
    loadComboboxData(periodo);
}

function LoadGrupoSiguiente() {
    var grupo = null;
    var gradonew = document.getElementById("numgrado_programa_new");
    grupo = document.getElementById("id_grupo_new");
    grupo.setAttribute('findby', 'numgrado_programa');
    grupo.setAttribute('findbyvalue', gradonew.value);
    grupo.innerHTML = '<option value="" >Ninguna</option>';
    loadComboboxData(grupo);
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
    return mytable;
}

function ClearTable() {
    var mytable = document.getElementById("dataTable0");
    clearTableData(mytable);
    return mytable;
}

function Send(item) {
    var myform = null;
    var periodo = document.getElementById("id_periodo");
    var newperiodo = document.getElementById("id_periodo_new");
    myform = getForm(item);
    if (validateForm(myform) && periodo.value !== newperiodo.value && confirm('Este procedimiento no se puede reversar. Está seguro de ejecutarlo?')) {
        submitForm(myform, false).done(function () {
            setTimeout(function () {
                ClearTable();
                if (parseInt(getRowCount()) > 0) {
                    setFindbyTable();
                    LoadTable();
                    alert('Exito: Se han Promovido los Estudiantes que Aprobaron!.');
                } else {
                    alert("Hubo un error. Es posible que no hayan datos. Tambien es posible que ya se haya realizado la Operacion de Calculo anteriormente.");
                }
            }, 100);
        });
    }
}

