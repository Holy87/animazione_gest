/**
 * Created by frbos on 22/05/2017.
 */
function createUser() {
    var btn = $("#actionbtn");
    btn.attr("disabled", "disabled");
    btn.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Elaborazione...');
    $.ajax({
        type: "POST",
        url: "services?action=create_user",
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
    btn.attr("disabled", "disabled");
    btn.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Elaborazione...');
    $.ajax({
        type: "POST",
        url: "services?action=edit_user_master",
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
                btn.html("Crea");
            }

        }
    })
}

$(document).ready(function(){
        $("#masterform").on("submit", function() {
            if(("#mode").val() === 'new')
                createUser();
            else
                editUser();
        })
    });