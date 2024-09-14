#!/bin/bash


wget -q https://mailfud.org/geoip-legacy/GeoIP-legacy.csv.gz && rm GeoIP-legacy.csv
gunzip GeoIP-legacy.csv.gz

# fix header
mv GeoIP-legacy.csv GeoIP-legacy.csv.tmp
cat << EOF > GeoIP-legacy.csv
"start_ip","end_ip","notused1","notused2","country","country_name"
EOF
cat GeoIP-legacy.csv.tmp >> GeoIP-legacy.csv
rm GeoIP-legacy.csv.tmp

python country_ranges.py 

bash geoip.sh
