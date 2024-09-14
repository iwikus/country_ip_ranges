#!/usr/bin/python

# This script proceses data from GeoIP-legacy.csv built by https://mailfud.org/geoip-legacy
# https://mailfud.org/geoip-legacy/GeoIP-legacy.csv.gz

from ipaddress import ip_address, IPv4Address, IPv6Address, IPv4Network, IPv6Network
import ipaddress
import csv
import time
import os

data=[]
country=[]

header = "# IP address data are based on MaxMind GeoLite2 data released under https://www.maxmind.com/en/geolite2/eula ; generated at %s @ %s\n" % (time.strftime("%Y-%m-%d %H:%M"), os.uname().nodename)

def IpaddrType(IP: str) -> str: 
    try: 
        return "IPv4" if type(ip_address(IP)) is IPv4Address else "IPv6"
    except ValueError: 
        return "Invalid"

with open('GeoIP-legacy.csv', mode ='r') as file:
  csvFile = csv.DictReader(file)
  for line in csvFile:   
       if IpaddrType(line['start_ip']) == "IPv4" :
            start_ip=ipaddress.IPv4Address(line['start_ip'])
            end_ip=ipaddress.IPv4Address(line['end_ip'])
            cidr =[ipaddr for ipaddr in ipaddress.summarize_address_range(start_ip, end_ip)]

       else:
            start_ip=ipaddress.IPv6Address(line['start_ip'])
            end_ip=ipaddress.IPv6Address(line['end_ip'])
            cidr =[ipaddr for ipaddr in ipaddress.summarize_address_range(start_ip, end_ip)]
          
       data.append([line['country'],cidr])

for line in data:
    country.append(line[0])
    
countries=list(set(country))

# Remove special country codes
# https://dev.maxmind.com/geoip/whats-new-in-geoip2#custom-country-codes
countries.remove("EU")
countries.remove("A1")
countries.remove("A2")

for country in countries:
    print (country)
    with open("%s.cidr" % country, 'w') as ipv4, open("%s.ipv6" % country, 'w') as ipv6 :
        
        ipv4.write(header)
        ipv6.write(header)
        for line in data:
            if line[0] == country:
                for cidr in line[1]:
                    if type(cidr) == IPv4Network:
                            ipv4.write("%s\n" % (cidr))
                    if type(cidr) == IPv6Network:
                            ipv6.write("%s\n" % (cidr))
                    #print(line)
        ipv4.close()
        ipv6.close()
