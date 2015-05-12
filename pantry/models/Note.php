<?php

namespace models;


class Note extends \Model {
    public static $_table_use_short_name = true;
    public static $_table = 'Notes';

    public function customer() {
        return $this->belongs_to('Customer');
    }
}