<?php
/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 16/05/2017
 * Time: 00:13
 */

/** @noinspection JSUnusedGlobalSymbols */

function buttonDisabled() {
    global $user;
    if($user->can_edit_events())
        return '';
    else
        return 'disabled';
}
?>

<div class="container">
    <h1>Inventario</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" data-item="0" <?php echo buttonDisabled(); ?>><i class="fa fa-plus" aria-hidden="true"></i> Nuovo materiale</button>

    <div class="alert alert-info" id="alert-info" role="alert">
        <i class='fa fa-spinner fa-spin '></i> Caricamento in corso...
    </div>
    <div class="table-responsive">
        <input type="hidden" value="<?php buttonDisabled() ?>" id="btn-disabled">
        <table id="itemt" class="table">
            <thead>
            <tr>
                <th>Codice</th>
                <th>Nome</th>
                <th>Quantità</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<!-- Modal DI CREAZIONE E MODIFICA -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="saveform">
                    <div id="name-form" class="form-group">
                        <input type="hidden" name="item-id" id="item-id">
                        <label for="item-name" class="form-control-label">Nome:</label>
                        <input placeholder="Nome oggetto" type="text" name="name" class="form-control" id="item-name" required>
                    </div><div class="form-group" id="num-form">
                        <label for="item-number" class="form-control-label">Numero:</label>
                        <input type="number" name="number" class="form-control" id="item-number" required>
                    </div>
                </form>
                <div class="alert alert-warning" id="num-alert" role="alert" hidden>
                    <strong>Attenzione!</strong> Devi insere un numero valido.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                <button type="button" id="save_button" class="btn btn-primary">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal DI ELIMINAZIONE -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Elimina oggetto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-delete">
                    <input type="hidden" name="item-id" id="delete-id">
                </form>
                <p class="modmess">Sei sicuro di voler eliminare?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" id="delete_button" class="btn btn-danger">Sì, elimina</button>
            </div>
        </div>
    </div>
</div>