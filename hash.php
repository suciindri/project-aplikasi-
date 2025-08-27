<?php
$password = "suci10";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
?>
