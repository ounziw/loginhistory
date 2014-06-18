<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));
/**
 * @author Fumito MIZUNO <mizuno@rescuework.jp>
 * @license MIT
 */

class LoginhistoryPackage extends Package {
    protected $pkgDescription = 'loginhistory';
    protected $pkgName = "Loginhistory";
    protected $pkgHandle = 'loginhistory';

    protected $appVersionRequired = '5.6.2';
    protected $pkgVersion = '1.0';

    public function getPackageName() {
        return t("Login History");
    }

    public function getPackageDescription() {
        return t('Record login time, IP, and User-Agent. You can view them on your dashboard.');
    }

    public function on_start() {
        Events::extend('on_user_login', 'LoginhistoryPackage', 'on_user_login', 'packages/loginhistory/controller.php');
    }
    public function on_user_login() {
        $u = new User();
        $uID = intval($u->uID);
        $uo = UserInfo::getByID($uID);
        $loginTime = date("Y-m-d H:i:s",$uo->getLastLogin());
        $IP = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $db = Loader::db();
        $db->Execute('insert into ounziwLoginHistory (uID, loginTime, IP, userAgent) values (?, ?, ?, ?)',
            array(
                $uID,
                $loginTime,
                $IP,
                $userAgent
            ));

    }
    public function install() {

        $pkg = parent::install();
        Loader::model('single_page');
        SinglePage::add('/dashboard/loginhistory/view', $pkg);

    }

}
