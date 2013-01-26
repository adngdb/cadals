<?php

include '_base.php';

class GiftService extends JsonWebServiceBase {

    /**
     * Create or update a gift.
     *
     * Expected data:
     * id: int, Id of the gift if updating
     * list_id: int, Id of the list if creating a new gift
     * gift_name: string, Name of the gift
     * gift_link: string, Link to a resource describing the gift
     * gift_descripion: string, Description of the gift
     */
    public function post($data) {
        $output = array(
            'status' => 'Unkown',
            'message' => ''
        );

        $gift_id = A('request/id');

        if (!isset($gift_id) || empty($gift_id)) {
            return $this->bad_request('Invalid or empty parameter "id" for gift');
        }
        $gift_id = intval($gift_id);

        $gift = array(
            'name' => $data['gift_name'],
            'link' => $data['gift_link'],
            'description' => $data['gift_description'],
        );

        if ($id) {
            // Update an existing gift
            $output['status'] = 'Not implemented';
            $output['message'] = 'It is not possible yet to edit a gift. ';
        }
        else if ($list_id) {
            // Insert a new gift
            $gift['list_id'] = $list_id;
            if (Atomik_Db::insert('gifts', $gift)) {
                $output['status'] = 'Created';
            }
            else {
                $output['status'] = 'Not created';
                $output['message'] = 'Error while creating the new gift. ';
            }
        }

        return $output;
    }
}

$service = new GiftService();
$service->main();
