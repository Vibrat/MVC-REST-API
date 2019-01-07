<?php

/**
 * Class Model for Group Permission
 */

class GroupModel extends BaseModel {

    public function newGroup(Array $data) {

        $sql = "INSERT INTO `" . DB_PREFIX . "users_group` SET name = '" . $data['name'] . "', permission = '" . $data['permission'] . "'";
        return $this->db->query($sql)->rowsCount();
    }

    public function countGroup(String $group_name) {
        
        $sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "users_group` WHERE name = '" . $group_name . "'";
        return $this->db->query($sql)->row('total');
    }
}