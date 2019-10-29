/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function ChangePassword(item) {
    var form = getForm(item);
    var newpw = getElement(form, 'newpassword_usuario');
    var newpw2 = getElement(form, 'newpassword2_usuario');
    if (validateForm(form)) {
        if (newpw.value == newpw2.value) {
            submitForm(item, false).done(function () {
                
            });
        } else {
            alert('La Confirmacion de Contraseña no Concuerda con la Nueva Contraseña!.');
        }
    }
}

