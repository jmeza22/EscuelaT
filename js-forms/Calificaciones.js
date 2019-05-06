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
    var listalogrossup = null;
    var listalogrosalt = null;
    var listalogrosbas = null;
    var listalogrosbaj = null;
    nombre = document.getElementById('nombrecompleto_docente');
    idasignatura = document.getElementById('id_asignatura');
    nomasignatura = document.getElementById('nombre_asignatura');
    grado = document.getElementById('numgrado_programa');
    grupo = document.getElementById('id_grupo');
    listalogros = document.getElementById('lista_id_logro');
    listalogrossup = document.getElementById('lista_id_logro_sup');
    listalogrosalt = document.getElementById('lista_id_logro_alt');
    listalogrosbas = document.getElementById('lista_id_logro_bas');
    listalogrosbaj = document.getElementById('lista_id_logro_baj');
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
    listalogrossup.setAttribute('findby', idasignatura.id);
    listalogrossup.setAttribute('findbyvalue', idasignatura.value);
    listalogrossup.setAttribute('findby2', 'numgrado_logro');
    listalogrossup.setAttribute('findbyvalue2', grado.value);
    loadComboboxData(listalogrossup);
    listalogrosalt.setAttribute('findby', idasignatura.id);
    listalogrosalt.setAttribute('findbyvalue', idasignatura.value);
    listalogrosalt.setAttribute('findby2', 'numgrado_logro');
    listalogrosalt.setAttribute('findbyvalue2', grado.value);
    loadComboboxData(listalogrosalt);
    listalogrosbas.setAttribute('findby', idasignatura.id);
    listalogrosbas.setAttribute('findbyvalue', idasignatura.value);
    listalogrosbas.setAttribute('findby2', 'numgrado_logro');
    listalogrosbas.setAttribute('findbyvalue2', grado.value);
    loadComboboxData(listalogrosbas);
    listalogrosbaj.setAttribute('findby', idasignatura.id);
    listalogrosbaj.setAttribute('findbyvalue', idasignatura.value);
    listalogrosbaj.setAttribute('findby2', 'numgrado_logro');
    listalogrosbaj.setAttribute('findbyvalue2', grado.value);
    loadComboboxData(listalogrosbaj);
}

function ObtenerConfiguracion() {
    var idescuela = null;
    var formconf = null;
    formconf = document.getElementById("formConfig");
    if (formconf !== undefined && formconf !== null) {
        idescuela = getElement(formconf, 'id_escuela');
        idescuela.value = getEnterpriseID();
        OcultarPeriodos();
        getData(formconf).done(function () {
            MostrarPeriodos();
        });
    }
}

function getIdCargaFromPOST() {
    var idcarga = null;
    idcarga = GET('id_carga');
    var mytable = document.getElementById("dataTable0");
    mytable.setAttribute('findby', 'id_carga');
    mytable.setAttribute('findbyvalue', idcarga);
}

function setRequiredND(nd) {
    if (nd !== undefined && nd !== null) {
        nd.setAttribute('required', 'required');
        nd.setAttribute('float', 'true');
    }
}

function ValidarNotasPorFila(item) {
    if (item !== undefined && item !== null) {
        var tr = null;
        var nc = null;
        var np = null;
        var na = null;
        var nd = null;
        var valor = null;
        var valmin = document.getElementById('valmin_configuracion');
        var valmax = document.getElementById('valmax_configuracion');
        tr = getParentTR(item);
        nc = getElementByName(tr, 'nc_calificacion[]');
        np = getElementByName(tr, 'np_calificacion[]');
        na = getElementByName(tr, 'na_calificacion[]');
        nd = getElementByName(tr, 'nd_calificacion[]');
        if (nc !== undefined && np !== undefined && na !== undefined) {
            valmin = parseFloat(valmin.value);
            valmax = parseFloat(valmax.value);

            if (parseFloat(nc.value) < valmin || parseFloat(nc.value) > valmax) {
                showNotification('Validacion', 'NC o L1 debe ser un valor Numerico entre ' + valmin + ' y ' + valmax + '.');
                nc.value=valmin;
                return false;
            }
            if (parseFloat(np.value) < valmin || parseFloat(np.value) > valmax) {
                showNotification('Validacion', 'NP o L2 debe ser un valor Numerico entre ' + valmin + ' y ' + valmax + '.');
                np.value=valmin;
                return false;
            }
            if (parseFloat(na.value) < valmin || parseFloat(na.value) > valmax) {
                showNotification('Validacion', 'NA o L3 debe ser un valor Numerico entre ' + valmin + ' y ' + valmax + '.');
                na.value=valmin;
                return false;
            }
            return true;
        }
        return false;
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
            if (item !== nc && (nc.value === '' || isNaN(nc.value))) {
                nc.value = 0;
            }
            if (item !== np && (np.value === '' || isNaN(np.value))) {
                np.value = 0;
            }
            if (item !== na && (na.value === '' || isNaN(na.value))) {
                na.value = 0;
            }
            valor = (parseFloat(nc.value) + parseFloat(np.value) + parseFloat(na.value)) / 3;
            nd.value = Math.round(valor * Math.pow(10, 1)) / Math.pow(10, 1);
            ;
        }
    }
}

