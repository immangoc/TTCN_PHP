var heder = document.getElementById('header');
var mobileMenu = document.getElementById('mobile-menu');
var headerHeight = heder.clientHeight;
    mobileMenu.onclick = function() {
        var isClosed = header.clientHeight === headerHeight;
            if (isClosed) {
                heder.style.height = 'auto';
            } else {
                heder.style.height = null;
            }
    }

 const buttons = document.querySelectorAll('.home-filter_btn');

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            // Xoá class active khỏi tất cả nút
            buttons.forEach(b => b.classList.remove('btn--primary'));

            // Thêm class active vào nút được click
            this.classList.add('btn-primary');
        });
    });