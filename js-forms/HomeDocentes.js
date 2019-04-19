jQuery(document).ready(function () {
    showNotification('Bienvendo a EscuelaT de '+getEnterpriseName()+'!', 'Docente: '+getFullnameLogin());
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

function setFullNameDocente(){
    var nombre=document.getElementById("nombrecompleto_docente");
    if(nombre!==undefined && nombre!==null){
        nombre.innerHTML=''+getFullnameLogin();
    }
}