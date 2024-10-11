drop database airbnb;
create database airbnb;
use airbnb;

CREATE TABLE `airbnb`.`dim_listings_airbnb` (
  `listing_id` INT NOT NULL AUTO_INCREMENT,
  `country` VARCHAR(25) NOT NULL,
  `listing_created_at` DATETIME NOT NULL,
  PRIMARY KEY (`listing_id`));

CREATE TABLE `airbnb`.`dim_bookings_airbnb` (
  `reservation_id` INT NOT NULL AUTO_INCREMENT,
  `listing_id` INT NOT NULL,
   FOREIGN KEY (`listing_id`) REFERENCES dim_listings_airbnb(listing_id),
  `reservation_time` DATETIME NOT NULL,
  PRIMARY KEY (`reservation_id`));
  
  INSERT INTO
dim_listings_airbnb
(country, listing_created_at)
VALUES
('England', '2024-10-01 02:40:25');

  INSERT INTO
dim_listings_airbnb
(country, listing_created_at)
VALUES
('Australia', '2024-10-03 01:47:12');
  
    INSERT INTO
dim_listings_airbnb
(country, listing_created_at)
VALUES
('Australia', '2024-10-03 01:47:12');


    INSERT INTO
dim_listings_airbnb
(country, listing_created_at)
VALUES
('Sweden', '2024-10-05 08:51:46');

INSERT INTO dim_bookings_airbnb 
(
listing_id,
reservation_time
)
VALUES
(
1,
'2024-10-06 23:32:15'
);

INSERT INTO dim_bookings_airbnb ( listing_id, reservation_time ) VALUES (
1,
'2024-10-08 20:15:10'
);

INSERT INTO dim_bookings_airbnb ( listing_id, reservation_time ) VALUES (
2,
'2024-10-09 21:11:37'
);

INSERT INTO dim_bookings_airbnb ( listing_id, reservation_time ) VALUES (
2,
'2024-10-10 16:08:10'
);

INSERT INTO dim_bookings_airbnb ( listing_id, reservation_time ) VALUES (
3,
'2024-10-09 18:55:52'
);

INSERT INTO dim_bookings_airbnb ( listing_id, reservation_time ) VALUES (
3,
'2024-10-08 23:51:25'
);

-- basic query 1
SELECT 
reservation_id,
l.listing_id,
country,
reservation_time
listing_created_at
FROM 
dim_listings_airbnb l,
dim_bookings_airbnb b
WHERE l.listing_id = b.listing_id;

-- Narrowing attempt 1; maybe it works?  Not sure yet
-- I think it does work.
SELECT 
reservation_id,
l.listing_id,
country,
reservation_time,
listing_created_at
FROM 
dim_listings_airbnb l,
dim_bookings_airbnb b
WHERE l.listing_id = b.listing_id
AND 
reservation_id = 
(
SELECT reservation_id FROM 
dim_bookings_airbnb b2
WHERE
b2.listing_id = b.listing_id
ORDER BY reservation_time ASC
LIMIT 1 OFFSET 1
);

