<?
$help="Error! Country is missing! Don't use this script directly. Request url with country code, for example <a href=http://www.iwik.org/ipcountry/mikrotik_ipv6/SK>http://www.iwik.org/ipcountry/mikrotik_ipv6/SK</a> from router os script:";
$help.="<br>/tool fetch url=http://www.iwik.org/ipcountry/mikrotik_ipv6/SK";
$help.="<br>/import file-name=SK";

error_reporting(E_ALL ^ E_NOTICE);
$country=$_GET['country'];
if (!isset($country))  die($help);
$country=strtoupper($country);
header('Cache-Control: public, max-age=86400');
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + ((60 * 60) * 8)));
header("Content-Type:text/plain");
echo "/log info \"Loading $country ipv6 address list\"\n";
echo "/ipv6 firewall address-list remove [/ipv6 firewall address-list find list=$country]\n";
echo "/ipv6 firewall address-list\n";
$handle = @fopen("$country.ipv6", "r");
if ($handle) {
    fgets($handle, 4096);
    while (($buffer = fgets($handle, 4096)) !== false) {
	$buffer=trim($buffer);
        echo ":do { add address=$buffer list=$country } on-error={}\n";
    }
    fclose($handle);
}

?>
