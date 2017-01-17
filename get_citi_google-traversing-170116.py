#!/usr/bin/python
# -*- coding: UTF-8 -*-

from __future__ import division
import urllib2
from bs4 import BeautifulSoup
import os
import re
from lib_convert_pdf_to_txt2 import convert_pdf_to_txt
# Score the possible title line
def score_it(item):
    ini_score = 100
    uppers=0
    digits=0
    item=item.strip()
    # Special sign
    cut_score = (item.count(',')+item.count('.')+item.count(':')+item.count(';')+item.count('@')+item.count('*')+item.count('/')+item.count('-')-1)*5
    if cut_score >0 :
        ini_score=ini_score-cut_score
    # Numbers and Uppercases
    for ch in item:  
        if ch.isupper():  
            uppers += 1  
        elif ch.isdigit():  
            digits += 1 
    if (uppers+digits)/len(item) > 0.3:
        ini_score = ini_score - 50
    item_list = item.split(' ')
    if (len(item_list) > 7) and (len(item)/len(item_list)>4):
        ini_score = ini_score+5
    
    #print item+" S:"+str(ini_score)
    return ini_score
# Find doi
def find_doi(items):
    doi_pos=-1
    for item in items:
        item=item.strip()
        item=item.upper()
        doi_pos=item.find('DOI')
        if doi_pos>=0:
            return item[doi_pos:]
    return ''
# Title line parser 
def title_parser(headlines):
    items0=headlines.split('\n')
    head_items = [ item for item in items0 if item != '' ]
    title='' 
    title=find_doi(head_items)
    if title !='':
        return title
    
    high_score=0 # Init high score
    id_score=0
    high_idx=-1
    title_pos= -1 # Title position for special journal
    for idx, item in enumerate(head_items):
        if item.find('Clim Dyn')>=0:
            title_pos=3
        elif item.find('Climate Dynamics')>=0:
            title_pos=2
        elif item.find('J O')>=0 or item.find('M O')>=0: 
            title_pos=3
        elif item.find('GEOPHYSICAL RESEARCH LETTERS') >=0 or item.find('JOURNAL OF GEOPHYSICAL RESEARCH')>=0:
            title_pos=1
        if title_pos >0:
            title=head_items[title_pos]+' '+head_items[title_pos+1]
            break
        if len(item)>5:
            id_score=score_it(item)
        if id_score > high_score:
            high_score=id_score
            high_idx=idx
    if high_idx>=0 and title=='':
        try:
            title=head_items[high_idx]+' '+head_items[high_idx+1]
        except:
            title=head_items[high_idx]
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
        response2 = urllib2.urlopen(req).read()
        soup2 = BeautifulSoup(response2)
        for link in soup.find_all('RefMan'):
            print link.get('href')
