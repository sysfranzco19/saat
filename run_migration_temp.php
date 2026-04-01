<?php
// Database connection parameters
$servername = "localhost";
$username = "tiqui0_admintiqui";
$password = "Tiquipaya2309";
$dbname = "tiqui0_prueba_tiqui";
$port = 3308;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_create_behavior_types = "CREATE TABLE IF NOT EXISTS `behavior_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `type` enum('positive','negative') NOT NULL DEFAULT 'negative',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$sql_seed_behaviors = "INSERT INTO `behavior_types` (`name`, `icon`, `points`, `type`) VALUES
('No presentar tarea', 'fa-times-circle', -5, 'negative'),
('Comer en clase', 'fa-hamburger', -5, 'negative'),
('Indisciplina o ruido', 'fa-volume-up', -5, 'negative'),
('Uso de celular', 'fa-mobile-alt', -5, 'negative'),
('Participación positiva', 'fa-star', 5, 'positive');";

$sql_create_daily_scores = "CREATE TABLE IF NOT EXISTS `daily_scores` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) unsigned NOT NULL,
  `date_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 100,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$sql_create_behavior_log = "CREATE TABLE IF NOT EXISTS `behavior_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) unsigned NOT NULL,
  `behavior_type_id` int(11) unsigned NOT NULL,
  `subject_id` int(11) NOT NULL,
  `date_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if ($conn->query($sql_create_behavior_types) === TRUE) {
    echo "Table behavior_types created successfully\n";
    if ($conn->query($sql_seed_behaviors) === TRUE) {
        echo "Seed data inserted successfully\n";
    } else {
        echo "Error inserting seed data: " . $conn->error . "\n";
    }
} else {
    echo "Error creating behavior_types: " . $conn->error . "\n";
}

if ($conn->query($sql_create_daily_scores) === TRUE) {
    echo "Table daily_scores created successfully\n";
} else {
    echo "Error creating daily_scores: " . $conn->error . "\n";
}

if ($conn->query($sql_create_behavior_log) === TRUE) {
    echo "Table behavior_log created successfully\n";
} else {
    echo "Error creating behavior_log: " . $conn->error . "\n";
}

$conn->close();
?>