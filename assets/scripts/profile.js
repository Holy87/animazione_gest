/**
 * Created by Gold Service on 17/05/2017.
 */
function checkMailAlreadyUsed() {
    $("#mail-input").removeClass("has-danger");
    $("#mail-state").html("");
    if(document.getElementById('user-mail').value.length > 0) {
        $.ajax({
            type: "POST",
            url: "services?action=check_mail",
            data: $("#mailedit").serialize(),
            dataType: "json",
            success: function(response) {
                if(!response.ok) {
                    $("#mail-input").addClass("has-danger");
                    $("#mail-state").html(response.reason);
                }
            }
        })
    }
}

function changeMailAddress(e) {
    var mailButton = $("#mailbtn");
    $("#mail-input").removeClass("has-danger");
    $("#mail-state").html("");
    mailButton.prop("disabled", "disabled");
    mailButton.html('Salvataggio...');
    $.ajax({
        type: "POST",
        url: "services?action=change_mail",
        data: $("#mailedit").serialize(),
        dataType: "json",
        success: function(response) {
            //console.log("Dati ricevuti: " + response.toString());
            mailButton.removeAttr("disabled");
            if(!response.ok) {
                $("#mail-input").addClass("has-danger");
                $("#mail-state").html(response.reason);
                mailButton.html("Salva");
            } else {
                mailButton.html('<i class="fa fa-check" aria-hidden="true"></i> Salvato');
                setTimeout(function() {
                    mailButton.html("Salva");
                }, 2000);
                $("#mail-input").addClass("has-success");
                $("#mail-state").html("Email salvata.");
                //showMessage("Indirizzo email modificato con successo");
            }
        }
    });
    e.preventDefault();
}

function passwordMatch() {
    return $("#new-password").val() === $("#check-password").val();
}

function passwordMatchFilled() {
    return $("#new-password").val().length > 0 && $("#check-password").val().length > 0
}

function checkPasswordMatch() {
    var passwordCheck = $("#password-check");
    passwordCheck.removeClass("has-success");
    passwordCheck.removeClass("has-danger");
    passwordCheck.html("");
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

function changeName(e) {
    var namebtn = $("#namebtn");
    var form = $("#profileedit");
    namebtn.prop("disabled", "disabled");
    namebtn.html('Salvataggio...');
    $.ajax({
        type: "POST",
        url: "services?action=change_name",
        data: form.serialize(),
        dataType: "json",
        success: function(response) {
            namebtn.removeAttr("disabled");
            namebtn.html('<i class="fa fa-check" aria-hidden="true"></i> Salvato');
            setTimeout(function() {namebtn.html("Salva");}, 2000);
            if(response.ok) {
                //showMessage("Hai cambiato il tuo nome");
                $("#profile-name").html(response.name);
                $("#dropdown02").html(response.name);
            } else {
                showMessage("Errore: " + response.reason);
            }
        }
    });
    e.preventDefault();
}

function showMessage(message) {
    $("#modal-message").html(message);
    $("#myModal").modal();
}

$(document).ready(function() {
    $("#user-mail").on("blur", checkMailAlreadyUsed);
    $("#new-password").on("blur", checkPasswordMatch);
    $("#check-password").on("blur", checkPasswordMatch);
    $("#mailedit").on("submit", function(e) {changeMailAddress(e)});
    $("#passedit").on("submit", function(e) {changePassword(e)});
    $("#profileedit").on("submit", function (e) {changeName(e)});
});