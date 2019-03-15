/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function LocalStorageStatus() {
    if (window.sessionStorage && window.localStorage) {
        return true;
    } else {
        console.log('Your Device is not Compatible with Local Storage.');
        return false;
    }
    return false;
}

function setWebServicePath(path) {
    if (LocalStorageStatus()) {
        if (path !== null) {
            localStorage.setItem("WebServicePath", path);
            return true;
        }
    }
    return null;
}

function getWebServicePath() {
    if (LocalStorageStatus()) {
        var path = null;
        path = localStorage.getItem("WebServicePath");
        if (path === null) {
            console.log("WebServicePath is null.");
        } else {
            return path;
        }
    }
    return null;
}

function setWebServiceLocalPath(path) {
    if (LocalStorageStatus()) {
        if (path !== null) {
            localStorage.setItem("WebServiceLocalPath", path);
            return true;
        }
    }
    return null;
}

function getWebServiceLocalPath() {
    if (LocalStorageStatus()) {
        var path = null;
        path = localStorage.getItem("WebServiceLocalPath");
        if (path === null) {
            console.log("WebServiceLocalPath is null.");
        } else {
            return path;
        }
    }
    return null;
}

function setUsernameLogin(username) {
    if (LocalStorageStatus()) {
        if (username !== null && username !== '') {
            localStorage.setItem("UsernameLogin", "" + username);
            return true;
        }
    }
    return false;
}

function setPasswordLogin(password) {
    if (LocalStorageStatus()) {
        if (password !== null && password !== '') {
            localStorage.setItem("PasswordLogin", "" + password);
            return true;
        }
    }
    return false;
}

function setUserIdLogin(userid) {
    if (LocalStorageStatus()) {
        if (userid !== null && userid !== '') {
            localStorage.setItem("UserIdLogin", userid);
            return true;
        }
    }
    return false;
}

function setUserRoleLogin(userrole) {
    if (LocalStorageStatus()) {
        if (userrole !== null && userrole !== '') {
            localStorage.setItem("UserRoleLogin", userrole);
            return true;
        }
    }
    return false;
}

function setTokenLogin(token) {
    if (LocalStorageStatus()) {
        if (token !== null && token !== '') {
            localStorage.setItem("TokenLogin", token);
            return true;
        }
    }
    return false;
}

function getUsernameLogin() {
    if (LocalStorageStatus()) {
        var username = null;
        username = localStorage.getItem("UsernameLogin");
        return username;
    }
    return null;
}

function getPasswordLogin() {
    if (LocalStorageStatus()) {
        var password = null;
        password = localStorage.getItem("PasswordLogin");
        return password;
    }
    return null;
}

function getUserIdLogin() {
    if (LocalStorageStatus()) {
        var userid = null;
        userid = localStorage.getItem("UserIdLogin");
        return userid;
    }
    return null;
}

function getUserRoleLogin() {
    if (LocalStorageStatus()) {
        var userrole = null;
        userrole = localStorage.getItem("UserRoleLogin");
        return userrole;
    }
    return null;
}

function getTokenLogin() {
    if (LocalStorageStatus()) {
        var token = null;
        token = localStorage.getItem("TokenLogin");
        return token;
    }
    return null;
}

function setUserIdSession(userid) {
    if (LocalStorageStatus()) {
        if (userid !== null && userid !== '') {
            sessionStorage.setItem("UserIdSession", userid);
            return true;
        }
    }
    return false;
}

function setUsernameSession(username) {
    if (LocalStorageStatus()) {
        if (username !== null && username !== '') {
            sessionStorage.setItem("UsernameSession", username);
            return true;
        }
    }
    return false;
}

function setPasswordSession(password) {
    if (LocalStorageStatus()) {
        if (password !== null && password !== '') {
            sessionStorage.setItem("PasswordSession", password);
            return true;
        }
    }
    return false;
}

function getUserIdSession() {
    if (LocalStorageStatus()) {
        var userid = null;
        userid = sessionStorage.getItem("UserIdSession");
        return userid;
    }
    return null;
}



