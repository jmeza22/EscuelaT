/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    LoadSede();
    LoadPrograma();
    LoadGrupo();
    LoadPeriodo();
    setRequiredsInputs(document.getElementById("tipo_reporte"));
});

function setRequiredsInputs(tipo) {
    var form0 = document.getElementById("form0");
    var sede = getElement(form0, 'id_sede');
    var jornada = getElement(form0, 'id_jornada');
    var programa = getElement(form0, 'id_programa');
    var planestudio = getElement(form0, 'id_planestudio');
    var periodo = getElement(form0, 'id_periodo');
    var corte = getElement(form0, 'id_corte');
    var grado = getElement(form0, 'numgrado_programa');
    var grupo = getElement(form0, 'id_grupo');
    var estudiante = getElement(form0, 'id_estudiante');
    sede.removeAttribute('required');
    jornada.removeAttribute('required');
    programa.removeAttribute('required');
    planestudio.removeAttribute('required');
    periodo.removeAttribute('required');
    corte.removeAttribute('required');
    grado.removeAttribute('required');
    grupo.removeAttribute('required');
    estudiante.removeAttribute('required');
    if (tipo !== undefined && tipo !== null) {
        if (tipo.value === 'CarnetEstudiantil') {
            programa.setAttribute('required', 'required');
            periodo.setAttribute('required', 'required');
        }
        if (tipo.value === 'RecibosMatriculas') {
            programa.setAttribute('required', 'required');
            grado.setAttribute('required', 'required');
            grupo.setAttribute('required', 'required');
            periodo.setAttribute('required', 'required');
        }
        if (tipo.value === 'CertificadoEstudios') {
            programa.setAttribute('required', 'required');
            grado.setAttribute('required', 'required');
            periodo.setAttribute('required', 'required');
        }
        if (tipo.value === 'CertificadoEstudios') {
            sede.setAttribute('required', 'required');
            programa.setAttribute('required', 'required');
            grado.setAttribute('required', 'required');
            grupo.setAttribute('required', 'required');
            periodo.setAttribute('required', 'required');
            estudiante.setAttribute('required', 'required');
        }
        if (tipo.value === 'CertificadoNotas') {
            sede.setAttribute('required', 'required');
            jornada.setAttribute('required', 'required');
            programa.setAttribute('required', 'required');
            grado.setAttribute('required', 'required');
            grupo.setAttribute('required', 'required');
            estudiante.setAttribute('required', 'required');
        }
        if (tipo.value === 'RendimientoBajo') {
            programa.setAttribute('required', 'required');
            periodo.setAttribute('required', 'required');
        }
        if (tipo.value === 'PlanEstudioDetallado') {
            programa.setAttribute('required', 'required');
            planestudio.setAttribute('required', 'required');
        }
        if (tipo.value === 'EstadisticosBasicosEstudiante') {
            programa.setAttribute('required', 'required');
            grado.setAttribute('required', 'required');
            grupo.setAttribute('required', 'required');
        }
        if (tipo.value === 'PlanillasCalificaciones') {
            programa.setAttribute('required', 'required');
            planestudio.setAttribute('required', 'required');
            grado.setAttribute('required', 'required');
            grupo.setAttribute('required', 'required');
            periodo.setAttribute('required', 'required');
            corte.setAttribute('required', 'required');
        }
        if (tipo.value === 'ObservadorEstudiante') {
            estudiante.setAttribute('required', 'required');
        }
        if (tipo.value === 'Estudiantes') {
            sede.setAttribute('required', 'required');
            jornada.setAttribute('required', 'required');
        }
        if (tipo.value === 'ContactosEstudiantes') {
            programa.setAttribute('required', 'required');
            periodo.setAttribute('required', 'required');
        }
        if (tipo.value === 'DirectoresGrupos') {
            programa.setAttribute('required', 'required');
            periodo.setAttribute('required', 'required');
        }
        if (tipo.value === 'CargasDocentes') {
            programa.setAttribute('required', 'required');
            periodo.setAttribute('required', 'required');
        }
        
    }
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

function LoadCorte() {
    var corte = null;
    corte = document.getElementById("id_corte");
    corte.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(corte);
}

function LoadEstudiante() {
    var estudiante = null;
    estudiante = document.getElementById("id_estudiante");
    estudiante.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(estudiante);
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false);
    }
}