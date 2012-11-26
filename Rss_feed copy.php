<?php

/**
 * read and parse a identi.ca rss feed
 */

function debugger($label, $value) {
  echo("<pre>".$label.": \n".htmlentities(print_r($value, 1))."</pre>\n");
}

class Rss_feed {
    
    var $parser = null;
    var $url = "";
    function set_url($url) {$this->url = $url;}
    var $item = array();
    function get_item() {return $this->item;}
    var $item_n = 0;
    var $item_active = false;





    function Rss_feed () {

        $this->parser = xml_parser_create();

        //This is the RIGHT WAY to set everything inside the object.
        xml_set_object($this->parser, $this);

        xml_set_element_handler($this->parser, 'start_element', 'end_element');
        xml_set_character_data_handler($this->parser, 'content');

    }

    function read() {

        if (!($fp = fopen($this->url, "r"))) {
            echo("could not open rss feed");
        }

        while ($data = fread($fp, 4096)) {
            // debugger('data', $data);
            if (!xml_parse($this->parser, $data)) {
                echo(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($this->parser)), xml_get_current_line_number($this->parser)));
            }
        }
        xml_parser_free($this->parser);
    }


    function start_element($parser, $name, $attrs) {
        $name = strtolower($name);
        if ($name == 'item') {
            // debugger('start name', $name);
            $this->item[$this->item_n] = array(
                'title' => '',
                'description' => '',
                'date' => '',
                'link' => '',
            );
            $this->item_active = true;
        } elseif ($this->item_active) {
            $this->item_attribute = $name;
        } else {
            $this->item_attribute = '';
        }
    }

    function end_element($parser, $name) {
        $name = strtolower($name);
        // debugger('end name', $name);
        if ($this->item_active) {
            if ($name == 'item') {
                $this->item_n++;
                $this->item_active = false;
            } elseif ($name == 'pubdate') {
                // @todo: split the date and get it in a better format
                // Fri, 20 Nov 2009 01:34:02 +0000
                $this->item[$this->item_n]['date'] = substr($this->item[$this->item_n]['date'], 0, 22);
                
            }
            $this->item_attribute = '';
        }
    }

    function content ($parser, $data) {
        if ($this->item_active) {
            // debugger('data', $data);
            switch ($this->item_attribute) {
                case 'title' :
                    $this->item[$this->item_n]['author'] .= substr($data, 0, strpos($data, ':'));
                    $this->item[$this->item_n]['title'] .= $data;
                break;
                case 'description' :
                    $this->item[$this->item_n]['description'] .= $data;
                break;
                case 'pubdate' :
                    $this->item[$this->item_n]['date'] .= $data;
                break;
                case 'link' :
                    $this->item[$this->item_n]['link'] .= $data;
                break;
            }
        }
    }
}

// $feed = new Rss_feed();
// $feed->set_url("http://identi.ca/api/statusnet/groups/timeline/lgm.rss");
// $feed->read();

// debugger('item', $feed->item);


?>
