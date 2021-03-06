/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    createAjaxLoading();
    ajaxLoading();
    getIdFromGET();
    setTokenForms();
    requestNotificationPermission();
});

function getMETAElement(name) {
    if (name !== undefined && name !== null) {
        var metaelements = document.getElementsByTagName("META");
        for (var i = 0; i < metaelements.length; i++) {
            if (metaelements[i].getAttribute('name') === name) {
                return metaelements[i];
            }
        }
    }
    return null;
}

function requestNotificationPermission() {
    if (Notification.permission == "default" || Notification.permission == "denied") {
        Notification.requestPermission();
        //alert('Please enable the Notifications! ');
    }
    if (Notification.permission == "denied") {
        //RequestNotificationPermission();
    }
}

function showNotification(mytitle, mytext, time = 5000) {
    if (Notification) {
        if (Notification.permission === "granted") {
            if (mytitle !== null && mytext !== null) {
                var message = null;
                var title = mytitle;
                var extra = {
                    body: mytext
                };
                message = new Notification(title, extra);
                setTimeout(function () {
                    message.close();
                }, time);
                return true;
            }
        } else {
            alert(mytext);
        }
    }
    return false;
}

function noContextMenu() {
    document.oncontextmenu = function () {
        return false;
    };
    document.oncopy = function () {
        return false;
    };
    document.oncut = function () {
        return false;
    };
}

function noBackButton() {
    window.location.hash = "no-back-button";
    window.location.hash = "Again-No-back-button";
    window.onhashchange = function () {
        window.location.hash = "no-back-button";
    };
}

function automaticSize(element) {
    var contentsize = 0;
    if (element !== undefined && element !== null) {
        if (!window.opera && document.all && document.getElementById) {
            contentsize = parseInt(element.contentWindow.document.body.scrollHeight);
            contentsize = contentsize + 100;
            element.style.height = contentsize;
            element.setAttribute("height", "" + contentsize + "px");
            return true;
        } else if (document.getElementById) {
            contentsize = parseInt(element.contentDocument.body.scrollHeight);
            contentsize = contentsize + 100;
            element.style.height = "" + contentsize + "px";
            element.setAttribute("height", "" + contentsize + "px");
            return true;
        }
        return false;
    }
    return false;
}

function getRandomNumber(lowerlimit, upperlimit) {
    var num = null;
    if (lowerlimit !== null && upperlimit !== null && (!isNaN(lowerlimit) && !isNaN(upperlimit))) {
        num = Math.round(Math.random() * (upperlimit - lowerlimit) + parseInt(lowerlimit));
    }
    return num;
}

