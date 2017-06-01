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

}