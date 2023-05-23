# Assisment Short Documentation :

 ### Project Overview
 - #### `Constants.php` : Contains the database credentials
 - #### `Helper.php` : Contains the routing path
 - #### `Dockerfile` : Contains the informtaion about docker setup
 - #### `controllers/Operation.php` : Contains the controlling logic

### Database Schema:
  Run the below mysql query to create table :
  
        CREATE TABLE `orders` (
        `id` BIGINT(20) AUTO_INCREMENT,
        `amount` INT(10) NOT NULL,
        `buyer` VARCHAR(255) NOT NULL,
        `receipt_id` VARCHAR(20) NOT NULL,
        `items` VARCHAR(255) NOT NULL,
        `buyer_email` VARCHAR(50) NOT NULL,
        `buyer_ip` VARCHAR(20),
        `note` TEXT NOT NULL,
        `city` VARCHAR(20) NOT NULL,
        `phone` VARCHAR(20) NOT NULL,
        `hash_key` VARCHAR(255),
        `entry_at` DATE,
        `entry_by` INT(10) NOT NULL,
        PRIMARY KEY (`id`)
        );
### Docker image:
 From given link you can find the docker image:
 [latest assesment image](https://hub.docker.com/repository/docker/shakilmahmud/xspeed_assessment/tags)
