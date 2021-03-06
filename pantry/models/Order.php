<?php

namespace models;


class Order extends \Model {
    public static $_table_use_short_name = true;
    public static $_table = 'Order';

    public function customer() {
        return $this->belongs_to('Customer');
    }
}