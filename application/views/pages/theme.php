<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 22/05/2017
 * Time: 11:20
 */
$user = User::getCurrent();
$theme = null;

if(isset($_GET['theme_id']))
{
    $theme = PartyTheme::getTheme($_GET['theme_id']);
    if($theme == null)
        header("location: error");
} else {
    $theme = new PartyTheme(0 , '', 0, '');
}

function is_edit() {
    return isset($_GET['theme_id']);
}

function hide_banner() {
    if(is_edit()) echo 'hidden';
}

function hide_table() {
    if(!is_edit()) echo 'hidden';
}

function mode() {
    if (is_edit())
        echo 'update';
    else
        echo 'create';
}

/**
 * @param PartyTheme $theme
 */
function items_select($theme) {
    $items = Item::get_all();
    $output = '';
    /** @var Item $item */
    foreach($items as $item) {
        $output.= '<option value="'.$item->id.'">'.$item->name.'</option>';
    }
    echo $output;
}

?>
<br>
<div class="container">
    <h1 id="theme-title">Tema <?php echo $theme->name ?></h1>
    <p>I temi per le feste raggruppano gli oggetti che possono servire alla festa, e predispongono un prezzo base.</p>
    <div class="row">
        <div class="col-md-6">
            <form id="master-form">
                <input type="hidden" id="theme-id" name="theme-id" value="<?php echo $theme->id ?>">
                <input type="hidden" id="mode" name="mode" value="<?php mode() ?>">
                <div class="form-group">
                    <label for="theme-name">Nome del tema</label>
                    <input type="text" class="form-control form-control-lg" id="theme-name" name="theme-name" placeholder="Inserisci un nome nel tema" maxlength="50" value="<?php echo $theme->name ?>">
                </div>
                <div class="form-group">
                    <label for="theme-description">Descrizione</label>
                    <textarea placeholder="Inserisci una descrizione che possa ricordare il tema" class="form-control" name="theme-description" id="theme-description"><?php echo $theme->description ?></textarea>
                </div>
                <div class="form-group">
                    <label for="theme-price">Prezzo (Euro)</label>
                    <div class="input-group col-sm-4">
                        <span class="input-group-addon">€</span>
                        <input type="number" min="0" step="any" class="form-control" id="theme-price" name="theme-price" placeholder="100" value="<?php echo $theme->price ?>">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" id="save-btn" class="btn btn-primary btn-block">Salva</button>
                    <button type="button" id="back-btn" class="btn btn-secondary btn-block">Indietro</button>
                </div>
            </form>
        </div>
        <div class="col-md-6" <?php hide_banner() ?>>
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading"><i class="fa fa-info-circle" aria-hidden="true"></i> Informazioni</h4>
                <hr>
                <p>Nella creazione del tema non puoi aggiungere oggetti.</p>
                <p class="mb-0">Crea il tema, quindi riaprilo in modifica e potrai inserire gli oggetti del tema per la festa.</p>
            </div>
        </div>
        <div class="col-md-6" <?php hide_table() ?>>
            <form id="item-form">
                <div class="row" >
                    <div class="col-sm-6">
                        <input type="hidden" value="<?php echo $theme->id ?>" name="theme-id">
                        <div class="form-group">
                            <label for="add-item">Aggiungi oggetto</label>
                            <select id="add-item" class="form-control" name="item-id" required>
                                <?php items_select($theme) ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input type="number" placeholder="N." min="1" class="form-control" id="item-number" value="1" name="item-number" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
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
