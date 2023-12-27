<?php
function log_r($message) {
    echo "<hr class='border border-5 mt-5 border-danger'><pre class='p-5'>";
    print_r($message);
    echo "</pre><hr>";
}