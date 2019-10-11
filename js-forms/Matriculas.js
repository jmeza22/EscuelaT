/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    showNotification('Espere...', 'Espere a que se carguen todos los elementos. Tenga paciencia. No recargue la pagina.')
    //LoadEscuela();
    autoLoadNameFromId('id_estudiante', 'nombrecompleto_estudiante', null, null);
    LoadSede();
    LoadJornada();
    LoadPrograma();
    LoadPlanEstudios();
    LoadPeriodo();
    LoadGrado();
    LoadGrupo();
    LoadTable();

});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false).done(function () {
    });
    return mytable;
}

function LoadEscuela() {
    var escuela = null;
    escuela = document.getElementById("id_escuela");
    escuela.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(escuela);
}

function LoadSede() {
    var sede = null;
    sede = document.getElementById("id_sede");
    sede.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(sede);
}

function LoadJornada() {
    var jornada = null;
    jornada = document.getElementById("id_jornada");
    jornada.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(jornada);
}

function LoadPrograma() {
    var programa = null;
    programa = document.getElementById("id_programa");
    programa.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(programa);
}

function LoadPlanEstudios() {
    var plan = null;
    plan = document.getElementById("id_planestudio");
    plan.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(plan);
}

function LoadGrado() {
    var grado = null;
    grado = document.getElementById("numgrado_programa");
    grado.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(grado);
}

function LoadGrupo() {
    var grupo = null;
    grupo = document.getElementById("id_grupo");
    grupo.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(grupo);
}

function LoadPeriodo() {
    var periodo = null;
    periodo = document.getElementById("id_periodo");
    periodo.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(periodo);
}

function GrabarMatricula() {
    var form0 = null;
    form0 = document.getElementById('form0');
    setIdMatricula();
    if (validateForm(form0)) {
        submitForm(form0, false).done(function () {
            LoadTable();
        });
    }
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false);
    }
}

function Edit(item) {
    var myform = null;
    myform = document.getElementById('form0');
    resetForm(myform);
    sendValue(item, null, myform, null);
    getData(myform).done(function () {
        setTimeout(function () {
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
                    deleteRowInTable(mytable);
                } else {
                    status.value = '1';
                }
            });
        }
    }
}

function setIdMatricula() {
    var form0 = null;
    var idmatricula = null;
    var idescuela = null;
    var fecha = null;
    form0 = document.getElementById('form0');
    idmatricula = getElement(form0, 'id_matricula');
    idescuela = getElement(form0, 'id_escuela');
    if (idmatricula !== null && idmatricula !== undefined && idmatricula.value === '') {
        fecha = new Date();
        idmatricula.value = 'M' + fecha.getFullYear() + '' + (fecha.getMonth()+1) + '' + fecha.getDate()+ '' + fecha.getHours()+ '' + fecha.getMinutes()+ '' + fecha.getSeconds() + '' + (getRandomNumber(1, 9) * getRandomNumber(1, 9));
    }
}

function MatricularAsignaturas(item) {
    if (item !== null) {
        var subform = null;
        subform = getForm(item);
        var idmat = null;
        idmat = getElement(subform, 'id_matricula');
        if (idmat !== null) {
            window.location.href = 'FormMatriculaAsignaturas.html';
            setPOST('id_matricula', idmat.value);
        }
    }
}

