/**
 * Created by Gold Service on 12/06/2017.
 */
function showError(response) {
    $("#error-message").html("<strong>Errore: </strong>"+response.reason);
    $("#error-code").html("<strong>Codice: </strong>"+response.code);
    $("#errorModal").modal();
}