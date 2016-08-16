function load_input_forms() {
    var fd = new FormData();
    fd.append('cmd', 'load_input_forms');
    //fd.append('args[product_id]', product_id);

    $.ajax({
        url: 'cmd.php',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        success: function (data) {
            alert(data);
            //$("#comments" + product_id).html(data);
        }
    });
}

function make_select_form() {

}

$(document).ready(function () {
    load_input_forms();
});

