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


    /**
     * Returns owner id
     * @return int
     */
    public function getOwnerId(): int
    {
        $sql = "SELECT * FROM user_owner_infos o INNER JOIN user u ON o.user_id = u.user_id WHERE u.role ='owner'";
        $owner = $this->getSingleAsObject($sql, []);
        return $owner->owner_id;
    }

    /**
     * Returns owner info
     * @return null|object
     */
    public function getOwnerInfo(): null|object
    {
        $ownerId = $this->getOwnerId();
        $sql = "SELECT * FROM user_owner_infos WHERE owner_id =?";
        return $this->getSingleAsObject($sql, [$ownerId]);
    }
}
