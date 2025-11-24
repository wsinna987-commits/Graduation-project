<?php
require_once 'session.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>班级管理系统 - 顶部导航</title>
    <style type="text/css">
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Microsoft YaHei", Arial, sans-serif;
        }
        
        /* 主容器 - 适配60px高度的iframe */
        .top-container {
            height: 60px;
            background: #333; /* 与iframe背景颜色一致 */
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            border-bottom: 1px solid #444;
        }
        
        /* 左侧区域 - 系统标题 */
        .top-left {
            display: flex;
            align-items: center;
        }
        
        .system-title {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            margin-right: 20px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        /* 中间区域 - 时间显示 */
        .top-middle {
            flex: 1;
            text-align: center;
            font-size: 14px;
            color: #ccc;
        }
        
        /* 右侧区域 - 用户信息和操作 */
        .top-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info {
            font-size: 14px;
            color: #ccc;
        }
        
        .user-name {
            color: #fff;
            font-weight: 500;
        }
        
        /* 退出按钮 */
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 13px;
            transition: background-color 0.2s ease;
        }
        
        .logout-btn:hover {
            background-color: #c0392b;
        }
        
        .logout-btn:active {
            background-color: #a93226;
        }
        
        /* 响应式调整 */
        @media (max-width: 768px) {
            .top-container {
                padding: 0 10px;
            }
            
            .system-title {
                font-size: 16px;
                margin-right: 10px;
            }
            
            .top-middle {
                display: none; /* 在小屏幕上隐藏时间 */
            }
        }
    </style>
</head>
<body>
    <div class="top-container">
        <!-- 左侧 - 系统标题 -->
        <div class="top-left">
            <div class="system-title">班级管理系统</div>
        </div>
        
        <!-- 中间 - 当前时间 -->
        <div class="top-middle">
            <span id="current-time"></span>
        </div>
        
        <!-- 右侧 - 用户信息和退出按钮 -->
        <div class="top-right">
            <div class="user-info">
                欢迎，<span class="user-name"><?php echo htmlspecialchars($_SESSION['username'] ?? '未知用户'); ?></span>
            </div>
            <button class="logout-btn" onclick="confirmLogout()">退出</button>
        </div>
    </div>

    <script>
        // 更新当前时间
        function updateTime() {
            const now = new Date();
            const options = { 
                year: 'numeric', 
                month: '2-digit', 
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            
            const dateStr = now.toLocaleDateString('zh-CN', options);
            const dayOfWeek = '日一二三四五六'.charAt(now.getDay());
            
            document.getElementById('current-time').textContent = 
                `${dateStr} 星期${dayOfWeek}`;
        }
        
        // 初始化时间并设置定时器
        updateTime();
        setInterval(updateTime, 1000);
        
        // 退出登录确认
        function confirmLogout() {
            if (confirm('确定要退出登录吗？')) {
                try {
                    // 尝试通过父窗口跳转（适用于iframe环境）
                    if (window.parent && window.parent.location) {
                        window.parent.location.href = 'logout.php';
                    } else {
                        // 备用方案：直接跳转
                        window.location.href = 'logout.php';
                    }
                } catch (e) {
                    // 跨域或安全限制时的备用方案
                    window.location.href = 'logout.php';
                }
            }
        }
        
        // 确保页面在iframe中正确显示
        window.onload = function() {
            // 自动调整iframe高度（如果需要）
            try {
                if (window.parent && window.parent.document) {
                    const iframe = window.parent.document.querySelector('.top-frame');
                    if (iframe) {
                        iframe.style.height = '60px';
                    }
                }
            } catch (e) {
                // 跨域访问被拒绝时忽略
            }
        };
    </script>
</body>
</html>
