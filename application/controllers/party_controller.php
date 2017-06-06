<?php

/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 01/06/2017
 * Time: 11:46
 */
require_once ABS_PATH.'/application/models/Party.php';
require_once ABS_PATH.'/application/models/PartyTheme.php';
require_once ABS_PATH.'/application/models/User.php';

class PartyController
{
    public static function get_active_parties() {
        $user = User::getCurrent();
        if($user->access_level <= 0)
            return json_encode(['ok' => false, 'reason' => 'Non sei autenticato.', 'code' => -2]);
        $parties = Party::get_all();
        $ret = [];
        /** @var Party $party */
        foreach ($parties as $party) {
            if(!$party->is_done()) {
                $id = $party->party_id;
                $theme = $party->get_theme()->name;
                $animators = $party->get_animators_names();
                $date = $party->get_printable_date();
                $hour = $party->get_printable_hour();
                $ret[] = ['id' => $id, 'theme' => $theme, 'animators' => $animators, 'date' => $date, 'hour' => $hour];
            }
        }
        return json_encode(['data' => $ret]);
    }

    public static function get_passed_parties() {
        $user = User::getCurrent();
        if($user->access_level <= 0)
            return json_encode(['ok' => false, 'reason' => 'Non sei autenticato.', 'code' => -2]);
        $parties = Party::get_all();
        $ret = [];
        /** @var Party $party */
        foreach ($parties as $party) {
            if($party->is_done()) {
                $id = $party->party_id;
                $theme = $party->get_theme()->name;
                $animators = $party->get_animators_names();
                $date = $party->get_printable_date();
                $hour = $party->get_printable_hour();
                $ret[] = ['id' => $id, 'theme' => $theme, 'animators' => $animators, 'date' => $date, 'hour' => $hour];
            }
        }
        return json_encode(['data' => $ret]);
    }

    public static function save_party_informations() {
        $user = User::getCurrent();
        if(!$user->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per eseguire questa azione.', 'code' => -2]);
        if(!isset($_POST['party-id']) || !isset($_POST['party-date']) || !isset($_POST['party-hour']) || !isset($_POST['party-price']) || !isset($_POST['party-theme']))
            return json_encode(['ok' => false, 'reason' => 'Parametri richiesta errati.', 'code' => -2]);
        $party = Party::get_party($_POST['party-id']);
        $party->date = $_POST['party-date'];
        $party->time = $_POST['party-hour'];
        $party->address = $_POST['party-address'];
        $party->customer = $_POST['party-customer'];
        $party->theme_id = $_POST['party-theme'];
        $party->price = $_POST['party-price'];
        return json_encode($party->save());
    }

    public static function add_animator() {
        $user = User::getCurrent();
        if(!$user->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per eseguire questa azione.', 'code' => -2]);
        if(!isset($_POST['party-id']))
            return json_encode(['ok' => false, 'reason' => 'ID festa non impostato.', 'code' => -3]);
        if(!isset($_POST['animator-id']))
            return json_encode(['ok' => false, 'reason' => 'ID animatore non impostato.', 'code' => -3]);
        $party = Party::get_party($_POST['animator-id']);
        if($party == null)
            return json_encode(['ok' => false, 'reason' => 'Festa non trovata.', 'code' => 0]);
        $animator = User::get_user($_POST['animator-id']);
        if($animator == null)
            return json_encode(['ok' => false, 'reason' => 'Animatore non trovato.', 'code' => 0]);
        return json_encode($party->add_animator($animator));
    }

    public static function remove_animator() {
        $user = User::getCurrent();
        if(!$user->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per eseguire questa azione.', 'code' => -2]);
        if(!isset($_POST['party-id']))
            return json_encode(['ok' => false, 'reason' => 'ID festa non impostato.', 'code' => -3]);
        if(!isset($_POST['animator-id']))
            return json_encode(['ok' => false, 'reason' => 'ID animatore non impostato.', 'code' => -3]);
        $party = Party::get_party($_POST['animator-id']);
        if($party == null)
            return json_encode(['ok' => false, 'reason' => 'Festa non trovata.', 'code' => 0]);
        $animator = User::get_user($_POST['animator-id']);
        if($animator == null)
            return json_encode(['ok' => false, 'reason' => 'Animatore non trovato.', 'code' => 0]);
        return json_encode($party->remove_animator($animator));
    }

    public static function add_item() {

    }

    public static function remove_item() {

    }

    public static function increase_item_number() {

    }

    public static function decrease_item_number() {

    }

    public static function delete_party() {

    }

    public static function create_party() {

    }

}