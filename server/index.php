<?php
date_default_timezone_set('America/Denver');
# [START gae_simple_front_controller]
switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/':
    case '/byu':
        $dayofweek = date('w') - 1;
        if ($dayofweek > 4 || $dayofweek < 0) {
            require 'non_weekday.php';
            break;
        }
        require 'rooms.php';
        break;
    case '/360.php':
        require '360.php';
        break;
    default:
        http_response_code(404);
        exit('Not Found');
}
# [END gae_simple_front_controller]
