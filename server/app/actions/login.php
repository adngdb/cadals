<?php

include '_base.php';

class LoginService extends JsonWebServiceBase {

    public function post($data) {
        $output = array(
            'status' => 'Unknown',
            'message' => '',
        );

        if (AuthPlugin::isUser() ||
            AuthPlugin::login($data['user_login'], $data['user_password'])) {
            $output['status'] = 'Connected';
        }
        else {
            $output['status'] = 'Not connected';
            $output['message'] = 'Unable to log user in, user_login or user_password is incorrect. ';
        }

        return $output;
    }
}

$service = new LoginService();
$service->main();
