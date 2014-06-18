<?php 

defined('C5_EXECUTE') or die("Access Denied.");
class LoginhistoryList extends DatabaseItemList {
    protected $autoSortColumns = array('loginTime','uID', 'IP', 'userAgent');
    protected $itemsPerPage = 20;

    public function __construct() {
        $this->setQuery('SELECT uID, IP, loginTime, userAgent FROM ounziwLoginHistory');
    }

}