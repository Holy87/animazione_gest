/**
 * Created by Gold Service on 21/06/2017.
 */

function aggiorna_selettore() {
    var selector = $("#add-user");
    var date = $("#");
    selector.find('option').remove().end();
    $.ajax({
        type: 'post',
        url: 'services.php?action=get_active_users&date='+$("#party-date").val(),
        dataType: 'json',
        success: function (response) {
            $.each(response, function (i, item) {
                $('#add-user').append($('<option>', {
                    value: item.id,
                    text : item.name
                }));
            });
        }
    })
}

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
                button.html('<i class="fa fa-check" aria-hidden="true"></i> Salvato');
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
                button.html('<i class="fa fa-check" aria-hidden="true"></i> Salvato');
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

}

function RenderUsButtons(data) {

}

function set_animatori() {
    var partyId = $("#party-id").val();
    $("#users-table").DataTable({
        'ajax' : 'services?action=get_party_animators&theme_id='+partyId,
        'columns' : [
            {'data' : 'picture', 'searchable': false, 'orderable': false, 'type': 'html', 'render': function(data){return renderPicture(data)}},
            {'data' : 'name'},
            {'data' : 'id', 'searchable': false, 'orderable': false, 'type': 'html', 'render': function(data){return RenderUsButtons(data)}}
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

    $("#back-btn").on("click", back);

    set_animatori();
    set_oggetti();

    if($("#mode").val() === 'edit') {
        aggiorna_animatori();
        aggiorna_oggetti();
    }
});