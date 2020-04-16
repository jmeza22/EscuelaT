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
    setFindByField(mytable.id, programa.id, programa.value, 1);
    setFindByField(mytable.id, grado.id, grado.value, 2);
    setFindByField(mytable.id, grupo.id, grupo.value, 3);
    setFindByField(mytable.id, periodo.id, periodo.value, 4);
    LoadTable();
}

function setRequireds() {
    var server = document.getElementById('server_sms');
    if (server !== null && server !== undefined && server.tagName === 'SELECT') {
        var username = document.getElementById('username_sms');
        var password = document.getElementById('password_sms');
        username.removeAttribute('required');
        password.removeAttribute('required');
        if (getComboboxValue(server) === 'MyDevice') {

        }
        if (getComboboxValue(server) === 'WauSMS') {
            username.setAttribute('required', 'required');
            password.setAttribute('required', 'required');
        }
        if (getComboboxValue(server) === 'LabsMobile') {
            username.setAttribute('required', 'required');
            password.setAttribute('required', 'required');
        }
    }
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
            } else {
                alert('Codigo: ' + data.code + ' - Error: ' + data.message);
            }
        }
    }
}

function validatePhoneNumber(number) {
    if (number !== null && number !== '') {
        if (number.toString().length >= 7 && parseInt(number) > 0) {
            console.log('Numero Valido: '+number);
            return true;
        }
    }
    console.log('Numero Invalido: '+number);
    return false;
}

function sendMyDevice() {
    var myform = document.getElementById('formTable');
    var message = document.getElementById('mensaje_sms');
    var indicativo = document.getElementById('indicativo_sms');
    var destino = document.getElementById('destino_sms');
    var telefono1 = document.getElementsByName('telefono_persona[]');
    var telefono2 = document.getElementsByName('telefonoacudiente1_estudiante[]');
    if (message !== undefined && message.value !== '') {
        if (validatePhoneNumber(destino.value)) {
            console.log('Enviando Mensaje.');
            sendSMS(indicativo.value.toString() + destino.value.toString(), message.value.toString());
        }
    }
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        var server = document.getElementById('server_sms');
        if (getComboboxValue(server) !== 'MyDevice') {
            submitForm(form, false).done(function () {
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
        if (getComboboxValue(server) === 'MyDevice') {
            if (confirm('¿Desea enviar el SMS por medio de su Dispositivo?')) {
                sendMyDevice();
            }
        }
    }
}

