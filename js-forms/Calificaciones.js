/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    var idescuela = null;
    getIdCargaFromGET();
    idescuela = document.getElementById('id_escuela');
    setDatosEncabezado();
    ObtenerDatosCuerpo();
});

function setDatosEncabezado() {
    var nombre = null;
    var idasignatura = null;
    var nomasignatura = null;
    var periodo = null;
    var corte = null;
    var ncorte = null;
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
    periodo = document.getElementById('id_periodo');
    corte = document.getElementById('id_corte');
    ncorte = document.getElementById('num_corte');
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
    periodo.value = GET('id_periodo');
    corte.value = GET('id_corte');
    ncorte.value = GET('num_corte');
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

function ObtenerDatosCuerpo() {
    var idescuela = null;
    var mytable = null;
    var formconf = null;
    var valaprueba = null;
    mytable = document.getElementById("dataTable0");
    formconf = document.getElementById("formConfig");
    if (formconf !== undefined && formconf !== null) {
        idescuela = getElement(formconf, 'id_escuela');
        idescuela.value = getEnterpriseID();
        getData(formconf).done(function () {
            valaprueba = getElement(formconf, 'valaprueba_configuracion');
            if (valaprueba !== undefined && valaprueba !== null && valaprueba.value !== null && valaprueba.value !== '') {
                LoadTable();
            } else {
                alert('No existen datos de Configuracion de la Escuela. Contacte al Administrador.');
                document.getElementById('guardar').setAttribute('disabled', 'disabled');
            }
        });
    }
}

function getIdCargaFromGET() {
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
                //showNotification('Validacion', 'NC o L1 debe ser un valor Numerico entre ' + valmin + ' y ' + valmax + '.');
                nc.value = valmin;
                return false;
            }
            if (parseFloat(np.value) < valmin || parseFloat(np.value) > valmax) {
                //showNotification('Validacion', 'NP o L2 debe ser un valor Numerico entre ' + valmin + ' y ' + valmax + '.');
                np.value = valmin;
                return false;
            }
            if (parseFloat(na.value) < valmin || parseFloat(na.value) > valmax) {
                //showNotification('Validacion', 'NA o L3 debe ser un valor Numerico entre ' + valmin + ' y ' + valmax + '.');
                na.value = valmin;
                return false;
            }
            return true;
        }
        return false;
    }
    return false;
}

function setNotaDefinitiva(item) {
    if (item !== undefined && item !== null) {
        var tr = null;
        var nc = null;
        var np = null;
        var na = null;
        var nn = null;
        var nd = null;
        var valor = null;
        tr = getParentTR(item);
        nc = getElementByName(tr, 'nc_calificacion[]');
        np = getElementByName(tr, 'np_calificacion[]');
        na = getElementByName(tr, 'na_calificacion[]');
        nn = getElementByName(tr, 'nn_calificacion[]');
        nd = getElementByName(tr, 'nd_calificacion[]');
        var porcCog=document.getElementById('porcentajecognitivo_configuracion');
        var porcPro=document.getElementById('porcentajeprocedimental_configuracion');
        var porcAct=document.getElementById('porcentajeactitudinal_configuracion');
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
            valor = ((parseFloat(nc.value)*parseFloat(porcCog.value)/100) + (parseFloat(np.value)*parseFloat(porcPro.value)/100) + (parseFloat(na.value)*parseFloat(porcAct.value)/100));
            if (nn.value !== '' && parseFloat(nn.value) > 0) {
                valor = nn.value;
            }else{
                nn.value='';
            }
            nd.value = Math.round(valor * Math.pow(10, 1)) / Math.pow(10, 1);
            ;
        }
    }
}

