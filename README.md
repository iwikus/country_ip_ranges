# country_ip_ranges
This scripts generates country range blocks, which can be used for various access lists.
* country_ranges_geoip.py uses as source Maxmind's geoip legacy database GeoIP-legacy.csv.gz, which is built (converted from GeoLite2) by https://mailfud.org/geoip-legacy/
* country_ranges_ipinfo.py generates data from free to download data provided by IPinfo https://ipinfo.io/account/data-downloads "Free IP to Country" database in csv format

This lists can be used in Mikrotik firewall access lists https://blog.erben.sk/2014/02/06/country-cidr-ip-ranges/
or as HAProxy acls https://blog.erben.sk/2020/12/02/haproxy-country-filtering-acl/

Generated IP ranges are available at http://iwik.org/ipcountry


