<?php

class JsonWebServiceBase {

    public function main() {
        try {
            if (count($_POST)) {
                $output = $this->_post($_POST);
            }
            else {
                $output = $this->_get();
            }
        }
        catch (Exception $e) {
            if (strpos($e, 'get')) {
                $method = 'GET';
            }
            else if (strpos($e, 'post')) {
                $method = 'POST';
            }

            $output = $this->not_found();
            $output['status'] = 'Page does not implement '.$method;
        }

        Atomik::disableLayout();
        Atomik::noRender();

        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function get() {
        throw new Exception("Method 'get' must be implemented");
    }

    public function post($data) {
        throw new Exception("Method 'post' must be implemented");
    }

    public function _get() {
        return $this->get();
    }

    public function _post($data) {
        return $this->post($data);
    }

    public function not_found($resource_name=null, $resource_id=null) {
        header('HTTP/1.1 404 Not Found');
        $output = array(
            'error_code' => 404,
            'status' => 'Resource `'.$resource_name.'` with ID "'.$resource_id.'" could not be found.'
        );
        return $output;
    }

    public function bad_request($message) {
        header('HTTP/1.1 400 Bad Request');
        $output = array(
            'error_code' => 400,
            'status' => 'Bad Request: '.$message
        );
        return $output;
    }
}
