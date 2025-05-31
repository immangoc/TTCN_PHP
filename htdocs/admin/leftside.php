<?php
if (isset($_GET['admin_id'])) {
    Session::destroyAdmin();
}
?>

<header class="app-header">
    <div class="app-header__logo">
        <span class="app-header__title">Admin Dashboard</span>
    </div>
    <ul class="app-nav">
        <li>
            <a class="app-nav__item" href="?admin_id=<?php echo Session::get('admin_id') ?>" title="Đăng xuất">
                <i class="fas fa-sign-out-alt"></i> 
                <span>Đăng xuất</span>
            </a>
        </li>
    </ul>
</header>

<aside class="app-sidebar">
    <button class="app-sidebar__toggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>
    <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="./icon/AdmQ2.png" width="60px" alt="User Image">
        <div class="app-sidebar__user-info">
            <h3 class="app-sidebar__user-name"><?php echo Session::get('admin_User') ?></h3>
            <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
        </div>
    </div>
    <hr>
    <ul class="app-menu">
        <li>
            <a class="app-menu__item" href="index.php">
                <i class="app-menu__icon fas fa-cart-shopping"></i>
                <span class="app-menu__label">POS Bán Hàng</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="Sanphamlist.php">
                <i class="app-menu__icon fas fa-box"></i>
                <span class="app-menu__label">Quản lý sản phẩm</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="orderlistall.php">
                <i class="app-menu__icon fas fa-file-invoice"></i>
                <span class="app-menu__label">Quản lý đơn hàng</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="binhluanAll.php">
                <i class="app-menu__icon fas fa-comments"></i>
                <span class="app-menu__label">Quản lý bình luận</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="rating.php">
                <i class="app-menu__icon fas fa-star"></i>
                <span class="app-menu__label">Đánh giá sản phẩm</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="wishlist.php">
                <i class="app-menu__icon fas fa-heart"></i>
                <span class="app-menu__label">Sản phẩm yêu thích</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="registerKH.php">
                <i class="app-menu__icon fas fa-users"></i>
                <span class="app-menu__label">Khách hàng</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="statistical.php">
                <i class="app-menu__icon fas fa-chart-line"></i>
                <span class="app-menu__label">Báo cáo doanh thu</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="slider.php">
                <i class="app-menu__icon fas fa-images"></i>
                <span class="app-menu__label">Slider</span>
            </a>
        </li>
    </ul>
</aside>

<style>
    :root {
        --primary-color: #1a2639;
        --accent-color: #3f8efc;
        --text-color: #2d3748;
        --sidebar-bg: #ffffff;
        --hover-bg: #f1f5f9;
        --active-bg: #3f8efc;
        --active-text: #ffffff;
        --border-color: #e2e8f0;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .app-header {
        background: var(--primary-color);
        padding: 12px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow);
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
    }

    .app-header__logo {
        display: flex;
        align-items: center;
    }

    .app-header__title {
        color: white;
        font-size: 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .app-nav {
        display: flex;
        list-style: none;
    }

    .app-nav__item {
        color: #ffffff;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 500;
        transition: var(--transition);
    }

    .app-nav__item:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-1px);
    }

    .app-nav__item i {
        font-size: 16px;
    }

    .app-sidebar {
    background: var(--sidebar-bg);
    width: 70px;
    height: calc(100vh - 64px); 
    position: fixed;
    top: 50px; 
    left: 0;
    padding: 20px 10px;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
    transition: width var(--transition);
    z-index: 999;
    overflow-y: auto;
    }

    .app-sidebar.expanded {
        width: 260px;
    }

    .app-sidebar__toggle {
        display: none;
        background: none;
        border: none;
        color: var(--text-color);
        font-size: 20px;
        cursor: pointer;
        margin-bottom: 20px;
        width: 100%;
        text-align: center;
    }

    .app-sidebar__user {
        display: flex;
        align-items: center;
        padding: 0 10px;
        margin-bottom: 20px;
        opacity: 0;
        transition: opacity var(--transition);
    }

    .app-sidebar.expanded .app-sidebar__user {
        opacity: 1;
    }

    .app-sidebar__user-avatar {
        border-radius: 50%;
        border: 2px solid var(--accent-color);
        margin-right: 12px;
        transition: transform 0.4s ease;
    }

    .app-sidebar.expanded .app-sidebar__user-avatar {
        transform: scale(1.1);
    }

    .app-sidebar__user-info {
        flex: 1;
    }

    .app-sidebar__user-name {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-color);
        margin: 0;
        white-space: nowrap;
    }

    .app-sidebar__user-designation {
        font-size: 13px;
        color: #64748b;
        margin-top: 4px;
    }

    hr {
        border: 0;
        border-top: 1px solid var(--border-color);
        margin: 16px 0;
        opacity: 0;
        transition: opacity var(--transition);
    }

    .app-sidebar.expanded hr {
        opacity: 1;
    }

    .app-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .app-menu__item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        margin: 4px 0;
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-color);
        font-size: 15px;
        font-weight: 500;
        transition: var(--transition);
    }

    .app-menu__item:hover {
        background: var(--hover-bg);
        transform: translateX(4px);
        color: var(--accent-color);
    }

    .app-menu__item.active {
        background: var(--active-bg);
        color: var(--active-text);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .app-menu__icon {
        font-size: 18px;
        width: 24px;
        text-align: center;
        margin-right: 12px;
    }

    .app-menu__label {
        opacity: 0;
        transition: opacity var(--transition);
    }

    .app-sidebar.expanded .app-menu__label {
        opacity: 1;
    }

    .app-menu__item.active .app-menu__label {
        opacity: 1;
    }

    @media (max-width: 768px) {
        .app-sidebar {
            width: 60px;
        }

        .app-sidebar.expanded {
            width: 220px;
        }

        .app-sidebar__toggle {
            display: block;
        }

        .app-header {
            padding: 10px 16px;
        }

        .app-header__title {
            font-size: 18px;
        }

        .app-nav__item {
            padding: 6px 12px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .app-sidebar__user-avatar {
            width: 40px !important;
        }

        .app-sidebar__user-name {
            font-size: 14px;
        }

        .app-sidebar__user-designation {
            font-size: 12px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.app-sidebar');
    const toggleButton = document.querySelector('.app-sidebar__toggle');
    const menuItems = document.querySelectorAll('.app-menu__item');
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';

    // Set active menu item based on current page
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPage) {
            item.classList.add('active');
        }

        item.addEventListener('click', function() {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Toggle sidebar on button click
    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('expanded');
    });

    // Auto-expand sidebar on hover for desktop
    sidebar.addEventListener('mouseenter', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.add('expanded');
        }
    });

    sidebar.addEventListener('mouseleave', function() {
        if (window.innerWidth > 768 && !sidebar.classList.contains('pinned')) {
            sidebar.classList.remove('expanded');
        }
    });

    // Persist sidebar state on mobile
    if (window.innerWidth <= 768) {
        sidebar.classList.remove('expanded');
    }
});
</script>