<?php

include_once 'therp_include.php';
include_once 'IConnectInfo.php';

class DbManager implements IConnectInfo {

//Passing values using scope resolution operator
    private $db_type = IConnectInfo::DB_TYPE;
    private $server = IConnectInfo::HOST;
    private $currentDB = IConnectInfo::DBNAME;
    private $user = IConnectInfo::UNAME;
    private $pass = IConnectInfo::PW;
    private $hookup = '';

    public function OpenDb() {
	if (!$this->hookup) {
	    $this->hookup = mysql_connect($this->server, $this->user, $this->pass, $this->currentDB) or die("<h2>Can't Connect Database</h2>");
	    set('DB_TYPE', $this->db_type);

	    //$this->CheckUserPermission();
	}
	mysql_selectdb($this->currentDB) or die("<h2>Database Not Selected</h2>");
    }

    public function CloseDb() {
	mysql_close();
    }

    public function begin() {
	sql("set autocommit=0");
	sql("begin");
    }

    public function commit() {
	sql("commit");
	sql("set autocommit=1");
    }

    public function rollback() {
	sql("rollback");
	sql("set autocommit=1");
    }

    function find($sql, $dummy = false) {

	$result = $this->query($sql);
	$row = $this->fetch_object($result);

	//print_r(fetch_object($result));

	if (!$row) {
	    if ($dummy)
		return new Dummy();
	    else
		return null;
	}


	return $row;
    }

    function findValue($sql, $default = null) {
	$rs = $this->query($sql);
	$row = $this->fetch_array($rs);
	if ($row == null)
	    return $default;
	if ($row[0] == null)
	    return $default;

	return $row[0];
    }

    function rs2array($sql) {
	$sql_result = $this->query($sql);
	$result = array();
	while ($row = $this->fetch_row($sql_result)) {
	    $result[] = $row;
	}
	return $result;
    }

    function fetch_row($query) {
	return mysql_fetch_row($query);
    }

    function query($sql) {

	global $conn;
	$db_type = $this->db_type;

	if ($db_type == 'oci') {
	    $q = oci_parse($conn, $sql) or die($sql);
	    oci_execute($q);
	} elseif ($db_type == 'mysql') {
	    $q = mysql_query($sql) or die($sql);
	} else {
	    //echo $sql;
	    return;
	}

	return $q;
    }

    function sql($sql) {
	return $this->query($sql);
    }

    function fetch_object($result) {

	$db_type = get('DB_TYPE');

	if ($db_type == 'oci') {
	    $data_set = oci_fetch_object($result);
	} else {
	    $data_set = mysql_fetch_object($result);
	}

	return $data_set;
    }

    function fetch_array($query) {

	$db_type = get('DB_TYPE');

	if ($db_type == 'oci') {
	    $num_rows = oci_fetch_array($query);
	} else {
	    $num_rows = mysql_fetch_array($query);
	}

	return $num_rows;
    }

    public function authenticate() {
	if (strstr($_SERVER['SCRIPT_NAME'], 'login.php'))
	    return;
	if (array_key_exists('logout', $_GET)) {
	    logout();
	    showLoginDialog();
	    return;
	}

	if (!isEmpty(get('user_name')) && get('DBNAME') == $this->currentDB)
	    return;

	$userSupplied = isset($_SERVER['PHP_AUTH_USER']) || !isEmpty(getParam('user')) || !isEmpty(getParam('username'));
	if (!$userSupplied) {
	    //echo "dddddddddddddddddddddd";
	    showLoginDialog();
	    return;
	}

	if (!$this->login())
	    die();
    }

    public function login() {
	$username = null;
	if (array_key_exists('PHP_AUTH_USER', $_SERVER))
	    $username = $_SERVER['PHP_AUTH_USER'];
	if (isEmpty($username))
	    $username = getParam('user', getParam('username'));
	$pwd = null;
	if (array_key_exists('PHP_AUTH_PW', $_SERVER))
	    $pwd = $_SERVER['PHP_AUTH_PW'];
	if (isEmpty($pwd))
	    $pwd = getParam('pwd', getParam('password'));

	$password = md5($pwd);

	$escaped_password = escape_string($password);
	$sql = "SELECT USER_TYPE_ID, mu.USER_LEVEL_ID, ed.DESIGNATION_ID, bd.BRANCH_DEPT_NAME,
            ed.EMPLOYEE_ID, ed.BRANCH_DEPT_ID, bd.OFFICE_TYPE_ID, d.DESIGNATION_NAME, 
            mu.ROUTE_ID, ed.LINE_MANAGER_ID, COST_CENTER_ID
            
            FROM master_user AS mu
            INNER JOIN employee AS ed ON ed.EMPLOYEE_ID=mu.EMPLOYEE_ID
            LEFT JOIN designation d ON d.DESIGNATION_ID=ed.DESIGNATION_ID
            LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=ed.BRANCH_DEPT_ID
            LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=bd.OFFICE_TYPE_ID
            WHERE USER_NAME='$username' AND USER_PASS='$escaped_password' AND ed.ISACTIVE='Yes'";
	$user = $this->find($sql);


	if (!$user) {
	    showLoginDialog(tr("Invalid username/password"));
	    return false;
	} else {
	    set('user_type', $user->USER_TYPE_ID);
	    set('UserLevelId', $user->USER_LEVEL_ID);
	    set('DESIGNATION_ID', $user->DESIGNATION_ID);
	    set('user_name', $username);
	    set('OfficeType', $user->OFFICE_TYPE_ID);
	    set('BranchDeptId', $user->BRANCH_DEPT_ID);
	    set('BranchDeptName', $user->BRANCH_DEPT_NAME);
	    set('employeeId', $user->EMPLOYEE_ID);
	    set('DBNAME', $this->currentDB);
	    set('DESIGNATION_NAME', $user->DESIGNATION_NAME);
	    set('ProcessDeptId', $user->ROUTE_ID);
	    set('lineManagerId', $user->LINE_MANAGER_ID);
            set('costCenterId', $user->COST_CENTER_ID);
	    set('DB_TYPE', 'mysql');
	}

	$remoteHost = $_SERVER['REMOTE_ADDR'];

	$this->sql("insert into master_session (username, logintime, remote_host) values ('$username', now(), '$remoteHost')");

	echo "<script>location.replace('../product/blank.php');</script>";
    }

    function CheckUserPermission($userName) {

	$link = str_replace('/primebank', '..', $_SERVER['REQUEST_URI']);

	$MenuMainId = $this->findValue("SELECT SYS_MENU_ID FROM sys_menu WHERE LINKS LIKE '%$link%'");

	$sql = "SELECT MENU_SUB_ID
        FROM user_level ul 
        INNER JOIN master_user mu ON mu.USER_LEVEL_ID=ul.USER_LEVEL_ID
        WHERE USER_NAME='$userName'";
	$MENU_SUB_ID = $this->findValue($sql);

	$permitied_menu = array_flip(explode(",", $MENU_SUB_ID));
	if (!array_key_exists($MenuMainId, $permitied_menu)) {
	    //echo "<h2 align='center'>Unauthorized, you need this permission</h2>";
	    //die();
	}
    }

}

$db = new DbManager();
$db->OpenDb();
$db->authenticate();

if ($userName != '') {
    //$db->CheckUserPermission($userName);
}
?>
