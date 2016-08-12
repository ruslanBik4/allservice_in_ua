function validateOtherField(field)
{
    return (field == "") ? "Вы заполнили не все поля.\n" : "";
}

function validateLogin(field)
{
    if (field == "") return "Не введено имя пользователя.\n";
    else if (field.length < 5)
        return "В имени пользователя должно быть не менее 5 символов.\n";
    else if (/[^a-zA-Z0-9_-]/.test(field))
        return "В имени пользователя разрешены только a-z, A-Z, 0-9, - и _.\n";
    return "";
}

function validatePassword(field) {
    if (field == "") return "Не введен пароль.\n";
    else if (field.length < 6)
        return "В пароле должно быть не менее 6 символов.\n";
    else if (!/[a-z]/.test(field) || ! /[A-Z]/.test(field) || !/[0-9]/.test(field))
        return "Пароль требует по одному символу из каждого набора a-z, A-Z и 0-9.\n";
    return "";
}

function validateRepeatPassword(field1, field2) {
    if (field2 != field1) return "Пароли не совпадают! Будьте внимательны!\n";
    else return "";
}

function validateEmail(field) {
    if (field == "") return "Не введен адрес электронной почты.\n";
    else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) ||/[^a-zA-Z0-9.@_-]/.test(field))
        return "Электронный адрес имеет неверный формат.\n";
    return "";
}

function validate(form) {
    fail = validateOtherField(form.ref_clients__company_name.value);
    // fail += validateUsername(form.username.value);
    // fail += validatePassword(form.passwordReg.value);
    // fail += validateRepeatPassword(form.passwordReg.value, form.passwordRepeat.value);
    // fail += validateEmail(form.emailReg.value);
    if (fail == "")
    {
        return true;
    }
    else
    {
        //document.getElementById('jsWarnings').innerText = fail+"\n";
        alert(fail); //Не очень красиво, когда посточнно выпрыгивает
        return false;
    }
}
