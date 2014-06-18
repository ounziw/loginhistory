<?php 
defined('C5_EXECUTE') or die("Access Denied.");
/**
 * @author Fumito MIZUNO <mizuno@rescuework.jp>
 * @license MIT
 */

class DashboardLoginhistoryController extends DashboardBaseController {
    public function view() {
        Loader::model('loginhistory_list','loginhistory');
        $loginhistorylist = new LoginhistoryList;
        if (!empty($_GET['user'])) {
            $loginhistorylist->filter('uID',$_GET['user']);
        }
        if (!empty($_GET['from'])) {
            $loginhistorylist->filter('loginTime',$_GET['from'], '>=');
        }
        if (!empty($_GET['to'])) {
            $loginhistorylist->filter('loginTime',$_GET['to'], '<=');
        }
        if (!empty($_GET['itemsperpage'])) {
            $loginhistorylist->setItemsPerPage($_GET['itemsperpage']);
        }


        $logins = $loginhistorylist->getPage();

        $this->set('loginhistorylist', $loginhistorylist);
        $this->set('logins', $logins);

        $userList = new UserList();
        $users = $userList->getPage();

        $this->set('userList', $userList);
        $this->set('users', $users);
        $this->set('pagination', $userList->getPagination());


    }
}