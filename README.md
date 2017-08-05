# animazione_gest
È un gestionale web per agenzie di animazione (tuttora in sviluppo e mancante di molte funzioni)

**Utilizzo**
1. Inserire la cartella del progetto nella cartella web di Apache. Di default è root/animazione, ma puoi configurare il nome della cartella nel file config.php
2. Nel file config.php puoi impostare i parametri di connessione al database, la directory e tutti i parametri necessari.
3. Collegarvi da browser. Vi porterà automaticamente alla pagina di login. La prima volta che tenterete l'accesso, il programma ricostruirà un database vuoto con un utente amministratore. I parametri sono admin come username e password. Al primo login ci metterà un po' perché dovrà ricostruire il database.

**Configurazioni**
Per configurare il programma, apri il file config.php.
In particolare, assicurati che il parametro Web Root per il percorso sia corretto.
Per cambiare il livello d'accesso alle voci del menu, al momento l'unico modo è cambiare l'inizializzazione della clase MenuContainer

public function __construct()
    {
        $this->menu_elements = [
            new MenuElement('Home', 'home', 1, 'home', 'fa-home'),
            new MenuElement('Eventi', 'events', 1, 'events', 'fa-calendar'),
            new MenuElement('Temi Feste', 'themes', 2, 'themes', 'fa-magic'),
            new MenuElement('Inventario', 'items', 2,'items', 'fa-archive'),
            new MenuElement('Utenti', 'users', 3, 'users', 'fa-users')
        ];
    }
    
Cambia il terzo parametro a seconda del livello d'accesso richiesto. Ricordati che:
0: bannato
1: normale animatore
2: gestione e segreteria (può modificare l'inventario, le feste ecc...)
3: amministratore (può anche gestire utenti)