function OcultarCamposPeriodoFinal() {
    var numcorte = document.getElementById('num_corte');
    if (numcorte.value === 'fin' || numcorte.value === 'hab') {
        var logc = document.getElementsByName('logroc_calificacion[]');
        var logp = document.getElementsByName('logrop_calificacion[]');
        var loga = document.getElementsByName('logroa_calificacion[]');
        var nc = document.getElementsByName('nc_calificacion[]');
        var np = document.getElementsByName('np_calificacion[]');
        var na = document.getElementsByName('na_calificacion[]');
        var i = 0;
        console.log('Ocultando Campos No Necesarios para Periodo Final.');
        while (nc[i] !== undefined && nc[i] !== null) {
            logc[i].value = '';
            logc[i].setAttribute('disabled', 'disabled');
            logp[i].value = '';
            logp[i].setAttribute('disabled', 'disabled');
            loga[i].value = '';
            loga[i].setAttribute('disabled', 'disabled');
            nc[i].value = '';
            nc[i].setAttribute('disabled', 'disabled');
            nc[i].removeAttribute('required');
            np[i].value = '';
            np[i].setAttribute('disabled', 'disabled');
            np[i].removeAttribute('required');
            na[i].value = '';
            na[i].setAttribute('disabled', 'disabled');
            na[i].removeAttribute('required');
            i++;
        }
    }
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false).done(function () {
        OcultarCamposPeriodoFinal();
    });
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(item, false).done(function () {
            LoadTable();
        });
    }
}

function ValidarConfiguracionEscuela() {
    var vmin = document.getElementById('valmin_configuracion');
    var vmax = document.getElementById('valmax_configuracion');
    var vap = document.getElementById('valaprueba_configuracion');
    if (vmin !== undefined && vmin !== null && isNaN(vmin.value) === false) {
        vmin = parseFloat(vmin.value);
    }
    if (vmax !== undefined && vmax !== null && isNaN(vmax.value) === false) {
        vmax = parseFloat(vmax.value);
    }
    if (vap !== undefined && vap !== null && isNaN(vap.value) === false) {
        vap = parseFloat(vap.value);
    }
    if (!isNaN(vmin) && !isNaN(vmax) && !isNaN(vap)) {
        return true;
    }
    return false;
}

function setLogroCAutomatico(NC) {
    var tr = null;
    var ncvalue = null;
    var logroc = null;
    var vsupmin = document.getElementById('valsupmin_configuracion');
    var vsupmax = document.getElementById('valsupmax_configuracion');
    var valtmin = document.getElementById('valaltomin_configuracion');
    var valtmax = document.getElementById('valaltomax_configuracion');
    var vbasmin = document.getElementById('valbasmin_configuracion');
    var vbasmax = document.getElementById('valbasmax_configuracion');
    var vbajmin = document.getElementById('valbajomin_configuracion');
    var vbajmax = document.getElementById('valbajomax_configuracion');
    var lcsuperior = document.getElementById('lcsuperior');
    var lcalto = document.getElementById('lcalto');
    var lcbasico = document.getElementById('lcbasico');
    var lcbajo = document.getElementById('lcbajo');

    if (NC !== undefined && NC !== null && NC.value !== '') {
        if (isNaN(NC.value) === false) {
            ncvalue = parseFloat(NC.value);
            tr = getParentTR(NC);
            if (tr !== undefined && tr !== null) {
                logroc = getElementByName(tr, 'logroc_calificacion[]');
            }
            if (logroc !== undefined && logroc !== null && ValidarConfiguracionEscuela()) {
                console.log('Seteando LogroC o L1.');
                if (vsupmin !== undefined && vsupmin !== null && isNaN(vsupmin.value) === false) {
                    vsupmin = parseFloat(vsupmin.value);
                }
                if (vsupmax !== undefined && vsupmax !== null && isNaN(vsupmax.value) === false) {
                    vsupmax = parseFloat(vsupmax.value);
                }
                if (valtmin !== undefined && valtmin !== null && isNaN(valtmin.value) === false) {
                    valtmin = parseFloat(valtmin.value);
                }
                if (valtmax !== undefined && valtmax !== null && isNaN(valtmax.value) === false) {
                    valtmax = parseFloat(valtmax.value);
                }
                if (vbasmin !== undefined && vbasmin !== null && isNaN(vbasmin.value) === false) {
                    vbasmin = parseFloat(vbasmin.value);
                }
                if (vbasmax !== undefined && vbasmax !== null && isNaN(vbasmax.value) === false) {
                    vbasmax = parseFloat(vbasmax.value);
                }
                if (vbajmin !== undefined && vbajmin !== null && isNaN(vbajmin.value) === false) {
                    vbajmin = parseFloat(vbajmin.value);
                }
                if (vbajmax !== undefined && vbajmax !== null && isNaN(vbajmax.value) === false) {
                    vbajmax = parseFloat(vbajmax.value);
                }

                console.log('Valor NC: ' + ncvalue);
                if (ncvalue >= vsupmin && ncvalue <= vsupmax) {
                    console.log('Valor de NC es Superior.');
                    if (lcsuperior !== undefined && lcsuperior !== null && lcsuperior.value !== '') {
                        logroc.value = lcsuperior.value;
                    }
                }
                if (ncvalue >= valtmin && ncvalue <= valtmax) {
                    console.log('Valor de NC es Alto.');
                    if (lcalto !== undefined && lcalto !== null && lcalto.value !== '') {
                        logroc.value = lcalto.value;
                    }
                }
                if (ncvalue >= vbasmin && ncvalue <= vbasmax) {
                    console.log('Valor de NC es Basico.');
                    if (lcbasico !== undefined && lcbasico !== null && lcbasico.value !== '') {
                        logroc.value = lcbasico.value;
                    }
                }
                if (ncvalue >= vbajmin && ncvalue <= vbajmax) {
                    console.log('Valor de NC es Bajo.');
                    if (lcbajo !== undefined && lcbajo !== null && lcbajo.value !== '') {
                        logroc.value = lcbajo.value;
                    }
                }
            }

        }
    }
}