function OcultarPeriodos() {
    var thp1 = document.getElementById('thp1');
    var thp2 = document.getElementById('thp2');
    var thp3 = document.getElementById('thp3');
    var thp4 = document.getElementById('thp4');
    var thp5 = document.getElementById('thp5');
    var thp6 = document.getElementById('thp6');
    thp1.setAttribute('style', 'display: none !important;');
    thp2.setAttribute('style', 'display: none !important;');
    thp3.setAttribute('style', 'display: none !important;');
    thp4.setAttribute('style', 'display: none !important;');
    thp5.setAttribute('style', 'display: none !important;');
    thp6.setAttribute('style', 'display: none !important;');

    console.log('Ocultando Periodos');
}

function MostrarPeriodos() {
    var numcortes = document.getElementById('numcortes_configuracion');
    var thp1 = document.getElementById('thp1');
    var thp2 = document.getElementById('thp2');
    var thp3 = document.getElementById('thp3');
    var thp4 = document.getElementById('thp4');
    var thp5 = document.getElementById('thp5');
    var thp6 = document.getElementById('thp6');
    var tdp1 = null;
    var tdp2 = null;
    var tdp3 = null;
    var tdp4 = null;
    var tdp5 = null;
    var tdp6 = null;
    if (numcortes !== undefined && numcortes !== null && numcortes.value !== '' && numcortes.value !== '0') {
        numcortes = numcortes.value;
        numcortes = parseFloat(numcortes);
        console.log('Mostrando ' + numcortes + ' Periodos.');

        if (numcortes > 0) {
            thp1.removeAttribute('style');
            console.log('Mostrando 1er Periodo.');
        }
        if (numcortes > 1) {
            thp2.removeAttribute('style');
            console.log('Mostrando 2do Periodo.');
        }
        if (numcortes > 2) {
            thp3.removeAttribute('style');
            console.log('Mostrando 3er Periodo.');
        }
        if (numcortes > 3) {
            thp4.removeAttribute('style');
            console.log('Mostrando 4to Periodo.');
        }
        if (numcortes > 4) {
            thp5.removeAttribute('style');
            console.log('Mostrando 5to Periodo.');
        }
        if (numcortes > 5) {
            thp6.removeAttribute('style');
            console.log('Mostrando 6to Periodo.');
        }

        var i = 0;
        tdp1 = document.getElementsByName('tdp1');
        tdp2 = document.getElementsByName('tdp2');
        tdp3 = document.getElementsByName('tdp3');
        tdp4 = document.getElementsByName('tdp4');
        tdp5 = document.getElementsByName('tdp5');
        tdp6 = document.getElementsByName('tdp6');

        while (tdp1[i] !== undefined && tdp1[i] !== null) {
            if (numcortes > 0) {
                tdp1[i].removeAttribute('style');
            }
            if (numcortes > 1) {
                tdp2[i].removeAttribute('style');
            }
            if (numcortes > 2) {
                tdp3[i].removeAttribute('style');
            }
            if (numcortes > 3) {
                tdp4[i].removeAttribute('style');
            }
            if (numcortes > 4) {
                tdp5[i].removeAttribute('style');
            }
            if (numcortes > 5) {
                tdp6[i].removeAttribute('style');
            }
            i++;
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



