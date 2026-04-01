<?php
// Script to strictly enforce UTF-8 and 8 Behavior Items
// Run via CLI: php run_db_fix.php

$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'tiqui0_tiquiweb26';
$port = 3306;

$mysqli = new mysqli($host, $user, $pass, $db_name, $port);

if ($mysqli->connect_errno) {
    die("Connect failed: " . $mysqli->connect_error . "\n");
}

// 1. Force UTF8mb4 Connection
if (!$mysqli->set_charset("utf8mb4")) {
    printf("Error loading character set utf8mb4: %s\n", $mysqli->error);
    exit();
} else {
    printf("Current character set: %s\n", $mysqli->character_set_name());
}

// 2. Clear Old Data if needed (Strict 8 items means we want to force these 8)
// Be careful not to break logs, but we need to fix the definitions.

// Update ENUM first to allow 'neutral'
$mysqli->query("ALTER TABLE behavior_types MODIFY COLUMN type ENUM('positive', 'negative', 'neutral') NOT NULL");

$strict_items = [
    [1, 'No present&oacute; tarea', '&#128221;', -5, 'negative'],    // 📝 (Memo)
    [2, 'Participaci&oacute;n Positiva', '&#127775;', 5, 'positive'], // 🌟 (Glowing Star)
    [3, 'Llegada tard&iacute;a', '&#9200;', -5, 'negative'],          // ⏰ (Alarm Clock)
    [4, 'Comer en clases', '&#127828;', -5, 'negative'],              // 🍔 (Hamburger)
    [5, 'Uso de celular', '&#128241;', -5, 'negative'],               // 📱 (Mobile Phone)
    [6, 'Indisciplina/ruido', '&#128226;', -5, 'negative'],           // 📣 (PA Loudspeaker - Alternative Megaphone)
    [7, 'Uniforme incompleto', '&#128085;', -5, 'negative'],          // 👕 (T-Shirt)
    [8, 'Otro', '&#128204;', -5, 'negative'],                          // 📌 (Pushpin)
    [9, 'Olvid&oacute; su material', '&#127890;', -5, 'negative'],    // 🎒 (Backpack - using School Satchel entity)
    [10, 'Enfermer&iacute;a', '&#127973;', 0, 'neutral'],             // 🏥 (Hospital)
    [11, 'Salida al Ba&ntilde;o', '&#128701;', 0, 'neutral']          // 🚽 (Toilet)
];

echo "Updating Behavior Types...\n";

foreach ($strict_items as $item) {
    $id = $item[0];
    // Escaping is crucial here, even though we hardcoded strings, to match connection charset
    $name = $mysqli->real_escape_string($item[1]);
    $icon = $mysqli->real_escape_string($item[2]);
    $points = $item[3];
    $type = $mysqli->real_escape_string($item[4]);

    $sql = "INSERT INTO behavior_types (id, name, icon, points, type) 
            VALUES ($id, '$name', '$icon', $points, '$type')
            ON DUPLICATE KEY UPDATE name='$name', icon='$icon', points=$points, type='$type'";

    if (!$mysqli->query($sql)) {
        echo "Error updating ID $id: " . $mysqli->error . "\n";
    } else {
        echo "Updated ID $id: $item[1] $item[2]\n";
    }
}

// 3. Delete Extraneous
$mysqli->query("DELETE FROM behavior_types WHERE id > 11");
echo "Cleaned up extra items.\n";

// 4. Force Table Charsets to utf8mb4
$mysqli->query("ALTER TABLE behavior_types CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->query("ALTER TABLE daily_scores CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->query("ALTER TABLE behavior_log CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
echo "Forced table charsets to utf8mb4 (behavior_types, daily_scores, behavior_log).\n";

// 5. Ensure behavior_log has observation column
$result = $mysqli->query("SHOW COLUMNS FROM behavior_log LIKE 'observation'");
if ($result->num_rows == 0) {
    $mysqli->query("ALTER TABLE behavior_log ADD COLUMN observation TEXT AFTER date_id");
    echo "Added 'observation' column to 'behavior_log'.\n";
}

echo "DONE. Check your browser.\n";

$mysqli->close();
