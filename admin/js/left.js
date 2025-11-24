        // 加载页面到右侧iframe
        function loadPage(pageUrl) {
            try {
                // 获取父窗口中的右侧iframe
                const rightFrame = parent.document.querySelector('.right-frame');
                
                if (rightFrame) {
                    // 移除所有菜单项的active类
                    const menuLinks = document.querySelectorAll('.menu a');
                    menuLinks.forEach(link => {
                        link.classList.remove('active');
                    });
                    
                    // 为当前点击的菜单项添加active类
                    event.target.classList.add('active');
                    
                    // 加载页面
                    rightFrame.src = pageUrl;
                    
                    // 可选：添加加载中提示
                    showLoading();
                    
                    // 监听iframe加载完成事件
                    rightFrame.onload = function() {
                        hideLoading();
                    };
                    
                    // 可选：记录当前页面状态
                    localStorage.setItem('lastPage', pageUrl);
                } else {
                    console.error('右侧iframe不存在');
                    alert('错误：无法找到右侧显示区域');
                }
            } catch (e) {
                console.error('加载页面失败:', e);
                alert('加载页面失败，请重试');
            }
        }
        
        // 显示加载中提示
        function showLoading() {
            try {
                const loadingDiv = parent.document.getElementById('loading-indicator');
                if (loadingDiv) {
                    loadingDiv.style.display = 'block';
                } else {
                    // 如果没有加载指示器，创建一个
                    const indicator = parent.document.createElement('div');
                    indicator.id = 'loading-indicator';
                    indicator.style.cssText = `
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background: rgba(0,0,0,0.7);
                        color: white;
                        padding: 20px;
                        border-radius: 5px;
                        z-index: 9999;
                        display: block;
                    `;
                    indicator.innerHTML = '<div>加载中...</div>';
                    parent.document.body.appendChild(indicator);
                }
            } catch (e) {
                console.error('显示加载提示失败:', e);
            }
        }
        
        // 隐藏加载中提示
        function hideLoading() {
            try {
                const loadingDiv = parent.document.getElementById('loading-indicator');
                if (loadingDiv) {
                    loadingDiv.style.display = 'none';
                }
            } catch (e) {
                console.error('隐藏加载提示失败:', e);
            }
        }
        
        // 页面加载完成后初始化
        document.addEventListener('DOMContentLoaded', function() {
            // 尝试加载上次访问的页面
            const lastPage = localStorage.getItem('lastPage');
            if (lastPage) {
                // 延迟加载，给页面足够的初始化时间
                setTimeout(() => {
                    // 找到对应的链接并点击
                    const links = document.querySelectorAll('.menu a');
                    links.forEach(link => {
                        // 这里需要根据实际情况调整匹配逻辑
                        if (link.getAttribute('onclick').includes(lastPage)) {
                            link.click();
                        }
                    });
                }, 500);
            }
        });