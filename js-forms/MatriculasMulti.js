/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    autoLoadNameFromId('id_estudiante', 'nombrecompleto_estudiante', null, null);
    LoadSede();
    LoadPrograma();
    LoadPeriodo();
});

function setFindbyTable() {
    var myform = document.getElementById('form0');
    var mytable = document.getElementById("dataTable0");
    var idprograma = getElement(myform, 'id_programa');
    var idplanestudio = getElement(myform, 'id_planestudio');
    var idperiodo = getElement(myform, 'id_periodo');
    var numgrado = getElement(myform, 'numgrado_programa');
    var idgrupo = getElement(myform, 'id_grupo');
    clearTableData(mytable);
    if (idprograma !== null && idprograma.value !== '') {
        mytable.setAttribute('findby1', idprograma.id);
        mytable.setAttribute('findbyvalue1', idprograma.value);
    } else {
        mytable.removeAttribute('findby1');
        mytable.removeAttribute('findbyvalue1');
    }
    if (idplanestudio !== null && idplanestudio.value !== '') {
        mytable.setAttribute('findby2', idplanestudio.id);
        mytable.setAttribute('findbyvalue2', idplanestudio.value);
    } else {
        mytable.removeAttribute('findby2');
        mytable.removeAttribute('findbyvalue2');
    }
    if (idperiodo !== null && idperiodo.value !== '') {
        mytable.setAttribute('findby3', idperiodo.id);
        mytable.setAttribute('findbyvalue3', idperiodo.value);
    } else {
        mytable.removeAttribute('findby3');
        mytable.removeAttribute('findbyvalue3');
    }
    if (numgrado !== null && numgrado.value !== '') {
        mytable.setAttribute('findby4', numgrado.id);
        mytable.setAttribute('findbyvalue4', numgrado.value);
    } else {
        mytable.removeAttribute('findby4');
        mytable.removeAttribute('findbyvalue4');
    }
    if (idgrupo !== null && idgrupo.value !== '') {
        mytable.setAttribute('findby5', idgrupo.id);
        mytable.setAttribute('findbyvalue5', idgrupo.value);
    } else {
        mytable.removeAttribute('findby5');
        mytable.removeAttribute('findbyvalue5');
    }
}

function LoadTable() {
    var myform = document.getElementById('form0');
    var mytable = document.getElementById("dataTable0");
    clearTableData(mytable);
    setFindbyTable();
    if (validateForm(myform)) {
        loadTableData(mytable, false).done(function () {
        });
    }
    return mytable;
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
    setFindbyTable();
}

function onChangeSede() {
    var sede = null;
    sede = document.getElementById("id_sede");
    setComboboxFindby('id_jornada', sede.id, sede.value, 1);
    setFindByField('id_grupo', sede.id, getComboboxValue(sede), 1);
    setFindbyTable();
}

function onChangePrograma() {
    var programa = null;
    programa = document.getElementById("id_programa");
    setComboboxFindby('id_planestudio', programa.id, programa.value, 1);
    setFindByField('id_grupo', programa.id, getComboboxValue(programa), 2);
    setFindbyTable();
}

function onChangePlanEstudios() {
    var plan = null;
    plan = document.getElementById("id_planestudio");
    setComboboxFindby('numgrado_programa', plan.id, getComboboxValue(plan), 1);
    setFindbyTable();
}

function onChangeGrado() {
    var grado = null;
    grado = document.getElementById("numgrado_programa");
    setComboboxFindby('id_grupo', grado.id, getComboboxValue(grado), 3);
    setFindbyTable();
}

function setIdMatricula() {
    var form0 = null;
    var idescuela = getEnterpriseID();
    var idmatricula = null;
    var idestudiante = null;
    var idperiodo = null;
    var idprograma = null;
    var fecha = null;
    form0 = document.getElementById('form0');
    idmatricula = getElement(form0, 'id_matricula');
    idestudiante = getElement(form0, 'id_estudiante');
    idperiodo = getElement(form0, 'id_periodo');
    idprograma = getElement(form0, 'id_programa');
    if (idmatricula !== null && idmatricula !== undefined && idmatricula.value === '') {
        fecha = new Date();
        idmatricula.value = 'M' + idescuela + idperiodo.value + idprograma.value + idestudiante.value;
    }
}

function resetMatricula() {
    var form0 = document.getElementById('form0');
    var idperiodo = getElement(form0, 'id_periodo');
    var idprograma = getElement(form0, 'id_programa');
    idperiodo.removeAttribute('disabled');
    idprograma.removeAttribute('disabled');
    form0.reset();
}

function GrabarMatricula() {
    var form0 = null;
    var idmat = null;
    form0 = document.getElementById('form0');
    idmat = getElement(form0, 'id_matricula');
    setIdMatricula();
    if (validateForm(form0)) {
        submitForm(form0, false).done(function () {
            setFindbyTable();
            LoadTable();
            var rowcount = sessionStorage.getItem('rowCount');
            rowcount = parseInt(rowcount);
            if (isNaN(rowcount) === false && rowcount > 0) {
                MatriculaAsignaturasAutomatica(getElement(form0, 'id_matricula'));
            } else {
                idmat.value = "";
            }
        });
    }
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false);
    }
}

function GenerarReciboMatricula(item) {
    var form = null;
    if (item !== null) {
        form = getForm(item);
        form.setAttribute('action', '' + getWSPath() + 'Base/Controllers/ReportesController.php');
        var tiporep = getElement(form, 'tipo_reporte');
        tiporep.removeAttribute('disabled');
        form.submit();
        tiporep.setAttribute('disabled', 'disabled');
    }
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
            console.log('Tratando de Eliminar id: ' + id.value);
            console.log('Enviando a :'+form.id);
            status.value = '0';
            submitForm(form, false).done(function () {
                rowcount = window.sessionStorage.getItem('rowCount');
                rowcount = parseFloat(rowcount);
                if (rowcount !== undefined && rowcount !== null && rowcount > 0) {
                    deleteTableRow(mytable);
                } else {
                    status.value = '1';
                }
            });
        }
    }
}


function MatriculaAsignaturasAutomatica(idmatricula) {
    if (idmatricula !== null && idmatricula !== undefined && idmatricula.value !== '' && idmatricula.value !== '0') {
        if (confirm('Desea intentar Matriculacion Automatica de Asignaturas?')) {
            var formMA = document.getElementById('FormMatAsignatura');
            var idmatFormMA = null;
            if (formMA !== null && formMA !== undefined) {
                idmatFormMA = getElement(formMA, 'id_matricula');
                idmatFormMA.value = idmatricula.value;
                if (validateForm(formMA)) {
                    submitForm(formMA, false).done(function () {
                        showNotification('Matricula Automatica de Asignaturas:', 'Se ha ejecutado el proceso. Verifique el resultado por favor.');
                        idmatFormMA.value = '';
                    });
                }
            }
        }
    }
}


