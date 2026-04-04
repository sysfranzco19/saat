<?php
echo "<h3>Prueba de Conexión a Bases de Datos MAMP</h3>";
echo "<b>Host:</b> 127.0.0.1<br><b>User/Pass:</b> root / root<br><hr>";

$databases = ["tiqui0_tiquisaat26", "tiqui0_tiquiasis26", "tiqui0_tiquiweb26"];
$puertos = [3306, 8889];

foreach ($puertos as $puerto) {
    echo "<h4>Probando en el Puerto $puerto</h4>";
    foreach ($databases as $db) {
        $conn = @new mysqli("127.0.0.1", "root", "root", $db, $puerto);
        if ($conn->connect_error) {
            echo "<div style='color:red;'>✗ Falló: <b>$db</b> (Error: " . $conn->connect_error . ")</div>";
        } else {
            echo "<div style='color:green;'>✓ Éxito: <b>$db</b> conectada perfectamente.</div>";
            $conn->close();
        }
    }
    echo "<hr>";
}
?>
