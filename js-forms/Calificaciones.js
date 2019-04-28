/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    var idescuela = null;
    getIdCargaFromPOST();
    LoadTable();
    idescuela = document.getElementById('id_escuela');
    setDatosEncabezado();
    ObtenerConfiguracion();
});

function setDatosEncabezado() {
    var nombre = null;
    var idasignatura = null;
    var nomasignatura = null;
    var grado = null;
    var grupo = null;
    var listalogros = null;
    nombre = document.getElementById('nombrecompleto_docente');
    idasignatura = document.getElementById('id_asignatura');
    nomasignatura = document.getElementById('nombre_asignatura');
    grado = document.getElementById('numgrado_programa');
    grupo = document.getElementById('id_grupo');
    listalogros = document.getElementById('lista_id_logro');
    nombre.value = getFullnameLogin();
    idasignatura.value = GET('id_asignatura');
    nomasignatura.value = GET('nombre_asignatura');
    grado.value = GET('numgrado_programa');
    grupo.value = GET('id_grupo');
    listalogros.setAttribute('findby', idasignatura.id);
    listalogros.setAttribute('findbyvalue', idasignatura.value);
    listalogros.setAttribute('findby2', 'numgrado_logro');
    listalogros.setAttribute('findbyvalue2', grado.value);
    loadComboboxData(listalogros);
}

function ObtenerConfiguracion(){
    var idescuela=null;
    var formconf=null;
    formconf=document.getElementById("formConfig");
    if(formconf!==undefined && formconf!==null){
        idescuela=getElement(formconf,'id_escuela');
        idescuela.value=getEnterpriseID();
        getData(formconf);
    }
}

function getIdCargaFromPOST() {
    var idcarga = null;
    idcarga = GET('id_carga');
    var mytable = document.getElementById("dataTable0");
    mytable.setAttribute('findby', 'id_carga');
    mytable.setAttribute('findbyvalue', idcarga);
}

function setRequiredND(nd){
    if(nd!==undefined && nd!==null){
        nd.setAttribute('required','required');
        nd.setAttribute('float','true');
    }
}

function setNotaDefinitiva(item) {
    if (item !== undefined && item !== null) {
        var tr = null;
        var nc = null;
        var np = null;
        var na = null;
        var nd = null;
        var valor = null;
        tr = getParentTR(item);
        nc = getElementByName(tr, 'nc_calificacion[]');
        np = getElementByName(tr, 'np_calificacion[]');
        na = getElementByName(tr, 'na_calificacion[]');
        nd = getElementByName(tr, 'nd_calificacion[]');
        setRequiredND(nd);
        if (nc !== undefined && np !== undefined && na !== undefined) {
            if(item!==nc && (nc.value==='' || isNaN(nc.value))){
                nc.value=0;
            }
            if(item!==np && (np.value==='' || isNaN(np.value))){
                np.value=0;
            }
            if(item!==na && (na.value==='' || isNaN(na.value))){
                na.value=0;
            }
            valor=(parseFloat(nc.value) + parseFloat(np.value) + parseFloat(na.value))/3;
            nd.value = Math.round(valor*Math.pow(10,1))/Math.pow(10,1);;
        }
    }
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            LoadTable();
        });
    }
}

