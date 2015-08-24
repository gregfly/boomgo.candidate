var ajax = {};
ajax.x = function() {
    if (typeof XMLHttpRequest !== 'undefined') {
        return new XMLHttpRequest();  
    }
    var versions = [
        "MSXML2.XmlHttp.6.0",
        "MSXML2.XmlHttp.5.0",   
        "MSXML2.XmlHttp.4.0",  
        "MSXML2.XmlHttp.3.0",   
        "MSXML2.XmlHttp.2.0",  
        "Microsoft.XmlHttp"
    ];

    var xhr;
    for(var i = 0; i < versions.length; i++) {  
        try {  
            xhr = new ActiveXObject(versions[i]);  
            break;  
        } catch (e) {
        }  
    }
    return xhr;
};

ajax.send = function(url, callback, method, data, sync) {
    var x = ajax.x();
    x.open(method, url, sync === undefined? true : sync);
    x.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    x.onreadystatechange = function() {
        if (x.readyState == 4) {
            callback(x.responseText)
        }
    };
    if (method == 'POST') {
        x.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    }
    x.send(data)
};

ajax.get = function(url, data, callback, sync) {
    var query = [];
    for (var key in data) {
        query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
    }
    ajax.send(url + (query.length ? '?' + query.join('&') : ''), callback, 'GET', null, sync)
};

ajax.post = function(url, data, callback, sync) {
    if(typeof data !== 'string' && !(data instanceof String)) {
        var query = [];
        for (var key in data) {
            query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
        }
        data = query.join('&');
    }
    ajax.send(url, callback, 'POST', data, sync);
};

var utils = {};

utils.toggleClass = function(o, c) {
    if (o.className.indexOf(c) >= 0) {
        this.removeClass(o, c);
    } else {
        this.addClass(o, c);
    }
};

utils.addClass = function(o, c) {
    var re = new RegExp("(^|\\s)" + c + "(\\s|$)", "g");
    if (re.test(o.className)) {
        return;
    }
    o.className = (o.className + " " + c).replace(/\s+/g, " ").replace(/(^ | $)/g, "");
};

utils.removeClass = function(o, c) {
    var re = new RegExp("(^|\\s)" + c + "(\\s|$)", "g");
    o.className = o.className.replace(re, "$1").replace(/\s+/g, " ").replace(/(^ | $)/g, "");
};

function SimpleForm(elem) {
    this._elem = elem;
}

SimpleForm.prototype.serialize = function() {
    var form = this._elem;
    if (!form || form.nodeName !== "FORM") {
        return;
    }
    var i, j, q = [];
    for (i = form.elements.length - 1; i >= 0; i = i - 1) {
        if (form.elements[i].name === "") {
            continue;
        }
        switch (form.elements[i].nodeName) {
        case 'INPUT':
            switch (form.elements[i].type) {
            case 'text':
            case 'hidden':
            case 'password':
            case 'button':
            case 'reset':
            case 'number':
            case 'date':
            case 'submit':
                q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                break;
            case 'checkbox':
            case 'radio':
                if (form.elements[i].checked) {
                    q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                }
                break;
            }
            break;
            case 'file':
            break;
        case 'TEXTAREA':
            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
            break;
        case 'SELECT':
            switch (form.elements[i].type) {
            case 'select-one':
                q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                break;
            case 'select-multiple':
                for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
                    if (form.elements[i].options[j].selected) {
                        q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
                    }
                }
                break;
            }
            break;
        case 'BUTTON':
            switch (form.elements[i].type) {
            case 'reset':
            case 'submit':
            case 'button':
                q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                break;
            }
            break;
        }
    }
    return q.join("&");
};

SimpleForm.prototype.clear = function() {
    var form = this._elem;
    if (!form || form.nodeName !== "FORM") {
        return;
    }
    var i;
    for (i = form.elements.length - 1; i >= 0; i = i - 1) {
        if (form.elements[i].name === "") {
            continue;
        }
        switch (form.elements[i].nodeName) {
        case 'INPUT':
            switch (form.elements[i].type) {
            case 'text':
            case 'hidden':
            case 'password':
            case 'button':
            case 'reset':
            case 'date':
            case 'number':
            case 'submit':
                form.elements[i].value = '';
                break;
            }
            break;
        case 'TEXTAREA':
            form.elements[i].value = '';
            break;
        }
    }
    return this.errors({});
};

SimpleForm.prototype.errors = function(data) {
    var lists = document.getElementsByClassName('errors');
    for (var i = lists.length - 1; i >= 0; --i) {
        lists[i].parentNode.removeChild(lists[i]);
    }
    for (var field in data) {
        var container = document.getElementById(field + '-element');
        var errors = document.createElement('ul');
        errors.setAttribute('class', 'errors');
        container.appendChild(errors);
        for (var key in data[field]) {
            var error = document.createElement('li');
            error.textContent = data[field][key];
            errors.appendChild(error);
        }
    }
    return this;
};

SimpleForm.prototype.submit = function() {
    var form = this._elem;
    var self = this;
    ajax.post(form.getAttribute('action'), this.serialize(form), function(data) {
        data = JSON.parse(data);
        if (data === true) {
            alert('Запись сохранена');
            self.clear();
        } else {
            self.errors(data);
        }
    });
    return false;
};

function GridView(config) {
    this._config = config;
    this._src = config.src || '';
    delete config.src;
}

GridView.prototype._mergeConfigs = function(a, b) {
    var c = {};
    for (var index in a) { 
        if (a.hasOwnProperty(index)) {
            c[index] = a[index];
        }
    }
    for (var index in b) { 
        if (b.hasOwnProperty(index)) {
            c[index] = b[index];
        }
    }
    return c;
};

GridView.prototype.load = function(config) {
    var req = this._mergeConfigs(this._config, config || {});
    var self = this;
    ajax.get(this._src, req, function(data) {
        self.ui().updateTable(JSON.parse(data));
        self._config = req;
    });
    return false;
};

GridView.prototype.getPage = function() {
    return parseFloat(this._config.page) || 0;
};

GridView.prototype.getGroup = function() {
    return parseFloat(this._config.group) || 0;
};

GridView.prototype.nextPage = function() {
    return this.load({
        page: this.getPage() + 1
    });
};

GridView.prototype.prevPage = function() {
    return this.load({
        page: this.getPage() - 1
    });
};

GridView.prototype.switchGroup = function(group) {
    return this.load({
        group: group || this.getGroup()
    });
};

GridView.prototype.ui = function() {
    if (!this._ui) {
        var self = this, tableElem;
        this._ui = {
            getTable: function() {
                return tableElem || document.getElementsByTagName('table')[0];
            },
            setTable: function(elem) {
                tableElem = elem;
            },
            activate: function(elem, c) {
                var nodes = elem.parentNode.childNodes;
                for (var i = 0; i < nodes.length; ++i) {
                    if (nodes[i].nodeType === 1) {
                        utils.removeClass(nodes[i], c);
                    }
                }
                utils.addClass(elem, c);
                return self;
            },
            updateTable: function(data) {
                var table = this.getTable();
                for (var index = table.rows.length - 1; index > 0; --index) {
                    table.deleteRow(index);
                }
                console.log('Update table rows', data);
                var rowIndex = 1;
                for (var index in data) {
                    var row = table.insertRow(rowIndex++), colCount = 0;
                    for(var key in data[index]) {
                        if (data[index].hasOwnProperty(key)) {
                            var cell = row.insertCell(colCount++);
                            cell.innerHTML = data[index][key];
                        }
                    }
                }
            }
        };
    }
    return this._ui;
};