/**
 * Created by Gold Service on 22/05/2017.
 */
function addNum(id, button) {
    button.disabled = true;
    var theme = $("#theme-id").val();
    $.ajax({
        type: "POST",
        url: "services?action=incr_item",
        dataType: "json",
        data: 'item_id='+id+'&theme_id='+theme,
        success: function (response) {
            button.disabled = false;
            if(response.ok) {
                $("#items-table").DataTable().ajax.reload();
            } else {
                showError(response);
            }
        }
    })
}

function subNum(id, button) {
    button.disabled = true;
    var theme = $("#theme-id").val();
    $.ajax({
        type: "POST",
        url: "services?action=decr_item",
        dataType: "json",
        data: 'item_id='+id+'&theme_id='+theme,
        success: function (response) {
            button.disabled = false;
            if(response.ok) {
                $("#items-table").DataTable().ajax.reload();
            } else {
                showError(response);
            }
        }
    })
}

function back() {window.location.href ="themes"}

function deleteItem(id, button) {
    button.disabled = true;
    var theme = $("#theme-id").val();
    $.ajax({
        type: "POST",
        url: "services?action=delete_theme_item",
        data: 'item-id='+id+'&theme-id='+theme,
        dataType: "json",
        success: function (response) {
            button.disabled = false;
            if(response.ok) {
                $("#items-table").DataTable().ajax.reload();
            } else {
                showError(response);
            }
        }
    })
}

function save(e) {
    var button = $("#save-btn");
    button.prop("disabled", "disabled");
    button.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Salvataggio...');
    $.ajax({
        type: "post",
        url: 'services?action=save_theme',
        data: $("#master-form").serialize(),
        dataType: 'json',
        success: function (response) {
            if(response.ok) {
                button.html('<i class="fa fa-check" aria-hidden="true"></i> Salvato');
                if($("#mode").val() === 'create')
                    window.location.href = 'themes';
            } else {
                showError(response);
                button.html("Errore!");
            }
            setTimeout(function() {
                button.html('Salva');
                button.removeAttr('disabled');
            }, 2000);
        }
    });
    e.preventDefault();
}

function addItem(e) {
    var button = $("#add-btn");
    var option = $("#add-item");
    var number = $("#item-number");
    var form = $("#item-form");
    var data = form.serialize();
    button.prop("disabled", "disabled");
    option.prop("disabled", "disabled");
    number.prop("disabled", "disabled");
    button.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Aggiungo...');
    $.ajax({
        type: "post",
        url: 'services?action=add_theme_item',
        data: data,
        dataType: 'json',
        success: function (response) {
            button.removeAttr('disabled');
            option.removeAttr('disabled');
            number.removeAttr('disabled');
            button.html("Aggiungi");
            if(response.ok) {
                $("#items-table").DataTable().ajax.reload();
                number.val(1);
                //option.val(null);
            } else {
                showError(response);
            }
        }
    });
    e.preventDefault();
}


function aggiornaNome() {
    $("#theme-title").html("Tema " + $("#theme-name").val());
}

function adderButton(id) {
    return '<button class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Aggiungi 1 unità" onclick="addNum('+id+', this)"><i class="fa fa-plus" aria-hidden="true"></i></button>';
}

function subberButton(id) {
    return '<button class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Rimuovi 1 unità" onclick="subNum('+id+', this)"><i class="fa fa-minus" aria-hidden="true"></i></button>';
}

function deleteButton(id) {
    return '<button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Rimuovi l\'oggetto" onclick="deleteItem('+id+', this)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
}

function renderButtons(id) {
    return '<div class="btn-group" role="group">' + adderButton(id) + subberButton(id) + deleteButton(id) + '</div>';
}

function setTable() {
    var themeName = $("#theme-name");
    themeName.on("change", aggiornaNome);
    themeName.on("keyup", aggiornaNome);
    var id = $("#theme-id").val();

    $("#items-table").DataTable({
        "ajax" : "services?action=get_theme_items&theme_id="+id,
        "columns": [
            {"data" : "name"},
            {"data" : "number", "searchable": false},
            {"data" : "id", "searchable": false, "orderable": false, "type": "html", "render": function(data){return renderButtons(data)}}
        ]

    });
}

$(document).ready(function(){
    // Tabella oggetti tema
    setTable();
    // Impostazione submit della form di modifica dati
    $("#master-form").submit(function(e) {save(e)});
    // Impostazione submit della form di aggiunta oggetti
    $("#item-form").submit(function(e) {addItem(e)});

    $("#back-btn").on("click", back);
    // Impostazione di configurazione del select
    //$("#add-item").selectpicker();
    //$('select').select2();

});