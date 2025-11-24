<?php 
session_start();  

if (!isset($_SESSION['ischecked']) || $_SESSION['ischecked'] != "ok") {
    header("Location: login.html");  // 第4行，跳转也必须在输出之前
    exit;
}
?>