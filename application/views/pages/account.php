<?php

function go_error() {die("Non puoi visualizzare questa pagina");}

function subm_name() {
    if(!isset($_GET['user_id']))
        echo 'Crea';
    else
        echo 'Aggiorna';
}

/** @var User $user */
$user = User::getCurrent();
$o_user = new User(0, '','', '', 1);
$mode = 'edit';
if($user->can_create_users()) {
    if(isset($_GET['user_id'])) {
        $o_user = User::get_user($_GET['user_id']);
        $mode = 'edit';
        if($o_user == null) {
            go_error();
        }
    } else
        $mode = 'new';
} else {
    go_error();
}

function disable() {
    if(isset($_GET['user_id'])) {
        echo 'disabled';
    }
}

function required() {
    if(!isset($_GET['user_id'])) echo 'required';
}

/**
 * @param User $user
 * @param $role_num
 */
function role_selected($role_num, $user) {
    if($role_num == $user->access_level)
        echo 'selected';
}

function hidden_button() {
    if(!isset($_GET['user_id'])) {
        echo 'hidden';
    }
}
?>

<div class="container">
    <h1><?php subm_name() ?> utente</h1>
    <form id="masterform">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group-row">
                    <label class="col-2 col-form-label" for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" <?php disable() ?> placeholder="user" value="<?php echo $o_user->name ?>" required>
                </div>
                <div class="form-group-row">
                    <label class="col-2 col-form-label" for="userfriendly">Nome</label>
                    <input type="text" name="userfriendly" id="userfriendly" class="form-control" placeholder="Inserisci un nome di riconoscimento" value="<?php echo $o_user->friendly_name ?>" required>
                </div>
                <div class="form-group-row">
                    <label class="col-2 col-form-label" for="mail">Email</label>
                    <input type="email" name="mail" id="mail" class="form-control" placeholder="esempio@gmail.com" value="<?php echo $o_user->mail ?>" required>
                </div>
                <div class="form-group-row">
                    <label class="col-2 col-form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" <?php required() ?>>
                </div>
                <div class="form-group-row">
                    <label class="col-2 col-form-label" for="role">Ruolo</label>
                    <select class="custom-select mb-2 mr-sm-2 mb-sm-0" name="role" id="role"  required>
                        <option value="0" <?php role_selected(0, $o_user) ?>>Disattivato</option>
                        <option value="1" <?php role_selected(1, $o_user) ?>>Animatore normale</option>
                        <option value="2" <?php role_selected(2, $o_user) ?>>Gestione e segreteria</option>
                        <option value="3" <?php role_selected(3, $o_user) ?>>Amministratore</option>
                    </select>
                </div>
                <input type="hidden" value="<?php echo $o_user->id ?>" name="id">
                <input type="hidden" value="<?php echo $mode ?>" name="mode" id="mode">
                <br>
                <button type="submit" class="btn btn-primary btn-block" id="actionbtn"><?php subm_name() ?></button>
                <button type="button" class="btn btn-danger btn-block" id="deletebtn" data-toggle="modal" data-target="#deleteModal" <?php hidden_button() ?>>Elimina</button>
                <button type="button" class="btn btn-secondary btn-block">Indietro</button>
            </div>
        </div>
    </form>
</div>

<!-- Modal DI ELIMINAZIONE -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Elimina utente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-delete">
                    <input type="hidden" name="item-id" id="delete-id">
                </form>
                <p class="modmess">Sei sicuro di voler eliminare l'utente <?php echo $o_user->name ?>?</p>
                <div class="alert alert-danger" role="alert" hidden>
                    <strong>Errore: </strong> L'utente non può essere eliminato perché è presente in una o più feste. Rimuovi prima questo utente dalle feste.
                    <p>Codice errore: </p>
                    <p class="error-code"></p>
                    <p>Errore: </p>
                    <p class="error-message"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" id="delete_button" class="btn btn-danger">Sì, elimina</button>
            </div>
        </div>
    </div>
</div>