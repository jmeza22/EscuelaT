/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {

});

function Send(item) {
    var idpersona = document.getElementById('id_persona');
    if (validateForm(item)) {
        if (idpersona.value === '0' || idpersona.value === '') {
            nuevoId();
        }
        submitForm(item, false);
    }
}

function nuevoId() {
    var nombre1 = null;
    var apellido1 = null;
    var tipodoc = null;
    var fecha = null;
    var nuevo = null;
    if (document.getElementById('nombre1_persona') !== null && document.getElementById('nombre1_persona') !== undefined) {
        nombre1 = document.getElementById('nombre1_persona').value;
    }
    if (document.getElementById('apellido1_persona') !== null && document.getElementById('apellido1_persona') !== undefined) {
        apellido1 = document.getElementById('apellido1_persona').value;
    }
    if (document.getElementById('tipodoc_persona') !== null && document.getElementById('tipodoc_persona') !== undefined) {
        tipodoc = document.getElementById('tipodoc_persona');
        tipodoc = tipodoc.value;
    }
    fecha = new Date();
    if (nombre1 !== null && nombre1 !== "" && apellido1 !== null && apellido1 !== "" && tipodoc !== null && fecha !== null) {
        if (document.getElementById('id_persona') !== undefined) {
            nuevo = fecha.getFullYear().toString()+(fecha.getMonth()+1)+(fecha.getDate()*getRandomNumber(0,100000)) ;
            console.log('Generando Id: ' + nuevo);
            document.getElementById('id_persona').value = nuevo;
            document.getElementById('show_id_persona').value = nuevo;
        }
    } else {
        alert("Debe ingresar Nombre y Apellido.");
        return false;
    }
    return true;
}

