jQuery(document).ready(function () {
    resetLocalPOST();
    document.getElementById("mynickname").value = getUsernameLogin();
    document.getElementById("mypassword").value = getPasswordLogin();
    loadComboboxData(document.getElementById("id_tipousuario"));
    LoadEscuela();
    LoadSede();
    if (getUserIdLogin() !== null) {
        window.location.href = 'home.html';
    }
});

function LoadEscuela(){
    var escuela = null;
    escuela = document.getElementById("id_escuela");
    escuela.innerHTML='<option value="">Ninguna</option>';
    loadComboboxData(escuela);
}

function LoadSede(){
    var sede = null;
    sede = document.getElementById("id_sede");
    sede.innerHTML='<option disabled="disabled" value="">Ninguna</option>';
    loadComboboxData(sede);
}

function setFinbySede(findby) {
    if (findby !== null && findby !== '') {
        var sede = document.getElementById("id_sede");
        sede.setAttribute('findby', findby);
    }
}

function setFinbyValueSede(findbyvalue) {
    if (findbyvalue !== null && findbyvalue !== '') {
        var sede = document.getElementById("id_sede");
        sede.setAttribute('findbyvalue', findbyvalue);
    }
}

function RefreshSede(item) {
    if (item !== undefined && item !== null) {
        console.log('Filtrando por ' + item.id);
        setFinbySede('id_escuela');
        if (item.selected !== undefined && item.selected !== null) {
            setFinbyValueSede(item.selected);
        } else {
            setFinbyValueSede(item.value);
        }
        LoadSede();
    }
}

function Send(item) {
    var form = document.getElementById("FormLogin");
    var enterprise = document.getElementById("id_escuela");
    if (validateForm(form)) {
        login(form, null).done(function () {
            setTimeout(function () {
                console.log(getErrorMessage());
                setUsernameLogin(document.getElementById("mynickname").value);
                if (enterprise.selected !== undefined && enterprise.selected !== null) {
                    setIdEnterprise(enterprise.selected);
                    setNameEnterprise(enterprise.getAttribute('text'));
                } else {
                    setIdEnterprise(enterprise.value);
                    setNameEnterprise(enterprise.getAttribute('text'));
                }
                if (getUserIdLogin() !== null) {
                    window.location.href = 'home.html';
                }
            }, 0);
        });
    }
}