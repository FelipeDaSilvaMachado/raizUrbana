<?php
// Verificar se há resultado para exibir
if (isset($_GET['resultado'])) {
    $resultado = urldecode($_GET['resultado']);
    echo "<div class='result'><h3>Resultado:</h3><pre>" . htmlspecialchars($resultado) . "</pre></div>";
}
?>