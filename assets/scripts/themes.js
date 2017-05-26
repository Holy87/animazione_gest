/**
 * Created by Gold Service on 23/05/2017.
 */
function editButton(id) {
    return '<button class="btn btn-primary btn-sm" data-toggle="tooltip" onclick="editTheme('+id+')" data-placement="top" title="Modifica tema" '+buttonDisabled()+'><i class="fa fa-pencil" aria-hidden="true"></i></button>';
}

function buttonDisabled() {
    return $("#btn-disabled").val();
}

function renderButtons(id) {
    return '<div class="btn-group" role="group">' + editButton(id) + " " + deleteButton(id, "l'oggetto") + '</div>';
}

function createTheme() {
    window.location.href = "theme";
}

function editTheme(themeId) {
    window.location.href = "theme?theme_id="+themeId;
}

function deleteButton(id, name) {
    return '<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-toggle="tooltip" data-placement="top" title="Elimina tema" data-name="'+name+'" data-item="'+id+'"'+buttonDisabled()+'><i class="fa fa-trash" aria-hidden="true"></i></button>';
}


$(document).ready(function() {
   $("#themes").dataTable({
       "ajax": "services?action=get_themes",

       "columns": [
           {"data": "name"},
           {"data": "description", "orderable": false},
           {"data": "price"},
           {"data": "items", "orderable": false},
           {"data": "e_id", "searchable": false, "orderable": false, "type": "html", "render": function(data){return renderButtons(data)}}
       ]
   })
});