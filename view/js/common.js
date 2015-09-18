
var baseUrl = document.getElementsByTagName("base")[0].getAttribute("href");

function Constraint(value) {
    /**
     *
     * @returns {boolean}
     * @constructor
     */
    this.NotBlank = function() {
        return value.length != 0;
    };

    /**
     *
     * @returns {*}
     * @constructor
     */
    this.Email = function() {
        if (!value) return true;
        return /^(([a-zA-Z]|[0-9])|([-]|[_]|[.]))+[@](([a-zA-Z0-9])|([-])){2,63}[.](([a-zA-Z0-9]){2,63})+$/i.exec(value);
    };

    /**
     *
     * @returns {*}
     * @constructor
     */
    this.DateFormat = function() {
        if (!value) return true;
        return /^(?:(?:31(-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.exec(value);
    };

    /**
     *
     * @returns {boolean}
     * @constructor
     */
    this.Numeric = function() {
        if (!value) return true;
        return !isNaN(parseFloat(value)) && isFinite(value);
    };

    /**
     *
     * @returns {*}
     * @constructor
     */
    this.Password = function() {
        if (!value) return true;
        return /^.*(?=.{8,})(?=.*[a-zA-Z])(?=.*\d)(?=.*[!#$%&@? "]).*$/.exec(value);
    };
}

function RequiredField(id, constraints) {
    this.element = document.getElementById(id);
    var value;
    if (this.element) {
        value = this.element.value
    }
    var validators = new Constraint(value);

    /**
     *
     * @returns {boolean}
     * @constructor
     */
    this.IsValid = function() {
        var func;
        for (var key in constraints) {
            func  = constraints[key];
            try {
                if (!validators[func]()) {
                    return false;
                }
            } catch (err) {
                console.log(err);
            }
        }
        return true;
    };
}

/**
 * @param input RequiredField
 */
function validateField(input) {
    var parent = input.element.parentNode;
    if (input.IsValid()) {
        removeClass(parent , "has-errors");
        if (input.element.value) {
            addClass(parent, "has-success");
        } else {
            removeClass(parent, "has-success");
        }
        // remove error messages
        for (var i = 0; i < parent.childNodes.length; i++) {
            if (parent.childNodes[i].className == "message") {
                parent.childNodes[i].remove();
            }
        }
    } else {
        removeClass(parent, "has-success");
        addClass(parent, "has-errors");
    }
}

/**
 *
 * @param inputs Object
 */
function bindFeedbackValidation(inputs) {
    for (var key in inputs) {
        document.getElementById(key).addEventListener("keyup", function(e) {
            var id = e.target.getAttribute("id");
            validateField(new RequiredField(id, inputs[id]["constraints"]));
        }, false);
        document.getElementById(key).addEventListener("change", function(e) {
            var id = e.target.getAttribute("id");
            validateField(new RequiredField(id, inputs[id]["constraints"]));
        }, false);
    }
}

/**
 *
 * @param id
 */
function uploadImage(id) {
    var control = document.getElementById(id + "-file-control");
    var container = document.getElementById(id + "-container");
    var image = document.getElementById(id + "-image");
    var input = document.getElementById(id);
    if (!control.value) {
        return;
    }
    var formData = new FormData();
    formData.append("file", control.files[0], control.files[0]["name"]);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", baseUrl + "upload-file", true);
    xhr.contentType = "Content-Type: application/json";
    xhr.onload = function () {
        if (xhr.status === 200) {
            data = JSON.parse(xhr.response);
            if (data.message) {
                addClass(container, "has-errors");
                uploadErrorMessage(id, data.message);
                return;
            }
            image.src = data.fileUrl;
            input.value = data.fileUrl;
            removeClass(container, "empty-image");
            removeClass(container, "has-errors");
        } else {
            addClass(container, "has-errors");
            uploadErrorMessage(id, "Internal server error.");
        }
    };
    xhr.send(formData);
}

function uploadErrorMessage(id, message) {
    document.getElementById(id + "-error-message").innerHTML = message;
}

function removeImage(id) {
    var input = document.getElementById(id);
    var image = document.getElementById(id + "-image");
    var container = document.getElementById(id + "-container");
    image.src = "";
    input.value = null;
    addClass(container, "empty-image");
}

function removeClass(node, className) {
    node.className = node.className.replace(new RegExp(className), "").replace(/\s{1,}$/, "");
}

function addClass(node, className) {
    var exp = new RegExp(className);
    node.className = node.className .replace(/\s{1,}$/, "") + (!exp.exec(node.className) ? " " + className : "");
}
