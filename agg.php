<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "environmental_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the time interval for aggregation (e.g., hourly)
$interval = 'HOUR';

// SQL query to aggregate data
$sql = "
    INSERT INTO aggregated_sensor_data (
        datetime,
        avg_temperature, min_temperature, max_temperature,
        avg_humidity, min_humidity, max_humidity,
        avg_light, min_light, max_light,
        avg_dust, min_dust, max_dust,
        avg_airquality_raw, min_airquality_raw, max_airquality_raw,
        avg_sound, min_sound, max_sound,
        avg_temperature_c, min_temperature_c, max_temperature_c,
        avg_temperature_f, min_temperature_f, max_temperature_f,
        data_count
    )
    SELECT
        DATE_FORMAT(datetime, '%Y-%m-%d %H:00:00') as datetime,
        AVG(temperature) as avg_temperature, MIN(temperature) as min_temperature, MAX(temperature) as max_temperature,
        AVG(humidity) as avg_humidity, MIN(humidity) as min_humidity, MAX(humidity) as max_humidity,
        AVG(light) as avg_light, MIN(light) as min_light, MAX(light) as max_light,
        AVG(dust) as avg_dust, MIN(dust) as min_dust, MAX(dust) as max_dust,
        AVG(airquality_raw) as avg_airquality_raw, MIN(airquality_raw) as min_airquality_raw, MAX(airquality_raw) as max_airquality_raw,
        AVG(sound) as avg_sound, MIN(sound) as min_sound, MAX(sound) as max_sound,
        AVG(temperature_c) as avg_temperature_c, MIN(temperature_c) as min_temperature_c, MAX(temperature_c) as max_temperature_c,
        AVG(temperature_f) as avg_temperature_f, MIN(temperature_f) as min_temperature_f, MAX(temperature_f) as max_temperature_f,
        COUNT(*) as data_count
    FROM sensor_data
    GROUP BY DATE_FORMAT(datetime, '%Y-%m-%d %H:00:00')
";

if ($conn->query($sql) === TRUE) {
    echo "Data aggregated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
