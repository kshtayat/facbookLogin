facbookLogin
============
1. INSTALL LAMP STACK ( APACHE, pgsql, and PHP )

sudo -it
apt-get install apache2 mysql-server php5 php5-mysql phpmyadmin php5-pgsql curl php5-curl php-apc

2.Install Yii framwork 
DOWNLOAD YII
wget http://yii.googlecode.com/files/yii-1.1.13.e9e4a0.tar.gz
cd /var/www
tar -zxf /root/yii-1.1.13.e9e4a0.tar.gz
mv yii-* yii
3.Make sure to chage the database connection on main.php 
Note: I use Postgres as database stor please deploay the file postgrs_create_users_table.sql before start work

4.Facebook login with accesstoke is not real time keep this in your mind when you test the webapp 
