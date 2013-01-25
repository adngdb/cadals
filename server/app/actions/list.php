<?php

include '_base.php';

class ListService extends JsonWebServiceBase {

    public function get() {
        $output = array();

        $list_id = A('request/id');

        if (!isset($list_id) || empty($list_id)) {
            return $this->bad_request('Invalid or empty parameter "id" for list');
        }
        $list_id = intval($list_id);

        // Information about the list
        $list = Atomik_Db::find('gifts_lists', array('id' => $list_id));

        if (!$list) {
            return $this->not_found('list', $list_id);
        }

        // Gifts from the list
        $gifts = Atomik_Db::findAll('gifts', array('list_id' => $list_id));

        // Those who can access this list
        $query = '
            select u.id, u.email
            from users u, users_lists ul
            where ul.list_id = '.$list_id.'
            and ul.user_id = u.id
        ';
        $friends = Atomik_Db::query($query);

        $output['id'] = $list['id'];
        $output['name'] = $list['event_name'];
        $output['description'] = $list['description'];
        $output['gifts'] = array();
        $output['access'] = array();

        foreach ($gifts as $gift) {
            $output['gifts'][] = array(
                'id' => $gift['id'],
                'name' => $gift['name'],
                'description' => $gift['description'],
                'link' => $gift['link'],
            );
        }

        foreach ($friends as $access) {
            $output['access'][] = array(
                'id' => $access['id'],
                'email' => $access['email'],
            );
        }

        return $output;
    }

    /**
     * Create a new list owned by the logged in user.
     *
     * Accepted data:
     * list-name: string, name of the list
     * list-date: string, date of the event the list is for
     * list-description: string, description of the list
     */
    public function post($data) {
        $output = array(
            'status' => 'Unknown',
            'message' => ''
        );

        if (!isset($data['list-name']) || empty($data['list-name'])) {
            return $this->bad_request('list-name is missing or empty.');
        }

        $list = array(
            'user_id' => AuthPlugin::getId(),
            'event_name' => $data['list-name'],
            'date' => $data['list-date'],
            'description' => $data['list-description'],
        );

        if (Atomik_Db::insert('gifts_lists', $list)) {
            $output['status'] = 'Created';
        }
        else {
            $output['status'] = 'Failed';
            $output['message'] = 'Failed creating the new list';
        }

        return $output;
    }
}

$service = new ListService();
$service->main();
