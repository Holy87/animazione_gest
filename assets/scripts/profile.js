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
    $("#mail-input").removeClass("has-danger");
    $("#mail-state").html("");
    $("#mailbtn").prop("disabled", "disabled");
    $("#mailbtn").html('Salvataggio...');
    $.ajax({
        type: "POST",
        url: "services?action=change_mail",
        data: $("#mailedit").serialize(),
        dataType: "json",
        success: function(response) {
            console.log("Dati ricevuti: " + response.toString());
            $("#mailbtn").removeAttr("disabled");
            if(!response.ok) {
                $("#mail-input").addClass("has-danger");
                $("#mail-state").html(response.reason);
                $("#mailbtn").html("Salva");
            } else {
                $("#mailbtn").html('<i class="fa fa-check" aria-hidden="true"></i> Salvato');
                setTimeout(function() {
                    $("#mailbtn").html("Salva");
                }, 2000);
                $("#mail-input").addClass("has-success");
                $("#mail-state").html("Email salvata.");
                showMessage("Indirizzo email modificato con successo");
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
    $("#password-check").removeClass("has-success");
    $("#password-check").removeClass("has-danger");
    $("#feedback-pcheck").html("");
    if(passwordMatchFilled()) {
        if(passwordMatch()) {
            $("#password-check").addClass("has-success");
            $("#feedback-pcheck").html("Verifica password accettata");
        } else {
            $("#password-check").addClass("has-danger");
            $("#feedback-pcheck").html("Le password non corrispondono");
        }
    }
}

function changePassword(e) {
    if(passwordMatchFilled() && passwordMatch()) {
        $("#password-div").removeClass("has-danger");
        $("#password-check").removeClass("has-success");
        $("#password-check").removeClass("has-danger");
        $("#pwd-state").html("");
        $("#feedback-pcheck").html("");
        $("#pwdsave").html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i> Salvataggio...');
        $("#pwdsave").prop("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "services?action=change_password",
            data: $("#passedit").serialize(),
            dataType: "json",
            success: function(response) {
                $("#pwdsave").removeAttr("disabled");
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
    console.log($("#profileedit").serialize());
    $("#namebtn").prop("disabled", "disabled");
    $("#namebtn").html('Salvataggio...');
    $.ajax({
        type: "POST",
        url: "services?action=change_name",
        data: $("#profileedit").serialize(),
        dataType: "json",
        success: function(response) {
            $("#namebtn").removeAttr("disabled");
            $("#namebtn").html("Salva");
            if(response.ok) {
                showMessage("Hai cambiato il tuo nome");
                $("#profile-name").html(response.name);
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