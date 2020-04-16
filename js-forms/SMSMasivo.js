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
            showNotification('Telefono', 'Esta opcion requiere conectarse a tu Telefono y requiere tener saldo o SMS Ilimitado en tu Plan Movil.', 8000);
        }
        if (getComboboxValue(server) === 'WauSMS') {
            username.setAttribute('required', 'required');
            password.setAttribute('required', 'required');
            showNotification('WauSMS', 'Esta opcion requiere que usted tenga Cuenta y Saldo en WauSMS.', 8000);
        }
        if (getComboboxValue(server) === 'LabsMobile') {
            username.setAttribute('required', 'required');
            password.setAttribute('required', 'required');
            showNotification('LabsMobile', 'Esta opcion requiere que usted tenga Cuenta y Saldo en LabsMobile.', 8000);
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
            console.log('Numero Valido: ' + number);
            return true;
        }
    }
    console.log('Numero Invalido: ' + number);
    return false;
}

function openSMSLink(to, message) {
    if (to !== null && to !== '' && message !== null && message !== '') {
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;
        var href = '';
        if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            console.log('IPhone');
            href = 'sms:' + to + ';body=' + message;
            window.open(href, '_self');
        } else {
            console.log('Android/WindowsPhone');
            href = 'sms:' + to + '?body=' + message;
            window.open(href, '_self');
        }
    }
}

function sendMyDevice() {
    var message = document.getElementById('mensaje_sms');
    var indicativo = document.getElementById('indicativo_sms');
    var destino = document.getElementById('destino_sms');
    var telefono1 = document.getElementsByName('telefono_persona[]');
    var telefono2 = document.getElementsByName('telefonoacudiente1_estudiante[]');
    if (message !== undefined && message.value !== '') {
        console.log('Construyendo Mensaje.');
        var i = null;
        var tels = '';
        if (validatePhoneNumber(destino.value)) {
            tels = tels + indicativo.value + destino.value + ',';
        }
        i = 0;
        while (telefono1[i] !== undefined && telefono1[i] !== null) {
            if ((telefono1[i].getAttribute('disabled') === null || telefono1[i].getAttribute('disabled') === undefined) && validatePhoneNumber(telefono1[i].value)) {
                tels = tels + indicativo.value + telefono1[i].value + ',';
            }
            i++;
        }
        i = 0;
        while (telefono2[i] !== undefined && telefono2[i] !== null) {
            if ((telefono2[i].getAttribute('disabled') === null || telefono2[i].getAttribute('disabled') === undefined) && validatePhoneNumber(telefono2[i].value)) {
                tels = tels + indicativo.value + telefono2[i].value + ',';
            }
            i++;
        }
        tels = tels.toString().substring(0, tels.toString().length - 1);
        console.log('Destino: ' + tels);
        openSMSLink(tels, message.value);
    }
}

function selectAllPhones() {
    var check = document.getElementsByName('check[]');
    if (check !== undefined && check !== null) {
        console.log('Selecting all Contascts');
        var i = null;
        i = 0;
        while (check[i] !== undefined && check[i] !== null) {
            if (check[i].getAttribute('uncheckedvalue') !== undefined && check[i].getAttribute('uncheckedvalue') !== null && check[i].value === check[i].getAttribute('uncheckedvalue')) {
                check[i].click();
            }
            i++;
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
            if (confirm('¿Desea enviar el SMS por medio de su Telefono?')) {
                sendMyDevice();
            }
        }
    }
}

