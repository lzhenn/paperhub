#!/bin/sh
mkdir temp
unzip -n paper.zip -d temp
python solve_temp_pdf.py
rm -rf temp
