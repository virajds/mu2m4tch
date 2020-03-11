# mu2m4tch (used this repo name to avoid web search)
## mu2m4tch PHP test
### Initial Server setup. 
#### Below instructions given for Ubuntu 18.04.4 LTS. But should work for any Debian kernel

##### Downloading package information from all configured sources.

    sudo apt update && upgrade

##### Install Apache Web Server

    sudo apt-get -y install apache2

##### Check Apache server is listning to the port 80 (http)

Go to 

    http://localhost/ 
    
on your browser 

##### Main Apache config file

    /etc/apache2/apache2.conf

This now redirect port 80 to your working directory and define your main working file (default index.php)

##### Now open the new configuration file:
    sudo vi /etc/apache2/sites-available/000-default.conf
    
Now add below for your en point Structure

    <VirtualHost *:80>
            ServerAdmin webmaster@localhost
            DocumentRoot /home/viraj/www/mu2m4tch
        <Location />
                    Order deny,allow
                    Allow from all
                    DirectoryIndex main.php
            </Location>
    
            <Location /user/create>
                    Order deny,allow
                    Allow from all
                    DirectoryIndex create.php
            </Location>
    
            <Location /user/gallery>
                    Order deny,allow
                    Allow from all                
                    DirectoryIndex gallery.php
            </Location>
    
            <Location /user/*>
                    Order deny,allow
                    Allow from all
                    DirectoryIndex "../main.php"
            </Location>
    
            <Location /profiles>
                    Order deny,allow
                    Allow from all
                    DirectoryIndex profiles.php
            </Location>
    
            <Location /login>
                    Order deny,allow
                    Allow from all
                    DirectoryIndex login.php
            </Location>
    
            <Location /swipe>
                    Order deny,allow
                    Allow from all
                    DirectoryIndex swipe.php
            </Location>
    
          <Location /json>
                    Order deny,allow
                    Allow from all
                    DirectoryIndex json_main.php
            </Location>
            ErrorLog ${APACHE_LOG_DIR}/error.log
            CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>


* Please change the DocumentRoot according to your working directory.

##### Save the file and activate the new configuration file:
    sudo a2ensite 000-default

##### restart apache to apply config changes

    sudo service apache2 restart

##### Adding the php7 ppa: i.e.: In order to load Apache2 modules  

    sudo add-apt-repository ppa:ondrej/php

##### Update package information

    sudo apt update && upgrade

##### Install PHP7 and the Apache2 PHP module

    sudo apt-get -y install php7.4 libapache2-mod-php7.4

##### Testing PHP info

Write a test file info.php in your new document root then load 

    http://localhost/info.php

##### info.php content

    <?php
    phpinfo();
    ?>

##### Install MySQL client

    sudo apt-get -y install mysql-server mysql-client

##### Configre secure root password for MySQL client

    sudo mysql_secure_installation

Enter: y when promt to setup validate password plugin
Enter: 2 for STRONG

Enter and conform a strong pasword 

Enter: y to continue

Enter: y to remove anonymous users

Enter: y to disallow root login remotely or n is you want to use the root password on your HTTP MySQL cleint

Enter: y to remove default test db access remotely or n to allow access
Enter: y to reload privilage tables

* All done! means MySQL client is ready for your app

##### switch to super user

    sudo su

##### Test mysql – You will not be able to use mySQL password commandline as secure features enabled above

    mysql -uroot -p

enter the password when prompted

##### Create DB and HTTP user

    create database muzmatch;
    CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'mu2m@tch';
    GRANT ALL PRIVILEGES ON muzmatch.* TO 'newuser'@'localhost';
    FLUSH PRIVILEGES;

##### Now you should be able to access the app via;

    http://localhost/ 
    
Above is the Landig page

##### End points

    http://localhost/user/create/
    
To create random user with Random data for name, password 
    (sha256 encoded), gender (m,f), age (18-60), Email, Geo Location 
    (between Latitude 51.4552307(bristol)/55.8651505(Glasgow) AND 
    Longitude -4.2576299(Glasgow)/-0.10304(Islington))
    
    http://localhost/login/
    
Login with Valid credintial. Please make sure to take down the password before it encoded and save in the DB.

    http://localhost/profiles/
    
Main profiles section. Has information of

* Logged in user's matched profiles - (When the male is maximum two years younger then female or female is always younger than male)
* Ratings, Swiped, Accepted info (Email shown only accepted), Ratings, distance from logged in user's geo location
* Filtering options for age, distance and ratings
* Table search options
* Table row selector
* Table column sorting options
* Swipe link if not Swiped already


    http://localhost/swipe/
    
Main Swipe end point

    http://localhost/user/gallery/
    
Change profile picture option. Restricted to JPEG images smaller than 500KB and sized 100x100 pixels

    http://localhost/json/
    
Main JSON service end point. Key authenticated to prevent hacking