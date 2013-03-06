<?php
Class lgm_Talk {
    public function get_field() {
        global $wpdb;
        $result = array();
        $row = $wpdb->get_results( "SELECT display_meta FROM ".$wpdb->prefix."rg_form_meta WHERE form_id = 3", ARRAY_A );
        // debug('row', $row);
        foreach ($row as $item) {
            // debug('item', $item);
            $iitem = unserialize($item['display_meta']);
            // debug('entry', $entry);
            foreach ($iitem['fields'] as $iiitem) {
                $result[$iiitem['id']] = $iiitem['label'];
            }
        }
        return $result;
    } // lgm_Talk::get_field();

    public function get_entry() {
        global $wpdb;
        $result = array();
        $row = $wpdb->get_results( "SELECT ld.*, ldd.value as lvalue FROM ".$wpdb->prefix."rg_lead_detail AS ld LEFT JOIN ".$wpdb->prefix."rg_lead_detail_long as ldd ON id=lead_detail_id WHERE form_id = 3 AND field_number IN (2, 1, 4, 17, 16, 7, 6, 5, 8, 11, 12, 13, 22, 23, 24, 14, 21) ORDER BY lead_id", ARRAY_A  );
        // debug('row', $row);
        foreach ($row as $item) {
            if (!array_key_exists($item['lead_id'], $result)) {
                $result[$item['lead_id']] = array (
                    'firstname' => '',
                    'lastname' => '',
                    'email' => '',
                    'url' => '',
                    'speakers' => '',
                    'title' => '',
                    'day' => '',
                    'time' => '',
                    'duration' => '',
                    'summary' => '',
                    'biography' => '',
                    'type' => '',
                    'sponsorhip' => '',
                    'sponsorhip_currency' => '',
                    'remarks' => '',
                    'status' => '',
                    'slide' => '',
                );
            }
            // debug('item', $item);
            switch ($item['field_number']) {
                case 2 :
                    $result[$item['lead_id']]['firstname'] = $item['value'];
                break;
                case 1 :
                    $result[$item['lead_id']]['lastname'] = $item['value'];
                break;
                case 4 :
                    $result[$item['lead_id']]['email'] = $item['value'];
                break;
                case 17 :
                    $result[$item['lead_id']]['url'] = str_replace('http://', '', $item['value']);
                break;
                case 16 :
                    $result[$item['lead_id']]['speakers'] = $item['value'];
                break;
                case 7 :
                    $result[$item['lead_id']]['title'] = $item['value'];
                break;
                case 6 :
                    if (!empty($item['lvalue'])) {
                        $result[$item['lead_id']]['summary'] = $item['lvalue'];
                    } else {
                        $result[$item['lead_id']]['summary'] = $item['value'];
                    }
                break;
                case 5 :
                    if (!empty($item['lvalue'])) {
                        $result[$item['lead_id']]['biography'] = $item['lvalue'];
                    } else {
                        $result[$item['lead_id']]['biography'] = $item['value'];
                    }
                break;
                case 8 :
                    $result[$item['lead_id']]['type'] = $item['value'];
                break;
                case 11 :
                    $result[$item['lead_id']]['sponsorhip'] = $item['value'];
                break;
                case 12 :
                    $result[$item['lead_id']]['sponsorhip_currency'] = $item['value'];
                break;
                case 13 :
                    if (!empty($item['lvalue'])) {
                        $result[$item['lead_id']]['remarks'] = $item['lvalue'];
                    } else {
                        $result[$item['lead_id']]['remarks'] = $item['value'];
                    }
                break;
                case 22 :
                    $result[$item['lead_id']]['day'] = $item['value'];
                break;
                case 23 :
                    $result[$item['lead_id']]['time'] = $item['value'];
                break;
                case 24 :
                    $result[$item['lead_id']]['duration'] = $item['value'];
                break;
                case 14 :
                    $result[$item['lead_id']]['status'] = $item['value'];
                break;
                case 21 :
                    $result[$item['lead_id']]['slide'] = $item['value'];
                break;
            }
        }
        return $result;
    }
} // lgm_Talk
