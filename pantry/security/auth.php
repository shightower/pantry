<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/pantry/';

include_once $rootPath . 'common/showErrors.php';

require_once $rootPath . 'common/idiorm.php';
require_once $rootPath . 'common/paris.php';
require_once $rootPath . 'db_configs/db_configs.php';
require_once $rootPath . 'models/Admin.php';

class Auth {

    public function __construct() {

    }

    public function authenticateUser($username, $password) {
        $userAuthenticated = false;

        $admin = models\Admin::where('username', $username)->findOne();

        if(false === $admin) {
            //TODO figure out some error message
        } else {
            $userAuthenticated = $this->validatePassword($password, $admin->password);
        }

        return $userAuthenticated;
    }

    private function validatePassword($password, $passwordHash) {
        $isCorrectPassword = false;

        if(password_verify($password, $passwordHash)) {
            $isCorrectPassword = true;
        }

        return $isCorrectPassword;
    }
}