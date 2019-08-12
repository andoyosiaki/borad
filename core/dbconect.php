<?php

define("SERVER", "localhost");
define("USERNAME", "root");
define("PASSWORD", "root");
define("DATABASE", "twitter");
define("CHARSET", "utf8");
// 
// define("SERVER", "mysql7074.xserver.jp");
// define("USERNAME", "yutori4dayo_mydb");
// define("PASSWORD", "yysa7200");
// define("DATABASE", "yutori4dayo_twitter");
// define("CHARSET", "utf8");



define("DSN", "mysql:host=".SERVER.";dbname=".DATABASE.";charset=".CHARSET);


try {
    $db = new PDO(DSN, USERNAME, PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo $e->getMessage();
}
