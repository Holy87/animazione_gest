/**
 * Created by Gold Service on 22/05/2017.
 */
function aggiornaNome() {
    $("#theme-title").html("Tema " + $("#theme-name").val());
}

function adderButton(id) {
    return '<button class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Aggiungi 1 unità" onclick="addNum('+id+'"';
}

function subberButton(id) {

}

function deleteButton(id) {

}

function renderButtons(id) {
    return '<div class="btn-group" role="group">' + adderButton(id) + subberButton(id) + deleteButton(id) + '</div>';
}

$(document).ready(function(){
    var themeName = $("#theme-name");
    themeName.on("change", aggiornaNome);
    themeName.on("keyup", aggiornaNome);

    $("#items-table").DataTable({
        "ajax" : "services?action=get_theme_items&theme_id="+id,
        "columns": [
            {"data" : "name"},
            {"data" : "number", "searchable": false},
            {"data" : "id", "searchable": false, "orderable": false, "type": "html", "render": function(data){return renderButtons()}}
        ]

    })
});