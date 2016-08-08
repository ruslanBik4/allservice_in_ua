<?php

class user
{
    public $login;
    public $id_client;
    public $permission_table; //array: key-table_name, value - permission string
    public $permission_action;//array allow action

    public function __construct($login, $id_client)
    {
        $this->login = $login;
        $this->id_client = $id_client;
        $this->permission_table = [];
        $this->permission_action = [];

        $query = new Query();

        $sql = sprintf(
            "SELECT pt.table_name, pt.permission FROM ref_users u 
JOIN ref_permissions_for_tables pt ON u.id = pt.id_ref_users  
WHERE u.id_ref_clients = %d AND u.login = '%s'",
            $this->id_client, $this->login
        );

        $result = $query->runSql($sql);
        foreach ($result as $row) {
            $this->permission_table[$row['table_name']] = $row['permission'];
        }

        $sql = sprintf("get_permissions('%s', %d)", $this->login, $this->id_client);

        $result = $query->callProcedure($sql);
        foreach ($result as $row) {
            $this->permission_action[] = $row['name'];
        }

    }
}
