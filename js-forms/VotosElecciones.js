/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    var imgcandidato = document.getElementById("imgcandidato");
    imgcandidato.src = getWSPath() + "ImageFiles/{{foto_candidato}}";
    LoadEleccion();
    LoadCargo();
});

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
    return mytable;
}

function LoadEleccion() {
    var eleccion = null;
    eleccion = document.getElementById("id_eleccion");
    eleccion.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(eleccion);
}

function LoadCargo() {
    var cargo = null;
    cargo = document.getElementById("id_cargo");
    cargo.innerHTML = '<option value="">Ninguna</option>';
    loadComboboxData(cargo);
}

function setWhere() {
    var table0 = null;
    var idcargo = null;
    var ideleccion = null;
    table0 = document.getElementById("dataTable0");
    idcargo = document.getElementById("id_cargo");
    ideleccion = document.getElementById("id_eleccion");
    if (idcargo !== null && ideleccion !== null) {
        if (idcargo.value !== "" && ideleccion.value !== "") {
            table0.setAttribute('findby1', idcargo.id);
            table0.setAttribute('findbyvalue1', idcargo.value);
            table0.setAttribute('findby2', ideleccion.id);
            table0.setAttribute('findbyvalue2', ideleccion.value);
        }
    }
}

function filtrar(item) {
    var form0 = null;
    form0 = getForm(item);
    if (form0 !== null) {
        if (validateForm(form0)) {
            LoadTable();
        }
    }
}

function EnviarVoto(item) {
    var myform = null;
    myform = getForm(item);
    if (validateForm(myform)) {
        submitForm(myform, false).done(function () {
            setTimeout(function () {
                var rowcount = window.sessionStorage.getItem('rowCount');
                var votook = document.getElementById('showOk');
                rowcount = parseFloat(rowcount);
                if (rowcount !== undefined && rowcount !== null && rowcount > 0) {
                    votook.click();
                } else {
                    alert("Hubo un error. Informe a los Jurados de Votacion!");
                }
            }, 100);
        });
    }
}

