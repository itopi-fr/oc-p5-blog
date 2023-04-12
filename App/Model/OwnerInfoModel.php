<?php

namespace App\Model;

use App\Database\Connection;

class OwnerInfoModel extends Connection
{


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function getOwnerId()
    {
        $sql = "SELECT * FROM user_owner_infos o INNER JOIN user u ON o.user_id = u.id WHERE u.role ='owner'";
        $owner = $this->getSingleAsObject($sql, []);
        return $owner->owner_id;
    }

    /**
     * @return mixed
     */
    public function getOwnerInfo()
    {
        $ownerId = $this->getOwnerId();
        $sql = "SELECT * FROM user_owner_infos WHERE owner_id =?";
        return $this->getSingleAsObject($sql, [$ownerId]);
    }
}