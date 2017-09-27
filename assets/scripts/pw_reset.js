function passwordMatchFilled() {
    return $("#new-password").val().length > 0 && $("#check-password").val().length > 0
}

function checkPasswordMatch() {
    var passwordCheck = $("#password-check");
    passwordCheck.removeClass("has-success");
    passwordCheck.removeClass("has-danger");
    //passwordCheck.html("");
    if(passwordMatchFilled()) {
        if(passwordMatch()) {
            passwordCheck.addClass("has-success");
            $("#feedback-pcheck").html("Verifica password accettata");
        } else {
            passwordCheck.addClass("has-danger");
            $("#feedback-pcheck").html("Le password non corrispondono");
        }
    }
}

function changePassword(e) {
    var passwordCheck = $("#password-check");
    var passwordSave = $("#pwdsave");
    if(passwordMatchFilled() && passwordMatch()) {
        $("#password-div").removeClass("has-danger");
        passwordCheck.removeClass("has-success");
        passwordCheck.removeClass("has-danger");
        $("#pwd-state").html("");
        $("#feedback-pcheck").html("");
        passwordSave.html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i> Salvataggio...');
        passwordSave.prop("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "services?action=change_password",
            data: $("#passedit").serialize(),
            dataType: "json",
            success: function(response) {
                passwordSave.removeAttr("disabled");
                if(response.ok) {
                    showMessage("Password modificata");
                    $("#pwdsave").html('<i class="fa fa-check" aria-hidden="true"></i> Salvato');
                    setTimeout(function() {
                        $("#pwdsave").html("Modifica password");
                    }, 2000);
                } else {
                    $("#pwdsave").html("Modifica password");
                    $("#password-div").addClass("has-danger");
                    $("#pwd-state").html(response.reason);
                    console.log(response.old);
                }
            }
        })
    }
    e.preventDefault();
}

$(document).ready(function() {
    $("#new-password").on("blur", checkPasswordMatch);
    $("#check-password").on("blur", checkPasswordMatch);
    $("#reset").on("submit", function (e) {changePassword(e)});
});