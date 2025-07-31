document.addEventListener('DOMContentLoaded', function() {

    /**
     * ===========================================
     * VARIABEL GLOBAL & FUNGSI BANTUAN
     * ===========================================
     */
    const header = document.querySelector('header');
    const mainNav = document.querySelector('.main-nav');
    const hamburgerBtn = document.querySelector('.hamburger-menu');
    const body = document.body;

    // Fungsi untuk membuka/menutup menu mobile (overlay)
    function toggleMobileMenu() {
        if (!mainNav || !hamburgerBtn || !body) {
            console.error("Elemen navigasi penting (header, .main-nav, .hamburger-menu) tidak ditemukan.");
            return;
        }
        
        mainNav.classList.toggle('active');
        body.classList.toggle('no-scroll');
        const icon = hamburgerBtn.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        }
    }


    /**
     * ===========================================
     * 1. INISIALISASI NAVIGASI (MOBILE & DESKTOP)
     * Logika scroll manual dihapus dan diserahkan ke CSS (scroll-padding-top) untuk akurasi maksimal.
     * ===========================================
     */
    function initNavigation() {
        // a. Inisialisasi Tombol Hamburger
        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', toggleMobileMenu);
        }

        // b. Inisialisasi Klik Link Navigasi
        // Tugasnya hanya untuk menutup menu mobile jika terbuka.
        const navLinks = document.querySelectorAll('.main-nav a');
        if (navLinks.length === 0) return;

        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Jika menu mobile terbuka, tutup setelah link diklik.
                // Ini tidak akan mengganggu navigasi asli karena tidak ada e.preventDefault().
                if (mainNav && mainNav.classList.contains('active')) {
                    toggleMobileMenu();
                }
            });
        });
    }


    /**
     * ===========================================
     * 2. INISIALISASI PENANDA LINK AKTIF SAAT SCROLL
     * Memberi kelas '.active' pada link navigasi yang sesuai dengan posisi scroll.
     * ===========================================
     */
    function initActiveNavLink() {
        const sections = document.querySelectorAll('main section[id]');
        const navLinks = document.querySelectorAll('.main-nav a');

        if (sections.length === 0 || navLinks.length === 0 || !header) return;

        function updateActiveLink() {
            const headerHeight = header.offsetHeight;
            let currentSectionId = '';

            sections.forEach(section => {
                // Memberi toleransi agar link aktif terasa lebih responsif.
                const sectionTop = section.offsetTop - headerHeight - 150; 
                if (window.scrollY >= sectionTop) {
                    currentSectionId = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                // Hanya cocokkan jika hash link tidak kosong dan sama dengan section saat ini.
                if (link.hash && link.hash === `#${currentSectionId}`) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', updateActiveLink);
        updateActiveLink(); // Panggil sekali saat halaman dimuat untuk keadaan awal.
    }


    /**
     * ===========================================
     * 3. INISIALISASI TOMBOL "BACK TO TOP"
     * ===========================================
     */
    function initBackToTop() {
        let backToTopBtn = document.getElementById('backToTopBtn');
        if (!backToTopBtn) {
            backToTopBtn = document.createElement('a');
            backToTopBtn.id = 'backToTopBtn';
            backToTopBtn.href = '#';
            backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
            document.body.appendChild(backToTopBtn);
        }
        
        backToTopBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
        // Panggil sekali untuk status awal
        if (window.pageYOffset > 300) { backToTopBtn.style.display = 'block'; }
    }

    /**
     * ===========================================
     * 4. INISIALISASI ANIMASI SAAT SCROLL (FADE-IN)
     * ===========================================
     */
    function initScrollAnimations() {
        const fadeElements = document.querySelectorAll('.fade-in');
        if (fadeElements.length === 0) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                entry.target.classList.toggle('active', entry.isIntersecting);
            });
        }, { threshold: 0.1 });

        fadeElements.forEach(element => observer.observe(element));
    }

    /**
     * ===========================================
     * 5. INISIALISASI NEWS SLIDER
     * ===========================================
     */
    function initNewsSlider() {
        const sliderContainer = document.querySelector('.berita-slider-container');
        if (!sliderContainer) return;

        const sliderWrapper = sliderContainer.querySelector('.berita-slider-wrapper');
        const slides = sliderContainer.querySelectorAll('.berita-slide-item');
        const prevBtn = sliderContainer.querySelector('.prev-btn');
        const nextBtn = sliderContainer.querySelector('.next-btn');
        const dotsContainer = sliderContainer.querySelector('.slider-dots');

        if (!sliderWrapper || slides.length <= 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
            if (dotsContainer) dotsContainer.style.display = 'none';
            return;
        }

        let currentIndex = 0;
        let slideInterval;

        function updateSlider() {
            sliderWrapper.style.transform = `translateX(${-currentIndex * 100}%)`;
            if (dotsContainer) {
                dotsContainer.querySelectorAll('.dot').forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }
        }

        function goToSlide(index) {
            currentIndex = index;
            updateSlider();
            resetAutoSlide();
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            updateSlider();
        }
        
        function prevSlide() {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            updateSlider();
        }

        function startAutoSlide() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        function resetAutoSlide() {
            startAutoSlide();
        }

        if (dotsContainer) {
            dotsContainer.innerHTML = ''; // Kosongkan dulu untuk mencegah duplikasi
            slides.forEach((_, index) => {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                dot.addEventListener('click', () => goToSlide(index));
                dotsContainer.appendChild(dot);
            });
        }

        if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); resetAutoSlide(); });
        if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); resetAutoSlide(); });

        updateSlider();
        startAutoSlide();
    }
    
    /**
     * ===========================================
     * 6. INISIALISASI FORM FEEDBACK
     * ===========================================
     */
    function initFeedbackForm() {
        const feedbackForm = document.getElementById('feedbackFormMain');
        if (!feedbackForm) return;

        feedbackForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formMessage = document.getElementById('formMessageMain');
            const submitButton = feedbackForm.querySelector('button[type="submit"]');
            const formData = new FormData(feedbackForm);
            const originalButtonText = submitButton.innerHTML;

            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            submitButton.disabled = true;

            fetch('proses_feedback.php', { method: 'POST', body: formData })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    formMessage.className = `form-message ${data.status}`;
                    formMessage.textContent = data.message;
                    if (data.status === 'success') feedbackForm.reset();
                })
                .catch(error => {
                    console.error('Error:', error);
                    formMessage.className = 'form-message error';
                    formMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                })
                .finally(() => {
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                    formMessage.style.display = 'block';
                    formMessage.style.opacity = 1;
                    setTimeout(() => {
                        formMessage.style.opacity = 0;
                        setTimeout(() => formMessage.style.display = 'none', 500);
                    }, 5000);
                });
        });
    }

    /**
     * ===========================================
     * 7. INISIALISASI BALAS KOMENTAR
     * ===========================================
     */
    function initCommentReplies() {
        const replyButtons = document.querySelectorAll('.reply-btn');
        if (replyButtons.length === 0) return;

        replyButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const commentId = this.getAttribute('data-comment-id');
                const replyForm = document.getElementById(`reply-form-${commentId}`);

                if (replyForm) {
                    const isVisible = replyForm.style.display === 'block';
                    document.querySelectorAll('.reply-form-container').forEach(form => {
                        form.style.display = 'none';
                    });
                    replyForm.style.display = isVisible ? 'none' : 'block';
                    if (!isVisible) {
                        replyForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
    }


    /**
     * ===========================================
     * INISIALISASI SEMUA FUNGSI
     * ===========================================
     */
    function initAll() {
        initNavigation();
        initActiveNavLink();
        initBackToTop();
        initScrollAnimations();
        initNewsSlider();
        initFeedbackForm();
        initCommentReplies();
    }

    // Jalankan semua fungsi setelah halaman selesai dimuat.
    initAll();
});
