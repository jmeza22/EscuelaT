/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    loadComboboxData(document.getElementById("lista_id_escuela")).done(function () {
    });
    setIdEscuela();
    var idescuela = document.getElementById('id_escuela');
    if (idescuela !== undefined && idescuela !== null && idescuela.value !== '') {
        console.log('Buscando Datos de Configuracion.');
        getData(idescuela);
    }
});

function setIdEscuela() {
    var item = null;
    item = document.getElementById('id_escuela');
    var item2 = null;
    item2 = document.getElementById('nombremostrar_configuracion');
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Escuela.');
        if (getEnterpriseID() !== null) {
            item.value = getEnterpriseID();
        }
        if (getEnterpriseName() !== null) {
            item2.value = getEnterpriseName();
        }
        item.focus();
    }
}

function ValidarValoracion() {
    var vmin = document.getElementById('valmin_configuracion');
    var vmax = document.getElementById('valmax_configuracion');
    var vap = document.getElementById('valaprueba_configuracion');
    var vsupmin = document.getElementById('valsupmin_configuracion');
    var vsupmax = document.getElementById('valsupmax_configuracion');
    var valtmin = document.getElementById('valaltomin_configuracion');
    var valtmax = document.getElementById('valaltomax_configuracion');
    var vbasmin = document.getElementById('valbasmin_configuracion');
    var vbasmax = document.getElementById('valbasmax_configuracion');
    var vbajmin = document.getElementById('valbajomin_configuracion');
    var vbajmax = document.getElementById('valbajomax_configuracion');

    if (vmin !== undefined && vmin !== null && isNaN(vmin.value) === false) {
        vmin = parseFloat(vmin.value);
    } else {
        return false;
    }
    if (vmax !== undefined && vmax !== null && isNaN(vmax.value) === false) {
        vmax = parseFloat(vmax.value);
    } else {
        return false;
    }
    if (vap !== undefined && vap !== null && isNaN(vap.value) === false) {
        vap = parseFloat(vap.value);
    } else {
        return false;
    }
    if (vsupmin !== undefined && vsupmin !== null && isNaN(vsupmin.value) === false) {
        vsupmin = parseFloat(vsupmin.value);
    } else {
        return false;
    }
    if (vsupmax !== undefined && vsupmax !== null && isNaN(vsupmax.value) === false) {
        vsupmax = parseFloat(vsupmax.value);
    } else {
        return false;
    }
    if (valtmin !== undefined && valtmin !== null && isNaN(valtmin.value) === false) {
        valtmin = parseFloat(valtmin.value);
    } else {
        return false;
    }
    if (valtmax !== undefined && valtmax !== null && isNaN(valtmax.value) === false) {
        valtmax = parseFloat(valtmax.value);
    } else {
        return false;
    }
    if (vbasmin !== undefined && vbasmin !== null && isNaN(vbasmin.value) === false) {
        vbasmin = parseFloat(vbasmin.value);
    } else {
        return false;
    }
    if (vbasmax !== undefined && vbasmax !== null && isNaN(vbasmax.value) === false) {
        vbasmax = parseFloat(vbasmax.value);
    } else {
        return false;
    }
    if (vbajmin !== undefined && vbajmin !== null && isNaN(vbajmin.value) === false) {
        vbajmin = parseFloat(vbajmin.value);
    } else {
        return false;
    }
    if (vbajmax !== undefined && vbajmax !== null && isNaN(vbajmax.value) === false) {
        vbajmax = parseFloat(vbajmax.value);
    } else {
        return false;
    }

    if (vmin < 0 || vmin > 1000) {
        alert('Valor Minimo Fuera de Rango (0 - 1000)');
        return false;
    }
    if (vmax < 0 || vmax > 1000) {
        alert('Valor Maximo Fuera de Rango (0 - 1000)');
        return false;
    }
    if (vmin > vmax) {
        alert('Valor Maximo debe ser Mayor que Valor Minimo');
        console.log('Rango: (' + vmin + ' - ' + vmax + ')');
        return false;
    }
    if (vap < vmin || vap > vmax) {
        alert('Valor Aprueba debe ser (Mayor que Valor Minimo) y (Menor que Valor Maximo)');
        return false;
    }
    if ((vsupmax < vmin || vsupmax > vmax)) {
        alert('Val.Max.Sup debe estar entre Valor Minimo y Valor Maximo');
        return false;
    }
    if ((vsupmin < vmin || vsupmin > vmax) || vsupmin > vsupmax || vsupmin < valtmax) {
        alert('Val.Min.Sup debe estar entre Valor Minimo y Valor Maximo. \nDebe ser mayor que Val.Max.Alto. \nDebe ser menor que Val.Max.Sup.');
        return false;
    }
    if ((valtmax < vmin || valtmax > vmax)) {
        alert('Val.Max.Alto debe estar entre Valor Minimo y Valor Maximo');
        return false;
    }
    if ((valtmin < vmin || valtmin > vmax) || valtmin > valtmax || valtmin < vbasmax) {
        alert('Val.Min.Alto debe estar entre Valor Minimo y Valor Maximo. \nDebe ser mayor que Val.Max.Basico. \nDebe ser menor que Val.Max.Alto.');
        return false;
    }
    if ((vbasmax < vmin || vbasmax > vmax)) {
        alert('Val.Max.Basico debe estar entre Valor Minimo y Valor Maximo');
        return false;
    }
    if ((vbasmin < vmin || vbasmin > vmax) || vbasmin > vbasmax || vbasmin < vbajmax) {
        alert('Val.Min.Basico debe estar entre Valor Minimo y Valor Maximo. \nDebe ser mayor que Val.Max.Bajo. \nDebe ser menor que Val.Max.Basico.');
        return false;
    }
    if ((vbajmax < vmin || vbajmax > vmax)) {
        alert('Val.Max.Bajo debe estar entre Valor Minimo y Valor Maximo');
        return false;
    }
    if ((vbajmin < vmin || vbajmin > vmax) || vbajmin > vbajmax) {
        alert('Val.Min.Bajo debe estar entre Valor Minimo y Valor Maximo. Debe ser menor que Val.Max.Bajo.');
        return false;
    }

    return true;
}

