<?php

include '_base.php';

class LogoutService extends JsonWebServiceBase {

    public function get() {
        AuthPlugin::logout();
        return array(
            'status' => 'Not connected',
            'message' => 'You have been logged out.'
        );
    }
}

$service = new LogoutService();
$service->main();
