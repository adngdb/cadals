<?php

include '_base.php';

class ListsService extends JsonWebServiceBase {

    public function get() {
        $output = array(
            'owned_lists' => array(),
            'accessible_lists' => array()
        );

        // Lists the user created and thus owns
        $owned_lists = Atomik_Db::findAll('gifts_lists', array('user_id' => AuthPlugin::getId()));

        foreach ($owned_lists as $key => $value) {
            $list = array(
                'id' => $value['id'],
                'name' => $value['event_name'],
                'description' => $value['description'],
            );
            $output['owned_lists'][] = $list;
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
            $list = array(
                'id' => $value['id'],
                'name' => $value['event_name'],
                'description' => $value['description'],
            );
            $output['accessible_lists'][] = $list;
        }

        return $output;
    }
}

$service = new ListsService();
$service->main();