function setLogroPAutomatico(NP) {
    var tr = null;
    var npvalue = null;
    var logrop = null;
    var vsupmin = document.getElementById('valsupmin_configuracion');
    var vsupmax = document.getElementById('valsupmax_configuracion');
    var valtmin = document.getElementById('valaltomin_configuracion');
    var valtmax = document.getElementById('valaltomax_configuracion');
    var vbasmin = document.getElementById('valbasmin_configuracion');
    var vbasmax = document.getElementById('valbasmax_configuracion');
    var vbajmin = document.getElementById('valbajomin_configuracion');
    var vbajmax = document.getElementById('valbajomax_configuracion');
    var lpsuperior = document.getElementById('lpsuperior');
    var lpalto = document.getElementById('lpalto');
    var lpbasico = document.getElementById('lpbasico');
    var lpbajo = document.getElementById('lpbajo');

    if (NP !== undefined && NP !== null && NP.value !== '') {
        if (isNaN(NP.value) === false) {
            npvalue = parseFloat(NP.value);
            tr = getParentTR(NP);
            if (tr !== undefined && tr !== null) {
                logrop = getElementByName(tr, 'logrop_calificacion[]');
            }
            if (logrop !== undefined && logrop !== null && ValidarConfiguracionEscuela()) {
                console.log('Seteando LogroP o L2.');
                if (vsupmin !== undefined && vsupmin !== null && isNaN(vsupmin.value) === false) {
                    vsupmin = parseFloat(vsupmin.value);
                }
                if (vsupmax !== undefined && vsupmax !== null && isNaN(vsupmax.value) === false) {
                    vsupmax = parseFloat(vsupmax.value);
                }
                if (valtmin !== undefined && valtmin !== null && isNaN(valtmin.value) === false) {
                    valtmin = parseFloat(valtmin.value);
                }
                if (valtmax !== undefined && valtmax !== null && isNaN(valtmax.value) === false) {
                    valtmax = parseFloat(valtmax.value);
                }
                if (vbasmin !== undefined && vbasmin !== null && isNaN(vbasmin.value) === false) {
                    vbasmin = parseFloat(vbasmin.value);
                }
                if (vbasmax !== undefined && vbasmax !== null && isNaN(vbasmax.value) === false) {
                    vbasmax = parseFloat(vbasmax.value);
                }
                if (vbajmin !== undefined && vbajmin !== null && isNaN(vbajmin.value) === false) {
                    vbajmin = parseFloat(vbajmin.value);
                }
                if (vbajmax !== undefined && vbajmax !== null && isNaN(vbajmax.value) === false) {
                    vbajmax = parseFloat(vbajmax.value);
                }

                console.log('Valor NP: ' + npvalue);
                if (npvalue >= vsupmin && npvalue <= vsupmax) {
                    console.log('Valor de NP es Superior.');
                    if (lpsuperior !== undefined && lpsuperior !== null && lpsuperior.value !== '') {
                        logrop.value = lpsuperior.value;
                    }
                }
                if (npvalue >= valtmin && npvalue <= valtmax) {
                    console.log('Valor de NP es Alto.');
                    if (lpalto !== undefined && lpalto !== null && lpalto.value !== '') {
                        logrop.value = lpalto.value;
                    }
                }
                if (npvalue >= vbasmin && npvalue <= vbasmax) {
                    console.log('Valor de NP es Basico.');
                    if (lpbasico !== undefined && lpbasico !== null && lpbasico.value !== '') {
                        logrop.value = lpbasico.value;
                    }
                }
                if (npvalue >= vbajmin && npvalue <= vbajmax) {
                    console.log('Valor de NP es Bajo.');
                    if (lpbajo !== undefined && lpbajo !== null && lpbajo.value !== '') {
                        logrop.value = lpbajo.value;
                    }
                }
            }

        }
    }
}

