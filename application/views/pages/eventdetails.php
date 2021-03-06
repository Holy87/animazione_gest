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
function disabled_delete($party) {
    if($party->party_id == 0)
        echo 'disabled';
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
    $outp = '<option value="0">Scegli un tema</option>';
    foreach($themes as $theme) {
        $sel = '';
        if ($theme->id == $party->theme_id)
            $sel = 'selected';
        $outp.= "<option value=\"$theme->id\" $sel>$theme->name</option>";
    }
    echo $outp;
}

function get_all_items() {
    $items = Item::get_all();
    /** @var Item $item */
    $out = '';
    foreach($items as $item) {
        $id = $item->id;
        $name = $item->name;
        $out.= '<option value="'.$id.'">'.$name.'</option>';
    }
    echo $out;
}

function hour_selector() {

}


?>

<div class="container">
    <h1>Dettagli festa</h1>
    <div class="row">
        <div class="col-md-6">
            <form id="edit-form">
                <input type="hidden" id="party-id" name="party-id" value="<?php echo $party->party_id ?>">
                <input type="hidden" id="mode" name="mode" value="<?php echo mode($party) ?>">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="party-customer">Nome del cliente</label>
                            <input type="text" class="form-control" id="party-customer" maxlength="100" name="party-customer" placeholder="Il nome del cliente per ricordare una festa" value="<?php echo $party->customer ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="phone">Telefono</label>
                            <input type="tel" class="form-control" id="phone" maxlength="20" name="phone" placeholder="+39 123 456 78" value="<?php echo $party->phone ?>">
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="guest">Nome del festeggiato</label>
                            <input type="text" class="form-control" id="guest" maxlength="100" name="party-guest" value="<?php echo $party->guest_of_honor ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="guest_age">Età</label>
                            <input type="number" class="form-control" id="guest_age" min="0" maxlength="2" name="guest_age" value="<?php echo $party->guest_age ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="party-address">Indirizzo</label>
                    <textarea id="party-address" class="form-control" maxlength="200" name="party-address"><?php echo $party->address ?></textarea>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="theme-id">Tema</label>
                            <select id="theme-id" name="theme-id" class="form-control">
                                <?php theme_select($party) ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="mid_age">Età med</label>
                            <input type="number" class="form-control" id="mid_age" name="mid_age" min="0" value="<?php echo $party->generic_age ?>">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label for="children">N.Bambini</label>
                        <input type="number" min="1" class="form-control" name="children" id="children" value="<?php echo $party->child_number ?>">
                    </div>
                    <div class="col-sm-2">
                        <label for="hours">N. Ore</label>
                        <input id="hours" min="1" class="form-control" type="number" name="hours" value="<?php echo $party->hours ?>" required>
                    </div>
                    <div class="col-sm-3">
                        <label for="party-date">Data</label>
                        <input id="party-date" class="form-control" type="date" name="party-date" placeholder="aaaa/mm/gg" value="<?php echo date('Y-m-d', $party->get_date()->getTimestamp()) ?>" required>
                    </div>

                    <div class="col-sm-2">
                        <label>Ora</label>
                        <input id="party-hour" class="form-control" type="time" name="party-hour" placeholder="HH:MM"  value="<?php echo date('H:i', $party->get_time()->getTimestamp()) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="theme-price">Prezzo</label>
                            <div class="input-group">
                                <span class="input-group-addon">€</span>
                                <input type="number" min="0" step="any" class="form-control" id="party-price" name="party-price" placeholder="Prezzo totale" value="<?php echo $party->price ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fuel">Carburante</label>
                            <div class="input-group">
                                <span class="input-group-addon">€</span>
                                <input type="number" min="0" step="any" class="form-control" id="fuel" name="fuel" value="<?php echo $party->fuel ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <textarea name="notes" placeholder="Note..." class="form-control" maxlength="300"><?php echo $party->notes ?></textarea>
                </div>
                <hr>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <button id="save-btn" class="btn btn-primary btn-block">Salva</button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="back-btn" class="btn btn-secondary btn-block">Indietro</button>
                        </div>
                        <div class="col-md-4">
                            <button id="delete-btn" class="btn btn-danger btn-block" <?php disabled_delete($party) ?>>Elimina</button>
                        </div>
                    </div>
                </div>
            </form>
            <p <?php hidden_users() ?>>*Creato da <?php echo $party->get_creator()->friendly_name ?></p>
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
                        <input type="hidden" id="party-id2" name="party-id" value="<?php echo $party->party_id ?>">
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
                                    <?php get_all_items() ?>
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

<!-- Modal DI ELIMINAZIONE FESTA -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Conferma eliminazione</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="modmess">Sei sicuro di voler eliminare questa festa?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" id="delete_button" class="btn btn-danger">Sì, elimina</button>
            </div>
        </div>
    </div>
</div>