<?php
$help="Error! Country is missing! Don't use this script directly. Request url with country code, for example <a href=http://www.iwik.org/ipcountry/nft/SK>http://www.iwik.org/ipcountry/nft/SK</a> from a script:";
$help.="<br>wget -q http://www.iwik.org/ipcountry/nft/SK -O - | nft -f -";

error_reporting(E_ALL ^ E_NOTICE);
$country = $_GET['country'] ?? '';
if (!$country) die($help);
$tmp = explode('.', $country);
$country = strtoupper($tmp[0]);
$ipv6 = isset($tmp[1]) && $tmp[1] === 'ipv6';
	header('Cache-Control: max-age=43200');
header('Content-Type: text/plain');

echo "add table inet GEOIP\n";
$type = $ipv6 ? 'ipv6_addr' : 'ipv4_addr';
$setname = $ipv6 ? $country . '_ipv6' : $country;
echo "add set inet GEOIP $setname { type $type; flags interval; }\n";

$filename = $ipv6 ? "$country.ipv6" : "$country.cidr";
$handle = @fopen($filename, 'r');
if ($handle) {
    fgets($handle, 4096);
    while (($buffer = fgets($handle, 4096)) !== false) {
	$buffer=trim($buffer);
        echo "add element inet GEOIP $setname { $buffer } \n";
    }
    fclose($handle);
}

?>

