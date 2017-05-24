/**
 * Created by Gold Service on 22/05/2017.
 */
function aggiornaNome() {
    $("#theme-title").html("Tema " + $("#theme-name").val());
}

$(document).ready(function(){
    var themeName = $("#theme-name");
    themeName.on("change", aggiornaNome);
    themeName.on("keyup", aggiornaNome);
});