/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    LoadTable();
    LoadEleccion();
    LoadCargo();
    autoNameFromDataList('id_persona', 'nombrecompleto_candidato', null, null);
    CargarFrameFoto();
});

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

function LoadPersona() {
    var persona = null;
    var periodo = null;
    var gradominimo = null;
    persona = document.getElementById("lista_id_persona");
    periodo = document.getElementById("id_periodo");
    gradominimo = document.getElementById("gradominimo_cargo");
    
    persona.innerHTML = '<option value="">Ninguna</option>';
    persona.setAttribute('findby1','id_periodo');
    persona.setAttribute('findbyvalue1',periodo.value);
    persona.setAttribute('findby2','numgrado_programa');
    persona.setAttribute('findbyvalue2',gradominimo.value);
    
    loadComboboxData(persona);
}

function LoadTable() {
    var mytable = document.getElementById("dataTable0");
    loadTableData(mytable, false);
}

function CargarFrameFoto() {
    console.log('Cargando Margo de Foto!.');
    var prefix="Candidato";
    var form0 = document.getElementById("form0");
    var idestudiante = getElement(form0, "id_persona");
    var frameFoto = document.getElementById("frameFoto");
    var foto = document.getElementById("foto_candidato");
    foto.value = prefix + idestudiante.value + ".jpg";
    frameFoto.src = "UploadImageForm.html?prefix="+prefix+"&id=" + idestudiante.value + "&img=" + foto.value;
}

function Send(item) {
    var form = getForm(item);
    if (validateForm(form)) {
        CargarFrameFoto();
        submitForm(item, false).done(function () {
            LoadTable();
        });
    }
}

function Edit(item) {
    var myform = null;
    myform = document.getElementById('form0');
    resetForm(myform);
    sendValue(item, null, myform, null);
    getData(myform).done(function () {
        setTimeout(function () {
            CargarFrameFoto();
        }, 1);
    });
}

function DeleteItem(item) {
    if (confirm('Desea eliminar este Registro?')) {
        var form = getForm(item);
        var tr = getParentTR(item);
        var mytable = getParentTable(item);
        var id = null;
        var status = null;
        var rowcount = 0;
        if (tr !== null && tr !== undefined) {
            id = getElement(tr, getFindBy(form));
            status = getElement(tr, getStatusFieldName(form));
        }
        if (id !== null && status !== null && mytable !== null) {
            console.log('Tratando de Eliminar id: ' + id);
            addAttributeDisabled(mytable);
            removeAttributeDisabled(tr);
            status.value = '0';
            submitForm(form, false).done(function () {
                rowcount = window.sessionStorage.getItem('rowCount');
                rowcount = parseFloat(rowcount);
                if (rowcount !== undefined && rowcount !== null && rowcount > 0) {
                    deleteRowInTable(mytable);
                } else {
                    status.value = '1';
                }
            });
        }
    }
}