function ValidarPorcentajesCortes() {
    var numcortes = document.getElementById('numcortes_configuracion');
    var p1 = document.getElementById('p1_porcentaje_configuracion');
    var p2 = document.getElementById('p2_porcentaje_configuracion');
    var p3 = document.getElementById('p3_porcentaje_configuracion');
    var p4 = document.getElementById('p4_porcentaje_configuracion');
    var p5 = document.getElementById('p5_porcentaje_configuracion');
    var p6 = document.getElementById('p6_porcentaje_configuracion');
    if (numcortes !== undefined && numcortes !== null && p1 !== undefined && p1 !== null && p2 !== undefined && p2 !== null && p3 !== undefined && p3 !== null && p4 !== undefined && p4 !== null && p5 !== undefined && p5 !== null && p6 !== undefined && p6 !== null) {
        if (!isNaN(numcortes.value)) {
            if (parseFloat(numcortes.value) >= 1) {
                p1.removeAttribute('readonly');
            } else {
                p1.value = '0';
                p1.setAttribute('readonly', 'readonly');
            }
            if (parseFloat(numcortes.value) >= 2) {
                p2.removeAttribute('readonly');
            } else {
                p2.value = '0';
                p2.setAttribute('readonly', 'readonly');
            }
            if (parseFloat(numcortes.value) >= 3) {
                p3.removeAttribute('readonly');
            } else {
                p3.value = '0';
                p3.setAttribute('readonly', 'readonly');
            }
            if (parseFloat(numcortes.value) >= 4) {
                p4.removeAttribute('readonly');
            } else {
                p4.value = '0';
                p4.setAttribute('readonly', 'readonly');
            }
            if (parseFloat(numcortes.value) >= 5) {
                p5.removeAttribute('readonly');
            } else {
                p5.value = '0';
                p5.setAttribute('readonly', 'readonly');
            }
            if (parseFloat(numcortes.value) === 6) {
                p6.removeAttribute('readonly');
            } else {
                p6.value = '0';
                p6.setAttribute('readonly', 'readonly');
            }
        }
        if (isNaN(p1.value)) {
            alert('Formato Invalido Periodo 1');
            return false;
        }
        if (isNaN(p2.value)) {
            alert('Formato Invalido Periodo 2');
            return false;
        }
        if (isNaN(p3.value)) {
            alert('Formato Invalido Periodo 3');
            return false;
        }
        if (isNaN(p4.value)) {
            alert('Formato Invalido Periodo 4');
            return false;
        }
        if (isNaN(p5.value)) {
            alert('Formato Invalido Periodo 5');
            return false;
        }
        if (isNaN(p5.value)) {
            alert('Formato Invalido Periodo 6');
            return false;
        }
        p1 = p1.value;
        p2 = p2.value;
        p3 = p3.value;
        p4 = p4.value;
        p5 = p5.value;
        p6 = p6.value;
        p1 = parseFloat(p1);
        p2 = parseFloat(p2);
        p3 = parseFloat(p3);
        p4 = parseFloat(p4);
        p5 = parseFloat(p5);
        p6 = parseFloat(p6);
        if (p1 < 0 || p1 > 100) {
            alert('Porcentaje Invalido Periodo 1');
            return false;
        }
        if (p2 < 0 || p2 > 100) {
            alert('Porcentaje Invalido Periodo 2');
            return false;
        }
        if (p3 < 0 || p3 > 100) {
            alert('Porcentaje Invalido Periodo 3');
            return false;
        }
        if (p4 < 0 || p4 > 100) {
            alert('Porcentaje Invalido Periodo 4');
            return false;
        }
        if (p5 < 0 || p5 > 100) {
            alert('Porcentaje Invalido Periodo 5');
            return false;
        }
        if (p6 < 0 || p6 > 100) {
            alert('Porcentaje Invalido Periodo 6');
            return false;
        }
        if (parseFloat(p1 + p2 + p3 + p4 + p5 + p6) !== 100) {
            alert('La Suma de los Porcentajes de los Periodos debe ser Igual a 100%.');
            return false;
        } else {
            return true;
        }

    }
    return false;
}

