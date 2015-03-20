<?php

namespace models;

class Customer extends \Model {
    public static $_table_use_short_name = true;
    public static $_table = 'Customer';

    public function orders() {
        return $this->has_many('Order');
    }
}