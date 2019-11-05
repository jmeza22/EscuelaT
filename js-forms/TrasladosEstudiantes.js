/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    LoadPrograma();
    LoadPeriodo();
    LoadGrado();
    LoadSedeOrigen();
    LoadSedeDestino();
});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false).done(function () {
    });
    return mytable;
}

function LoadPrograma() {
    var programa = null;
    programa = document.getElementById("id_programa");
    programa.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(programa);
}

function LoadPeriodo() {
    var periodo = null;
    periodo = document.getElementById("id_periodo");
    periodo.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(periodo);
}

function LoadGrado() {
    var grado = null;
    grado = document.getElementById("numgrado_programa");
    grado.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(grado);
}

function LoadSedeOrigen() {
    var sede = null;
    sede = document.getElementById("id_sede_origen");
    sede.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(sede);
}

function LoadSedeDestino() {
    var sede = null;
    sede = document.getElementById("id_sede_destino");
    sede.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(sede);
}

function LoadGrupoOrigen() {
    var grupo = null;
    grupo = document.getElementById("id_grupo_origen");
    grupo.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(grupo);
}

function LoadGrupoDestino() {
    var grupo = null;
    grupo = document.getElementById("id_grupo_destino");
    grupo.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(grupo);
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false).done(function () {
            setTimeout(function () {
                if (parseInt(sessionStorage.getItem('rowCount')) > 0) {
                    alert('Se han Trasladado ' + sessionStorage.getItem('rowCount') + ' Estudiantes!.');
                    LoadTable();
                }
            }, 100);
        });
    }
}

function setFindByTable() {
    var mytable = document.getElementById("dataTable0");
    var programa = null;
    programa = document.getElementById("id_programa");
    var periodo = null;
    periodo = document.getElementById("id_periodo");
    var grado = null;
    grado = document.getElementById("numgrado_programa");
    var sede = null;
    sede = document.getElementById("id_sede_origen");
    var grupo = null;
    grupo = document.getElementById("id_grupo_origen");

    mytable.setAttribute('findby1', 'id_programa');
    mytable.setAttribute('findbyvalue1', programa.value);
    mytable.setAttribute('findby2', 'id_periodo');
    mytable.setAttribute('findbyvalue2', periodo.value);
    mytable.setAttribute('findby3', 'numgrado_programa');
    mytable.setAttribute('findbyvalue3', grado.value);
    mytable.setAttribute('findby4', 'id_sede');
    mytable.setAttribute('findbyvalue4', sede.value);
    mytable.setAttribute('findby5', 'id_grupo');
    mytable.setAttribute('findbyvalue5', grupo.value);

    LoadTable();
}

function CheckItem(item) {
    if (item !== undefined) {
        var formItem = getForm(item);
        var mytable = getParentTable(item);
        var tr = getParentTR(item);
        var id = null;
        if (tr !== null && tr !== undefined) {
            id = getElement(tr, getFindBy(formItem));
        }
        if (id !== null) {
            console.log('Id:' + id.value);
            if (item.checked) {
                console.log('OK:' + id.name);
                id.removeAttribute('disabled');
            } else {
                id.setAttribute('disabled', 'disabled');
            }
        }
    }
}