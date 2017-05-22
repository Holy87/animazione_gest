/**
 * Created by Gold Service on 22/05/2017.
 */
function aggiornaNome() {
    $("#theme-title").html("Tema " + $("#theme-name").val());
}

$(document).ready(function(){
    $("#theme-name").on("change", aggiornaNome);
    $("#theme-name").on("keyup", aggiornaNome);
});