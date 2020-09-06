#!/bin/sh

project="auto"
path_from="/home/mstar/backup/$project/_backup"
path_site="/var/www/auto.mstarproject.com"

username=$(php $path_site/connect.php _for_cron_username)
password=$(php $path_site/connect.php _for_cron_password)
database=$(php $path_site/connect.php _for_cron_database)

/usr/bin/mysql $database -u $username -p$password  < $path_from/"$project"_demo_backup.sql