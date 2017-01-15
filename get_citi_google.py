#!/usr/bin/python
# -*- coding: UTF-8 -*-

import urllib2
from bs4 import BeautifulSoup
import os
import re
from lib_convert_pdf_to_txt2 import convert_pdf_to_txt

# Request 
headers = { 'User-Agent' : 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.360' }

pdf=os.popen('ls ./warehouse/*.pdf').readlines()
for item in pdf:
    item=item.replace('\n','')
    txt_pdf=convert_pdf_to_txt(item)
    text_list=txt_pdf[0:300].split()
    content="+".join(text_list)
    
    url='https://scholar.google.com/scholar?hl=en&q='+content+'&btnG=&as_sdt=1%2C5&as_sdtp='
    req = urllib2.Request(url, None, headers)
    response = urllib2.urlopen(req).read()
    soup = BeautifulSoup(response,'lxml')
    for link in soup.find_all(onclick=re.compile('gs_ocit')):
        str_click=link.get('onclick').split('\'')
        url2='https://scholar.google.com/scholar?q=info:'+str_click[1]+':scholar.google.com/&output=cite&scirp=0&hl=en'
        print str_click[1]
        req = urllib2.Request(url2, None, headers)
        response = urllib2.urlopen(req).read()
        file=open('test.html','a')
        file.write(response)
