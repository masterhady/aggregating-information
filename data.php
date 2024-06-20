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

// Path to the CSV file
$csvFile = 'iot.csv';

if (($handle = fopen($csvFile, 'r')) !== FALSE) {
    // Skip the first row if it contains column headings
    fgetcsv($handle);
    
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        // Handle possible scientific notation in timestamp
        $timestamp = floatval($data[0]);
        $datetime = date('Y-m-d H:i:s', intval($timestamp / 1000)); // Assuming the timestamp is in milliseconds

        // Ensure all data types are correct
        $temperature = floatval($data[1]);
        $humidity = floatval($data[2]);
        $light = intval($data[3]);
        $dust = floatval($data[4]);
        $airquality_raw = floatval($data[5]);
        $sound = floatval($data[6]);
        $temperature_c = floatval($data[7]);
        $temperature_f = floatval($data[8]);
        
        $sql = "INSERT INTO sensor_data (datetime, temperature, humidity, light, dust, airquality_raw, sound, temperature_c, temperature_f) VALUES ('$datetime', '$temperature', '$humidity', '$light', '$dust', '$airquality_raw', '$sound', '$temperature_c', '$temperature_f')";

        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    fclose($handle);
} else {
    echo "Error: Unable to open file $csvFile";
}

echo "CSV data successfully imported into the database.";

$conn->close();
?>
