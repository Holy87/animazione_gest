<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 22/05/2017
 * Time: 08:05
 */

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
    <h1>Temi feste</h1>
    <div class="container"><button type="button" class="btn btn-primary btn-sm"" data-toggle="modal" data-target="#editModal" data-item="0" <?php echo buttonDisabled(); ?>><i class="fa fa-plus" aria-hidden="true"></i> Nuovo tema</button></div>
    <br>
    <div class="table-responsive">
        <input type="hidden" onclick="createTheme()" value="<?php buttonDisabled() ?>" id="btn-disabled">
        <table id="themes" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>Tema</th>
                <th>Descrizione</th>
                <th>Prezzo</th>
                <th>Articoli</th>
                <th>Azioni</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<!-- Modal DI ELIMINAZIONE -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Elimina tema</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-delete">
                    <input type="hidden" name="item-id" id="delete-id">
                </form>
                <p class="modmess">Sei sicuro di voler eliminare questo tema?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" id="delete_button" class="btn btn-danger">Sì, elimina</button>
            </div>
        </div>
    </div>
</div>