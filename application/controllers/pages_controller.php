<?php
class NavigationController {
    /**
     * Va alla pagina di errore
     */
    public function error() {
        require_once ABS_PATH.'/application/views/pages/error.php';
    }

    /**
     * Disconnette l'utente
     */
    public function logout() {
        $_SESSION['user_id'] = null;
        $_SESSION = [];
        session_destroy();
        header('location: login');
    }

    /**
     * Va ad una pagina specifica
     * @param string $page
     */
    public function go_to($page) {
        switch ($page) {
            case 'logout':
                $this->logout();
                break;
            default:
                if (file_exists(ABS_PATH."/application/views/pages/$page.php"))
                    require_once(ABS_PATH."/application/views/pages/$page.php");
                else
                    $this->error();
        }
    }
}