function setLogroAAutomatico(NA) {
    var tr = null;
    var navalue = null;
    var logroa = null;
    var vsupmin = document.getElementById('valsupmin_configuracion');
    var vsupmax = document.getElementById('valsupmax_configuracion');
    var valtmin = document.getElementById('valaltomin_configuracion');
    var valtmax = document.getElementById('valaltomax_configuracion');
    var vbasmin = document.getElementById('valbasmin_configuracion');
    var vbasmax = document.getElementById('valbasmax_configuracion');
    var vbajmin = document.getElementById('valbajomin_configuracion');
    var vbajmax = document.getElementById('valbajomax_configuracion');
    var lasuperior = document.getElementById('lasuperior');
    var laalto = document.getElementById('laalto');
    var labasico = document.getElementById('labasico');
    var labajo = document.getElementById('labajo');

    if (NA !== undefined && NA !== null && NA.value !== '') {
        if (isNaN(NA.value) === false) {
            navalue = parseFloat(NA.value);
            tr = getParentTR(NA);
            if (tr !== undefined && tr !== null) {
                logroa = getElementByName(tr, 'logroa_calificacion[]');
            }
            if (logroa !== undefined && logroa !== null && ValidarConfiguracionEscuela()) {
                console.log('Seteando LogroA o L3.');
                if (vsupmin !== undefined && vsupmin !== null && isNaN(vsupmin.value) === false) {
                    vsupmin = parseFloat(vsupmin.value);
                }
                if (vsupmax !== undefined && vsupmax !== null && isNaN(vsupmax.value) === false) {
                    vsupmax = parseFloat(vsupmax.value);
                }
                if (valtmin !== undefined && valtmin !== null && isNaN(valtmin.value) === false) {
                    valtmin = parseFloat(valtmin.value);
                }
                if (valtmax !== undefined && valtmax !== null && isNaN(valtmax.value) === false) {
                    valtmax = parseFloat(valtmax.value);
                }
                if (vbasmin !== undefined && vbasmin !== null && isNaN(vbasmin.value) === false) {
                    vbasmin = parseFloat(vbasmin.value);
                }
                if (vbasmax !== undefined && vbasmax !== null && isNaN(vbasmax.value) === false) {
                    vbasmax = parseFloat(vbasmax.value);
                }
                if (vbajmin !== undefined && vbajmin !== null && isNaN(vbajmin.value) === false) {
                    vbajmin = parseFloat(vbajmin.value);
                }
                if (vbajmax !== undefined && vbajmax !== null && isNaN(vbajmax.value) === false) {
                    vbajmax = parseFloat(vbajmax.value);
                }

                console.log('Valor NA: ' + navalue);
                if (navalue >= vsupmin && navalue <= vsupmax) {
                    console.log('Valor de NA es Superior.');
                    if (lasuperior !== undefined && lasuperior !== null && lasuperior.value !== '') {
                        logroa.value = lasuperior.value;
                    }
                }
                if (navalue >= valtmin && navalue <= valtmax) {
                    console.log('Valor de NA es Alto.');
                    if (laalto !== undefined && laalto !== null && laalto.value !== '') {
                        logroa.value = laalto.value;
                    }
                }
                if (navalue >= vbasmin && navalue <= vbasmax) {
                    console.log('Valor de NA es Basico.');
                    if (labasico !== undefined && labasico !== null && labasico.value !== '') {
                        logroa.value = labasico.value;
                    }
                }
                if (navalue >= vbajmin && navalue <= vbajmax) {
                    console.log('Valor de NA es Bajo.');
                    if (labajo !== undefined && labajo !== null && labajo.value !== '') {
                        logroa.value = labajo.value;
                    }
                }
            }

        }
    }
}


