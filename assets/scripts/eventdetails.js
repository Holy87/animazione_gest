/**
 * Created by Gold Service on 21/06/2017.
 */

function aggiorna_selettore() {
    var selector = $("#add-user");
    var date = $("#party-date").val();
    selector.find('option').remove().end();
    $.ajax({
        type: 'post',
        url: 'services?action=get_active_users&date='+date,
        dataType: 'json',
        success: function(response) {
            if(response.ok) {
                var data = response.data;
                data.forEach(addUserList);
            } else {
                showError(response);
            }
        }
    })
}

function addUserList(item) {
    $('#add-user').append($('<option>', {
        value: item.id,
        text : item.name
    }));
}

function back() {window.location.href ="events"}

function aggiorna_animatori() {
    aggiorna_selettore();
    $("#users-table").DataTable().ajax.reload();
}

function aggiorna_oggetti() {
    $("#items-table").DataTable().ajax.reload();
}

function salva_festa(e) {
    var button = $("#save-btn");
    button.prop("disabled", "disabled");
    button.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Salvataggio...');
    $.ajax({
        type: "post",
        url: 'services?action=save_party',
        data: $("#edit-form").serialize(),
        dataType: 'json',
        success: function (response) {
            if(response.ok) {
                button.html('<i class="fa fa-check"></i> Salvato');
                aggiorna_oggetti();
                aggiorna_animatori();
            } else {
                showError(response);
                button.html("Errore!");
            }
            setTimeout(function() {
                button.html('Salva');
                button.removeAttr('disabled');
            }, 1000);
        }
    });
    e.preventDefault();
}

function crea_festa(e) {
    var button = $("#save-btn");
    button.prop("disabled", "disabled");
    button.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Creazione...');
    $.ajax({
        type: "post",
        url: 'services?action=create_party',
        data: $("#edit-form").serialize(),
        dataType: 'json',
        success: function (response) {
            if(response.ok) {
                button.html('<i class="fa fa-check"></i> Salvato');
                window.location.href = 'eventdetails?id='+response.id;
            } else {
                showError(response);
                button.html("Errore!");
            }
            setTimeout(function() {
                button.html('Crea');
                button.removeAttr('disabled');
            }, 2000);
        }
    });
    e.preventDefault();
}

function aggiornaPrezzo() {
    var priceInput = $("#party-price");
    var themeId = $("#theme-id").val();
    if(themeId === 'null')
        return;
    //var prezzo = parseInt(priceInput.val());
    $.ajax({
        type: 'post',
        url: 'services?action=theme_price&theme='+themeId,
        dataType: 'json',
        success: function (response) {
            if(response.ok) {
                priceInput.val(response.value);
            } else {
                console.log(response.reason);
            }
        }
    })
}

function elimina_festa(e) {
    $("#deleteModal").modal();
    e.preventDefault();
}

function conferma_elimina(e) {
    $("#deleteModal").modal("toggle");
    $.ajax({
        type: "post",
        url: 'services?action=delete_party',
        data: $("#edit-form").serialize(),
        dataType: 'json',
        success: function(response) {
            if(response.ok) {
                back();
            } else {
                showError(response);
            }
        }
    });
    e.preventDefault();
}

function renderPicture(data) {
    return '<div class="avatar-little"> <div class="circle-avatar" style="background-image:url('+data+')"></div></div>';
}

/**
 * @return {string}
 */
function RenderUsButton(id) {
    return '<button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Rimuovi l\'utente" onclick="deleteUser('+id+', this)"><i class="fa fa-minus-square-o"></i> Rimuovi</button>'
}

function renderItemButton(info) {
    var id = info.id;
    var disabled = '';
    var description;
    if(!info.own) {
        disabled = 'disabled';
        description = "L'oggetto fa parte del tema della festa, quindi non è possibile rimuoverlo.";
    } else
        description = "Rimuovi l'oggetto";
    var html = '';
    html += '<button class="btn btn-secondary btn-sm" onclick="increaseItem('+id+', this)"><i class="fa fa-plus-square"></i></button>';
    html += '<button class="btn btn-secondary btn-sm" onclick="decreaseItem('+id+', this)" '+disabled+'><i class="fa fa-minus-square"></i></button>';
    html += '<button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="'+description+'" onclick="deleteItem('+id+', this)" '+disabled+'><i class="fa fa-trash"></i> Rimuovi</button>';
    return html;
}

