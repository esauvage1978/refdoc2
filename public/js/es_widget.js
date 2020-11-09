function required(field) {
    field.prop("required", true);
}

function unrequired(field) {
    field.prop("required", false);
}

function disabled(field) {
    field.prop("disabled", true);
}

function undisabled(field) {
    field.prop("disabled", false);
}

function html(field, value) {
    field.html(value);
}

function getVal(field) {
    return field.val();
}

function getText(field) {
    return field.text();
}

function setVal(field, value) {
    return field.val(value);
}

function hide(field) {
    field.addClass('d-none');
}

function show(field) {
    field.removeClass('d-none');
}

