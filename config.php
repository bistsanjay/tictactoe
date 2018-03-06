<?php
session_start();
define("OPPONENT","X");
spl_autoload_register(function ($className) {
    include "/core/".$className . '.php';
});
?>