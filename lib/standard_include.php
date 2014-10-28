<?php
session_start();
//include '../conf/config.php';
include('database.inc.php');
include 'CityBank_lib.php';

//$conn = connect(DB_TYPE, DBHOST, DBUSER, DBPWD, DBNAME);
//authenticate($DB_NAME);

function myExceptionHandler($e) {
    echo "<h1>Technical error</h1>";
    echo "<pre>";
    echo $e;
    echo "</pre>";
}

define('TYPE_DAYS', 1);
define('TYPE_HOURS', 0);
define('TYPE_MONTHS', 2);
define('TYPE_WEEKS', 3);
define('TYPE_YEARS', 4);
define('TYPE_FY_JULY', 5);   // new insert for balance sheet 2009-2010

function hours2minutes($hours) {
    $hours = strtok($hours, ":.");
    $minutes = strtok(":.");
    return $hours * 60 + $minutes;
}

function minutes2hours($minutes) {
    $hours = floor($minutes / 60);
    $minutes = $minutes - $hours * 60;
    return sprintf("%02d:%02d", $hours, $minutes);
}

function isEmpty($str) {
    return (strlen(trim($str)) == 0) || ($str == "null");
}

function escape($string) {
    $string = htmlspecialchars($string);
    if (get_magic_quotes_runtime())
        $string = stripslashes($string);
    return @mysql_real_escape_string($string);
}

#-#escape()

function getParam($name, $default = null) {
    if (array_key_exists($name, $_REQUEST)) {
        $param = $_REQUEST[$name];

        if (is_string($param)) {
            $param = escape_string($param);
        } else {
            $param = $_REQUEST[$name];
        }
        //$param = $_REQUEST[$name];
        if ($default != null && isEmpty($param)) {
            return $default;
        }
        return $param;
    }
    else
        return $default;
}

function formatCase($str) {
    $first = substr($str, 0, 1);
    $first = strtoupper($first);
    $tail = substr($str, 1);
    return $first . $tail;
}

function field_name($rs, $i) {
    return mysql_field_name($rs, $i);
}

function num_fields($rs) {
    return mysql_num_fields($rs);
}

function buttonRow($buttons) {
    echo "<table class='buttonrow'>";
    echo "<tr>";
    for ($i = 0; $i < count($buttons); $i++) {
        echo "<td>";
        echo $buttons[$i];
        echo "</td>";
    }
    echo "</tr>\n";
    echo "</table>";
}

function button($caption, $name, $class = null, $url = null, $accesskey = null) {

    $type = "submit";
    if ($class == null)
        $class = 'button';
    if ($url != null)
        $type = "button";
    echo "<button type=$type name='$name' class='$class' value='$caption'";
    if ($url != null)
        echo "onClick=\"window.location.href='$url'\"";
    if ($accesskey != null)
        echo "accesskey='$accesskey'";
    echo ">$caption</button>";
}

function newButton($url = null) {
    return button("New", "new", '', $url);
}

function saveButton() {
    return button("Save", "save", 'button');
}

function searchButton() {
    return button("Search", "search", 'button');
}

function deleteButton() {
    return button("Delete", "delete", 'button');
}

function paramInput($name) {
    echo "<input type='text' ";
    echo "name=$name ";
    echo "value='" . getParam("$name") . "' ";
    echo "/>";
}

function textbox($name, $value = null, $size = null, $mandatory = false) {
    if ($size == null)
        $size = 20;
    if (array_key_exists('readonly', $_REQUEST))
        $onKeyPress = "onKeyPress='return false;' ";
    else
        $onKeyPress = "onKeyPress='return checkLength(event, this.value, $size)' ";
    ?>
    <input type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" size="<?php echo $size; ?>" <?php echo $onKeyPress; ?>/>
    <?php
    hidden("old_$name", $value);
    if ($mandatory)
        addValidator("validateMandatory('" . tr($name) . "', document.postform.$name)");
}

function onChange($onChange) {
    return "ajaxLoader('$onChange.php?val='+this.value+'', '$onChange', '<img src=../public/images/loading.gif />');";
}