function getUsernameSession() {
    if (LocalStorageStatus()) {
        var username = null;
        username = sessionStorage.getItem("UsernameSession");
        return username;
    }
    return null;
}

function getPasswordSession() {
    if (LocalStorageStatus()) {
        var password = null;
        password = sessionStorage.getItem("PasswordSession");
        return password;
    }
    return null;
}

function setCode(code) {
    if (LocalStorageStatus()) {
        if (code !== null) {
            localStorage.removeItem("code");
            localStorage.setItem("code", code);
            return true;
        }
    }
    return false;
}

function getCode() {
    if (LocalStorageStatus()) {
        var code = null;
        code = localStorage.getItem("code");
        if (code === null) {
            console.log("Code is null");
        } else {
            return code;
        }
    }
    return null;
}

function setIdEnterprise(enterprise) {
    if (LocalStorageStatus()) {
        if (enterprise !== null) {
            localStorage.removeItem("IdEnterprise");
            localStorage.setItem("IdEnterprise", enterprise);
            return true;
        }
    }
    return false;
}

function getIdEnterprise() {
    if (LocalStorageStatus()) {
        var enterprise = null;
        enterprise = localStorage.getItem("IdEnterprise");
        if (enterprise === null) {
            console.log("Enterprise is null");
        } else {
            return enterprise;
        }
    }
    return null;
}

function setNameEnterprise(enterprise) {
    if (LocalStorageStatus()) {
        if (enterprise !== null) {
            localStorage.removeItem("NameEnterprise");
            localStorage.setItem("NameEnterprise", enterprise);
            return true;
        }
    }
    return false;
}

function getNameEnterprise() {
    if (LocalStorageStatus()) {
        var enterprise = null;
        enterprise = localStorage.getItem("NameEnterprise");
        if (enterprise === null) {
            console.log("Enterprise is null");
        } else {
            return enterprise;
        }
    }
    return null;
}

function setIdOrder(order) {
    if (LocalStorageStatus()) {
        if (order !== null) {
            localStorage.removeItem("IdOrder");
            localStorage.setItem("IdOrder", order);
            return true;
        }
    }
    return false;
}

function getIdOrder() {
    if (LocalStorageStatus()) {
        var order = null;
        order = localStorage.getItem("IdOrder");
        if (order === null) {
            console.log("Order is null");
        } else {
            return order;
        }
    }
    return null;
}

function setValueToJSON(jsonstring, index, value) {
    var json = null;
    var element = null;
    if (LocalStorageStatus()) {
        if (jsonstring !== null && jsonstring !== undefined && index !== null && value !== null) {
            if (jsonstring !== "" && jsonstring !== "[]") {
                try {
                    json = JSON.parse(jsonstring);
                } catch (e) {
                    console.error('String to JSON Failed (' + jsonstring + ')');
                    json = null;
                }
            } else {
                json = null;
                json = new Array();
            }
            if (json !== null) {
                element = new Object();
                element[index] = value;
                json.push(element);
                json = JSON.stringify(json);
            }
        }
    }
    return json;
}

function getValueFromJSON(jsonstring, index) {
    var json = null;
    var jsonvalue = null;
    if (LocalStorageStatus()) {
        if (jsonstring !== null && jsonstring !== undefined && index !== null) {
            if (jsonstring !== "" && jsonstring !== "[]") {
                try {
                    json = JSON.parse(jsonstring);
                } catch (e) {
                    console.error('String to JSON Failed (' + jsonstring + ')');
                    json = null;
                }
                if (json !== null) {
                    for (var i = 0; i < json.length; i++) {
                        if (Object.keys(json[i])[0] === index) {
                            jsonvalue = json[i][index];
                        }
                    }
                }
            }
        }
    }
    return jsonvalue;
}

