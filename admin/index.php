<?php
require './session.php';
require '../public/conn.php'; // 数据库连接
?>
<!DOCTYPE htnl>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>班级管理系统</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <div class="app-container">
        <!-- 顶部iframe -->
        <iframe class="top-frame" src="top.php"></iframe>
        
        <!-- 中间iframe容器 -->
        <div class="middle-container">
            <!-- 左侧iframe -->
            <iframe class="left-frame" src="left.html"></iframe>
            
            <!-- 右侧iframe -->
            <iframe class="right-frame" name = "right" src="right.php"></iframe>
        </div>
    </div>
</body>
</html>
