<?php
/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 09/06/2017
 * Time: 00:44
 */

$party = null;
if(isset($_GET['id']))
    $party = Party::get_party($_GET['id']);
else
    $party = Party::get_party(0);

/**
 * @param Party $party
 */
function hidden_message($party) {
    if($party->party_id != 0)
        echo 'hidden';
}

/**
 * @param Party $party
 */
function hidden_otion($party) {
    if($party->party_id == 0)
        echo 'hidden';
}

function hidden_users() {
    global $user;
    if(!$user->can_edit_events())
        echo 'hidden';
}

/**
 * @param Party $party
 */
function mode($party) {
    if($party->party_id == 0)
        return 'create';
    else
        return 'update';
}


?>

<div class="container">
    <h1>Dettagli festa</h1>
    <div class="row">
        <div class="col-md-6">
            <form id="edit-form">
                <input type="hidden" name="party-id" value="<?php $party->party_id ?>">
                <input type="hidden" name="mode" value="<?php echo mode($party) ?>">
                <div class="form-group">
                    <label for="party-customer">Nome del cliente</label>
                    <input type="text" class="form-control" id="party-customer" name="party-customer" placeholder="Il nome del cliente per ricordare una festa" required>
                </div>
                <div class="form-group">
                    <label for="party-address">Indirizzo</label>
                    <textarea id="party-address" name="party-address">
                </div>
                <div class="form-group">
                    <label for="theme-price">Prezzo (Euro)</label>
                    <div class="input-group col-sm-4">
                        <span class="input-group-addon">€</span>
                        <input type="number" min="0" step="any" class="form-control" id="party-price" name="party-price" placeholder="Prezzo totale" value="<?php echo $party->price ?>">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" id="save-btn" class="btn btn-primary btn-block">Salva</button>
                    <button type="button" id="back-btn" class="btn btn-secondary btn-block">Indietro</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="alert alert-info" role="alert" <?php hidden_message($party) ?>>
                <h4 class="alert-heading"><i class="fa fa-info-circle" aria-hidden="true"></i> Informazioni</h4>
                <hr>
                <p>Nella creazione della festa non puoi inserire oggetti e animatori.</p>
                <p class="mb-0">Crea prima la festa, quindi inserisci gli oggetti.</p>
            </div>
            <div class="container" <?php hidden_otion($party) ?>>
                <h5>Animatori</h5>
                <div class="form-group" <?php hidden_users() ?>>
                    <label for="add-user">Aggiungi oggetto</label>
                    <select id="add-user" class="form-control" name="user-id" required>
                        <!-- JSON users -->
                    </select>
                    <button type="submit" id="add-btn" class="btn btn-primary">Aggiungi</button>
                </div>
            </div>
        </div>
    </div>
</div>