function unsetToJSON(jsonstring, index) {
    var json = null;
    if (LocalStorageStatus()) {
        if (jsonstring !== null && jsonstring !== undefined && index !== null) {
            if (jsonstring !== "" && jsonstring !== "[]") {
                try {
                    json = JSON.parse(jsonstring);
                } catch (e) {
                    console.error('String to JSON Failed (' + jsonstring + ')');
                    json = null;
                }
                if (json !== null) {
                    for (var i = 0; i < json.length; i++) {
                        if (Object.keys(json[i])[0] === index) {
                            json[i] = null;
                            json.splice(i, 1);
                        }
                    }
                    json = JSON.stringify(json);
                }
            }
        }
    }
    return json;
}


function setLocalPOST(post) {
    if (LocalStorageStatus()) {
        if (post !== null) {
            localStorage.removeItem("POST");
            localStorage.setItem("POST", post);
            return true;
        }
    }
    return false;
}

function getLocalPOST() {
    if (LocalStorageStatus()) {
        var post = null;
        post = localStorage.getItem("POST");
        if (post === null || post === undefined) {
            console.log("LocalPOST is null");
            setLocalPOST("[]");
            return getLocalPOST();
        } else {
            return post;
        }
    }
    return null;
}

function resetLocalPOST() {
    if (LocalStorageStatus()) {
        localStorage.removeItem("POST");
        getLocalPOST();
        return true;
    }
    return false;
}

function setPOST(pname, pvalue) {
    if (pname !== null && pvalue !== null) {
        var inputpost = null;
        var outputpost = null;
        inputpost = getLocalPOST();
        outputpost = setValueToJSON(inputpost, pname, pvalue);
        if (outputpost !== undefined && outputpost !== null && outputpost !== inputpost) {
            setLocalPOST(outputpost);
            return true;
        }

    }
    return false;
}

function getPOST(pname) {
    if (pname !== null) {
        var post = null;
        var pvalue = null;
        post = getLocalPOST();
        pvalue = getValueFromJSON(post, pname);
        return pvalue;
    }
    return null;
}

function unsetPOST(pname) {
    if (pname !== null) {
        var inputpost = null;
        var outputpost = null;
        inputpost = getLocalPOST();
        outputpost = unsetToJSON(inputpost, pname);
        if (outputpost !== undefined && outputpost !== null && outputpost !== inputpost) {
            setLocalPOST(outputpost);
            return true;
        }
    }
    return false;
}

function setLastInsertId(lastid) {
    if (LocalStorageStatus()) {
        if (lastid !== null) {
            localStorage.removeItem("LastInsertId");
            localStorage.setItem("LastInsertId", lastid);
            return true;
        }
    }
    return false;
}

function getLastInsertId() {
    if (LocalStorageStatus()) {
        var id = null;
        id = sessionStorage.getItem("LastInsertId");
        if (id === null) {
            console.log("LastInsertId is null");
        } else {
            return id;
        }
    }
    return null;
}

function getIdFromPOST() {
    var frms = document.forms;
    var form = null;
    var findby = null;
    var id = null;
    var result = false;
    var button = null;
    for (var i = 0; i < frms.length; i++) {
        if (frms[i].getAttribute('findBy') !== null && frms[i].getAttribute('findBy') !== '' && frms[i].getAttribute('mainform') !== null && frms[i].getAttribute('mainform') === 'true') {
            form = frms[i];
            findby = form.getAttribute('findBy');
            for (var j = 0; j < form.elements.length; j++) {
                if (form.elements[j].getAttribute('save') !== null) {
                    button = form.elements[j];
                }
                if (form.elements[j].id === findby || form.elements[j].name === findby) {
                    id = form.elements[j];
                    if (getPOST(findby) !== null) {
                        id.value = getPOST(findby);
                        result = true;
                    }
                }
                if (button !== null) {
                    if (getPOST('action') !== null) {
                        button.setAttribute('action', getPOST('action'));
                    } else if (getPOST('action') === 'view') {
                        form.elements[j].setAttribute("readonly", "readonly");
                        form.elements[j].setAttribute("disabled", "disabled");
                    }
                }
            }
        }
    }

    return result;
}

jQuery(document).ready(function () {
    getIdFromPOST();
});