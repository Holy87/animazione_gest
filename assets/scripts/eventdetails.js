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
        url: 'services.php?action=save_party',
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
            }, 2000);
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
        url: 'services.php?action=create_party',
        data: $("#edit-form").serialize(),
        dataType: 'json',
        success: function (response) {
            if(response.ok) {
                button.html('<i class="fa fa-check"></i> Salvato');
                location.reload();
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

function renderPicture(data) {
    return '<div class="avatar-little"> <div class="circle-avatar" style="background-image:url('+data+')"></div></div>';
}

/**
 * @return {string}
 */
function RenderUsButton(id) {
    return '<button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Rimuovi l\'utente" onclick="deleteUser('+id+', this)"><i class="fa fa-minus-square-o"></i> Rimuovi</button>'
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
            {'data' : 'id', 'searchable': false, 'orderable': false, 'type': 'html', 'render': function(data){return RenderUsButton(data)}}
        ]
    })
}

function set_oggetti() {

}

$(document).ready(function() {
    $("#edit-form").submit(function(e) {
        if($("#mode").val() === 'create')
            crea_festa(e);
        else
            salva_festa(e);
    });

    $("#users-form").submit(function (e) {
        addUser(e);
    });

    $("#back-btn").on("click", back);

    set_animatori();
    set_oggetti();

    if($("#mode").val() === 'update') {
        aggiorna_animatori();
        //aggiorna_oggetti();
    }
});