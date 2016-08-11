function send_form(objForm, url) {
    if (verify_fields(objForm)) {
        var fd = new FormData();
        fd.append('form_name', objForm.attr('name'));

        var fields = [];
        objForm.find('input[name]').each(
            function (i, elem) {
                var field = {};
                field.name = $(elem).attr('name');
                field.value = $(elem).val();
                field.db_table_name = $(elem).data('db_table_name');
                field.db_field_name = $(elem).data('db_field_name');
                fields.push(field);
            }
        );
        fd.append('fields', JSON.stringify(fields));

        $.ajax({
            url: url,
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function (data) {
                $("body").html(data);
            }
        });
    }
}


function verify_fields(objForm) {
    var retVal;
    retVal = true;
    objForm.find('input[name]').each(
        function (i, elem) {
            $(elem).data('field_constraints').forEach(function (constraint) {

                if (constraint.name == 'min_len') {
                    if ($(elem).val().length < constraint.value) {
                        retVal = false;
                        alert('Длина ' + $(elem).attr('name') + ' меньше чем ' + constraint.value + ' символов.');
                    }
                }

                if (constraint.name == 'max_len') {
                    if ($(elem).val().length > constraint.value) {
                        retVal = false;
                        alert('Длина ' + $(elem).attr('name') + ' больше чем ' + constraint.value + ' символов.');
                    }
                }
            });
        }
    );
    return retVal;
}
