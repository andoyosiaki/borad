<?php
define("SERVER", "localhost");
define("USERNAME", "root");
define("PASSWORD", "root");
define("DATABASE", "ImageBoard");
define("CHARSET", "utf8");

define("DSN", "mysql:host=".SERVER.";dbname=".DATABASE.";charset=".CHARSET);

try {
    $db = new PDO(DSN, USERNAME, PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo $e->getMessage();
}
