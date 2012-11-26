<?php
Class lgm_Register {
    public function get_field() {
        global $wpdb;
        $result = array();
        $row = $wpdb->get_results( "SELECT display_meta FROM ".$wpdb->prefix."rg_form_meta WHERE form_id = 1", ARRAY_A );
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

        $field = array(
            19 => 'firstname',
            20 => 'lastname',
            5 =>  'nickmane',
            12 => 'email',
            '24.2' => 'participation', // if float, you need ROUND() in the comparison below
            '9' => 'comments',
        );

        $row = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."rg_lead_detail WHERE form_id = 1 AND ROUND(field_number, 1) IN (".implode(", ", array_keys($field)).") ORDER BY lead_id", ARRAY_A  );
        // debug('row', $row);

        $item_empty = array();
        foreach ($field as $key => $value) {
            $item_empty[$value] = '';
        }
        foreach ($row as $item) {
            if (!array_key_exists($item['lead_id'], $result)) {
                $result[$item['lead_id']] = $item_empty;
            }
            // debug('item', $item);
            if (array_key_exists($item['field_number'], $field)) {
                $result[$item['lead_id']][$field[$item['field_number']]] = $item['value'];
            }
        }

        return $result;
    }
} // lgm_Talk
