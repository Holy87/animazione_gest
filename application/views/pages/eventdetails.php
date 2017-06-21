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
 * @return string
 */
function mode($party) {
    if($party->party_id == 0)
        return 'create';
    else
        return 'update';
}

/**
 * @param Party $party
 */
function theme_select($party) {
    $themes = PartyTheme::getAllThemes();
    /** @var PartyTheme $theme */
    $outp = '';
    foreach($themes as $theme) {
        $sel = '';
        if ($theme->id == $party->theme_id)
            $sel = 'selected';
        $outp.= "<option value=\"$theme->id\" $sel>$theme->name</option>";
    }
    echo $outp;
}


?>

<div class="container">
    <h1>Dettagli festa</h1>
    <div class="row">
        <div class="col-md-6">
            <form id="edit-form">
                <input type="hidden" id="party-id" name="party-id" value="<?php $party->party_id ?>">
                <input type="hidden" id="mode" name="mode" value="<?php echo mode($party) ?>">
                <div class="form-group">
                    <label for="party-customer">Nome del cliente</label>
                    <input type="text" class="form-control" id="party-customer" name="party-customer" placeholder="Il nome del cliente per ricordare una festa" required>
                </div>
                <div class="form-group">
                    <label for="party-address">Indirizzo</label>
                    <textarea id="party-address" class="form-control" name="party-address"></textarea>
                </div>
                <div class="form-group">
                    <label for="theme-id">Tema</label>
                    <select id="theme-id" class="form-control">
                        <?php theme_select($party) ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="party-date">Data</label>
                        <input id="party-date" type="date" name="party-date" placeholder="aaaa/mm/gg" value="<?php echo $party->date ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Ora</label>
                        <input id="party-hour" type="time" name="party-hour" placeholder="HH:MM" value="<?php echo $party->time ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="theme-price">Prezzo</label>
                    <div class="input-group col-sm-4">
                        <span class="input-group-addon">€</span>
                        <input type="number" min="0" step="any" class="form-control" id="party-price" name="party-price" placeholder="Prezzo totale" value="<?php echo $party->price ?>" required>
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
                    <form id="users-form">
                        <div class="row">
                            <div class="col-lg-8">
                                <select title="Seleziona un animatore" id="add-user" class="form-control" name="user-id" required>
                                    <option value="" disabled selected>Seleziona un animatore</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <button type="submit" id="add-btn" class="btn btn-primary">Aggiungi</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table" id="users-table">
                        <thead>
                        <tr>
                            <td></td>
                            <td>Nome</td>
                            <td>Telefono</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <h5>Oggetti</h5>
                <form id="item-form" <?php hidden_users() ?>>
                    <div class="row" >
                        <div class="col-md-6">
                            <input type="hidden" value="<?php echo $party->party_id ?>" name="party-id">
                            <div class="form-group">
                                <label for="add-item">Aggiungi oggetto</label>
                                <select id="add-item" class="form-control" name="item-id" required>
                                    <!-- OGGETTI -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="item-number">Quantità</label>
                                <input type="number" placeholder="N." min="1" class="form-control" id="item-number" value="1" name="item-number" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="add-btn">Aggiungi</label>
                            <button type="submit" id="add-btn" class="btn btn-primary">Aggiungi</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table" id="items-table" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Oggetto</th>
                            <th>Quantità</th>
                            <th>Azioni</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
