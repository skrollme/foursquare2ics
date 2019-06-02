<?php
    error_reporting(E_ALL);
    require __DIR__. '/../etc/config.php';
    require_once __DIR__.'/../vendor/autoload.php';

    // MYSQL
    $db = new mysqli(MYSQL_HOST,MYSQL_USER, MYSQL_PASS,MYSQL_DB);
    if ($db->connect_errno) {
        die('Error connecting to MySQL: ('.$db->connect_errno.')'.$db->connect_error);
    }

    // FOURSQUARE API
    $foursquare = new FoursquareApi(FOURSQUARE_CLIENTID, FOURSQUARE_CLIENTSECRET);
    $foursquare->SetAccessToken(FOURSQUARE_OAUTHTOKEN);
    $params = array(
        "limit" => 100,
        "offset" => 0
    );
    $data = $foursquare->GetPrivate('users/' .FOURSQUARE_USERID. '/checkins',$params);

    if($data = json_decode($data)) {
        if ($data->meta->code == '200' && isset($data->response->checkins->items)) {
            foreach ($data->response->checkins->items AS $item) {
                if (!$resItemExists = mysqli_query($db, sprintf("SELECT * FROM 4sq2ics_checkins WHERE id = '%s'", $item->id))) {
                    die('Error fetching itemexists');
                }

                if ($resItemExists->num_rows !== 0) {
                    echo $item->id . " found, skipping\n";
                    continue;
                }

                echo $item->id . " adding, ";
                $dataset = array(
                    "id" => $item->id,
                    "timestamp" => $item->createdAt,
                    "user" => "1",
                    "venue" => $item->venue->name,
                    "lat" => $item->venue->location->lat,
                    "long" => $item->venue->location->lng,
                    "country" => $item->venue->location->cc,
                    "zip" => $item->venue->location->postalCode,
                    "city" => $item->venue->location->city,
                    "address" => $item->venue->location->address
                );

                foreach ($dataset AS $key => &$col) {
                    $col = addslashes(utf8_decode($col));
                }

                $sql = sprintf("INSERT INTO 4sq2ics_checkins VALUES (%s)", "'" . implode("','", $dataset) . "'");
                if ($resAdditem = mysqli_query($db, $sql)) {
                    echo "added\n";
                } else {
                    echo "problem (".mysqli_error($db).")\n";
                }
            }
        }
    }