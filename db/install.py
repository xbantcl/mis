#!/usr/bin/env python
# -*- coding: utf-8 -*-

import MySQLdb
import os
import sys

user = __import__('developer')

db = MySQLdb.connect(host='localhost', user='root', passwd='toor', db='MIS', charset = "utf8")
cursor = db.cursor()

for key in user.user_list.keys():
    for i in range(0, len(user.user_list[key])):
        userInfo = user.user_list[key][i].split(':')
        sql = "insert into mis_user(username, department, email, admin) values('%s', '%s', '%s', '%s')" \
                % (userInfo[0], key, userInfo[1], 0)
        cursor.execute(sql)
cursor.close()
