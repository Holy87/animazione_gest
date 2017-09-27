<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 20/09/2017
 * Time: 10:25
 */

?>

<div class="container">
    <h1>Recupera password</h1>
    <p>Hai dimenticato la password? Nessun problema! Inserisci la mail utilizzata per creare l'account. Se è registrata, riceverai una email con le istruzioni per riconfigurare la password.</p>
    <form id="resetform">
        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="6LcMZTEUAAAAADdx9xAJkQmTRYQW25Cdidw6qJOG"></div>
            <p class="text-danger" id="capcha-error"></p>
        </div>
        <div class="form-group">
            <label for="mail">Email dell'account</label>
            <div class="input-group">
                <input type="email" class="form-control" id="mail" name="mail" placeholder="es. rossi@gmail.com" required>
                <span class="input-group-btn">
                    <button class="btn btn-primary" id="sendbtn" type="submit">Invia</button>
                </span>
                <p id="mail-error"></p>
            </div>
        </div>
    </form>
    <p>Serve aiuto? <a href="#">Contatta un amministratore</a> o <a href="help">consulta le linee guida.</a></p>
</div>