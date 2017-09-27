function request_pw(e) {
    var form = $("#resetform");
    var mailInput = $("#mail");
    var button = $("#sendbtn");
    var message = $("#mail-error");
    var capcham = $("capcha-error");
    mailInput.attr('disabled', true);
    button.attr('disabled', true);
    button.html('Invio richiesta...');
    message.removeClass("text-danger");
    message.removeClass("text-success");
    message.html("");
    capcham.html("");
    $.ajax({
        type: "POST",
        url: "services?action=recover_passeord",
        data: form.serialize(),
        dataType: "json",
        success: function (response) {
            // noinspection JSUnresolvedVariable
            if(response.ok) {
                button.html("Inviato");
                message.addClass("text-success");
                message.html("Email inviata. Controlla la tua casella di posta per reimpostare la password.");
            } else {
                button.html("Errore");
                if(response.code === 999) {
                    capcham.html(response.reason);
                    showError(response);
                } else {
                    //message.addClass("text-danger");
                    message.html("Errore nell'invio dell'email.");
                    showError(response);
                }
                setTimeout(function() {
                    button.html("Invia");
                    button.attr('disabled', false);
                    mailInput.attr("disabled", false);
                }, 3000);
            }
        }
    });
    e.preventDefault();
}

$(document).ready(function(){
    $("#resetform").on("submit", function(e) {request_pw(e)})
});