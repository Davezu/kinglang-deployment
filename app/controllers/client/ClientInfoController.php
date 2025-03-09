<?php
require_once "../../../config/database.php";
require_once "../../models/client/ClientInfoModel.php";

class ClientInfoController {
    private $info;

    public function __construct($db) {
        $this->info = new ClientInfoModel($db);
    } 

    public function addClient($first_name, $last_name, $address, $contact_number, $company_name) {
        return $this->info->addClient($first_name, $last_name, $address, $contact_number, $company_name);
    }
}


?>