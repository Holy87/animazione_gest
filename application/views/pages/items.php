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
<br>
<div class="container">
    <h1>Inventario</h1>
    <div class="container"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal" data-item="0" <?php echo buttonDisabled(); ?>><i class="fa fa-plus" aria-hidden="true"></i> Nuovo materiale</button></div>
    <br>
    <div class="alert alert-info" id="alert-info" role="alert" hidden>
        <i class='fa fa-spinner fa-spin '></i> Caricamento in corso...
    </div>
    <div class="table-responsive">
        <input type="hidden" value="<?php buttonDisabled() ?>" id="btn-disabled">
        <table id="itemt" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>Codice</th>
                <th>Nome</th>
                <th>Quantità</th>
                <th>Reparto</th>
                <th>Azioni</th>
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
                    </div>
                    <div class="form-group" id="num-form">
                        <div class="row">
                            <label for="item-number" class="form-control-label">Numero:</label>
                            <div class="col-lg-offset-3 col-lg-6">
                                <div class="input-group">
                                    <input type="number" name="number" class="form-control" id="item-number" required>
                                    <span class="input-group-btn">
                                        <button class="btn btn-secondary" id="plus-btn" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </span>
                                    <span class="input-group-btn">
                                        <button class="btn btn-secondary" id="minus-btn" type="button"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-horizontal">
                        <div class="col-6 optioner">
                            <div class="form-group">
                                <label for="ward" class="form-control-label">Posizione:</label>
                                <select class="custom-select" name="ward" id="ward" required>
                                    <option value="1">Reparto 1</option>
                                    <option value="2">Reparto 2</option>
                                    <option value="3">Reparto 3</option>
                                    <option value="4">Reparto 4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group form-horizontal">
                                <label for="item-floor" class="form-control-label">Piano:</label>
                                <input type="number" name="floor" id="item-floor" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" name="consumable" value="consumable" id="consumable" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">È un oggetto che si consuma</span>
                        </label>
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