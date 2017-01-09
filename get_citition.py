#!/usr/bin/python
# -*- coding: UTF-8 -*-

import urllib2
from bs4 import BeautifulSoup
import re
# Request 
url='https://scholar.google.com/scholar?hl=en&q=Possible+impacts+of+spring+sea+surface+temperature+anomalies+over+South+Indian+Ocean+on+summer+rainfall+in+Guangdong-Guangxi+region+of+China&btnG=&as_sdt=1%2C5&as_sdtp='
headers = { 'User-Agent' : 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.360' }
req = urllib2.Request(url, None, headers)
response = urllib2.urlopen(req).read()
soup = BeautifulSoup(response,'lxml')
print soup.find_all(onclick=re.compile('gs_ocit'))
