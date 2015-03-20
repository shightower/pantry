<?php
/**
 * Created by PhpStorm.
 * User: rico
 * Date: 3/16/15
 * Time: 9:04 PM
 */

namespace models;


class Order extends \Model {

    public function customer() {
        return $this->belongs_to('Customer');
    }
}