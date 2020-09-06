#!/bin/sh
date=`date "+%Y-%m-%d"`
date_del=`date "+%Y-%m-%d" --date='7 day ago'`

project="irbis"
path_from="/home/mstar/backup/$project/_backup"
path_site="/var/www/$project.mstarproject.com"

username=$(php $path_site/connect.php _for_cron_username)
password=$(php $path_site/connect.php _for_cron_password)
database=$(php $path_site/connect.php _for_cron_database)

/usr/bin/mysqldump $database -u $username -p$password --compatible=no_key_options --ignore-table=$database.cat_cross --ignore-table=$database.cat_cross_stop \
 --default-character-set=UTF8 --set-charset \
> $path_from/"$project"_temp.sql

if [ -n "$1" ]  # && "demo" == $1
then
    mv $path_from/"$project"_temp.sql $path_from/auto_demo_backup.sql
else
    gzip -c $path_from/"$project"_temp.sql > $path_from/"$project"_backup_$date.sql.zi_
    rm $path_from/"$project"_temp.sql
    rm $path_from/"$project"_backup_$date_del.sql.zi_
fi

/usr/bin/lftp << EOF
open filer:fvbnw984rtuq03d2qw@46.166.162.51
mput $path_from/*
bye
EOF
