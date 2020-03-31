/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {

    ObtenerEncabezado();

});

function setIdEscuela() {
    var item = null;
    item = document.getElementById('id_escuela');
    if (item !== null && item !== undefined && item.value === '') {
        console.log('Seteando Id Escuela.');
        if (getEnterpriseID() !== null) {
            item.value = getEnterpriseID();
        }
    }
}

function ObtenerEncabezado() {
    var idmatricula = null;
    var table0=null;
    idmatricula = document.getElementById('id_matricula');
    table0 = document.getElementById('dataTable0');
    getFormData(idmatricula).done(function () {
        if(table0!==undefined && table0!==null){
            table0.setAttribute('findby',idmatricula.id);
            table0.setAttribute('findbyvalue',idmatricula.value);
            LoadTable();
        }
        
    });
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}
