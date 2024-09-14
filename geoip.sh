#!/bin/bash

OUTPUT=geoip.txt

echo "# Use this file with haproxy map_ip, see http://blog.erben.sk/2020/12/02/haproxy-country-filtering-acl/" > $OUTPUT

for COUNTRY in *.cidr ; do
	 CC=$(basename $COUNTRY .cidr)
	 awk -v CC=$CC '{print $0, CC}' $COUNTRY >>$OUTPUT
done

for COUNTRY in *.ipv6 ; do
	         CC=$(basename $COUNTRY .ipv6)
		          awk -v CC=$CC '{print $0, CC}' $COUNTRY >>$OUTPUT
done

md5sum geoip.txt > geoip.txt.md5
