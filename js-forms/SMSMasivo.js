/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    loadComboboxData(document.getElementById('id_programa'));
    loadComboboxData(document.getElementById('id_periodo'));
});

function setFindby() {
    var programa = document.getElementById('id_programa');
    var grado = document.getElementById('numgrado_programa');
    var grupo = document.getElementById('id_grupo');
    var periodo = document.getElementById('id_periodo');
    var mytable = document.getElementById("dataTable0");
    setFindbyField(mytable.id, programa.id, programa.value, 1);
    setFindbyField(mytable.id, grado.id, grado.value, 2);
    setFindbyField(mytable.id, grupo.id, grupo.value, 3);
    setFindbyField(mytable.id, periodo.id, periodo.value, 4);
    LoadTable();
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}

function MarcarContacto(item) {
    if (item !== null) {
        var tr = getParentTR(item);
        if (item.value === '1') {
            removeAttributeDisabled(tr);
        }
        if (item.value === '0') {
            addAttributeDisabled(tr);
        }
    }
}

function setResultadosWauSMS(data) {
    var tableR = document.getElementById('dataTableResultados');
    if (data !== undefined && data !== null) {
        clearTableData(tableR);
        data = JSON.parse(data);
        if (data.error !== undefined && data.error.description !== undefined && data.error.description !== '') {
            alert('Codigo: ' + data.error.code + ' - Error: ' + data.error.description);
        } else {
            alert('Se ha enviado el Mensaje por medio de WauSMS!.');
        }
        setTableData(tableR, data, false);
        document.getElementById('resultadoWauSMS').click();
    }
}

function setResultadosLabsMobile(data) {
    if (data !== undefined && data !== null) {
        data = JSON.parse(data);
        if (data.code !== undefined && data.subid !== undefined) {
            if (data.code === 0 || data.code === '0') {
                alert('Se ha enviado el Mensaje por medio de LabsMobile!.');
            }else{
                alert('Codigo: ' + data.code + ' - Error: ' + data.message);
            }
        }
    }
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        submitForm(form, false).done(function () {
            var server = document.getElementById('server_sms');
            var data = sessionStorage.getItem('data');
            if (data !== undefined && data !== null) {
                if (getComboboxValue(server) === 'WauSMS') {
                    setResultadosWauSMS(sessionStorage.getItem('data'));
                }
                if (getComboboxValue(server) === 'LabsMobile') {
                    setResultadosLabsMobile(sessionStorage.getItem('data'));
                }
            } else {
                alert(getErrorMessage());
            }
        });
    }
}