function ValidarPorcentajesComponentes() {
    var cog = document.getElementById('porcentajecognitivo_configuracion');
    var pro = document.getElementById('porcentajeprocedimental_configuracion');
    var act = document.getElementById('porcentajeactitudinal_configuracion');
    cog=parseFloat(cog.value);
    pro=parseFloat(pro.value);
    act=parseFloat(act.value);
    if (parseFloat(cog + pro + act) !== 100) {
        alert('La Suma de los Porcentajes de los Componentes debe ser Igual a 100%.');
        return false;
    } else {
        return true;
    }
}

function MostrarLogo() {
    var idescuela = null;
    var logo = null;
    var img = null;
    var nameimage = null;
    idescuela = document.getElementById('id_escuela');
    logo = document.getElementById('logo_configuracion');
    logo.value = 'Escuela' + idescuela.value + '.png';
    window.open("UploadImageForm.html?prefix=Escuela" + "&id=" + idescuela.value + "&img=" + logo.value + "", "Subir una Imagen al Servidor - EscuelaT", "width=600,height=600,scrollbars=NO");
}

function SendConfiguracion() {
    var form = document.getElementById('form0');
    var idescuela = getElement(form, 'id_escuela');
    var logo = document.getElementById('logo_configuracion');
    if (validateForm(form) && ValidarPorcentajesCortes() && ValidarPorcentajesComponentes() && ValidarValoracion()) {
        logo.value = 'Escuela' + idescuela.value + '.jpg';
        submitForm(form, false);
    }
}
