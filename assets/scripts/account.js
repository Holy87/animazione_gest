/**
 * Created by frbos on 22/05/2017.
 */
function createUser() {
    var btn = $("#actionbtn");
    var form = $("#masterform");
    btn.attr("disabled", "disabled");
    btn.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Elaborazione...');
    $.ajax({
        type: "POST",
        url: "services?action=create_user",
        data: form.serialize(),
        dataType: "json",
        success: function(response) {
            if(response.ok)
            {
                $("#actionbtn").html('<i class="fa fa-check" aria-hidden="true"></i> Salvato');
                window.location.href = "users";
            } else
            {
                alert(response.reason);
                btn.html("Crea");
                btn.removeAttr("disabled");
            }

        }
    })
}

function editUser() {
    var btn = $("#actionbtn");
    var form = $("#masterform");
    btn.attr("disabled", "disabled");
    btn.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Elaborazione...');
    $.ajax({
        type: "POST",
        url: "services?action=edit_user_master",
        data: form.serialize(),
        dataType: "json",
        success: function(response) {
            btn = $("#actionbtn");
            btn.removeAttr("disabled");
            if(response.ok)
            {
                btn.html('<i class="fa fa-check" aria-hidden="true"></i> Salvato');
                setTimeout(function() {$("#actionbtn").html("Modifica");}, 2000);
            } else
            {
                alert(response.reason);
                btn.html("Aggiorna");
            }

        }
    })
}

function deleteUser() {
    var btn = $("#delete_button");
    btn.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Eliminazione...');
    $.ajax({
        type: "POST",
        url: "services?action=delete_user",
        data: $("#masterform").serialize(),
        dataType: "json",
        success: function(response) {
            if(response.ok) {
                window.location.href="users";
            } else {
                $(".alert-danger").removeAttr("hidden");
                $("#error-code").html(response.code);
                $("#error-message").html(response.reason);
                btn.html("Sì, elimina");
                btn.removeAttr("disabled");
            }
        }
    })
}

$(document).ready(function(){
    $("#masterform").submit(function(e) {
        if($("#mode").val() === 'new') {
            createUser();
        }
        else {
            editUser();
        }
        e.preventDefault();
    });
    $("#delete_button").on("click", function() {
        deleteUser();
    })
});