<?
$help="Error! Country is missing! Don't use this script directly. Request url with country code, for example <a href=http://www.iwik.org/ipcountry/ipset/SK>http://www.iwik.org/ipcountry/ipset/SK</a> from a script:";
$help.="<br>wget -q http://www.iwik.org/ipcountry/ipset/SK -O - | ipset restore";

error_reporting(E_ALL ^ E_NOTICE);
$country=$_GET['country'];
if (!isset($country))  die($help);
$tmp=explode(".",strtoupper($country));
$country=$tmp[0];
header('Cache-Control: max-age=43200');
header("Content-Type:text/plain");
echo "create $country hash:net family inet hashsize 8192 maxelem 131070\n";
$handle = @fopen("$country.cidr", "r");
if ($handle) {
    fgets($handle, 4096);
    while (($buffer = fgets($handle, 4096)) !== false) {
	$buffer=trim($buffer);
        echo "add $country $buffer\n";
    }
    fclose($handle);
}
?>

