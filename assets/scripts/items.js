/**
 * Created by Gold Service on 19/05/2017.
 */
function add_row(item) {
    var table = document.getElementById("itemt");
    var body = table.getElementsByTagName("tbody")[0];
    //var tr = document.createElement("tr");
    var tr = body.insertRow();
    tr.id = item.id;
    tr.insertCell(0).innerHTML = item.id;
    tr.insertCell(1).innerHTML = item.name;
    tr.insertCell(2).innerHTML = item.number;
    tr.insertCell(3).innerHTML = editButton(item.id) + " " + deleteButton(item.id, item.name);
}

function editButton(id) {
    return '<button class="btn btn-primary" data-toggle="modal" data-target="#editModal" data-toggle="tooltip" data-placement="top" title="Modifica oggetto" data-item="'+id+'" '+buttonDisabled()+'><i class="fa fa-pencil" aria-hidden="true"></i></button>';
}

function buttonDisabled() {
    return $("#btn-disabled").val();
}

function deleteButton(id, name) {
    return '<button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-toggle="tooltip" data-placement="top" title="Elimina oggetto" data-name="'+name+'" data-item="'+id+'"'+buttonDisabled()+'><i class="fa fa-trash" aria-hidden="true"></i></button>';
}

function salvaOggetto() {
    $("#editModal").modal("hide");
    $.ajax({
        type: "POST",
        url: "services?action=update_item",
        data: $("#saveform").serialize(),
        dataType: "json",
        success: function(response) {
            if (response.ok) {
                var row = document.getElementById(response.id);
                row.cells[1].innerHTML = response.name;
                row.cells[2].innerHTML = response.number;
            } else {
                alert("Impossibile modificare: oggetto non trovato");
            }
        }
    })
}

function numFieldCheck() {
    var num = $("#item-number");
    var field = num.val();
    if(isANumber(field)){
        document.getElementById("num-form").className = "form-group";
        document.getElementById("item-number").className = "form-control";
        $("#num-alert").prop("hidden", true);
    } else {
        document.getElementById("num-form").className = "form-group has-danger";
        document.getElementById("item-number").className = "form-control form-control-danger";
        $("#num-alert").prop("hidden", false);
    }
}

function nameFieldCheck() {
    var nameInput = document.getElementById("item-name");
    if(nameInput.value.length > 0){
        document.getElementById("name-form").className = "form-group";
        nameInput.className = "form-control";
    } else {
        document.getElementById("name-form").className = "form-group has-danger";
        nameInput.className = "form-control form-control-danger";
    }
}

function isANumber(string) {
    var patt = /^(\d+)$/;
    return patt.test(string);
}

function creaOggeto() {
    $("#editModal").modal("hide");
    $.ajax({
        type: "POST",
        url: "services?action=create_item",
        data: $("#saveform").serialize(),
        dataType: "json",
        success: function(response) {
            if (response.ok) {
                add_row(response);
            } else {
                alert("Errore: Impossibile creare");
            }
        }
    })
}

function eliminaOggetto() {
    $("#deleteModal").modal("hide");
    $.ajax({
        type: "POST",
        url: "services?action=delete_item",
        data: $("#form-delete").serialize(),
        dataType: "json",
        success: function(response) {
            if(response.ok) {
                var row = document.getElementById(response.id).rowIndex;
                var table = document.getElementById("itemt");
                table.deleteRow(row);
            } else {
                alert("Errore nella eliminazione: oggetto non trovato.");
            }
        }
    })
}

$(document).ready(function() {
    console.log("test");
    $.ajax({
        type: "POST",
        url: "services?action=get_items",
        dataType: "json",
        success: function(response) {
            $("#alert-info").hide();
            response.forEach(add_row);
        }
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);// Button that triggered the modal
        var recipient = button.data('item');// Extract info from data-* attributes
        var name = button.data("name");
        var modal = $(this);
        modal.find(".modmess").text("Sei sicuro di voler eliminare "+name+"? L\'operazione non può essere annullata.");
        modal.find("#delete-id").val(recipient);
    });

    $('#editModal').on('show.bs.modal', function (event) {
        $("#num-alert").prop("hidden", true);
        document.getElementById("num-form").className = "form-group";
        document.getElementById("item-number").className = "form-control";
        document.getElementById("name-form").className = "form-group";
        document.getElementById("item-name").className = "form-control";
        var button = $(event.relatedTarget);// Button that triggered the modal
        var recipient = button.data('item');// Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal\'s content. We\'ll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        var value = parseInt(recipient);
        if (value > 0) {
            modal.find('.modal-title').text('Modifica oggetto');
            //modal.find(\'.modal-body input\').val(recipient)
            $.ajax({
                type: "POST",
                url: "services?action=get_item&id="+recipient,
                dataType: "json",
                success: function(response) {
                    modal.find("#item-id").val(response.id);
                    modal.find("#item-name").val(response.name);
                    modal.find("#item-number").val(response.number);
                }
            })
        } else {
            modal.find('.modal-title').text('Nuovo oggetto');
            modal.find("#item-id").val(0);
            modal.find("#item-name").val("");
            modal.find("#item-number").val("");
        }
    });

    $("#save_button").on("click", function() {
        if(isANumber($("#item-number").val()) && $("#item-name").val().length > 0)
        {
            if ($("#item-id").val() > 0)
                salvaOggetto();
            else
                creaOggeto();
        } else {
        }

    });

    $("#delete_button").on("click", function(){eliminaOggetto()});
    $("#item-number").on("blur", function(){numFieldCheck()});
    $("#item-name").on("blur", function() {nameFieldCheck()});
});