function comboBox($name, $data, $selectedValue, $allowNull, $class = null, $onChange = null, $ajux_sql = null, $onChangeFunction = null) {
    if ($onChange != '') {
        if ($ajux_sql != '') {
            $ajux_sql_call = '-' . $ajux_sql;
            $onChange = "ajaxLoader('$onChange.php?val='+this.value+'&id=$ajux_sql', '$onChange$ajux_sql_call', '<img src=../public/images/loading.gif />');";
        } else {
            $onChange = "ajaxLoader('$onChange.php?val='+this.value+'&id=$ajux_sql', '$onChange$ajux_sql', '<img src=../public/images/loading.gif />');";
        }
    } else {
        $onChange = $onChangeFunction == '' ? '' : "$onChangeFunction";
    }
    ?>
    <select name='<?php echo $name; ?>' id='<?php echo $name; ?>ID' class='<?php echo $class; ?>' onChange= "<?php echo $onChange; ?>"  

            <?php
            if (array_key_exists('readonly', $_REQUEST))
                echo "disabled=true ";
            echo ">\n";
            if ($allowNull)
                echo "<option></option>";
            for ($j = 0; $j < count($data); $j++) {
                $option = $data[$j];
                if (count($option) > 3)
                    $label = $option[1] . ' - ' . $option[2] . ' - ' . $option[3];
                else if (count($option) > 2)
                    $label = $option[1] . ' - ' . $option[2];
                else if (count($option) > 1)
                    $label = $option[1];
                else
                    $label = $option[0]; echo "<option value='$option[0]' ";
                if ($option[0] == $selectedValue)
                    echo "selected";
                echo ">$label</option>";
            }
            echo "</select>";
        }

        function comboChange($name, $data, $selectedValue, $allowNull, $class = null, $onChange = null) {
            $onChange = $onChange == '' ? '' : $onChange;
            ?>
            <select name='<?php echo $name; ?>' id='<?php echo $name; ?>ID' class='<?php echo $class; ?>' onChange= "ComboChange($(this), '<?php echo $onChange; ?>');"

            <?php
            if (array_key_exists('readonly', $_REQUEST))
                echo "disabled=true ";
            echo ">\n";
            if ($allowNull)
                echo "<option></option>";
            for ($j = 0; $j < count($data); $j++) {
                $option = $data[$j];
                if (count($option) > 3)
                    $label = $option[1] . ' - ' . $option[2] . ' - ' . $option[3];
                else if (count($option) > 2)
                    $label = $option[1] . ' - ' . $option[2];
                else if (count($option) > 1)
                    $label = $option[1];
                else
                    $label = $option[0]; echo "<option value='$option[0]' ";
                if ($option[0] == $selectedValue)
                    echo "selected";
                echo ">$label</option>";
            }
            echo "</select>";
        }

        function comboBox_table_top($name, $data, $selectedValue, $allowNull, $class = null) {
            ?>
            <select name ='<?php echo $name; ?>' id='<?php echo $name; ?>ID' class='<?php echo $class; ?>' onChange= "window.location.href = '?limit=' + this.value + ''"  

                <?php
                if (array_key_exists('readonly', $_REQUEST))
                    echo "disabled=true ";
                echo ">\n";
                if ($allowNull)
                    echo "<option></option>";
                for ($j = 0; $j < count($data); $j++) {
                    $option = $data[$j];
                    if (count($option) > 3)
                        $label = $option[1] . ' - ' . $option[2] . ' - ' . $option[3];
                    else if (count($option) > 2)
                        $label = $option[1] . ' - ' . $option[2];
                    else if (count($option) > 1)
                        $label = $option[1];
                    else
                        $label = $option[0]; echo "<option value='$option[0]' ";
                    if ($option[0] == $selectedValue)
                        echo "selected";
                    echo ">$label</option>";
                }
                echo "</select>";
            }

            function comboBox2($name, $data, $selectedValue, $allowNull, $class = null, $onChange = null, $ajux_sql = null) {
                if ($onChange != '') {
                    if ($ajux_sql != '') {
                        $ajux_sql_call = '-' . $ajux_sql;
                        $onChange = "ajaxLoader('$onChange.php?val='+this.value+'&id=$ajux_sql', '$onChange', '<img src=../public/images/loading.gif />');";
                    } else {
                        $onChange = "ajaxLoader('$onChange.php?val='+this.value+'&id=$ajux_sql', '$onChange', '<img src=../public/images/loading.gif />');";
                    }
                }
                ?>
                <select name='<?php echo $name; ?>' id='<?php echo $name; ?>ID' class='<?php echo $class; ?>' onChange= "<?php echo $onChange; ?>"  

                    <?php
                    if (array_key_exists('readonly', $_REQUEST))
                        echo "disabled=true ";
                    echo ">\n";
                    if ($allowNull)
                        echo "<option></option>";
                    for ($j = 0; $j < count($data); $j++) {
                        $option = $data[$j];
                        if (count($option) > 3)
                            $label = $option[1] . ' - ' . $option[2] . ' - ' . $option[3];
                        else if (count($option) > 2)
                            $label = $option[1] . ' - ' . $option[2];
                        else if (count($option) > 1)
                            $label = $option[1];
                        else
                            $label = $option[0]; echo "<option value='$option[0]' ";
                        if ($option[0] == $selectedValue)
                            echo "selected";
                        echo ">$label</option>";
                    }
                    echo "</select>";
                }

                function parseDate($datestr) {
                    if (isEmpty($datestr))
                        return null;
                    if (strlen($datestr) == 10) {
                        if (strstr($datestr, '-') === FALSE) {
                            return $datestr;
                        }
                        if (DATE_PATTERN == 'Y-m-d') {
                            $year = strtok($datestr, '-');
                            $month = strtok('-');
                            $day = strtok('-');
                            $date = mktime(0, 0, 0, $month, $day, $year);
                            return $date;
                        }
                    }
                    return strtotime($datestr);
                }

                function formatDate($date) {
                    if ($date == null)
                        return "";
                    $date = 0 + $date;
                    return date(DATE_PATTERN, $date);
                }

                function parseTime($hhmm) {
                    $hh = strtok($hhmm, ":.,");
                    $mm = strtok(":.,");
                    if (isEmpty($mm) && strlen($hhmm) == 4) {
                        $hh = substr($hhmm, 0, 2);
                        $mm = substr($hhmm, 2, 2);
                    } else {
                        if ($mm == '5') {
                            $mm = 30;
                        }
                    }
                    return $hh * 60 + $mm;
                }

                function formatTime($minutes) {
                    $hh = floor($minutes / 60);
                    $mm = $minutes - $hh * 60;
                    if (strlen($hh) == 1)
                        $hh = "0" . $hh;
                    if (strlen($mm) == 1)
                        $mm = "0" . $mm;
                    return $hh . ":" . $mm;
                }

                function formatDatetime($date) {
                    return formatDate($date) . ' ' . date('H:i', $date);
                }

                function bddate($date) {
                    if (($date != "") && ($date != "0000-00-00")) {
                        list($Y, $M, $D) = explode("-", $date);
                        //$date_=$D."-".$M."-".$Y;
                        $date = date("d-M-Y", mktime(0, 0, 0, $M, $D, $Y));
                        return $date; //25-02-2011
                    }
                }

                function mkdatetime($date, $minutes, $seconds = 0) {
                    $year = date("Y", $date);
                    $month = date("m", $date);
                    $day = date("d", $date);
                    $hour = floor($minutes / 60);
                    $minute = $minutes - $hour * 60;
                    return mktime($hour, $minute, $seconds, $month, $day, $year);
                }

                function addDay($date, $diff = 1) {
                    $year = date("Y", $date);
                    $month = date("m", $date);
                    $day = date("d", $date);
                    $hour = date("H", $date);
                    $minute = date("i", $date);
                    return mktime($hour, $minute, 0, $month, $day + $diff, $year);
                }

                function addTime($date, $type, $diff = 1) {
                    $year = date("Y", $date);
                    $month = date("m", $date);
                    $day = date("d", $date);
                    $hour = date("H", $date);
                    $minute = date("i", $date);
                    if ($type == TYPE_HOURS)
                        $hour += $diff;
                    else if ($type == TYPE_DAYS)
                        $day += $diff;
                    else if ($type == TYPE_WEEKS)
                        $day += $diff * 7;
                    else if ($type == TYPE_MONTHS)
                        $month += $diff;
                    else if ($type == TYPE_YEARS)
                        $year += $diff;
                    return mktime($hour, $minute, 0, $month, $day, $year);
                }

                function roundTime($date, $type) {
                    $year = date("Y", $date);
                    $month = date("m", $date);
                    $day = date("d", $date);
                    $hour = date("H", $date);
                    $minute = date("i", $date);
                    if ($type == TYPE_HOURS)
                        $minute = 0;
                    else if ($type == TYPE_DAYS) {
                        $minute = 0;
                        $hour = 0;
                    } else if ($type == TYPE_WEEKS) {
                        return strtotime("last Sunday", $date);
                    } else if ($type == TYPE_MONTHS) {
                        $minute = 0;
                        $hour = 0;
                        $day = 1;
                    }
                    return mktime($hour, $minute, 0, $month, $day, $year);
                }

                function getYear($date) {
                    return date("Y", $date);
                }

                function getYears($date) {
                    return date("d-m-Y", $date);
                }

                function getAge($birthday) {
                    list($year, $month, $day) = explode("-", $birthday);
                    $year_diff = date("Y") - $year;
                    $month_diff = date("m") - $month;
                    $day_diff = date("d") - $day;
                    if ($month_diff < 0)
                        $year_diff--;
                    elseif (($month_diff == 0) && ($day_diff < 0))
                        $year_diff--;
                    return $year_diff;
                }

                function dayDiff($date1, $date2) {
                    return round(($date1 - $date2) / 24 / 3600);
                }

                function isSearch() {
                    return array_key_exists("search", $_GET);
                }

                function isSave() {
                    return array_key_exists("save", $_POST);
                }

                function isDelete() {
                    return array_key_exists("delete", $_POST);
                }

                function isClear() {
                    return array_key_exists("clear", $_POST);
                }

                function isNew() {
                    if (!array_key_exists("new", $_POST))
                        return false;
                    return $_POST['new'] == "1";
                }

                function newbox() {
                    if (getParam("action") == "new") {
                        echo "<input type=hidden name=new value='1'/>";
                    }
                }

                function datebox($id, $value = null) {
                    if (strstr($value, '-') === false)
                        $value = formatDate($value);
                    echo "<input type='text' id='$id' name='$id' value='$value' size='12' ";
                    if (array_key_exists('readonly', $_REQUEST))
                        echo "onKeyPress='return false;' ";
                    else
                        echo "onKeyPress='return onDateKeyPress(event, this);' ";
                    echo ">";
                    echo "<img id='$id" . "_button' src='../include/jscalendar/img.gif'/>";
                    if (!array_key_exists('readonly', $_REQUEST)) {
                        echo "<script>\n";
                        echo "Calendar.setup(\n";
                        echo "{\n";
                        echo "  inputField: '$id',\n";
                        echo "  ifFormat: '" . DATE_PATTERN_MYSQL . "',\n";
                        echo "  button: '$id" . "_button'\n";
                        echo "}\n";
                        echo ");\n";
                        echo "</script>\n";
                        $label = $id;
                        addValidator("validateDate('" . tr($label) . "', document.postform.$id)");
                        hidden("old_$id", $value);
                    }
                }

                function localdate($date) {
                    list($Y, $M, $D) = explode("-", $date);
                    $date = gmdate("d-m-Y", mktime(0, 0, 0, $M, $D, $Y));
                    return $date;
                }

                function moneyBox($name, $value = null, $size = 10, $signed = false) {
                    $signed = $signed ? "true" : "false";
                    $length = $size + 3;
                    echo "<input type=text name='$name' value='$value' size=$length class=moneybox ";
                    if (array_key_exists('readonly', $_REQUEST))
                        echo "onKeyPress='return false;' ";
                    else
                        echo "onKeyPress='return onMoneyKeyPress(event, this, $signed, $size);' ";
                    echo "> ";
                    hidden("old_$name", $value);
                    $label = $name;
                    addValidator("validateMoney('" . tr($label) . "', document.postform.$name, $signed, $size)");
                }

                function datetimebox($name) {
                    datebox($name . "date");
                    echo "&nbsp;";
                    timebox($name . "time");
                }

                function getDateTimeParam($name, $defaultDate = null) {
                    $date = getParam($name . "date");
                    if (isEmpty($date))
                        $date = $defaultDate;
                    return $date . " " . getParam($name . "time");
                }

                function prepNull($str) {
                    if ($str == null)
                        return "null";
                    return $str;
                }

                function formatMoney($amount) {
                    $amount = round($amount, 2);
                    return sprintf('%9.2f', $amount);
                }

                function deleteIcon($href) {
                    echo "<a href='$href' onClick=\"javascript:conf=window.confirm('Delete the selected record?'); if(conf==false) { return false; }\">";
                    image("delete.png");
                    echo "</a>";
                }

                function editIcon($href) {
                    echo "<a href='$href' onClick=\"javascript:conf=window.confirm('Edit the selected record?'); if(conf==false) { return false; }\">";
                    image("edit.png");
                    echo "</a>";
                }

                function viewIcon($href) {
                    echo "<a href='$href'>";
                    image("view.png");
                    echo "</a>";
                }

                function deleteColumn($href) {
                    echo "<td align=center>";
                    deleteIcon($href);
                    echo "</td>";
                }

                function hidden($name, $value) {
                    echo "<input type=hidden name='$name' value='$value'/>";
                }

                class Dummy {

                    function __get($name) {
                        return null;
                    }

                }

                function checkBox($name, $value, $text = '', $onChange = null, $tooltip = null) {
                    if (!isEmpty($text))
                        $text = tr($text);
                    $checked = $value == 1 || $value ? 'checked' : '';
                    echo "<input type=checkbox name='$name' value='1' $checked ";
                    if (array_key_exists('readonly', $_REQUEST))
                        echo "disabled=true ";
                    else if ($onChange != null) {
                        echo " onClick='$onChange' ";
                    }
                    if ($tooltip != null)
                        echo " title='$tooltip' ";
                    echo ">$text</input>";
                    $value0 = $value ? 1 : '';
                    hidden("old_$name", $value0);
                }

                function numberBox($name, $value, $signed = false, $precision = 10, $scale = 0, $mandatory = false) {
                    $length = $scale + $precision;
                    if ($precision > 0)
                        $length++;
                    $signed = $signed ? 'true' : 'false';
                    echo "<input type=text name='$name' value='$value' size=$length class=numberbox ";
                    echo "onKeyPress='return onNumberKeyPress(event, this, $signed, $precision, $scale);' ";
                    echo ">";
                    hidden("old_$name", $value);
                    if ($scale > 0)
                        addValidator("validateNumber('" . tr($name) . "', document.postform.$name, $signed, $precision, $scale)");
                    if ($mandatory)
                        addValidator("validateMandatory('" . tr($name) . "', document.postform.$name)");
                }

                function tx($functionname, $params) {
                    begin();
                    $ret = call_user_func_array($functionname, $params);
                    commit();
                    return $ret;
                }

                function getDescription($value, $list, $default = 'Unknown') {
                    foreach ($list as $row) {
                        if ($row[0] == $value)
                            return tr($row[1]);
                    }
                    return $default;
                }

                function image($name) {
                    echo "<img src='../public/images/$name'/>";
                }

                function get($key) {
                    if (isset($_SESSION[$key]))
                        return $_SESSION[$key];
                }

                function getLanguage() {
                    return $_SESSION['language'];
                }

                function tr($text) {
                    if (!defined($text))
                        return $text;
                    return constant($text);
                }

                function etr($text) {
                    echo tr($text);
                }

                function escape_string($str) {
                    if ($str !== null) {
                        $str = str_replace(array('\\', '\''), array('\\\\', '\\\''), $str);
                        $str = "$str";
                    } else {
                        $str = "null";
                    }
                    return $str;
                }

                function logout() {
                    session_destroy();
                }

                function runScript($filename) {
                    $fh = fopen($filename, 'r');
                    $script = fread($fh, filesize($filename));
                    fclose($fh);
                    $sql = strtok($script, ";");
                    while ($sql !== false) {
                        if (strlen(trim($sql)) > 0)
                            sql($sql);
                        $sql = strtok(";");
                    }
                }

                function getMonthStepperDate() {
                    $year = getParam("year");
                    if (isEmpty($year))
                        $year = date("Y");
                    $month = getParam("month");
                    if (isEmpty($month))
                        $month = date("m");
                    if (!isEmpty(getParam("prev")))
                        $month--;
                    if (!isEmpty(getParam("next")))
                        $month++;
                    $date = mktime(0, 0, 0, $month, 1, $year);
                    return $date;
                }

                function monthStepper($date) {
                    echo "<center>";
                    echo "<table>";
                    echo "<tr>";
                    echo "<td><input type='submit' name='prev' value=' < '/></td>";
                    echo "<td>" . date("Y M", $date) . "</td>";
                    echo "<td><input type='submit' name='next' value=' > '/></td>";
                    echo "</tr>";
                    echo "</table>";
                    echo "</center>";
                    $year = date("Y", $date);
                    $month = date("m", $date);
                    hidden('year', $year);
                    hidden('month', $month);
                }

                function getYearStepperDate() {
                    $year = getParam("year");
                    if (isEmpty($year))
                        $year = date("Y");
                    if (!isEmpty(getParam("prev")))
                        $year--;
                    if (!isEmpty(getParam("next")))
                        $year++;
                    $date = mktime(0, 0, 0, 1, 1, $year);

                    return $date;
                }

                function getYearStepperDateFY() {
                    $year = getParam("year");
                    if (isEmpty($year))
                        $year = date("Y");
                    if (!isEmpty(getParam("prev")))
                        $year--;
                    if (!isEmpty(getParam("next")))
                        $year++;
                    $date = mktime(0, 0, 0, 7, 1, $year);

                    return $date;
                }

                function yearStepper($date) {
                    echo "<center>";
                    echo "<table>";
                    echo "<tr>";
                    echo "<td><input type='submit' name='prev' value=' < '/></td>";
                    echo "<td>" . date("Y", $date) . "</td>";
                    echo "<td><input type='submit' name='next' value=' > '/></td>";
                    echo "</tr>";
                    echo "</table>";
                    echo "</center>";
                    $year = date("Y", $date);
                    hidden('year', $year);
                }

                function set($key, $value) {
                    $_SESSION[$key] = $value;
                }

                function getSessionAttribute($name) {
                    if (array_key_exists($name, $_SESSION)) {
                        return $_SESSION[$name];
                    }
                    return null;
                }

                function getRealm() {
                    if (defined('DBNAME')) {
                        $dbname = DBNAME;
                    } else {
                        $dbname = 'therp';
                        if (array_key_exists('dbname', $_SESSION)) {
                            $dbname = $_SESSION['dbname'];
                        }
                        $dbname = getParam('dbname', $dbname);
                    }
                    return $dbname;
                }

                function defineIfNotDefined($name, $value) {
                    if (defined($name))
                        return;
                    define($name, $value);
                }

                function showLoginDialog($mess = null) {
                    $_SESSION['ORG_SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'];
                    if ($mess != null)
                        $_REQUEST['login_mess'] = $mess;
                    include("../common/login.php");
                    die();
                }

                function getCodebase() {
                    if (defined('CODEBASE'))
                        return CODEBASE;
                    return "http://localhost/therp";
                }

                function getError($str) {
                    if (substr($str, 0, 6) == "ERROR:")
                        return substr($str, 6);
                    return null;
                }

                function toggleClass($class) {
                    if ($class == 'odd')
                        return 'even';
                    return 'odd';
                }

                function prepDate($str) {
                    if (isEmpty($str))
                        return "null";
                    $str = parseDate($str);
                    return "from_unixtime($str)";
                }

                function prepDateParam($param) {
                    return prepDate(getParam($param));
                }

                function prepNumber($str) {
                    if (isEmpty($str))
                        return "null";
                    $str = str_replace(',', '.', $str);
                    return $str;
                }

                function prepNumberParam($param) {
                    return prepNumber(getParam($param));
                }

                function prepMoney($str) {
                    return prepNumber($str);
                }

                function prepMoneyParam($param) {
                    return prepMoney(getParam($param));
                }

                function prepStringParam($param) {
                    $value = getParam($param);
                    if (isEmpty($value))
                        return "null";
                    return "'$value'";
                }

                function prepParam($name) {
                    return prepNull(getParam($name));
                }

                function getDBName() {
                    if (defined('DBNAME')) {
                        if (DBNAME == 'alias') {
                            $path = $_SERVER['PHP_SELF'];
                            $alias = strtok($path, '/');
                            return $alias;
                        } else if (DBNAME == 'subdomain') {
                            $path = $_SERVER['SERVER_NAME'];
                            $subdomain = strtok($path, '.');
                            return $subdomain;
                        } else if (DBNAME == 'param') {
                            if (array_key_exists('dbname', $_SESSION))
                                $dbname = $_SESSION['dbname'];
                            else
                                $dbname = getParam('dbname');
                            return $dbname;
                        }
                        return DBNAME;
                    }
                    return "real";
                }

                function addValidator($validator) {
                    $validators = array();
                    if (array_key_exists('validators', $_REQUEST)) {
                        $validators = $_REQUEST['validators'];
                    }
                    $validators[] = $validator;
                    $_REQUEST['validators'] = $validators;
                }

                function Paging($link, $ct, $per_page) {
                    global $totalPaggingPage;
                    if ($ct == 0)
                        return FALSE;
                    $page = (int) getParam('page');
                    $to = ($page * $per_page + $per_page) < $ct ? ($page * $per_page + $per_page) : $ct;
                    echo "Showing Records <b>" . ($page * $per_page + 1) . " - " . $to . "</b>  of " . $ct . "    ";

                    $cnt = (int) (($ct - 1) / $per_page);
                    $totalPaggingPage = $cnt;

                    if ($cnt == 0)
                        return;
                    if ($page > 0)
                        echo "<a href=\"" . $link . "&page=" . ($page - 1) . "\"><img align='absmiddle' border='0' src=\"../public/images/left_arrow.png\"></a>&nbsp;&nbsp;&nbsp;";
                    for ($i = $page - 5; $i < $page + 5; $i++) {
                        if ($i == $page) {
                            echo "&nbsp;&nbsp;<b>[</b>" . ($i + 1) . "<b>]</b>&nbsp;&nbsp;";
                        } elseif ($i >= 0 && $i <= $cnt) {
                            echo "&nbsp;&nbsp;<a style='color:#000000;font-weight:bold;text-decoration:none' href=\"" . $link . "&page=" . $i . "\">" . ($i + 1) . "</a>&nbsp;&nbsp;";
                        }
                    }//for

                    if ($page < $cnt)
                        echo "&nbsp;&nbsp;&nbsp;<a href=\"" . $link . "&page=" . ($page + 1) . "\"><img align='absmiddle' border='0' src=\"../public/images/right_arrow.png\"></a>";
                }

