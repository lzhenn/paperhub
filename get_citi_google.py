#!/usr/bin/python
# -*- coding: UTF-8 -*-

import urllib2
from bs4 import BeautifulSoup
import os
import re
from lib_convert_pdf_to_txt2 import convert_pdf_to_txt

# Score the possible title line
def score_it(item):
    ini_score = 100
    
    # Special sign
    cut_score = (item.count(',')+item.count('.')+item.count(':')+item.count(';')+item.count('@')+item.count('*')+item.count('/')+item('-')-1)*5
    if cut_score >0:
        ini_score=ini_score-cut_score
    
    # Numbers Uppercases
    for ch in item:  
        if ch.isupper():  
            uppers += 1  
        elif ch.isdigit():  
            digits += 1  
# Climate Dyn Parser     
def parser_cd(headlines):



# Journal from AMS Parser
def parser_ams(headlines):



# Title line parser 
def title_parser(headlines):
    head_items=headlines.split('\n')
    
    high_score=0 # Init high score

    for idx, item in enumerate(head_items):
        if item.find('Clim Dyn'):
            title=parser_cd(head_items)
        elif item.find('J O'): 
            title=parser_ams(head_items)      

        id_score=score_it(item)
        if id_score > high_score:
            high_score=id_score
            id_high=idx
    return title



# Request 
headers = { 'User-Agent' : 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.360' }

pdf=os.popen('ls ./warehouse/*.pdf').readlines()
for item in pdf:
    item=item.replace('\n','')
    text_pdf=convert_pdf_to_txt(item)
    text_front=text_pdf[0:300]
    
    print title_parser(text_front)
    print '---------------------------------------------------'
    continue
    text_list=text_front.split()
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
