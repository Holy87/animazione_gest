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
   });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);// Button that triggered the modal
        var recipient = button.data('item');// Extract info from data-* attributes
        var name = button.data("name");
        var modal = $(this);
        modal.find(".modmess").text("Sei sicuro di voler eliminare "+name+"? L\'operazione non può essere annullata.");
        modal.find("#delete-id").val(recipient);
    });

   $("#delete_button").on("click", function() {
       $("#deleteModal").modal("hide");
       $.ajax({
           type: "post",
           url: 'services?action=delete_theme',
           data: $("#form-delete").serialize(),
           dataType: 'json',
           success: function(response) {
               console.log(response);
               if(response.ok)
                   $("#themes").DataTable().ajax.reload();
               else
                   alert("Errore nella eliminazione: " + response.reason);
           }
       })
   })
});