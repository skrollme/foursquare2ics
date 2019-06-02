<?php

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Eluceo\iCal\Property\Event\Geo;

require __DIR__ . '/../etc/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// MYSQL
$db = new mysqli(MYSQL_HOST,MYSQL_USER, MYSQL_PASS,MYSQL_DB);
if ($db->connect_errno) {
    die('Error connecting to MySQL: ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
}

$vCalendar = new Calendar('foursquare2ics.dev.skroll.me');

if($resCheckins = mysqli_query($db, 'SELECT * FROM 4sq2ics_checkins ORDER BY timestamp DESC')) {
    foreach ($resCheckins AS $checkin) {

        $dtStart = new DateTime("@{$checkin['timestamp']}",new DateTimeZone('Europe/Berlin'));

        $dtEnd = clone $dtStart;
        $dtEnd->add(new DateInterval('PT30M'));

        $location = array();
        if(trim($checkin['address']) != '') {
            $location[0] = $checkin['address'];
        }
        if(trim($checkin['zip']) != '') {
            $location[1] = $checkin['zip'];
        }
        if(trim($checkin['city']) != '') {
            if(isset($location[1])) {
                $location[1] .= ' ' . $checkin['city'];
            } else {
                $location[1] = $checkin['city'];
            }
        }
        if(trim($checkin['country']) != '') {
            $location[2] = $checkin['country'];
        }

        $location = implode(', ', $location);

        $vEvent = new Event();
        $vEvent
            ->setDtStart($dtStart)
            ->setDtEnd($dtEnd)
            ->setLocation(utf8_encode($location), $checkin['venue'], new Geo((float)$checkin['lat'], (float)$checkin['long']))
            ->setUrl(sprintf('https://www.swarmapp.com/checkin/%s', $checkin['id']))
            ->setSummary($checkin['venue']
            );
        $vCalendar->addComponent($vEvent);
    }
}

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="foursquare2ics.ics"');

echo $vCalendar->render();