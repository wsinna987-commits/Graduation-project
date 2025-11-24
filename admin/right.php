<?php
// 引入会话验证（确保已登录才能访问）
require './session.php';
// 引入数据库连接（如果需要显示动态数据）
require '../public/conn.php';

// 可选：查询系统统计数据（示例）
$stats = [
    'total_students' => 0,
    'total_classes' => 0,
    'total_courses' => 0,
    'total_teachers' => 0
];

// 尝试从数据库获取统计数据
try {
    // 学生总数
    $student_sql = "SELECT COUNT(*) as count FROM students";
    $student_result = mysqli_query($conn, $student_sql);
    if ($student_result) {
        $stats['total_students'] = mysqli_fetch_assoc($student_result)['count'];
    }

    // 班级总数
    $class_sql = "SELECT COUNT(*) as count FROM classes";
    $class_result = mysqli_query($conn, $class_sql);
    if ($class_result) {
        $stats['total_classes'] = mysqli_fetch_assoc($class_result)['count'];
    }

    // 课程总数
    $course_sql = "SELECT COUNT(*) as count FROM courses";
    $course_result = mysqli_query($conn, $course_sql);
    if ($course_result) {
        $stats['total_courses'] = mysqli_fetch_assoc($course_result)['count'];
    }

    // 教师总数
    $teacher_sql = "SELECT COUNT(*) as count FROM teachers";
    $teacher_result = mysqli_query($conn, $teacher_sql);
    if ($teacher_result) {
        $stats['total_teachers'] = mysqli_fetch_assoc($teacher_result)['count'];
    }

} catch (Exception $e) {
    // 数据库查询失败时显示默认数据
    $stats = [
        'total_students' => '--',
        'total_classes' => '--',
        'total_courses' => '--',
        'total_teachers' => '--'
    ];
}

// 关闭数据库连接
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>班级管理系统 - 首页</title>
    <style>
        /* 统一右侧页面样式（与其他功能页面保持一致） */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Microsoft YaHei", Arial, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            padding: 20px;
        }

        .page-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* 页面标题样式 */
        .page-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #2c3e50;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
        }

        /* 统计卡片样式 */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 10px;
            color: #4299e1;
        }

        .stat-title {
            font-size: 14px;
            color: #718096;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
        }

        /* 快捷操作样式 */
        .quick-actions {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background-color: #4299e1;
            color: white;
            border-color: #4299e1;
        }

        .action-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .action-text {
            font-size: 14px;
            text-align: center;
        }

        /* 系统信息样式 */
        .system-info {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .info-table td {
            padding: 12px 0;
            font-size: 14px;
        }

        .info-label {
            width: 120px;
            color: #718096;
            font-weight: 500;
        }

        .info-value {
            color: #2d3748;
        }

        /* 响应式调整 */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
    <!-- 可选：引入Font Awesome图标库（增强视觉效果） -->
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="page-container">
        <!-- 页面标题 -->
        <h1 class="page-title">
            <i class="fas fa-tachometer-alt" style="margin-right: 10px; color: #4299e1;"></i>系统概览
        </h1>

        <!-- 数据统计卡片 -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-title">学生总数</div>
                <div class="stat-value"><?php echo $stats['total_students']; ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div class="stat-title">班级总数</div>
                <div class="stat-value"><?php echo $stats['total_classes']; ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="stat-title">课程总数</div>
                <div class="stat-value"><?php echo $stats['total_courses']; ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-title">教师总数</div>
                <div class="stat-value"><?php echo $stats['total_teachers']; ?></div>
            </div>
        </div>

        <!-- 快捷操作区 -->
        <div class="quick-actions">
            <h2 class="section-title">
                <i class="fas fa-rocket" style="margin-right: 8px; color: #38b2ac;"></i>快捷操作
            </h2>
            <div class="actions-grid">
                <a href="javascript:void(0);" onclick="parent.loadPage('student_add.php')" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="action-text">添加学生</div>
                </a>
                <a href="javascript:void(0);" onclick="parent.loadPage('class_add.php')" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-plus-square"></i>
                    </div>
                    <div class="action-text">添加班级</div>
                </a>
                <a href="javascript:void(0);" onclick="parent.loadPage('course_add.php')" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="action-text">添加课程</div>
                </a>
                <a href="javascript:void(0);" onclick="parent.loadPage('score_input.php')" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-pen-fancy"></i>
                    </div>
                    <div class="action-text">录入成绩</div>
                </a>
                <a href="javascript:void(0);" onclick="parent.loadPage('attendance_record.php')" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-check-square"></i>
                    </div>
                    <div class="action-text">考勤记录</div>
                </a>
                <a href="javascript:void(0);" onclick="parent.loadPage('notification_send.php')" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="action-text">发送通知</div>
                </a>
            </div>
        </div>

        <!-- 系统信息区 -->
        <div class="system-info">
            <h2 class="section-title">
                <i class="fas fa-info-circle" style="margin-right: 8px; color: #9f7aea;"></i>系统信息
            </h2>
            <table class="info-table">
                <tr>
                    <td class="info-label">当前用户</td>
                    <td class="info-value"><?php echo $_SESSION['username'] ?? '未知用户'; ?></td>
                </tr>
                <tr>
                    <td class="info-label">登录时间</td>
                    <td class="info-value"><?php echo date('Y-m-d H:i:s', $_SESSION['login_time'] ?? time()); ?></td>
                </tr>
                <tr>
                    <td class="info-label">系统版本</td>
                    <td class="info-value">v1.0.0</td>
                </tr>
                <tr>
                    <td class="info-label">最后更新</td>
                    <td class="info-value">2025-11-24</td>
                </tr>
                <tr>
                    <td class="info-label">技术支持</td>
                    <td class="info-value">班级管理系统开发团队</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 可选：引入Font Awesome图标库JS（如果需要动态图标） -->
    <script src="https://cdn.bootcdn.net/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        // 确保与左侧菜单的loadPage函数兼容
        if (typeof parent.loadPage !== 'function') {
            // 备用加载函数（防止父窗口函数未定义）
            function loadPage(pageUrl) {
                window.location.href = pageUrl;
            }
        }
    </script>
</body>
</html>
