<?php

include '_base.php';

class RegisterService extends JsonWebServiceBase {

    public function post($data) {
        return null;
    }
}

$service = new RegisterService();
$service->main();
