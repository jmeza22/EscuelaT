/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function () {
    setWebServicePath();
});

function setWebServicePath() {
    var path = null;
    if (LocalStorageStatus()) {
        path = "http://localhost/EscuelaT/";
        if (path !== null && path !== "") {
            localStorage.setItem("WebServicePath", path);
            return true;
        } else {
            return false;
        }
    }
    return false;
}