function decreaseItem(id, button) {
    button.disabled = true;
    var party = $("#party-id").val();
    $.ajax({
        type: 'post',
        url: 'services?action=decr_party_item_n',
        data: 'item-id='+id+'&party-id='+party,
        dataType: 'json',
        success: function (response) {
            button.disabled = false;
            if(response.ok)
                aggiorna_oggetti();
            else
                showError(response);
        }
    })
}

function increaseItem(id, button) {
    button.disabled = true;
    var party = $("#party-id").val();
    $.ajax({
        type: 'post',
        url: 'services?action=incr_party_item_n',
        data: 'item-id='+id+'&party-id='+party,
        dataType: 'json',
        success: function (response) {
            button.disabled = false;
            if(response.ok)
                aggiorna_oggetti();
            else
                showError(response);
        }
    })
}

function deleteUser(id, button) {
    button.disabled = true;
    var party = $("#party-id").val();
    $.ajax({
        type: "POST",
        url: "services?action=remove_party_animator",
        data: 'animator-id='+id+'&party-id='+party,
        dataType: "json",
        success: function (response) {
            button.disabled = false;
            if(response.ok) {
                aggiorna_animatori();
            } else {
                showError(response);
            }
        }
    })
}

function addUser(e) {
    var btn = $("#add-btn");
    btn.prop("disabled", "disabled");
    $.ajax({
        type: "post",
        url: "services?action=add_party_animator",
        data: $("#users-form").serialize(),
        dataType: 'json',
        success: function (response) {
            btn.removeAttr('disabled');
            if(response.ok) {
                aggiorna_animatori();
            } else {
                showError(response);
            }
        }
    });
    e.preventDefault();
}

function addItem(e) {
    var button = $("#add-btn");
    button.prop("disabled", "disabled");
    $.ajax({
        type: 'post',
        url: 'services?action=add_party_item',
        data: $("#item-form").serialize(),
        dataType: 'json',
        success: function(response) {
            button.removeAttr('disabled');
            if(response.ok) {
                aggiorna_oggetti();
            } else {
                showError(response);
            }
        }
    });
    e.preventDefault();
}

function deleteItem(id, button) {
    button.disabled = true;
    var party = $("#party-id").val();
    $.ajax({
        type: "POST",
        url: "services?action=remove_party_item",
        data: 'item-id='+id+'&party-id='+party,
        dataType: "json",
        success: function (response) {
            button.disabled = false;
            if(response.ok) {
                aggiorna_oggetti();
            } else {
                showError(response);
            }
        }
    })
}

function set_animatori() {
    var partyId = $("#party-id").val();
    $("#users-table").DataTable({
        paging: false,
        info: false,
        searching: false,
        'ajax' : 'services?action=get_party_animators&party_id='+partyId,
        'columns' : [
            {'data' : 'picture', 'searchable': false, 'orderable': false, 'type': 'html', 'render': function(data){return renderPicture(data)}},
            {'data' : 'name'},
            {'data' : 'phone'},
            {'data' : 'id', 'searchable': false, 'orderable': false, 'type': 'html', 'render': function(data){return RenderUsButton(data)}}
        ]
    })
}

function set_oggetti() {
    var partyId = $("#party-id").val();
    $("#items-table").DataTable({
        paging: false,
        info: false,
        searching: false,
        ajax: 'services?action=get_party_items&party-id='+partyId,
        columns: [
            {'data' : 'name'},
            {'data' : 'number', 'searchable': false, 'orderable': false},
            {'data' : 'info', 'searchable': false, 'orderable': false, 'type': 'html', 'render': function(data){return renderItemButton(data)}}
        ]
    })
}

$(document).ready(function() {
    $("#edit-form").submit(function(e) {
        if($("#mode").val() === 'create')
            crea_festa(e);
        else
            salva_festa(e);
    });

    $("#theme-id").on('change', aggiornaPrezzo);

    $("#users-form").submit(function (e) {
        addUser(e);
    });

    $("#item-form").submit(function (e) {
        addItem(e);
    });

    $("#back-btn").on("click", back);
    $("#delete_button").on("click", function (e) {conferma_elimina(e)});
    $("#delete-btn").on("click", function (e) {elimina_festa(e)});

    set_animatori();
    set_oggetti();

    if($("#mode").val() === 'update') {
        aggiorna_animatori();
        aggiorna_oggetti();
    }
});