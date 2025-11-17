<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html; charset=UTF-8");

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$database = 'world';

$conn =new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    echo "<p>Database connection failed.</p>";
    exit();
}

// Retrieve all the 'GET' variables safely
$country = isset($_GET['country']) ? trim($_GET['country']) : "";
$lookup  = isset($_GET['lookup']) ? trim($_GET['lookup']) : "country";

// If no country provided then it will return all countries
$searchTerm = $country !== "" ? "%{$country}%" : "%";

// Country Lookup
if ($lookup === "country") {

    $stmt = $conn->prepare("SELECT name, continent, independence_year, head_of_state 
                            FROM countries 
                            WHERE name LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table border='1'>
            <thead>
                <tr>
                    <th>Country</th>
                    <th>Continent</th>
                    <th>Independence Year</th>
                    <th>Head of State</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['continent']}</td>
                <td>{$row['independence_year']}</td>
                <td>{$row['head_of_state']}</td>
              </tr>";
    }
    echo "</tbody></table>";
    $stmt->close();
}

//Cities Lookup
else if ($lookup === "cities") {

    $stmt = $conn->prepare(
        "SELECT cities.name AS city, cities.district, cities.population
         FROM cities
         JOIN countries ON cities.country_code = countries.code
         WHERE countries.name LIKE ?");

    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table border='1'>
            <thead>
                <tr>
                    <th>City</th>
                    <th>District</th>
                    <th>Population</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['city']}</td>
                <td>{$row['district']}</td>
                <td>{$row['population']}</td>
              </tr>";
    }

    echo "</tbody></table>";
    $stmt->close();
}

$conn->close();
?>
