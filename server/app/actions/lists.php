<?php

include '_base.php';

class ListsService extends JsonWebServiceBase {

    public function _list_from_db($value) {
        return array(
            'id' => intval($value['id']),
            'name' => stripslashes($value['event_name']),
            'description' => stripslashes($value['description']),
            'date' => $value['date'],
        );
    }

    public function get() {
        $output = array(
            'owned_lists' => array(),
            'accessible_lists' => array()
        );

        // Lists the user created and thus owns
        $owned_lists = Atomik_Db::findAll('gifts_lists', array('user_id' => AuthPlugin::getId()));

        foreach ($owned_lists as $key => $value) {
            $output['owned_lists'][] = $this->_list_from_db($value);
        }

        // Lists the user has access to
        $query = '
            select *
            from users_lists ul, gifts_lists gl
            where ul.user_id = '.AuthPlugin::getId().'
            and ul.list_id = gl.id
        ';
        $access_lists = Atomik_Db::query($query);

        foreach ($access_lists as $key => $value) {
            $output['accessible_lists'][] = $this->_list_from_db($value);
        }

        return $output;
    }
}

$service = new ListsService();
$service->main();
