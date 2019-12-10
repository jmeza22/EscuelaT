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
    periodo = document.getElementById("id_periodo");
    var programa = null;
    programa = document.getElementById("id_programa");
    var grado = null;
    grado = document.getElementById("numgrado_programa");
    var grupo = null;
    grupo = document.getElementById("id_grupo");
    mytable.setAttribute("findby1", periodo.id);
    mytable.setAttribute("findbyvalue1", periodo.value);
    mytable.setAttribute("findby2", programa.id);
    mytable.setAttribute("findbyvalue2", programa.value);
    mytable.setAttribute("findby3", grado.id);
    mytable.setAttribute("findbyvalue3", grado.value);
    mytable.setAttribute("findby4", grupo.id);
    mytable.setAttribute("findbyvalue4", grupo.value);

}

function LoadPeriodo() {
    var periodo = null;
    periodo = document.getElementById("id_periodo");
    periodo.innerHTML = '<option disabled="disabled" value="">Ninguna</option>';
    loadComboboxData(periodo);
}

function LoadPrograma() {
    var programa = null;
    programa = document.getElementById("id_programa");
    programa.innerHTML = '<option disabled="disabled" value="">Ninguna</option>';
    loadComboboxData(programa);
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
    myform = getForm(item);
    if (validateForm(myform)) {
        submitForm(myform, false).done(function () {
            setTimeout(function () {
                ClearTable();
                if (parseInt(getRowCount()) > 0) {
                    alert('Exito: Se han Calculado las Calificaciones Definitivas!.');
                    LoadTable();
                } else {
                    alert("Hubo un error. Es posible que no hayan datos. Tambien es posible que ya se haya realizado la Operacion de Calculo anteriormente.");
                }
            }, 100);
        });
    }
}

