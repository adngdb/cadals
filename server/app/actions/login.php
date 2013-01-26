<?php

include '_base.php';

class LoginService extends JsonWebServiceBase {

    public function get() {
        $output = array(
            'status' => 'Not connected',
            'message' => '',
        );
        if (AuthPlugin::isUser()) {
            $output['status'] = 'Connected';
        }
        return $output;
    }

    public function post($data) {
        $output = array(
            'status' => 'Unknown',
            'message' => '',
        );

        if (AuthPlugin::isUser() ||
            (   isset($data['user_name']) &&
                isset($data['user_password']) &&
                AuthPlugin::login($data['user_name'], $data['user_password']))) {
            $output['status'] = 'Connected';
        }
        else {
            $output['status'] = 'Not connected';
            $output['message'] = 'Unable to log user in, user_name or user_password is incorrect. ';
        }

        return $output;
    }
}

$service = new LoginService();
$service->main();