function getCurrentTime() {
    var hh = new Date().getHours();
    var mm = new Date().getMinutes();
    var ss = new Date().getSeconds();
    var text = '';
    if (hh < 10) {
        hh = '0' + hh;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    if (ss < 10) {
        ss = '0' + ss;
    }
    text = '' + hh + ':' + mm + ':' + ss;
    console.log(text);
    return text;
}

function getCurrentDate() {
    var Y = new Date().getFullYear();
    var M = new Date().getMonth();
    var D = new Date().getDate();
    var text = '';
    M = M + 1;
    if (M < 10) {
        M = '0' + M;
    }
    if (D < 10) {
        D = '0' + D;
    }
    text = '' + Y + '-' + M + '-' + D;
    console.log(text);
    return text;
}

function getDateTimeString() {
    var text = '';
    var date = getCurrentDate();
    var time = getCurrentTime();
    date = date.split('-').join('');
    date = date.split('/').join('');
    time = time.split(':').join('');
    text = date + time;
    return text;
}

function delay(milliseconds) {
    var i = 0;
    for (i = 0; i <= milliseconds; i++) {
        setTimeout('return 0', 1);
    }
    return 1;
}

function GET(key) {
    key = key.replace(/[\[]/, '\\[');
    key = key.replace(/[\]]/, '\\]');
    var pattern = "[\\?&]" + key + "=([^&#]*)";
    var regex = new RegExp(pattern);
    var url = unescape(window.location.href);
    var results = regex.exec(url);
    if (results === null) {
        return null;
    } else {
        return results[1];
    }
}

function getErrorMessage() {
    var message = 'Connection Error!.';
    return message;
}

function ajaxLoading() {
    $(document).on("ajaxStart", function () {
        showAjaxLoading();
    }).on("ajaxStop", function () {
        hideAjaxLoading();
    });
}

function createAjaxLoading() {
    var maindiv = null;
    var subdiv = null;
    var imgload = null;
    var text = null;

    maindiv = document.createElement("div");
    subdiv = document.createElement("div");
    imgload = document.createElement("img");
    text = document.createElement("p");

    maindiv.setAttribute("id", "ajax-loading");
    maindiv.setAttribute("class", "ajax-loading");
    maindiv.setAttribute("style", "display:none;");
    subdiv.setAttribute("id", "subloading");
    subdiv.setAttribute("class", "subloading");
    subdiv.setAttribute("style", "display: inline-block;");
    imgload.setAttribute("id", "image-loading");
    imgload.setAttribute("class", "image-loading");
    imgload.setAttribute("src", "css/loading.webp");
    imgload.setAttribute("alt", "?");
    text.setAttribute("class", "text-loading");
    text.innerHTML = '...LOADING...';

    maindiv.appendChild(subdiv);
    subdiv.appendChild(imgload);
    subdiv.appendChild(text);
    document.body.appendChild(maindiv);
}

function showAjaxLoading() {
    var loading = null;
    loading = document.getElementById("ajax-loading");
    if (loading !== null) {
        loading.style = "display: block; text-align: center;";
        return true;
    }
    return false;
}

function hideAjaxLoading() {
    var loading = null;
    loading = document.getElementById("ajax-loading");
    if (loading !== null) {
        loading.style = "display: none;";
        return true;
    }
    return false;
}

function setWSPath(path = null) {
    var project = null;
    if (LocalStorageStatus()) {
        project = getMETAElement('ProjectName').content;
        if (path === null || path === '') {
            path = prompt('Enter the Web Service path:', '');
        }
        if (path !== null && path !== "") {
            localStorage.setItem("WebServicePath" + project, path);
            return true;
        } else {
            return setWSPath();
        }
    }
    return false;
}

function getWSPath() {
    var path = null;
    var project = null;
    if (LocalStorageStatus()) {
        project = getMETAElement('ProjectName').content;
        path = localStorage.getItem("WebServicePath" + project);
        if (path === null) {
            console.log("WebServicePath is null.");
            setWSPath();
            return getWSPath();
        } else {
            return path;
        }
    }
    return null;
}

function setTokenForms() {
    var myforms = null;
    var token = null;
    try {
        myforms = document.forms;
        token = localStorage.getItem('TokenLogin');
        for (var i = 0; i < myforms.length; i++) {
            if (myforms[i] !== null && token !== null) {
                myforms[i].setAttribute('token', token);
            }
        }
        return true;
    } catch (e) {

    }
    return false;
}

function getTitle(Obj) {
    if (Obj !== null) {
        if (Obj.getAttribute('title') !== null) {
            return Obj.getAttribute('title');
        }
    }
    return '';
}

function getPlaceholder(Obj) {
    if (Obj !== null) {
        if (Obj.getAttribute('placeholder') !== null) {
            return Obj.getAttribute('placeholder');
        }
    }
    return '';
}

function deleteElement(element) {
    var parent = null;
    if (element !== null) {
        parent = element.parentNode;
        parent.removeChild(element);
        return true;
    }
    return false;
}

function hideElement(element) {
    if (element !== null) {
        element.style = 'visibility: hidden; display: none;';
        return true;
    }
    return false;
}

function disableElement(element) {
    if (element !== null) {
        element.setAttribute('disabled', 'disabled');
        return true;
    }
    return false;
}

function getElementDocument(id) {
    var element = null;
    if (id !== null && id !== '') {
        element = document.getElementById(id);
        return element;
    }
    return null;
}

function getElement(parent, id) {
    var j = 0;
    var elements = null;
    var result = null;
    if (parent !== null && parent.childNodes !== null && parent.childNodes !== undefined && id !== null && id !== '') {
        elements = parent.childNodes;
        if (elements.length > 0) {
            for (j = 0; j < elements.length; j++) {
                if (elements[j].id === id) {
                    console.log("Element Found: " + elements[j].id);
                    result = elements[j];
                    break;
                }
                if (elements.childNodes !== null && result === null) {
                    result = getElement(elements[j], id);
                }
            }
        }
    }
    if (parent !== null && parent !== undefined && result === null) {
        if (parent.id === id) {
            return parent;
        }
    }
    return result;
}

function getElementByName(parent, name) {
    var j = 0;
    var elements = null;
    var result = null;
    if (parent !== null && parent.childNodes !== undefined && parent.childNodes !== null && name !== null && name !== '') {
        elements = parent.childNodes;
        if (elements.length > 0) {
            for (j = 0; j < elements.length; j++) {
                if (elements[j].nodeType === 1) {
                    if (elements[j].name === name) {
                        console.log("Element Found: " + elements[j].name);
                        result = elements[j];
                        break;
                    }
                    if (elements[j].getAttribute('name') !== undefined && elements[j].getAttribute('name') === name) {
                        console.log("Element Found: " + elements[j].name);
                        result = elements[j];
                        break;
                    }
                }
                if (elements.childNodes !== null && result === null) {
                    result = getElementByName(elements[j], name);
                }
            }
        }
    }
    if (parent !== null && parent !== undefined && result === null) {
        if (parent.name === name) {
            return parent;
        }
    }
    return result;
}

function createInputHidden(form, name, value) {
    var element = null;
    if (form !== null && form.tagName.toString().toUpperCase() === "FORM" && name !== null) {
        element = document.createElement('input');
        element.setAttribute('type', 'hidden');
        element.setAttribute('id', name);
        element.setAttribute('name', name);
        element.setAttribute('value', value);
        form.appendChild(element);
        console.log('Hidden Input: ' + name);
        return true;
    }
    return false;
}

function createInputHiddenTemp(form, name, value) {
    var element = null;
    if (createInputHidden(form, name, value)) {
        element = getElement(form, name);
        if (element !== null) {
            element.setAttribute('temp', 'true');
            console.log('Setting Hidden Input: ' + name);
            return true;
        }
    }
    return false;
}

function deleteTemporalElements(parent) {
    var elements = null;
    var j = null;
    var exists = false;
    elements = parent.elements;
    if (elements.length > 0) {
        for (j = 0; j < elements.length; j++) {
            if (elements[j].getAttribute("temp") === 'true') {
                deleteElement(elements[j]);
                exists = true;
            }
        }
        return exists;
    }
    return false;
}

function getActionButton(element) {
    var form = null;
    if (element !== null) {
        form = getForm(element);
        if (form !== null && form.tagName.toString().toUpperCase() === "FORM") {
            for (var j = 0; j < form.elements.length; j++) {
                if (form.elements[j] !== null && (form.elements[j].tagName.toString().toUpperCase() === "BUTTON" || form.elements[j].tagName.toString().toUpperCase() === "INPUT")) {
                    if (form.elements[j].getAttribute('action') !== null) {
                        console.log('Action Button: ' + form.elements[j].id);
                        return form.elements[j];
                    }
                }
            }
        }
    }
    return null;
}

function getActionFromButton(button) {
    var form = null;
    if (button === null || (button.tagName.toString().toUpperCase() !== "BUTTON" && button.tagName.toString().toUpperCase() !== "INPUT")) {
        button = getActionButton(button);
    }
    if (button !== null && (button.tagName.toString().toUpperCase() === "BUTTON" || button.tagName.toString().toUpperCase() === "INPUT")) {
        if (button.getAttribute("action") !== null && button.getAttribute("action") !== '') {
            form = getForm(button);

            if (button.getAttribute("action") === 'find') {
                form.setAttribute('do', 'find');
            }
            if (button.getAttribute("action") === 'insert') {
                form.setAttribute('do', 'insert');
            }
            if (button.getAttribute("action") === 'insertorupdate') {
                form.setAttribute('do', 'insertorupdate');
            }
            if (button.getAttribute("action") === 'replace') {
                form.setAttribute('do', 'replace');
            }
            if (button.getAttribute("action") === 'update') {
                form.setAttribute('do', 'update');
            }
            if (button.getAttribute("action") === 'delete') {
                form.setAttribute('do', 'delete');
            }
            if (button.getAttribute("action") === 'findall') {
                form.setAttribute('do', 'findall');
            }
            if (button.getAttribute("action") === 'updatestatus') {
                form.setAttribute('do', 'updatestatus');
            }
            console.log("action: " + button.getAttribute("action"));
            return button.getAttribute("action");
        }
    }
    return null;
}

function createTempInputs(form) {
    deleteElement(getElement(form, 'token'));
    if (getElement(form, 'token') === null && getToken(form) !== null && getToken(form) !== '') {
        createInputHiddenTemp(form, 'token', getToken(form));
    }
    deleteElement(getElement(form, 'model'));
    if (getElement(form, 'model') === null && getModel(form) !== null && getModel(form) !== '') {
        createInputHiddenTemp(form, 'model', getModel(form));
    }
    deleteElement(getElement(form, 'action'));
    if (getElement(form, 'action') === null && getActionForm(form) !== null && getActionForm(form) !== '') {
        createInputHiddenTemp(form, 'action', getActionForm(form));
    }
    deleteElement(getElement(form, 'findBy'));
    if (getElement(form, 'findBy') === null && getFindBy(form) !== null && getFindBy(form) !== '') {
        createInputHiddenTemp(form, 'findBy', getFindBy(form));
    }
}

function autoValueCheckbox(checkbox) {
    if (checkbox !== undefined && checkbox !== null) {
        if (checkbox.tagName === undefined) {
            checkbox = document.getElementById(checkbox);
        }
        console.log('Setting Checkbox Value: ' + checkbox.id);
        if (checkbox.tagName.toString().toUpperCase() === 'INPUT' && checkbox.getAttribute('type').toString().toLowerCase() === 'checkbox') {
            var checkedValue = checkbox.getAttribute('checkedvalue');
            var uncheckedValue = checkbox.getAttribute('uncheckedvalue');
            checkbox.removeAttribute('checked');
            if (checkbox.checked) {
                checkbox.value = checkedValue;
                checkbox.setAttribute('checked', 'checked');
            } else {
                checkbox.value = uncheckedValue;
            }
            return true;
        }
        return false;
    }
}

function convertCheckboxesToTexts(form) {
    if (form !== undefined && form !== null) {
        if (form.elements.length > 0) {
            var checkboxarray = Array();
            var i = 0;
            for (i = 0; i < form.elements.length; i++) {
                if (form.elements[i].tagName.toString().toUpperCase() === 'INPUT' && form.elements[i].getAttribute('type').toString().toLowerCase() === 'checkbox') {
                    console.log('Elemento:' + form.elements[i].id);
                    form.elements[i].setAttribute('type', 'text');
                    checkboxarray.push(form.elements[i]);
                    console.log(form.elements[i].id + ' converted to ' + form.elements[i].type);
                }
            }
            return checkboxarray;
        }
    }
    return null;
}

function convertTextsToCheckboxes(array) {
    if (array !== undefined && array !== null) {
        if (array.length > 0) {
            var i = 0;
            for (i = 0; i < array.length; i++) {
                if (array[i].tagName.toString().toUpperCase() === 'INPUT' && array[i].getAttribute('type').toString().toLowerCase() === 'text') {
                    array[i].setAttribute('type', 'checkbox');
                }
            }
            return true;
        }
    }
    return false;
}

function getParent(element, tagname = null) {
    if (element !== null) {
        if (element.parentNode !== null && element.parentNode !== undefined) {
            if (tagname !== null && tagname !== undefined && tagname !== '') {
                if (element.parentNode.tagName.toString().toUpperCase() === tagname) {
                    return element.parentNode;
                } else {
                    return getParent(element.parentNode, tagname);
                }
            } else {
                return element.parentNode;
            }
        }
    }
    return null;
}

function clearForm(form) {
    if (form !== null && form.tagName !== undefined && form.tagName.toString().toUpperCase() === "FORM") {
        form.reset();
    }
}

function resetForm(element) {
    var form = null;
    if (element !== null) {
        form = getForm(element);
        for (var i = 0; i < document.forms.length; i++) {
            if (form === document.forms[i]) {
                form = document.forms[i];
                for (var j = 0; j < form.elements.length; j++) {
                    if (form.elements[j].tagName.toString().toUpperCase() !== "BUTTON") {
                        form.elements[j].value = "";
                    }
                    if (form.elements[j].tagName.toString().toUpperCase() === "TEXTAREA") {
                        form.elements[j].innerHTML = "";
                    }
                    if (form.elements[j].tagName.toString().toUpperCase() === "IMG") {
                        form.elements[j].src = "";
                    }
                }
            }
        }
        return true;
    }
    return false;
}

function disableForm(element) {
    var form = null;
    if (element !== null) {
        form = getForm(element);
        for (var i = 0; i < document.forms.length; i++) {
            if (form === document.forms[i]) {
                form = document.forms[i];
                for (var j = 0; j < form.elements.length; j++) {
                    disableElement(form.elements[j]);
                }
            }
        }
        return true;
    }
    return false;
}

function getForm(element) {
    if (element !== null) {
        var found = null;
        if (element.tagName === undefined) {
            var element0 = document.getElementById(element);
            if (element0 !== undefined && element0 !== null && element0.tagName.toString().toUpperCase() === 'FORM') {
                return element0;
            }
        }
        if (element.tagName !== undefined) {
            if (element.tagName.toString().toUpperCase() === 'FORM') {
                return element;
            }
            if (element.getAttribute('form') !== undefined && element.getAttribute('form') !== null) {
                var form = document.getElementById(element.getAttribute('form'));
                if (form !== undefined && form !== null && form.tagName.toString().toUpperCase() === 'FORM') {
                    return form;
                }
            }
            found = getParent(element, 'FORM');
            if (found !== null) {
                console.log('FORM Found: ' + found.id);
                return found;
            }
        }
    }
    console.log('FORM not Found!.');
    return null;
}

function getParentTable(element) {
    if (element !== null) {
        var found = null;
        if (element.tagName.toString().toUpperCase() === 'TABLE') {
            return element;
        }
        found = getParent(element, 'TABLE');
        if (found !== null) {
            console.log('Parent (HTML TABLE) Found: ' + found.id);
            return found;
        }
    }
    console.log('Parent (HTML TABLE) Not Found!.');
    return null;
}

function getParentTR(element) {
    if (element !== null) {
        var found = null;
        if (element.tagName.toString().toUpperCase() === 'TR') {
            return element;
        }
        found = getParent(element, 'TR');
        if (found !== null) {
            console.log('Parent (HTML TR) Found: ' + found.id);
            return found;
        }
    }
    console.log('Parent (HTML TR) Not Found!.');
    return null;
}

function getParentTD(element) {
    if (element !== null) {
        var found = null;
        if (element.tagName.toString().toUpperCase() === 'TD') {
            return element;
        }
        found = getParent(element, 'TD');
        if (found !== null) {
            console.log('Parent (HTML TD) Found: ' + found.id);
            return found;
        }
    }
    console.log('Parent (HTML TD) Not Found!.');
    return null;
}

function resetControls(parent) {
    if (parent.nodeType === 1 && parent.value !== null && parent.value !== undefined && parent.getAttribute('editable') !== null && parent.getAttribute('editable') === 'true') {
        console.log('Setting Empty Value to: ' + parent.id);
        parent.value = '';
    }
    if (parent !== null && parent.childNodes !== null && parent.childNodes !== undefined) {
        for (var i = 0; i < parent.childNodes.length; i++) {
            if (parent.childNodes[i].nodeType === 1) {
                if (parent.childNodes[i].getAttribute('editable') !== null && parent.childNodes[i].getAttribute('editable') === 'true') {
                    if (parent.childNodes[i].value !== null && parent.childNodes[i].value !== undefined) {
                        console.log('Setting Empty Value to: ' + parent.childNodes[i].id);
                        parent.childNodes[i].value = "";
                        parent.childNodes[i].removeAttribute("selected");
                    }
                }
            }
            if (parent.childNodes[i].childNodes !== null && parent.childNodes[i].childNodes !== undefined) {
                resetControls(parent.childNodes[i]);
            }
        }
        return true;
    }
    return false;
}

function setControlsAttribute(parent, child = null, name, value) {
    if (parent !== null && name !== null && value !== null && parent !== undefined && name !== undefined && value !== undefined) {
        if (parent.nodeType === 1 && parent.value !== null && parent.value !== undefined) {
            if (parent.tagName !== undefined && parent.tagName.toString().toUpperCase() === 'BUTTON' || parent.tagName.toString().toUpperCase() === 'INPUT' || parent.tagName.toString().toUpperCase() === 'SELECT' || parent.tagName.toString().toUpperCase() === 'TEXTAREA') {
                parent.setAttribute(name, value);
            }
        }
        if (parent.childNodes !== undefined && parent.childNodes !== null) {
            for (var i = 0; i < parent.childNodes.length; i++) {
                if (parent.childNodes[i].nodeType === 1) {
                    if (parent.childNodes[i].tagName !== undefined && parent.childNodes[i].tagName.toString().toUpperCase() === 'BUTTON' || parent.childNodes[i].tagName.toString().toUpperCase() === 'INPUT' || parent.childNodes[i].tagName.toString().toUpperCase() === 'SELECT' || parent.childNodes[i].tagName.toString().toUpperCase() === 'TEXTAREA') {
                        if (child !== undefined && child !== null) {
                            if (parent.childNodes[i].getAttribute('name') === child) {
                                console.log('Setting ' + name + ' to ' + parent.childNodes[i].name);
                                parent.childNodes[i].setAttribute(name, value);
                            }
                        }
                        if (child === undefined || child === null) {
                            console.log('Setting ' + name + ' to ' + parent.childNodes[i].name);
                            parent.childNodes[i].setAttribute(name, value);
                        }
                    }
                }
                if (parent.childNodes[i].childNodes !== null && parent.childNodes[i].childNodes !== undefined) {
                    setControlsAttribute(parent.childNodes[i], null, name, value);
                }
            }
            return true;
        }
    }
    return false;
}

function removeAttributeDisabled(parent) {
    if (parent !== null && parent.childNodes !== null && parent.childNodes !== undefined) {
        for (var i = 0; i < parent.childNodes.length; i++) {
            if (parent.childNodes[i].nodeType === 1) {
                if (parent.childNodes[i].disabled !== undefined && parent.childNodes[i].disabled !== null) {
                    if (parent.childNodes[i].getAttribute('editable') !== undefined && parent.childNodes[i].getAttribute('editable') !== null) {
                        console.log('Removing [Disabled] Attribute: ' + parent.childNodes[i].id);
                        parent.childNodes[i].removeAttribute("disabled");
                    }
                }
            }
            if (parent.childNodes[i].childNodes !== null && parent.childNodes[i].childNodes !== undefined) {
                removeAttributeDisabled(parent.childNodes[i]);
            }
        }
    }
    return false;
}

function addAttributeDisabled(parent) {
    if (parent !== null && parent.childNodes !== null && parent.childNodes !== undefined) {
        for (var i = 0; i < parent.childNodes.length; i++) {
            if (parent.childNodes[i].nodeType === 1) {
                if (parent.childNodes[i] !== undefined && parent.childNodes[i] !== null) {
                    if (parent.childNodes[i].getAttribute('editable') !== undefined && parent.childNodes[i].getAttribute('editable') !== null) {
                        console.log('Adding [Disabled] Attribute: ' + parent.childNodes[i].id);
                        parent.childNodes[i].setAttribute("disabled", "disabled");
                    }
                }
            }
            if (parent.childNodes[i].childNodes !== null && parent.childNodes[i].childNodes !== undefined) {
                addAttributeDisabled(parent.childNodes[i]);
            }
        }
    }
    return false;
}

function readOnlyForm(element) {
    var form = null;
    if (element !== null) {
        form = getForm(element);
        for (var i = 0; i < document.forms.length; i++) {
            if (form === document.forms[i]) {
                form = document.forms[i];
                for (var j = 0; j < form.elements.length; j++) {
                    form.elements[j].setAttribute('readonly', 'readonly');
                    console.log(form.elements[j].tagName);
                    if (form.elements[j].tagName.toString().toUpperCase() === "SELECT") {
                        var newelement = document.createElement('INPUT');
                        newelement.setAttribute('type', 'hidden');
                        newelement.setAttribute('id', form.elements[j].getAttribute('id'));
                        newelement.setAttribute('name', form.elements[j].getAttribute('name'));
                        newelement.setAttribute('value', form.elements[j].getAttribute('value'));
                        newelement.value = form.elements[j].value;
                        form.appendChild(newelement);
                        form.elements[j].setAttribute('disabled', 'disabled');
                    }
                    if (form.elements[j].tagName.toString().toUpperCase() === "BUTTON" ||
                            (form.elements[j].tagName.toString().toUpperCase() === "INPUT" &&
                                    (
                                            form.elements[j].getAttribute('type').toString().toLowerCase() === 'button' ||
                                            form.elements[j].getAttribute('type').toString().toLowerCase() === 'submit' ||
                                            form.elements[j].getAttribute('type').toString().toLowerCase() === 'reset')
                                    )
                            ) {
                        form.elements[j].setAttribute('disabled', 'disabled');
                    }
                }
                break;
                return true;
            }
        }
    }
    return false;
}

function getOptionByValue(parent, value) {
    var j = 0;
    var elements = null;

    if (parent !== null && value !== null && value !== '') {
        if (parent.tagName.toString().toUpperCase() === "SELECT" || parent.tagName.toString().toUpperCase() === "DATALIST") {
            elements = parent.childNodes;
            if (elements.length > 0) {
                for (j = 0; j < elements.length; j++) {
                    if (elements[j].value === value) {
                        console.log("Element (OPTION) Found: " + elements[j].getAttribute("id"));
                        return elements[j];
                    }
                }
            }
        }
    }
    return null;
}

function getTD(element) {
    if (element !== null) {
        if (element.parentNode.tagName.toString().toUpperCase() === "TD") {
            return element.parentNode;
        } else {
            return getTD(element.parentNode);
        }
    }
    return null;
}

function getComboboxValue(element) {
    if (element !== undefined && element !== null && element.tagName !== undefined && (element.tagName === 'SELECT' || element.tagName === 'DATALIST')) {
        if (element.selected !== undefined) {
            return element.selected;
        }
        if (element.value !== undefined) {
            return element.value;
        }
    }
    return null;
}

function getComboboxColName(element) {
    if (element !== null && (element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST")) {
        if (element.getAttribute("colname") !== null && element.getAttribute("colname") !== '') {
            return element.getAttribute("colname");
        }
    }
    return null;
}

function getComboboxColValue(element) {
    if (element !== null && (element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST")) {
        if (element.getAttribute("colvalue") !== null && element.getAttribute("colvalue") !== '') {
            return element.getAttribute("colvalue");
        }
    }
    return null;
}

function getComboboxOtherValue(element) {
    if (element !== null && (element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST")) {
        if (element.getAttribute("othervalue") !== null && element.getAttribute("othervalue") !== '') {
            return element.getAttribute("othervalue");
        }
    }
    return null;
}

function getActionForm(form) {
    if (form !== null && form.tagName.toString().toUpperCase() === "FORM") {
        if (form.getAttribute("do") !== null && form.getAttribute("do") !== '') {
            return form.getAttribute("do");
        }
    }
    return null;
}

function getModel(element) {
    if (element !== null && (element.tagName.toString().toUpperCase() === "INPUT" || element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST" || element.tagName.toString().toUpperCase() === "TABLE" || element.tagName.toString().toUpperCase() === "FORM")) {
        if (element.getAttribute("model") !== null && element.getAttribute("model") !== '') {
            return element.getAttribute("model");
        }
    }
    return null;
}

function getURL(element) {
    if (element !== null && (element.tagName.toString().toUpperCase() === "INPUT" || element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST" || element.tagName.toString().toUpperCase() === "TABLE" || element.tagName.toString().toUpperCase() === "FORM")) {
        if (element.getAttribute("url") !== null && element.getAttribute("url") !== '') {
            return getWSPath() + element.getAttribute("url");
        }
    }
    return null;
}

function getToken(element) {
    if (element !== null && (element.tagName.toString().toUpperCase() === "INPUT" || element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST" || element.tagName.toString().toUpperCase() === "TABLE" || element.tagName.toString().toUpperCase() === "FORM")) {
        if (element.getAttribute("token") !== null && element.getAttribute("token") !== '') {
            return element.getAttribute("token");
        }
    }
    return null;
}

function setFindBy(element, findby, num = "") {
    if (element !== null && (element.tagName.toString().toUpperCase() === "INPUT" || element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST" || element.tagName.toString().toUpperCase() === "TABLE" || element.tagName.toString().toUpperCase() === "FORM")) {
        element.setAttribute('findby' + num, findby);
    }
    return null;
}

function setFindByValue(element, findbyvalue, num = "") {
    if (element !== null && (element.tagName.toString().toUpperCase() === "INPUT" || element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST" || element.tagName.toString().toUpperCase() === "TABLE" || element.tagName.toString().toUpperCase() === "FORM")) {
        element.setAttribute('findbyvalue' + num, findbyvalue);
    }
    return null;
}

function getFindBy(element, num = "") {
    if (element !== null && element.tagName !== undefined && (element.tagName.toString().toUpperCase() === "INPUT" || element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST" || element.tagName.toString().toUpperCase() === "TABLE" || element.tagName.toString().toUpperCase() === "FORM")) {
        if (element.getAttribute("findby" + num) !== null && element.getAttribute("findby" + num) !== '') {
            return element.getAttribute("findby" + num);
        }
    }
    return null;
}

function getFindByValue(element, num = "") {
    if (element !== null && element.tagName !== undefined && (element.tagName.toString().toUpperCase() === "INPUT" || element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST" || element.tagName.toString().toUpperCase() === "TABLE" || element.tagName.toString().toUpperCase() === "FORM")) {
        if (element.getAttribute("findbyvalue" + num) !== null && element.getAttribute("findbyvalue" + num) !== '') {
            return element.getAttribute("findbyvalue" + num);
        }
    }
    return null;
}

function setFindByField(fieldname, findby, findbyvalue, num = "") {
    if (fieldname !== null && fieldname !== '' && findby !== null && findby !== '' && findbyvalue !== null && findbyvalue !== '') {
        var field = document.getElementById(fieldname);
        if (field !== null && field !== undefined) {
            if (field !== null && (field.tagName.toString().toUpperCase() === "INPUT" || field.tagName.toString().toUpperCase() === "SELECT" || field.tagName.toString().toUpperCase() === "DATALIST" || field.tagName.toString().toUpperCase() === "TABLE" || field.tagName.toString().toUpperCase() === "FORM")) {
                console.log('Setting Findby' + num + ' ' + findby + '=' + findbyvalue + ' TO ' + field.id);
                field.setAttribute('findby' + num, findby);
                field.setAttribute('findbyvalue' + num, findbyvalue);
            }
        }
}
}

function getFindbyArray(element) {
    if (element !== null && element.tagName !== undefined) {
        var findbyarray = Array();
        if (getFindBy(element) !== null) {
            findbyarray['findby'] = getFindBy(element);
            findbyarray['findbyvalue'] = getFindByValue(element);
        }
        for (var i = 0; i < 100; i++) {
            if (getFindBy(element, i) !== null) {
                findbyarray['findby' + i] = getFindBy(element, i);
                findbyarray['findbyvalue' + i] = getFindByValue(element, i);
            }
        }
        return findbyarray;
    }
    return null;
}

function getStatusFieldName(element) {
    if (element !== null && (element.tagName.toString().toUpperCase() === "INPUT" || element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST" || element.tagName.toString().toUpperCase() === "TABLE" || element.tagName.toString().toUpperCase() === "FORM")) {
        if (element.getAttribute("statusfield") !== null && element.getAttribute("statusfield") !== '') {
            return element.getAttribute("statusfield");
        }
    }
    return null;
}

function getOrderTable(element) {
    if (element.tagName.toString().toUpperCase() === "TABLE") {
        if (element.getAttribute("ordertable") !== null && element.getAttribute("ordertable") !== '') {
            return element.getAttribute("ordertable");
        }
    }
    return null;
}

function getTableOrderBy(element) {
    if (element.tagName.toString().toUpperCase() === "TABLE") {
        if (element.getAttribute("tableorderby") !== null && element.getAttribute("tableorderby") !== '') {
            return element.getAttribute("tableorderby");
        }
    }
    return null;
}

function getTableOrdering(element) {
    if (element.tagName.toString().toUpperCase() === "TABLE") {
        if (element.getAttribute("tableordering") !== null && element.getAttribute("tableordering") !== '') {
            return element.getAttribute("tableordering");
        }
    }
    return null;
}

function getSelectedOption(element) {
    if (element !== null && (element.tagName.toString().toUpperCase() === "INPUT" || element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST" || element.tagName.toString().toUpperCase() === "TABLE")) {
        if (element.getAttribute("selected") !== null && element.getAttribute("selected") !== '') {
            return element.getAttribute("selected");
        }
    }
    return null;
}

function submitAjax(formData, url, header, reload) {
    var promise = null;
    promise = $.ajax({
        method: "POST",
        url: url,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result, status) {
            sessionStorage.removeItem('error');
            sessionStorage.removeItem('textMessage');
            sessionStorage.removeItem('data');
            sessionStorage.removeItem('rowCount');
            sessionStorage.removeItem('lastInsertId');
            if (result !== null && result !== '') {
                try {
                    result = JSON.parse(result);
                } catch (e) {
                    console.error(result);
                }
            }
            if (result !== null && result !== '') {
                if (result.error !== null && result.error !== undefined && result.error !== '') {
                    console.error(result.error);
                    showNotification('Error:', result.error);
                    sessionStorage.setItem('error', result.error);
                }
                if (result.message !== null && result.message !== undefined && result.message !== '') {
                    showNotification('Result:', result.message);
                    sessionStorage.setItem('textMessage', result.message);
                }
                if (result.data !== null && result.data !== undefined && result.data !== '') {
                    console.log('Data: ' + result.data);
                    sessionStorage.setItem('data', result.data);
                }
                if (result.lastInsertId !== null && result.lastInsertId !== undefined && result.lastInsertId !== '') {
                    try {
                        console.log('LastId: ' + result.lastInsertId);
                        sessionStorage.setItem('lastInsertId', result.lastInsertId);
                    } catch (e) {
                        console.log('Error (lastInsertId).');
                    }
                }
                if (result.rowcount !== null && result.rowcount !== undefined && result.rowcount !== '') {
                    try {
                        console.log('rowCount: ' + result.rowcount);
                        sessionStorage.setItem('rowCount', result.rowcount);
                    } catch (e) {
                        console.log('Error (rowCount).');
                    }
                }
                if (result.status !== null && result.status !== undefined && result.status === 1) {
                    console.log('Submit OK!.');
                    if (reload === true) {
                        window.location.reload();
                    }
                } else {
                    console.error('Submit Error!.');
                }
            } else {
                showNotification('Result:', 'Null Result. There was an error during the process.');
            }

        },
        error: function (xhr, textStatus, errorThrown) {
            console.error('Error: [' + textStatus + '] --- [' + xhr + '] --- [' + errorThrown + ']');
            console.log(Object.values(xhr));
            showNotification('Connection error:', 'Try again later!');
        }

    });
    return promise;
}

function setValue(element, value) {
    if (element !== undefined && element !== null && element.tagName !== undefined) {
        if (element !== null) {
            if (value === null || value === 'null' || value === 'NULL') {
                value = '';
            }
            element.value = "";
            element.value = value;
            if (element.value === '[object Object]') {
                element.value = '';
            }
            if (element.getAttribute('type') !== null && element.getAttribute('type').toString().toLowerCase() === 'password') {
                element.value = '';
            }
            if (element.getAttribute('type') !== null && element.getAttribute('type').toString().toLowerCase() === 'checkbox') {
                element.value = value;
                if (value === '0' || value === 'n' || value === 'no') {
                    element.checked = false;
                }
                if (value === '1' || value === 'y' || value === 'yes') {
                    element.checked = true;
                }
            }
            if (element.tagName.toString().toUpperCase() === "TEXTAREA") {
                element.innerHTML = value;
            }
            if (element.tagName.toString().toUpperCase() === "SELECT") {
                element.setAttribute('value', value);
                element.value = value;
                element.setAttribute('selected', value);
                element.selected = value;
            }
            if (element.tagName.toString().toUpperCase() === "DIV" || element.tagName.toString().toUpperCase() === "LABEL" || element.tagName.toString().toUpperCase() === "P" || element.tagName.toString().toUpperCase() === "A"
                    || element.tagName.toString().toUpperCase() === "H1" || element.tagName.toString().toUpperCase() === "H2" || element.tagName.toString().toUpperCase() === "H3" || element.tagName.toString().toUpperCase() === "H4" || element.tagName.toString().toUpperCase() === "H5" || element.tagName.toString().toUpperCase() === "H6") {
                element.innerHTML = value;
            }
        }
    }
    return false;
}

function setFormData(myform, json) {
    var columns = null, values = null, col = null, element = null;
    if (json !== null && myform !== null && myform.tagName !== undefined && myform.tagName.toString().toUpperCase() === "FORM") {
        columns = Array();
        if (Object.keys(json).length === 1 && Object.keys(json)[0] === getModel(myform)) {
            for (var child in json) {
                json = json[child];
                break;
            }
        }
        if (json !== null) {
            values = json;
            if (json.length === 1 || json.length > 1) {
                values = json[0];
            }
            for (var aux in values) {
                if (isNaN(aux)) {
                    columns.push('' + aux);
                }
            }
            for (var j = 0; j < columns.length; j++) {
                col = null;
                element = null;
                col = columns[j];
                element = getElementByName(myform, '' + col);
                setValue(element, values[col]);
            }
            console.log('Set Form Data OK!.');
            return true;
        }

    }
    return false;
}

function getFormData(element, myurl = null) {
    var promise = null;
    var myform = null, object = null, url = null, formData = null;
    myform = getForm(element);
    myform.setAttribute("do", "find");
    url = getURL(myform);
    createTempInputs(myform);
    formData = new FormData(myform);
    deleteTemporalElements(myform);
    if (myurl !== null) {
        url = myurl;
    }
    console.log('Getting Form Data: ' + myform.id + ' !');
    if (formData !== null && url !== null && url !== '') {
        promise = $.ajax({
            method: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function (result, status) {
                console.log('Status: ' + status);
                if (result !== null && result !== '') {
                    try {
                        result = JSON.parse(result);
                        console.log('Parse to JSON (Successful) - getData!.');
                    } catch (e) {
                        console.log(result);
                        console.error('Parse to JSON (Failed) - getData!.');
                    }
                }
                if (result !== null && result !== '') {
                    if (result.error !== null && result.error !== undefined && result.error !== '') {
                        console.error(result.error);
                        showNotification('Error:', result.error);
                    }
                    if (result.message !== null && result.message !== undefined && result.message !== '') {
                        showNotification('Result:', result.message);
                    }
                    if (result.data !== null && result.data !== undefined && result.data !== '') {
                        object = result.data;
                        try {
                            object = JSON.parse(object);
                            setFormData(myform, object);
                        } catch (e) {
                            console.error("Parse DATA to JSON (Failed) - getData!.");
                        }
                    }
                } else {
                    showNotification('Error:', 'Web Service Fail!');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: [' + textStatus + '] --- [' + xhr + '] --- [' + errorThrown + ']');
                showNotification('Connection error:', 'Try again later!');
            }
        }
        );
    }
    return promise;
}

function setComboboxFindby(fieldname, findby, findbyvalue, num = "") {
    var myfield = null;
    myfield = document.getElementById(fieldname);
    if (myfield !== null && (myfield.tagName.toString().toUpperCase() === 'SELECT' || myfield.tagName.toString().toUpperCase() === 'DATALIST')) {
        setFindByField(fieldname, findby, findbyvalue, num);
        return loadComboboxData(myfield);
    }
    return null;
}

function setComboboxValue(element) {
    var option = null;
    var selected = null;
    if (element !== undefined && element !== null) {
        selected = element.getAttribute("selected");
        console.log('Setting Combobox Value.');
        for (var i = 0; i < element.childNodes.length; i++) {
            option = element.childNodes[i];
            if (selected !== null && (option.id === selected || option.value === selected)) {
                console.log('Combobox Value: ' + selected);
                option.setAttribute('selected', 'selected');
            }
        }
    }
}

function setComboboxOptions(element, json) {
    var option = null;
    var selected = null;
    if (element !== null && json !== null) {
        if (Object.keys(json).length === 1 && Object.keys(json)[0] === getModel(element)) {
            for (var child in json) {
                json = json[child];
                break;
            }
        }

        selected = element.getAttribute("selected");
        element.innerHTML = '<option value=""></option>';
        for (var i = 0; i < json.length; i++) {
            option = document.createElement('option');
            option.setAttribute('id', json[i]['ivalue']);
            option.setAttribute('value', json[i]['ivalue']);
            option.setAttribute('text', json[i]['iname']);
            option.setAttribute('othervalue', json[i]['iothervalue']);
            option.innerHTML = json[i]['iname'];
            if (selected !== null && (option.id === selected || option.value === selected)) {
                option.setAttribute('selected', 'selected');
            }
            element.appendChild(option);
            option = null;
        }
        console.log('Set SELECT data OK!. ' + element.id);
        return true;
    }
    return false;
}

function loadComboboxData(element) {
    var promise = null;
    var url = null;
    var model = null;
    var colname = null;
    var colvalue = null;
    var othervalue = null;
    var object = null;
    var dataarray = null;

    url = getURL(element);
    model = getModel(element);
    colname = getComboboxColName(element);
    colvalue = getComboboxColValue(element);
    othervalue = getComboboxOtherValue(element);
    dataarray = getFindbyArray(element);
    if (dataarray === null) {
        dataarray = Array();
    }
    dataarray['model'] = model;
    dataarray['action'] = 'findAll';
    dataarray['colname'] = colname;
    dataarray['colvalue'] = colvalue;
    dataarray['othervalue'] = othervalue;
    dataarray = Object.assign({}, dataarray);

    console.log('Getting Data for SELECT: ' + element.id);
    if (element !== null &&
            (element.tagName !== undefined && element.tagName.toString().toUpperCase() === "SELECT" || element.tagName.toString().toUpperCase() === "DATALIST") &&
            url !== null && url !== '' &&
            model !== null && model !== '' &&
            colname !== null && colname !== '' &&
            colvalue !== null && colvalue !== '') {
        promise = $.ajax({
            method: "POST",
            url: url,
            data: dataarray,
            success: function (result, status) {
                if (result !== null && result !== '') {
                    try {
                        result = JSON.parse(result);
                        console.log('Parse to JSON (Successful) - loadComboboxData!.');
                    } catch (e) {
                        console.log(result);
                        console.error('Parse to JSON (Failed) - loadComboboxData!.');
                    }
                }
                if (result !== null && result !== '') {
                    object = result;
                    setComboboxOptions(element, object);
                } else {
                    console.error('Web Service Fail!.');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: [' + textStatus + '] --- [' + xhr + '] --- [' + errorThrown + ']');
                showNotification('Connection error:', 'Try again later!');
            }
        }
        );
    }
    return promise;
}

function addNewTableRow(mytable) {
    var sample = null;
    var newrow = null;
    var tbody = null;
    var form = null;
    if (mytable !== null && mytable.tagName === undefined) {
        mytable = getElementDocument(mytable);
    }
    if (mytable !== null && mytable.tagName.toString().toUpperCase() === "TABLE") {
        tbody = getElement(mytable, "tbody_" + mytable.id);
        sample = getElement(mytable, "samplerow");
        if (tbody !== null && tbody.tagName.toString().toUpperCase() === "TBODY" && sample !== null && sample.tagName.toString().toUpperCase() === "TR") {
            newrow = sample.cloneNode(true);
        }
        if (newrow !== null && newrow.tagName.toString().toUpperCase() === "TR") {
            newrow.id = "row" + mytable.id + getDateTimeString();
            newrow.style = "";
            form = sample.getAttribute('form');
            newrow.removeAttribute("samplerow");
            newrow.removeAttribute("form");
            tbody.appendChild(newrow);
            console.log(form);
            if (form !== undefined && form !== null) {
                setControlsAttribute(newrow, null, 'form', form);
            }
            removeAttributeDisabled(newrow);
            resetControls(newrow);
            console.log('New Row (TR) added to TABLE: ' + mytable.id);
        }
    }
    return newrow;
}

function deleteTableRow(mytable) {
    var myrow = null;
    var element = null;
    if (mytable !== null && mytable.tagName === undefined) {
        mytable = getElementDocument(mytable);
    }
    if (mytable !== null && mytable.tagName.toString().toUpperCase() === "TABLE") {
        if (document.activeElement) {
            element = document.activeElement;
            myrow = getParentTR(element);
            if (myrow !== undefined && myrow !== null) {
                if (deleteElement(myrow)) {
                    console.log('Row (TR) Deleted!');
                    return true;
                }
            }
        }
    }
    return false;
}

function editTableRow(item) {
    if (item !== null && item !== undefined) {
        var tr = null;
        tr = getParentTR(item);
        if (tr !== null && tr !== undefined) {
            removeAttributeDisabled(tr);
            console.log('Removing [Disabled] Attribute to inner inputs (TR)!');
            return true;
        }
    }
    return false;
}

function clearTableData(element) {
    var TRs = null;
    if (element !== null && element.tagName.toString().toUpperCase() === "TABLE") {
        if (element.getElementsByTagName('TR') !== null) {
            TRs = element.getElementsByTagName('TR');
            for (var i = 0; i < TRs.length; i++) {
                if (TRs[i] !== null && TRs[i] !== undefined) {
                    if (TRs[i].getAttribute('samplerow') !== null) {
                        hideElement(TRs[i]);
                    }
                    if (TRs[i].getAttribute('headrow') === null && TRs[i].getAttribute('samplerow') === null) {
                        deleteElement(TRs[i]);
                        if (i > 0) {
                            i = i - 1;
                        }
                    }
                }
            }
        }
        return true;
    }
    return false;
}

function getDataTableColumnsTypes(table) {
    if (table !== null && table.tagName !== undefined && table.tagName.toString().toUpperCase() === "TABLE") {
        var headrow = null;
        var tr = null;
        headrow = getElement(table, 'thead_' + table.id);
        if (headrow !== null && headrow.tagName !== undefined && headrow.tagName.toString().toUpperCase() === "THEAD") {
            for (var i = 0; i < headrow.childNodes.length; i++) {
                if (headrow.childNodes[i].tagName !== undefined && headrow.childNodes[i].tagName.toString().toUpperCase() === "TR") {
                    tr = headrow.childNodes[i];
                    break;
                }
            }
        }
        var array = Array();
        var sub = new Array();
        if (tr !== null && tr.tagName !== undefined && tr.tagName.toString().toUpperCase() === "TR") {
            for (var i = 0; i < tr.childNodes.length; i++) {
                if (tr.childNodes[i].tagName !== undefined && (tr.childNodes[i].tagName.toString().toUpperCase() === "TH" || tr.childNodes[i].tagName.toString().toUpperCase() === "TD") && tr.childNodes[i].getAttribute('columntype') !== undefined) {
                    array.push({"type": tr.childNodes[i].getAttribute('columntype')});
                }
            }
        }
        return array;
    }
    return null;
}

function createDataTable(element) {
    var xtable = null;
    var tableId = null;
    var ordertable = getOrderTable(element);
    var orderby = getTableOrderBy(element);
    var ordering = getTableOrdering(element);
    if (element !== null && element.tagName.toString().toUpperCase() === "TABLE") {
        tableId = '#' + element.getAttribute('id');
        try {
            if (ordertable !== undefined && ordertable === 'true') {
                if (orderby === null) {
                    orderby = 0;
                }
                if (ordering === null) {
                    ordering = 'desc';
                }
                xtable = $(tableId).DataTable({
                    "columns": getDataTableColumnsTypes(element),
                    "order": [[orderby, ordering]],
                    "bFilter": true
                });
            } else {
                xtable = $(tableId).DataTable();
            }
            console.log('DataTable Created: ' + tableId + '');
        } catch (e) {
            console.error(e);
            null;
        }
    }
    return xtable;
}

function destroyDataTable(element) {
    var result = false;
    var tableId = null;
    if (element !== null && element.tagName.toString().toUpperCase() === "TABLE") {
        tableId = '#' + element.getAttribute('id');
        try {
            if ($.fn.DataTable.isDataTable(tableId)) {
                $(tableId).DataTable().destroy();
                console.log('DataTable Destroyed: ' + tableId + '');
                result = true;
            }
        } catch (e) {
            null;
        }
    }
    return result;
}

function setTableData(table, json, dynamic) {
    var TRs = null;
    var samplerow = null;
    var form = null;
    var newrow = null;
    var thead = null;
    var tbody = null;
    var values = null;
    var columns = null;
    var col = null;
    var element = null;
    var elementsarray = null;
    var xtable = null;

    if (table !== null && table.tagName.toString().toUpperCase() === "TABLE" && json !== null) {
        destroyDataTable(table);
        if (table.getElementsByTagName('THEAD') !== null) {
            thead = table.getElementsByTagName('THEAD')[0];
            thead.setAttribute('id', 'thead' + '_' + table.id);
        }
        if (table.getElementsByTagName('TBODY') !== null) {
            tbody = table.getElementsByTagName('TBODY')[0];
            tbody.setAttribute('id', 'tbody' + '_' + table.id);
        }
        if (table.getElementsByTagName('TR') !== null) {
            TRs = table.getElementsByTagName('TR');
        }
        for (var i = 0; i < TRs.length; i++) {
            if (TRs[i].getAttribute('samplerow') !== null || TRs[i].id === 'samplerow') {
                samplerow = TRs[i].innerHTML;
                if (TRs[i].getAttribute('form') !== null) {
                    form = TRs[i].getAttribute('form');
                }
                break;
            }
        }
        clearTableData(table);
        if (Object.keys(json).length === 1 && Object.keys(json)[0] === getModel(table)) {
            for (var child in json) {
                json = json[child];
                break;
            }
        }

        if (json.length) {
            values = json[0];
            columns = Array();
            for (var aux in values) {
                if (isNaN(aux)) {
                    columns.push("" + aux);
                }
            }
            var j1 = 0;
            var j2 = 0;
            for (var i = 0; i < json.length; i++) {
                newrow = null;
                newrow = document.createElement('TR');
                newrow.setAttribute('rowid', i);
                newrow.innerHTML = samplerow;
                newrow.innerHTML = newrow.innerHTML.split('{{i}}').join(i + 1);
                for (j1 = 0; j1 < columns.length; j1++) {
                    col = columns[j1];
                    if (json[i][col] === null || json[i][col] === 'null' || json[i][col] === 'NULL') {
                        json[i][col] = '';
                    }
                    newrow.innerHTML = newrow.innerHTML.split('{{' + col + '}}').join(json[i][col]);
                }
                tbody.appendChild(newrow);
                for (j2 = 0; j2 < columns.length; j2++) {
                    col = columns[j2];
                    elementsarray = document.getElementsByName(col + '[]');
                    if (elementsarray === undefined || elementsarray === null) {
                        elementsarray = document.getElementsByName(col);
                    }
                    element = elementsarray[i + 1];
                    setValue(element, json[i][col]);
                    if (form !== undefined && form !== null && element !== undefined) {
                        if (element.getAttribute('form') === undefined || element.getAttribute('form') === null) {
                            element.setAttribute('form', form);
                        }
                    }
                }
            }
            if (dynamic === true) {
                console.log('Dynamic DataTable!.');
                xtable = createDataTable(table);
            }
            console.log('Set TABLE data OK!. ' + table.id);
            return true;
        }
    }
    return false;
}

function loadTableData(element, dynamic) {
    var promise = null;
    var url = null;
    var model = null;
    var dataarray = null;
    var object = null;
    url = getURL(element);
    model = getModel(element);
    dataarray = getFindbyArray(element);
    if (dataarray === null) {
        dataarray = Array();
    }
    dataarray['model'] = model;
    dataarray['action'] = 'findAll';
    dataarray = Object.assign({}, dataarray);
    console.log('Getting Data for TABLE: ' + element.id);

    if (element !== null && element.tagName !== undefined && element.tagName.toString().toUpperCase() === "TABLE") {
        promise = $.ajax({
            method: "POST",
            url: url,
            data: dataarray,
            success: function (result, status) {
                if (result !== null && result !== '') {
                    try {
                        result = JSON.parse(result);
                        console.log('Parse to JSON (Successful) - loadTableData!');
                    } catch (e) {
                        console.log(result);
                        console.error('Parse to JSON (Failed) - loadTableData!');
                    }
                }
                if (result !== null && result !== '') {
                    object = result;
                    setTableData(element, object, dynamic);
                } else {
                    console.log('Web Service Fail!.');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: [' + textStatus + '] --- [' + xhr + '] --- [' + errorThrown + ']');
                showNotification('Connection error:', 'Try again later!');
            }
        }
        );
    }
    return promise;
}

function setNameFieldsValue(object, namefield1, namefield2, namefield3) {
    var field1 = null;
    var field2 = null;
    var field3 = null;

    if (object === null && object !== undefined) {
        return false;
    } else {
        if (object['data'] !== null && object['data'] !== undefined) {
            object = object['data'];
        }
        if (Object.keys(object).length === 1) {
            if (object[0] !== null && object[0] !== undefined) {
                object = object[0];
            }
        }
    }
    if (namefield1 !== null) {
        if (namefield1.tagName.toString().toUpperCase() === 'INPUT') {
            field1 = namefield1;
        } else {
            field1 = getElementDocument(namefield1);
        }
        if (field1 !== null) {
            field1.value = 'NOT FOUND';
            if (object[field1.id] !== null && object[field1.id] !== undefined) {
                field1.value = object[field1.id];
            }
        }
    }
    if (namefield2 !== null) {
        if (namefield2.tagName.toString().toUpperCase() === 'INPUT') {
            field2 = namefield2;
        } else {
            field2 = getElementDocument(namefield2);
        }
        if (field2 !== null) {
            field2.value = 'NOT FOUND';
            if (object[field2.id] !== null && object[field2.id] !== undefined) {
                field2.value = object[field2.id];
            }
        }
    }
    if (namefield3 !== null) {
        if (namefield3.tagName.toString().toUpperCase() === 'INPUT') {
            field3 = namefield3;
        } else {
            field3 = getElementDocument(namefield3);
        }
        if (field3 !== null) {
            field3.value = 'NOT FOUND';
            if (object[field3.id] !== null && object[field3.id] !== undefined) {
                field3.value = object[field3.id];
            }
        }
    }
    console.log('Set NameFieldsValue OK!.');
    return true;
}

function loadNameFromId(field, namefield1, namefield2, namefield3) {
    var promise = null;
    var url = null;
    var model = null;
    var findby = null;
    var id = null;
    var vals = null;
    var object = null;
    if (field !== null) {
        if (field.tagName === null || field.tagName === undefined) {
            field = document.getElementById(field);
        }
        url = getURL(field);
        model = getModel(field);
        findby = field.name;
        id = field.value;
    }
    vals = {
        "model": model,
        "action": 'find',
        "findby": findby
    };
    vals[findby] = id;
    console.log('Loading NameFromId: ' + field.id);
    if (field !== null && namefield1 !== null) {
        promise = $.ajax({
            method: "POST",
            url: url,
            data: vals,
            success: function (result, status) {
                if (result !== null && result !== '') {
                    try {
                        result = JSON.parse(result);
                        console.log('Parse to JSON (Successful) - LoadNameFromId!');
                        console.log('Result:' + result.message);
                    } catch (e) {
                        console.log(result);
                        console.log('Parse to JSON (Failed) - LoadNameFromId!');
                    }
                }
                if (result !== null && result !== '') {
                    if (result.data !== null && result.data !== undefined) {
                        try {
                            object = result.data;
                            object = JSON.parse(object);
                            setNameFieldsValue(object, namefield1, namefield2, namefield3);
                        } catch (e) {
                        }
                    }
                } else {
                    console.log('Web Service Fail!.');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: [' + textStatus + '] --- [' + xhr + '] --- [' + errorThrown + ']');
                showNotification('Connection error:', 'Try again later!');
            }
        }
        );
    }
    return promise;
}

function autoLoadNameFromId(idfield, namefield1, namefield2, namefield3) {
    var field = null;
    if (idfield !== null) {
        field = document.getElementById(idfield);
        field.onchange = function () {
            console.log('Getting Data from Id: ' + field.id);
            return loadNameFromId(field, namefield1, namefield2, namefield3);
        };
    }
    return null;
}

function setLoginData(data) {
    if (data !== undefined) {
        try {
            if (data !== null && data['user'] !== null) {
                localStorage.setItem("UsernameLogin", "" + data['user']);
                console.log('UsernameLogin Stored!');
            } else {
                localStorage.removeItem("UsernameLogin");
            }
            if (data !== null && data['userrole'] !== null) {
                localStorage.setItem("UserRoleLogin", "" + data['userrole']);
                console.log('UserRoleLogin Stored!');
            } else {
                localStorage.removeItem("UserRoleLogin");
            }
            if (data !== null && data['userid'] !== null) {
                localStorage.setItem("UserIdLogin", "" + data['userid']);
                console.log('UserIdLogin Stored!');
            } else {
                localStorage.removeItem("UserIdLogin");
            }
            if (data !== null && data['fullname'] !== null) {
                localStorage.setItem("FullnameLogin", "" + data['fullname']);
                console.log('FullnameLogin Stored!');
            } else {
                localStorage.removeItem("FullnameLogin");
            }
            if (data !== null && data['enterprisename'] !== null) {
                localStorage.setItem("EnterpriseName", "" + data['enterprisename']);
                console.log('EnterpriseName Stored!');
            } else {
                localStorage.removeItem("EnterpriseName");
            }
            return true;
        } catch (e) {
            console.error('Error. Could not Start Session Variables!.');
        }
    }
    return false;
}

function setToken(token) {
    try {
        if (token !== null) {
            localStorage.setItem("TokenLogin", "" + token);
            console.log('TokenLogin Stored ' + token);
            return true;
        } else {
            localStorage.removeItem("TokenLogin");
        }
    } catch (e) {
        console.error('Error. Could not Start Token!.');
    }
    return false;
}

function login(element, destinationPage) {
    var form = null;
    var url = null;
    var formData = null;
    var promise = null;
    var object = null;
    if (element !== null) {
        form = getForm(element);
        url = getURL(form);
    }
    formData = new FormData(form);
    if (form !== null) {
        promise = $.ajax({
            method: "POST",
            url: url,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (result, status) {
                console.log('Result:' + result);
                if (result !== null) {
                    if (result.error !== null && result.error !== undefined && result.error !== '') {
                        console.error(result.error);
                        showNotification('Error:', result.error);
                    }
                    if (result.message !== null && result.message !== '') {
                        showNotification('Result:', result.message);
                    }
                    if (result.status === 1) {
                        try {
                            object = JSON.parse(result.data);
                            console.log('Parse to JSON (Successful) - Login!');
                        } catch (e) {
                            console.error("Parse to JSON (Failed) - Login!");
                        }
                        setLoginData(object);
                        if (result.token !== null && result.token !== '') {
                            setToken(result.token);
                        }
                        if (destinationPage !== null) {
                            window.location.href = destinationPage;
                        }
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                showNotification('Connection error:', 'Try again later!');
                console.error('Error: [' + textStatus + '] --- [' + xhr + '] --- [' + errorThrown + ']');
            }
        });
    } else {
        console.error("Form Not Found!");
    }
    return promise;
}

function logout(url, destinationPage, token) {
    var promise = null;
    var object = null;
    var vals = null;
    vals = {
        "model": 'logout',
        "action": 'logout',
        "token": token
    };
    if (url !== null) {
        promise = $.ajax({
            method: "POST",
            url: url,
            data: vals,
            dataType: 'json',
            success: function (result, status) {
                if (result !== null && result !== '') {
                    if (result.error !== null && result.error !== undefined && result.error !== '') {
                        console.error(result.error);
                        showNotification('Error:', result.error);
                    }
                    if (result.message !== null && result.message !== '') {
                        console.log(result.message);
                    }
                    if (result.data !== null) {
                        try {
                            object = JSON.parse(result.data);
                            console.log('Parse to JSON (Successful) - Logout!');
                        } catch (e) {
                            console.error("Error de Conversion JSON - Logout!");
                        }
                        setLoginData(object);
                        setToken(null);
                        if (destinationPage !== null) {
                            window.location.href = destinationPage;
                        }
                    }

                } else {
                    console.log('Web Service Fail!.');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: [' + textStatus + '] --- [' + xhr + '] --- [' + errorThrown + ']');
                showNotification('Connection error:', 'Try again later!');
            }
        }
        );
    }
}

function submitJSON(url, json, action, model, token) {
    var next = false;
    var formdata = null;
    var promise = null;
    if (url !== null && json !== null && action !== null && model !== null && token !== null) {
        formdata = {
            "json": json,
            "model": model,
            "action": action,
            "token": token
        };
        promise = $.ajax({
            method: "POST",
            url: url,
            data: formdata,
            dataType: 'json',
            success: function (result, status) {
                if (result !== null && result !== '') {
                    console.log(result);
                    if (result.error !== null && result.error !== undefined && result.error !== '') {
                        console.error(result.error);
                        showNotification('Error:', result.error);
                    }
                    if (result.message !== null && result.message !== '') {
                        console.log(result.message);
                    }
                    if (result.data !== null) {
                        try {
                            object = JSON.parse(result.data);
                            console.log('Parse to JSON (Successful) - submitJSON!');
                        } catch (e) {
                            console.error("Error de Conversion JSON - submitJSON");
                        }
                    }
                } else {
                    console.log('Web Service Fail!.');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error('Error: [' + textStatus + '] --- [' + xhr + '] --- [' + errorThrown + ']');
                showNotification('Connection error:', 'Try again later!');
            }
        }
        );

    }
}

function submitForm(element, reload) {
    var next = false;
    var form = null;
    var url = null;
    var formdata = null;
    var promise = null;
    getActionFromButton(element);

    if (element !== null) {
        form = getForm(element);
    }

    if (form !== null) {
        deleteTemporalElements(form);
        url = getURL(form);
        if (url !== null && url !== "") {
            createTempInputs(form);
            formdata = null;
            formdata = new FormData(form);
            if (formdata !== null) {
                promise = submitAjax(formdata, url, null, reload);
            }
            deleteTemporalElements(form);
        } else {
            next = false;
            console.error("Destination URL Null.");
        }
    } else {
        next = false;
        console.error("Form Not Found!");
    }
    return promise;
}

function submitFormConfirm(button, reload) {
    var r = false;
    r = confirm("Are you sure?");
    if (r === true) {
        return submitForm(button, reload);
    } else {
        console.log("Action Canceled!");
        return false;
    }
    return false;
}

function sendValue(form1, field1, form2, field2) {
    var findby1 = null;
    var findby2 = null;
    var valid1 = null;
    var valid2 = null;
    if (form1 !== null && form2 !== null) {
        form1 = getForm(form1);
        form2 = getForm(form2);

        if (form1.tagName.toString().toUpperCase() === "FORM" && form2.tagName.toString().toUpperCase() === "FORM") {
            if (field1 !== null && field1 !== '') {
                valid1 = getElement(form1, field1);
            } else {
                console.log('Getting findby in ' + form1.id);
                findby1 = getFindBy(form1);
                valid1 = getElement(form1, findby1);
            }
            if (field2 !== null && field2 !== '') {
                valid2 = getElement(form2, field2);
            } else {
                console.log('Getting findby in ' + form2.id);
                findby2 = getFindBy(form2);
                valid2 = getElement(form2, findby2);
            }
            if (valid1 !== null && valid2 !== null) {
                console.log('Copy value FROM ' + valid1.id + '=' + valid1.value + ' TO ' + valid2.id + '=' + valid2.value);
                valid2.value = valid1.value;
            }
        }
    }
}

function setNameFromDataList(idfield, idfieldname, idothervalue) {
    if (idfield !== null) {

        var field = null;
        var fieldTarget = null;
        var fieldOther = null;
        var datalist = null;
        var valuefield = null;
        var selected = null;

        if (idfield.tagName === undefined && document.getElementById(idfield) !== undefined) {
            field = document.getElementById(idfield);
        } else {
            field = idfield;
        }
        if (field.getAttribute('list') !== null && field.getAttribute('list') !== '') {
            datalist = field.getAttribute('list');
        }
        if (datalist !== null && datalist !== undefined && document.getElementById(datalist) !== null) {
            datalist = document.getElementById(datalist);
        }
        if (idfieldname !== null && idfieldname !== undefined && document.getElementById(idfieldname) !== null) {
            fieldTarget = document.getElementById(idfieldname);
        }
        if (idothervalue !== null && idothervalue !== undefined && document.getElementById(idothervalue) !== null) {
            fieldOther = document.getElementById(idothervalue);
        }

        valuefield = field.value;
        if (datalist !== null && valuefield !== null) {
            selected = getOptionByValue(datalist, valuefield);
        }

        if (selected !== null) {
            if (fieldTarget !== null && fieldTarget.tagName !== undefined && selected.innerHTML !== null && selected.innerHTML !== '') {
                fieldTarget.value = selected.innerHTML;
            }
            if (fieldOther !== null && fieldOther.tagName !== undefined && selected.getAttribute('othervalue') !== null) {
                fieldOther.value = selected.getAttribute('othervalue');
            }
        }

        if (valuefield === null || valuefield === '') {
            fieldTarget.value = '';
        }

    }
}

function autoNameFromDataList(idfield, idfieldname, idothervalue) {
    var field = null;
    if (idfield !== null) {
        if (idfield.tagName === undefined || idfield.tagName === null) {
            field = document.getElementById(idfield);
        } else {
            field = idfield;
        }
        field.oninput = function () {
            setNameFromDataList(idfield, idfieldname, idothervalue);
        };
        field.onchange = function () {
            setNameFromDataList(idfield, idfieldname, idothervalue);
        };
        field.onfocus = function () {
            setNameFromDataList(idfield, idfieldname, idothervalue);
        };
    }

}

function getIdFromGET() {
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
                    if (GET(findby) !== null) {
                        id.value = GET(findby);
                        result = true;
                    }
                }
                if (button !== null) {
                    if (GET('action') !== null) {
                        button.setAttribute('action', GET('action'));
                    } else if (GET('action') === 'view') {
                        form.elements[j].setAttribute("readonly", "readonly");
                        form.elements[j].setAttribute("disabled", "disabled");
                    }
                }
            }
        }
    }
    return result;
}
