<?php
function sendPost($url,$params){
    echo "<form action='$url' id='a' method='post'>";
    foreach ($params as $name => $value) {
        echo "<input type='hidden' name='$name' value='$value' />";
    }
    echo "</form><script>document.getElementById('a').submit()</script>";
    die;
}
?>