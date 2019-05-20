jQuery(document).ready(function () {
    showNotification('Bienvendo a EscuelaT de ' + getEnterpriseName() + '!', 'Docente: ' + getFullnameLogin());
    setFullNameDocente();
    LoadTable();
});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
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

function LoadPrograma() {
    var programa = null;
    programa = document.getElementById("id_programa");
    programa.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(programa);
}

function setFullNameDocente() {
    var nombre = document.getElementById("nombrecompleto_docente");
    if (nombre !== undefined && nombre !== null) {
        nombre.innerHTML = '' + getFullnameLogin();
    }
}

function goCalificaciones(item) {
    if (item !== undefined && item !== null) {
        var form = getForm(item);
        var idcarga = getElement(form, 'id_carga');
        var periodo = getElement(form, 'id_periodo');
        var grado = getElement(form, 'numgrado_programa');
        var idgrupo = getElement(form, 'id_grupo');
        var idasignatura = getElement(form, 'id_asignatura');
        var nomasignatura = getElement(form, 'nombre_asignatura');
        setPOST('planilla_id_carga', idcarga.value);
        window.location.href = "FormCalificaciones.html?"+idcarga.id+"="+idcarga.value+"&"+grado.id+"="+grado.value+"&"+idgrupo.id+"="+idgrupo.value+"&"+idasignatura.id+"="+idasignatura.value+"&"+nomasignatura.id+"="+nomasignatura.value+"&"+periodo.id+"="+periodo.value+"";
    }
}