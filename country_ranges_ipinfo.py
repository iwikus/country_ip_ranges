#!/usr/bin/python

# This script proceses free to download data from IPinfo https://ipinfo.io/account/data-downloads
# register to get own token to download "Free IP to Country" database in csv format
# https://ipinfo.io/data/free/country.csv.gz?token=<your_token>

from ipaddress import ip_address, IPv4Address, IPv6Address, IPv4Network, IPv6Network
import ipaddress
import csv
import time
import os

data=[]
country=[]

header = "# IP address data powered by IPinfo https://ipinfo.io, released under https://creativecommons.org/licenses/by-sa/4.0/ ; generated at %s @ %s\n" % (time.strftime("%Y-%m-%d %H:%M"), os.uname().nodename)

def IpaddrType(IP: str) -> str: 
    try: 
        return "IPv4" if type(ip_address(IP)) is IPv4Address else "IPv6"
    except ValueError: 
        return "Invalid"

with open('country.csv', mode ='r') as file:
  csvFile = csv.DictReader(file)
  for line in csvFile:   
       if IpaddrType(line['start_ip']) == "IPv4" :
            start_ip=ipaddress.IPv4Address(line['start_ip'])
            end_ip=ipaddress.IPv4Address(line['end_ip'])
            cidr =[ipaddr for ipaddr in ipaddress.summarize_address_range(start_ip, end_ip)]
            # IPInfo data contains too many small networks. We filter out smaller networks than /24
            n = []
            for net in cidr: 
                if net.prefixlen<25: n.append(net)
            cidr = n

       else:
            start_ip=ipaddress.IPv6Address(line['start_ip'])
            end_ip=ipaddress.IPv6Address(line['end_ip'])
            cidr =[ipaddr for ipaddr in ipaddress.summarize_address_range(start_ip, end_ip)]
            # IPInfo data contains too many small networks. For IPv6 we filter all smaller networks than /56
            n = []
            for net in cidr: 
                if net.prefixlen<57: n.append(net)
            cidr = n
          
       data.append([line['country'],cidr])

for line in data:
    country.append(line[0])
    
countries=list(set(country))
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
