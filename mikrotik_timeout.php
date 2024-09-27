<?
$help="Error! Country is missing! Don't use this script directly. Request url with country code, for example <a href=http://www.iwik.org/ipcountry/mikrotik/SK>http://www.iwik.org/ipcountry/mikrotik/SK</a> from router os script:";
$help.="<br>/tool fetch url=http://www.iwik.org/ipcountry/mikrotik/SK";
$help.="<br>/import file-name=SK";

error_reporting(E_ALL ^ E_NOTICE);
$country=$_GET['country'];
$timeout=$_GET['timeout'];
if (!isset($country))  die($help);
if (isset($timeout)) $timeout="timeout=$timeout";
$country=strtoupper($country);
header('Cache-Control: public, max-age=43200');
header("Content-Type:text/plain");
echo "/log info \"Loading $country ipv4 address list\"\n";
echo "/ip firewall address-list remove [/ip firewall address-list find list=$country]\n";
echo "/ip firewall address-list\n";
$handle = @fopen("$country.cidr", "r");
if ($handle) {
    fgets($handle, 4096);
    while (($buffer = fgets($handle, 4096)) !== false) {
	$buffer=trim($buffer);
        echo ":do { add address=$buffer list=$country $timeout } on-error={}\n";
    }
    fclose($handle);
}
echo ":if ( [ :len [ /system package find where name=\"ipv6\" and disabled=no ] ] > 0 ) do={\n";
echo "/log info \"Loading $country ipv6 address list\"\n";
echo "/ipv6 firewall address-list remove [/ipv6 firewall address-list find list=$country]\n";
echo "/ipv6 firewall address-list\n";
$handle = @fopen("$country.ipv6", "r");
if ($handle) {
    fgets($handle, 4096);
    while (($buffer = fgets($handle, 4096)) !== false) {
	$buffer=trim($buffer);
        echo ":do { add address=$buffer list=$country $timeout } on-error={}\n";
    }
    fclose($handle);
}
echo "}";

?>

