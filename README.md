# foursquare2ics
A set of scripts to fetch your foursquare.com checkin-history, storing them to mysql and offering an endpoint for fetching an ical-subscription

## setup 
1) Setup the mysql-database with the _/4sq2ics_checkins.sql_ template-file
2) Install the composer dependencies 
3) Rename /etc/config.php.sample to /etc/config.php and add values **for all constants** (see notes)
4) Setup a cron for /cron/fetch.php (e.g. once per hour)
5) Point your calendar.app to /ics

## notes
- designed for apache + php 7.2
- to get foursquare client-id and client-secret, create an app in foursquare's developer-portal: https://de.foursquare.com/developers/apps
- to get your foursquare user-id check your foursquare-account settings page
- to get an foursquare accesstoken, check: https://github.com/hownowstephen/php-foursquare/blob/master/examples/tokenrequest.php

## disclaimer
no error-handling or other security-features at the moment, this project is provided as is with no warranty  