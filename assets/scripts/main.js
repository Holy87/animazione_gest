/**
 * Created by Gold Service on 12/06/2017.
 */
function showError(response) {
    $("#error-message").html("<strong>Errore: </strong>"+response.reason);
    $("#error-code").html("<strong>Codice: </strong>"+response.code);
    $("#errorModal").modal();
}

// noinspection JSUnusedLocalSymbols
$('.alert').on('closed.bs.alert', function (e) {
    $(".deletable").remove();
    $.ajax({
        type: "POST",
        url: "services?action=update_user_version",
        dataType: "json",
        data: '',
        success: function(response) {
            if(response.ok) {
                console.log('Aggiornato');
            } else {
                showError(response);
            }
        }
    })
});