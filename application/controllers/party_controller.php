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
                $theme = '';
                if($party->theme_id != null)
                    $theme = $party->get_theme()->name;
                $animators = $party->get_animators_names();
                $address = $party->address;
                $date = $party->get_printable_date();
                $hour = $party->get_printable_hour();
                $ret[] = ['id' => $id, 'address' => $address, 'theme' => $theme, 'animators' => $animators, 'date' => $date, 'hour' => $hour];
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
        if(!isset($_POST['party-id']) || !isset($_POST['party-date']) || !isset($_POST['party-hour']) || !isset($_POST['party-price']) || !isset($_POST['theme-id']))
            return json_encode(['ok' => false, 'reason' => 'Parametri richiesta errati.', 'code' => -2]);
        $party = Party::get_party($_POST['party-id']);
        $party->date = $_POST['party-date'];
        $party->time = $_POST['party-hour'];
        $party->address = $_POST['party-address'];
        $party->customer = $_POST['party-customer'];
        $party->theme_id = $_POST['theme-id'];
        $party->price = $_POST['party-price'];
        return json_encode($party->save());
    }

    public static function add_animator() {
        $user = User::getCurrent();
        if(!$user->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per eseguire questa azione.', 'code' => -2]);
        if(!isset($_POST['party-id']))
            return json_encode(['ok' => false, 'reason' => 'ID festa non impostato.', 'code' => -3]);
        if(!isset($_POST['user-id']))
            return json_encode(['ok' => false, 'reason' => 'ID animatore non impostato.', 'code' => -3]);
        $party = Party::get_party($_POST['party-id']);
        if($party == null)
            return json_encode(['ok' => false, 'reason' => 'Festa non trovata.', 'code' => 0]);
        $animator = User::get_user($_POST['user-id']);
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
        $party = Party::get_party($_POST['party-id']);
        if($party == null)
            return json_encode(['ok' => false, 'reason' => 'Festa non trovata.', 'code' => 0]);
        $animator = User::get_user($_POST['animator-id']);
        if($animator == null)
            return json_encode(['ok' => false, 'reason' => 'Animatore non trovato.', 'code' => 0]);
        return json_encode($party->remove_animator($animator));
    }

    public static function add_item() {
        $user = User::getCurrent();
        if(!$user->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per eseguire questa azione.', 'code' => -2]);
        if(!isset($_POST['party-id']))
            return json_encode(['ok' => false, 'reason' => 'ID festa non impostato.', 'code' => -3]);
        if(!isset($_POST['item-id']))
            return json_encode(['ok' => false, 'reason' => 'ID oggetto non impostato.', 'code' => -3]);
        if(!isset($_POST['item-number']))
            return json_encode(['ok' => false, 'reason' => 'Numero oggetto mancante.', 'code' => -3]);
        $party = Party::get_party($_POST['party-id']);
        if($party == null)
            return json_encode(['ok' => false, 'reason' => 'Festa non trovata.', 'code' => 0]);
        $item = Item::get_item($_POST['item-id']);
        if($item == null)
            return json_encode(['ok' => false, 'reason' => 'Oggetto non trovato.', 'code' => 0]);
        return json_encode($party->add_item($item, $_POST['item-number']));
    }

    public static function remove_item() {
        $user = User::getCurrent();
        if(!$user->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per eseguire questa azione.', 'code' => -2]);
        if(!isset($_POST['party-id']))
            return json_encode(['ok' => false, 'reason' => 'ID festa non impostato.', 'code' => -3]);
        if(!isset($_POST['item-id']))
            return json_encode(['ok' => false, 'reason' => 'ID oggetto non impostato.', 'code' => -3]);
        $party = Party::get_party($_POST['party-id']);
        if($party == null)
            return json_encode(['ok' => false, 'reason' => 'Festa non trovata.', 'code' => 0]);
        $item = Item::get_item($_POST['item-id']);
        if($item == null)
            return json_encode(['ok' => false, 'reason' => 'Oggetto non trovato.', 'code' => 0]);
        return json_encode($party->delete_item($item));
    }

    public static function increase_item_number() {
        $user = User::getCurrent();
        if(!$user->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per eseguire questa azione.', 'code' => -2]);
        if(!isset($_POST['party-id']))
            return json_encode(['ok' => false, 'reason' => 'ID festa non impostato.', 'code' => -3]);
        if(!isset($_POST['item-id']))
            return json_encode(['ok' => false, 'reason' => 'ID oggetto non impostato.', 'code' => -3]);
        $party = Party::get_party($_POST['party-id']);
        if($party) {
            $item_n = $party->get_own_item_number($_POST['item-id']);
            return $party->change_item_number_from_id($_POST['item-id'], $item_n + 1);
        } else
            return json_encode(['ok' => false, 'reason' => 'Festa non trovata', 'code' => 0]);
    }

    public static function decrease_item_number() {
        $user = User::getCurrent();
        if(!$user->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per eseguire questa azione.', 'code' => -2]);
        if(!isset($_POST['party-id']))
            return json_encode(['ok' => false, 'reason' => 'ID festa non impostato.', 'code' => -3]);
        if(!isset($_POST['item-id']))
            return json_encode(['ok' => false, 'reason' => 'ID oggetto non impostato.', 'code' => -3]);
        $party = Party::get_party($_POST['party-id']);
        if($party) {
            $item_n = $party->get_own_item_number($_POST['item-id']);
            return $party->change_item_number_from_id($_POST['item-id'], $item_n - 1);
        } else
            return json_encode(['ok' => false, 'reason' => 'Festa non trovata', 'code' => 0]);
    }

    public static function get_party_items() {
        $outp = [];
        if(User::getCurrent()->access_level > 0) {
            $party = Party::get_party($_GET['party_id']);
            if($party == null)
                return json_encode(['ok' => false, 'reason' => 'La festa cercata non esiste.', 'code' => 0]);
            $items = $party->get_items();
            /** @var Item $item */
            foreach($items as $item) {
                $outp[] = ['id' => $item->id, 'number' => $party->get_item_number($item->id), 'own' => $party->has_item($item), 'name' => $item->name];
            }
        }
        return json_encode(['data' => $outp]);
    }

    public static function get_party_animators() {
        $outp = [];
        if(User::getCurrent()->access_level > 0) {
            $party = Party::get_party($_GET['party_id']);
            if($party == null)
                return json_encode(['ok' => false, 'reason' => 'La festa cercata non esiste.', 'code' => 0]);
            $animators = $party->get_animators();
            /** @var User $animator */
            foreach($animators as $animator) {
                $outp[] = ['id' => $animator->id, 'name' => $animator->friendly_name, 'picture' => $animator->get_avatar_url()];
            }
        }
        return json_encode(['data' => $outp]);
    }

    public static function delete_party() {
        $user = User::getCurrent();
        if(!$user->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per eseguire questa azione.', 'code' => -2]);
        if(!isset($_POST['party-id']))
            return json_encode(['ok' => false, 'reason' => 'ID festa non impostato.', 'code' => -3]);
        $party = Party::get_party($_POST['party-id']);
        if($party == null)
            return json_encode(['ok' => false, 'reason' => 'Festa non trovata.', 'code' => 0]);
        return $party->delete();
    }

    public static function create_party() {
        //return json_encode(['ok' => false, 'code' => 0, 'reason' => print_r($_POST)]);
        $user = User::getCurrent();
        if(!$user->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per eseguire questa azione.', 'code' => -2]);
        if(!isset($_POST['party-date']) || !isset($_POST['party-hour']) || !isset($_POST['party-price']) || !isset($_POST['theme-id']))
            return json_encode(['ok' => false, 'reason' => 'Parametri richiesta errati.', 'code' => -2]);
        $customer = $_POST['party-customer'];
        $theme = $_POST['theme-id'];
        $address = $_POST['party-address'];
        $date = $_POST['party-date'];
        $price = $_POST['party-price'];
        $time = $_POST['party-hour'];
        $response = Party::create($customer, $address, $theme, $date, $time, $price);
        return json_encode($response);
    }

}