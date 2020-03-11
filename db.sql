-- Main DB setup SQL
-- Create User Table - AUTO_INCREMENT from 101

-- !!!!!!!!!!!!!!Important!!!!!!!!!!!!!!!!!!!!!!
-- Please make sure the DB and user is crated before
-- create database muzmatch;
-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'mu2m@tch';
-- GRANT ALL PRIVILEGES ON *.* TO 'newuser'@'localhost';
-- FLUSH PRIVILEGES;
-- !!!!!!!!!!!!!!Important!!!!!!!!!!!!!!!!!!!!!!

use muzmatch;

CREATE TABLE IF NOT EXISTS `user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` char(1) NOT NULL,
  `age` int(11) NOT NULL,
  `geo_location` varchar(32) NOT NULL,
  `image_available`` char(1),
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

-- Email is UNIQUE. Getting indexed
CREATE INDEX idx_email ON user_profile (email);
-- We use password for token search so indexing
CREATE INDEX idx_pwd ON user_profile (password);

GRANT permission ON muzmatch.user_profile TO 'user'@'localhost';

-- Craete user swipe Table
CREATE TABLE IF NOT EXISTS `user_swipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `swiped_id` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`,`swiped_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101;


SELECT up.geo_location as user_geo_location, up2.*,
                                                    IF(us.swiped_id IS NOT NULL,"y","n") as swiped,
                                                    IF(us2.id IS NOT NULL,"y","n") as accepted
                                                FROM user_profile up
                                                INNER JOIN user_profile up2 ON IF(up.gender="m",(up2.gender="f" AND up.age-up2.age >= -2),(up2.gender="m" AND up2.age-up.age >= -2))
                                                LEFT JOIN user_swipes us ON us.id=up.id AND us.swiped_id=up2.id
                                                LEFT JOIN user_swipes us2 ON us2.id=up2.id AND us2.id=us.swiped_id
                                                WHERE
                                            up.id = 77 ORDER BY accepted DESC,swiped DESC,up2.id

