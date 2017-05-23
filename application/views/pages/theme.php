<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 22/05/2017
 * Time: 11:20
 */
$user = User::getCurrent();
if(isset($_GET['theme_id']))
{
    $theme = PartyTheme::getTheme($_GET['theme_id']);
    if($theme == null)
        header("location: error");
} else {
    header("location: error");
}

?>
<br>
<div class="container">
    <h1 id="theme-title">Tema <?php echo $theme->name ?></h1>
    <p>I temi per le feste raggruppano gli oggetti che possono servire alla festa, e predispongono un prezzo base.</p>
    <div class="col-md-6">
        <form id="master-form">
            <div class="form-group">
                <label for="theme-name">Nome del tema</label>
                <input type="text" class="form-control form-control-lg" id="theme-name" name="name" placeholder="Inserisci un nome nel tema" value="<?php echo $theme->name ?>">
            </div>
            <div class="form-group">
                <label for="theme-description">Descrizione</label>
                <textarea placeholder="Inserisci una descrizione che possa ricordare il testo" class="form-control" name="theme-description" id="theme-description"><?php echo $theme->description ?></textarea>
            </div>
            <div class="form-group">
                <label for="theme-price">Prezzo (Euro)</label>
                <input type="number" class="form-control" id="theme-price" name="price" placeholder="100" value="<?php echo $theme->price ?>">
            </div>
            <div class="form-group">
                <button type="submit" id="save-btn" class="btn btn-primary btn-block">Salva</button>
            </div>
        </form>
    </div>
</div>