//===============to upload photo============
                function photoUploader($pathToSave, $fileName, $uploadType) {//photo uploader
                    global $self, $pageName, $photoDirecTory, $adminName, $today, $now, $sql, $queryString, $queryStringCheck, $queryStringInsert, $forDB, $forFolder;

                    if (($uploadType == "new") || ($uploadType == "")) {//new photo
                        //-------generate random name for image------------------
                        $sqlRand = "insert into bma_photo_id_generator (val) values ('1')";
                        $resultRand = mysql_query($sqlRand) or die(mysql_error() . '------------sqlRand');
                        if ($resultRand) {
                            $sqlMax = "select MAX(ID) as randomID from bma_photo_id_generator";
                            $resultMax = mysql_query($sqlMax) or die(mysql_error() . '------------sqlMax');
                            $showMax = mysql_fetch_array($resultMax);
                            $randomID = $showMax['randomID'];
                        }
                        $extention = explode('.', $fileName);
                        $extention = $extention[count($extention) - 1];
                        $forFolder = $pathToSave . $randomID . "." . $extention; //to rename file and save into folder
                        $forDB = $randomID . "." . $extention; //to insert into db
                    }//new photo
                    else {//change photo
                        $newFileName = explode('.', $uploadType);
                        $newFileName = $newFileName[count($newFileName) - 2];
                        $extention = explode('.', $fileName);
                        $extention = $extention[count($extention) - 1];
                        $newFileName = $newFileName . "." . $extention;

                        $forFolder = $pathToSave . $newFileName; //to rename file and save into folder
                        $forDB = $newFileName; //to insert into db
                        //delete previous photo from folder
                        if (is_file($pathToSave . $uploadType)) {
                            unlink($pathToSave . $uploadType);
                        }
                    }//change photo
                }

                function getMenu($user_name) {
                    $target = "";
                    $emp_row = find("select ul.menu_main_id, ul.menu_sub_id, ul.level_name
                    from master_user AS u 
                    left join user_level AS ul on ul.level_id=u.user_level_id
                    WHERE u.user_name='$user_name'");

                    $menugroup = $emp_row->menu_main_id;
                    $menuid = $emp_row->menu_sub_id;
                    echo "<ul>";

                    $q = query("Select sys_menu_id, menu_name, links, dependency, dependency_to, target from sys_menu where _group like '%main%' and _show = 1 and sys_menu_id in($menugroup) order by _sort");
                    //$q = mysql_query("Select id, name, links, dependency, dependency_to, target from sys_menu where _group like '%main%' and _show = 1 order by _sort") or trigger_error(mysql_error(), E_USER_ERROR);
                    while ($d = mysql_fetch_object($q)) {

                        if ($d->dependency != 1) {
                            if ($d->target != "") {
                                $target = "target = $d->target";
                            }

                            $links = "$d->links";
                            echo "<li><a href='$links' $target>$d->menu_name</a>";

                            $q_sub = query("Select sys_menu_id, menu_name, links, target from sys_menu where _subid = $d->sys_menu_id and _show = 1 and sys_menu_id in($menuid) order by _sort");
                            //$q_sub = mysql_query("Select sys_menu_id, name, links, target from sys_menu where _subid = $d->id and _show = 1 order by _sort") or trigger_error(mysql_error(), E_USER_ERROR);

                            if (num_rows($q_sub) != 0) {

                                echo "<ul>";
                                while ($d_sub = mysql_fetch_object($q_sub)) {

                                    $links = "$d_sub->links";
                                    echo "<li><a href='$links'>&#187; $d_sub->menu_name</a>";

                                    //-------------sub_sub menu begain here--------------					

                                    $q_sub_sub = mysql_query("Select sys_menu_id, menu_name, links, target from sys_menu where _subid = '$d_sub->sys_menu_id' and _show = 1 and sys_menu_id in($menuid) order by _sort");

                                    if (num_rows($q_sub_sub) != 0) {
                                        echo "<ul>";
                                        while ($d_sub_sub = mysql_fetch_object($q_sub_sub)) {

                                            $links = "$d_sub_sub->links";
                                            echo "<li><a href='$links'>&#187; $d_sub_sub->menu_name</a></li>";
                                        }

                                        echo "</ul>";
                                    } else {
                                        echo "</li>";
                                    }

                                    //end-------------------------------
                                }
                                echo "</ul>";
                            } else {
                                echo "</li>";
                            }
                        } else {


                            $links = "$d->links";
                            echo "<li><a href='$links'/>'$d->menu_name'</a>
                    <ul>";
                            $qq = query("Select sys_menu_id, category_name from '$d->dependency_to' where _group like '%main%' order by _sort ");
                            while ($dd = mysql_fetch_object($qq)) {

                                $links = "$d->links";
                                echo "<li>Please generate</li>";
                            }
                            echo "</ul>
                </li>";
                        }
                    }
                    echo "<li><a href='../common/modules.php?logout=true'>SIGN OUT</a></li>";
                    echo "</ul>";
                }

                function get_switcher_menu($user_name) {
                    $target = "";
                    $user_menu_sql = "SELECT ul.MENU_MAIN_ID, ul.MENU_SUB_ID, ul.USER_LEVEL_NAME
                    FROM master_user AS u left join user_level AS ul on ul.USER_LEVEL_ID=u.USER_LEVEL_ID
                    WHERE u.USER_NAME='$user_name'";

                    $emp_row = find($user_menu_sql);


                    $menugroup = $emp_row->MENU_MAIN_ID == '' ? 0 : $emp_row->MENU_MAIN_ID;
                    $menuid = $emp_row->MENU_SUB_ID == '' ? 0 : $emp_row->MENU_SUB_ID;

                    echo "<ul class='sf-menu'>";

                    $q = query("Select sys_menu_id, menu_name, links, dependency, dependency_to, target from sys_menu where _group like '%main%' and _show = 1 and sys_menu_id in($menugroup) order by _sort") or trigger_error(mysql_error(), E_USER_ERROR);
                    //$q = mysql_query("Select id, name, links, dependency, dependency_to, target from sys_menu where _group like '%main%' and _show = 1 order by _sort") or trigger_error(mysql_error(), E_USER_ERROR);
                    while ($d = fetch_object($q)) {

                        if ($d->dependency != 1) {
                            if ($d->target != "") {
                                $target = "target = $d->target";
                            }

                            $links = "$d->links";
                            echo "<li class='current'><a href='$links' $target>$d->menu_name</a>";
                            $q_sub = query("Select sys_menu_id, menu_name, links, target from sys_menu where _subid = $d->sys_menu_id and _show = 1 and sys_menu_id in($menuid) order by _sort") or trigger_error(mysql_error(), E_USER_ERROR);
                            //$q_sub = mysql_query("Select id, name, links, target from sys_menu where _subid = $d->id and _show = 1 order by _sort") or trigger_error(mysql_error(), E_USER_ERROR);

                            if (mysql_num_rows($q_sub) != 0) {

                                echo "<ul>";
                                while ($d_sub = fetch_object($q_sub)) {

                                    $links = "$d_sub->links";
                                    echo "<li><a href='$links'>$d_sub->menu_name</a>";

//-------------sub_sub menu begain here--------------					

                                    $q_sub_sub = query("Select sys_menu_id, menu_name, links, target from sys_menu where _subid = $d_sub->sys_menu_id and _show = 1 and sys_menu_id in($menuid) order by _sort") or trigger_error(mysql_error(), E_USER_ERROR);

                                    if (mysql_num_rows($q_sub_sub) != 0) {
                                        echo "<ul>";
                                        while ($d_sub_sub = fetch_object($q_sub_sub)) {

                                            $links = "$d_sub_sub->links";
                                            echo "<li><a href='$links'>$d_sub_sub->menu_name</a></li>";
                                        }

                                        echo "</ul>";
                                    } else {
                                        echo "</li>";
                                    }
//end-------------------------------
                                }
                                echo "</ul>";
                            } else {
                                echo "</li>";
                            }
                        } else {


                            $links = "$d->links";
                            echo "<li><a href='$links'/>$d->menu_name</a>
                    <ul>";
                            $qq = query("Select menu_main_id, category_name from $d->dependency_to where _group like '%main%' order by _sort ") or trigger_error(mysql_error(), E_USER_ERROR);
                            while ($dd = fetch_object($qq)) {

                                $links = "$d->links";
                                echo "<li>Please generate</li>";
                            }
                            echo "</ul>
                </li>";
                        }
                    }
                    echo "</ul>";
                }

//encrypt
                function encrypt($text) {
                    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
                }

//decrypt
                function decrypt($text) {
                    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
                }

                function firstDayMonth() {
                    return date("Y-m-d", strtotime(date('m') . '/01/' . date('Y') . ' 00:00:00'));
                }

                function lasDayMonth() {
                    return date("Y-m-d", strtotime('-1 second', strtotime('+1 month', strtotime(date('m') . '/01/' . date('Y') . ' 00:00:00'))));
                }

                function movePage($num, $url) {
                    static $http = array(
                100 => "HTTP/1.1 100 Continue",
                101 => "HTTP/1.1 101 Switching Protocols",
                200 => "HTTP/1.1 200 OK",
                201 => "HTTP/1.1 201 Created",
                202 => "HTTP/1.1 202 Accepted",
                203 => "HTTP/1.1 203 Non-Authoritative Information",
                204 => "HTTP/1.1 204 No Content",
                205 => "HTTP/1.1 205 Reset Content",
                206 => "HTTP/1.1 206 Partial Content",
                300 => "HTTP/1.1 300 Multiple Choices",
                301 => "HTTP/1.1 301 Moved Permanently",
                302 => "HTTP/1.1 302 Found",
                303 => "HTTP/1.1 303 See Other",
                304 => "HTTP/1.1 304 Not Modified",
                305 => "HTTP/1.1 305 Use Proxy",
                307 => "HTTP/1.1 307 Temporary Redirect",
                400 => "HTTP/1.1 400 Bad Request",
                401 => "HTTP/1.1 401 Unauthorized",
                402 => "HTTP/1.1 402 Payment Required",
                403 => "HTTP/1.1 403 Forbidden",
                404 => "HTTP/1.1 404 Not Found",
                405 => "HTTP/1.1 405 Method Not Allowed",
                406 => "HTTP/1.1 406 Not Acceptable",
                407 => "HTTP/1.1 407 Proxy Authentication Required",
                408 => "HTTP/1.1 408 Request Time-out",
                409 => "HTTP/1.1 409 Conflict",
                410 => "HTTP/1.1 410 Gone",
                411 => "HTTP/1.1 411 Length Required",
                412 => "HTTP/1.1 412 Precondition Failed",
                413 => "HTTP/1.1 413 Request Entity Too Large",
                414 => "HTTP/1.1 414 Request-URI Too Large",
                415 => "HTTP/1.1 415 Unsupported Media Type",
                416 => "HTTP/1.1 416 Requested range not satisfiable",
                417 => "HTTP/1.1 417 Expectation Failed",
                500 => "HTTP/1.1 500 Internal Server Error",
                501 => "HTTP/1.1 501 Not Implemented",
                502 => "HTTP/1.1 502 Bad Gateway",
                503 => "HTTP/1.1 503 Service Unavailable",
                504 => "HTTP/1.1 504 Gateway Time-out"
                    );
                    header($http[$num]);
                    header("Location: $url");
                }

//File Upload
                function SaveUploadFile($Request_Id, $Module_Name, $Attach_Title, $Attach_File_Path) {

                    $user_name = get('user_name');

                    if (isset($Attach_File_Path)) {

                        foreach ($Attach_File_Path as $key => $val) {
                            $MaxFile_Attach_List_Id = NextId('file_attach_list', 'FILE_ATTACH_LIST_ID');
                            $insert_sql = "INSERT INTO file_attach_list(FILE_ATTACH_LIST_ID, REQUEST_ID, MODULE_NAME, ATTACH_TITTLE, ATTACH_FILE_PATH, CREATED_BY, CREATED_DATE) 
                        values('$MaxFile_Attach_List_Id', '$Request_Id', '$Module_Name', '$Attach_Title[$key]', '$Attach_File_Path[$key]', '$user_name', NOW() )";

                            sql($insert_sql);
                        }
                    }
                }

                function attachResult($search_id, $module) {
                    $sql = "SELECT FILE_ATTACH_LIST_ID, ATTACH_TITTLE, ATTACH_FILE_PATH
                        FROM file_attach_list
                        WHERE REQUEST_ID = '$search_id' AND MODULE_NAME='$module'";
                    $result = query($sql);

                    return $result;
                }

                function FirstDayLastTwoMonth() {
                    return date("Y-m-d", strtotime(date('m') - 2 . '/01/' . date('Y') . ' 00:00:00'));
                }

                function FirstDayLastThreeMonth() {
                    return date("Y-m-d", strtotime(date('m') - 3 . '/01/' . date('Y') . ' 00:00:00'));
                }

                function convert_number_word($num) {
                    list($num, $dec) = explode(".", $num);

                    $output = "";

                    if ($num{0} == "-") {
                        $output = "negative ";
                        $num = ltrim($num, "-");
                    } else if ($num{0} == "+") {
                        $output = "positive ";
                        $num = ltrim($num, "+");
                    }

                    if ($num{0} == "0") {
                        $output .= "zero";
                    } else {
                        $num = str_pad($num, 36, "0", STR_PAD_LEFT);
                        $group = rtrim(chunk_split($num, 3, " "), " ");
                        $groups = explode(" ", $group);

                        $groups2 = array();
                        foreach ($groups as $g)
                            $groups2[] = convertThreeDigit($g{0}, $g{1}, $g{2});

                        for ($z = 0; $z < count($groups2); $z++) {
                            if ($groups2[$z] != "") {
                                $output .= $groups2[$z] . convertGroup(11 - $z) . ($z < 11 && !array_search('', array_slice($groups2, $z + 1, -1)) && $groups2[11] != '' && $groups[11]{0} == '0' ? " and " : ", ");
                            }
                        }

                        $output = rtrim($output, ", ");
                    }

                    if ($dec > 0) {
                        $output .= " point";
                        for ($i = 0; $i < strlen($dec); $i++)
                            $output .= " " . convertDigit($dec{$i});
                    }

                    return $output;
                }

                function convertGroup($index) {
                    switch ($index) {
                        case 11: return " decillion";
                        case 10: return " nonillion";
                        case 9: return " octillion";
                        case 8: return " septillion";
                        case 7: return " sextillion";
                        case 6: return " quintrillion";
                        case 5: return " quadrillion";
                        case 4: return " trillion";
                        case 3: return " billion";
                        case 2: return " million";
                        case 1: return " thousand";
                        case 0: return "";
                    }
                }

                function convertThreeDigit($dig1, $dig2, $dig3) {
                    $output = "";

                    if ($dig1 == "0" && $dig2 == "0" && $dig3 == "0")
                        return "";

                    if ($dig1 != "0") {
                        $output .= convertDigit($dig1) . " hundred";
                        if ($dig2 != "0" || $dig3 != "0")
                            $output .= " and ";
                    }

                    if ($dig2 != "0")
                        $output .= convertTwoDigit($dig2, $dig3);
                    else if ($dig3 != "0")
                        $output .= convertDigit($dig3);

                    return $output;
                }

                function convertTwoDigit($dig1, $dig2) {
                    if ($dig2 == "0") {
                        switch ($dig1) {
                            case "1": return "ten";
                            case "2": return "twenty";
                            case "3": return "thirty";
                            case "4": return "forty";
                            case "5": return "fifty";
                            case "6": return "sixty";
                            case "7": return "seventy";
                            case "8": return "eighty";
                            case "9": return "ninety";
                        }
                    } else if ($dig1 == "1") {
                        switch ($dig2) {
                            case "1": return "eleven";
                            case "2": return "twelve";
                            case "3": return "thirteen";
                            case "4": return "fourteen";
                            case "5": return "fifteen";
                            case "6": return "sixteen";
                            case "7": return "seventeen";
                            case "8": return "eighteen";
                            case "9": return "nineteen";
                        }
                    } else {
                        $temp = convertDigit($dig2);
                        switch ($dig1) {
                            case "2": return "twenty-$temp";
                            case "3": return "thirty-$temp";
                            case "4": return "forty-$temp";
                            case "5": return "fifty-$temp";
                            case "6": return "sixty-$temp";
                            case "7": return "seventy-$temp";
                            case "8": return "eighty-$temp";
                            case "9": return "ninety-$temp";
                        }
                    }
                }

                function convertDigit($digit) {
                    switch ($digit) {
                        case "0": return "zero";
                        case "1": return "one";
                        case "2": return "two";
                        case "3": return "three";
                        case "4": return "four";
                        case "5": return "five";
                        case "6": return "six";
                        case "7": return "seven";
                        case "8": return "eight";
                        case "9": return "nine";
                    }
                }

                function file_upload_html($multiple = NULL) { //true for multiple
                    ?>
                    <fieldset class="fieldset" style="width: 780px;"> 
                        <legend>Attachment Title</legend>

                        <table class="ui-state-default" id="attachment_tab" style="width: 780px;">
                            <thead>
                            <th width="20">SL.</th>
                            <th>Attachment Tittle</th>
                            <th>Attach File</th>
                            <th width="50">Action</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td align='left'><input type='text' name='AttachmentDetails[]' class="required"/></td>
                                    <td><input type='file' name='attachFile[]'/></td>
                                    <td><div class='remove float-right' onClick='$(this).parent().parent().remove();'><img src='../public/images/delete.png'/></div></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php
                        if ($multiple != "") {
                            echo "<button type='button' onclick='addFileMore();' class = 'button'>Add More</button>";
                        }
                        ?>
                    </fieldset>

                    <?php
                }

                function file_upload_save($targetFolder, $req_id, $module) {
                    //$targetFolder = '../documents/PR/'; // Relative to the root

                    $AttachmentDetails = getParam('AttachmentDetails');

                    if (!file_exists($targetFolder))
                        mkdir($targetFolder);


                    if (!empty($_FILES)) {

                        foreach ($_FILES["attachFile"]["error"] as $key => $error) {
                            $random_digit = rand(000000, 999999);

                            $tempFile = $_FILES['attachFile']['tmp_name'][$key];
                            $targetPath = $targetFolder; //$_SERVER['DOCUMENT_ROOT'] . $targetFolder;
                            // Validate the file type
                            $fileTypes = array('jpg', 'jpeg', 'gif', 'pdf', 'png', 'xls', 'xlsx', 'doc', 'docx', 'ppt'); // File extensions
                            $fileParts = pathinfo($_FILES['attachFile']['name'][$key]);

                            if (in_array($fileParts['extension'], $fileTypes)) {

                                $file_name = basename($_FILES['attachFile']['name'][$key], '.' . $fileParts['extension']);
                                $file_name = str_replace(' ', '', $file_name);
                                $targetFile = $targetPath . $file_name . $random_digit . '.' . $fileParts['extension'];
                                move_uploaded_file($tempFile, $targetFile);
                                $path = $targetFolder . $file_name . $random_digit . '.' . $fileParts['extension'];
                                $sqlInsert = "INSERT INTO file_attach_list(REQUEST_ID, MODULE_NAME, ATTACH_TITTLE, ATTACH_FILE_PATH, CREATED_BY, CREATED_DATE)
                                    VALUES('$req_id', '$module', '$AttachmentDetails[$key]', '$path', '$employeeId', NOW())";
                                sql($sqlInsert);
                            }
                        }
                    }
                }

                function file_upload_single($targetFolder) {

                    if (!file_exists($targetFolder))
                        mkdir($targetFolder);


                    if (!empty($_FILES)) {
                        $random_digit = rand(000000, 999999);

                        $tempFile = $_FILES['file_one']['tmp_name'];
                        $targetPath = $targetFolder; //$_SERVER['DOCUMENT_ROOT'] . $targetFolder;
                        // Validate the file type
                        $fileTypes = array('jpg', 'jpeg', 'gif', 'pdf', 'png', 'xls', 'xlsx', 'doc', 'docx', 'ppt'); // File extensions
                        $fileParts = pathinfo($_FILES['file_one']['name']);

                        if (in_array($fileParts['extension'], $fileTypes)) {

                            $file_name = basename($_FILES['file_one']['name'], '.' . $fileParts['extension']);
                            $file_name = str_replace(' ', '', $file_name);
                            $targetFile = $targetPath . $file_name . $random_digit . '.' . $fileParts['extension'];
                            move_uploaded_file($tempFile, $targetFile);
                            $path = $targetFolder . $file_name . $random_digit . '.' . $fileParts['extension'];
                        }
                        return $path;
                    }
                }

                function file_upload_edit($search_id, $module, $multiple = NULL) {
                    ?>
                    <fieldset class="fieldset" style="width: 780px;"> 
                        <legend>Attachment Title</legend>
                        <table class="ui-state-default" id="attachment_tab" style="width: 780px;">
                            <thead>
                            <th width="30">SL.</th>
                            <th align="left">Attachment Tittle</th>
                            <th align="left">File</th>
                            <th width="50">Action</th>
                            </thead>
                            <tbody>
                                <?php
                                $j = 1;
                                $ResultAttachment = attachResult($search_id, $module);
                                while ($rowAttachment = fetch_object($ResultAttachment)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $j; ?>.</td>
                                        <td><?php echo $rowAttachment->ATTACH_TITTLE; ?></td>
                                        <td align="center"><a href='../PR/<?php echo $rowAttachment->ATTACH_FILE_PATH; ?>' target='_blank'>View </a></td>
                                        <td><div class='remove float-right' id="<?php echo $rowAttachment->FILE_ATTACH_LIST_ID; ?>"><img src='../public/images/delete.png'/></div></td>
                                    </tr>

                                    <?php
                                    $j++;
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                        if ($multiple != "") {
                            echo "<button type='button' onclick='addFileMore();' class = 'button'>Add More</button>";
                        }
                        ?>
                    </fieldset>
                    <?php
                }

                function file_upload_view($searchId, $module) {
                    ?>
                    <fieldset class="fieldset"> 
                        <legend>Attachment Title</legend>

                        <table class="ui-state-default" id="attachment_tab">
                            <thead>
                            <th width="20">SL.</th>
                            <th align="left">Attachment Tittle</th>
                            <th width="80" align="right">Action</th>
                            </thead>
                            <tbody>
                                <?php
                                $j = 1;
                                $ResultAttachment = attachResult($searchId, $module);

                                while ($rowAttachment = fetch_object($ResultAttachment)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $j; ?>.</td>
                                        <td><?php echo $rowAttachment->ATTACH_TITTLE; ?></td>
                                        <td align="center"><a href='<?php echo $rowAttachment->ATTACH_FILE_PATH; ?>' target="_blank" class="">View </a></td>
                                    </tr>
                                    <?php
                                    $j++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </fieldset>
                    <?php
                }
                ?>
