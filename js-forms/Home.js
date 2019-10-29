/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
});

function logoutSystem() {
    if (confirm('Desea salir del Sistema?')) {
        window.location = 'logout.html';
        return true;
    }
    return false;
}