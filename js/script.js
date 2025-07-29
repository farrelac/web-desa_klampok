document.addEventListener('DOMContentLoaded', function () {
    // ===========================================
    // 1. INISIALISASI VARIABEL UTAMA
    // ===========================================
    const body = document.body;
    const hamburgerBtn = document.querySelector('.hamburger-menu');
    const mainNav = document.querySelector('.main-nav');
    const navLinks = mainNav ? mainNav.querySelectorAll('a') : [];
    const header = document.querySelector('header');
    const mainContent = document.querySelector('main');

    // Deklarasikan fungsi toggleMenu di scope terluar
    let toggleMenu;

    // ===========================================
    // 2. MOBILE NAVIGATION
    // ===========================================
    function initMobileNavigation() {
        const hamburgerBtn = document.querySelector('.hamburger-menu');
        const mainNav = document.querySelector('.main-nav');
        const overlay = document.querySelector('.mobile-nav-overlay');
        const body = document.body;

        if (!hamburgerBtn || !mainNav) return;

        const icon = hamburgerBtn.querySelector('i');

        const toggleMenu = function () {
            const isActive = !mainNav.classList.contains('active');

            // Toggle classes
            mainNav.classList.toggle('active', isActive);
            overlay.classList.toggle('active', isActive);
            body.classList.toggle('no-scroll', isActive);

            // Toggle icon
            if (icon) {
                icon.classList.toggle('fa-bars', !isActive);
                icon.classList.toggle('fa-times', isActive);
            }
        };

        // Event listeners
        hamburgerBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);

        // Close menu ketika klik link di mobile
        const navLinks = mainNav.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
            });
        });
    }

    // ===========================================
    // 3. SMOOTH SCROLL NAVIGATION
    // ===========================================
    function initSmoothScroll() {
        function handleNavClick(e) {
            const link = e.currentTarget;
            const targetUrl = new URL(link.href, window.location.origin);
            const currentUrl = new URL(window.location.href);
            const isSamePageAnchor = (targetUrl.pathname === currentUrl.pathname && targetUrl.hash);

            if (isSamePageAnchor) {
                e.preventDefault();

                // Close mobile menu if open
                if (mainNav && mainNav.classList.contains('active') && typeof toggleMenu === 'function') {
                    toggleMenu();
                }

                const targetElement = document.querySelector(targetUrl.hash);
                if (targetElement) {
                    // Dapatkan tinggi header yang sesuai (desktop/mobile)
                    const header = document.querySelector('header');
                    let headerHeight = header.offsetHeight;

                    // Jika mobile, sesuaikan dengan header mobile
                    if (window.innerWidth <= 768) {
                        headerHeight = parseInt(getComputedStyle(document.documentElement)
                            .getPropertyValue('--header-height-mobile').replace('px', ''));
                    }

                    // Scroll tepat di bawah header
                    const targetPosition = targetElement.getBoundingClientRect().top +
                        window.pageYOffset -
                        headerHeight;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    history.pushState(null, null, targetUrl.hash);

                    if (typeof updateActiveNavLink === 'function') {
                        updateActiveNavLink();
                    }
                }
            }
        }

        if (navLinks && navLinks.length > 0) {
            navLinks.forEach(link => {
                link.addEventListener('click', handleNavClick);
            });
        }
    }

    // ===========================================
    // 4. ACTIVE NAV LINK ON SCROLL
    // ===========================================
    function initActiveNavLink() {
        function updateActiveNavLink() {
            const sections = document.querySelectorAll('main section[id]');
            if (!header || sections.length === 0) {
                navLinks.forEach(link => link.classList.remove('active'));
                return;
            }

            const scrollPosition = window.scrollY + header.offsetHeight;
            let currentSectionId = '';

            // Check if above first section
            if (scrollPosition < sections[0].offsetTop) {
                currentSectionId = 'beranda';
            } else {
                sections.forEach(section => {
                    if (scrollPosition >= section.offsetTop) {
                        currentSectionId = section.id;
                    }
                });
            }

            // Update active state
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.hash === `#${currentSectionId}`) {
                    link.classList.add('active');
                }
            });
        }

        // Initialize
        window.addEventListener('scroll', updateActiveNavLink);
        updateActiveNavLink();

        // Ekspos fungsi ini ke scope luar
        return updateActiveNavLink;
    }

    // ===========================================
    // 5. BACK TO TOP BUTTON
    // ===========================================
    function initBackToTop() {
        const backToTopBtn = document.getElementById('backToTopBtn');
        if (!backToTopBtn) return;

        function toggleButton() {
            backToTopBtn.style.display = window.pageYOffset > 300 ? 'block' : 'none';
        }

        function scrollToTop(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Event listeners
        window.addEventListener('scroll', toggleButton);
        backToTopBtn.addEventListener('click', scrollToTop);

        // Initial check
        toggleButton();
    }

    // ===========================================
    // 6. SCROLL ANIMATIONS
    // ===========================================
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

    // ===========================================
    // 7. NEWS SLIDER
    // ===========================================
    function initNewsSlider() {
        const sliderContainer = document.querySelector('.berita-slider-container');
        if (!sliderContainer) return;

        const sliderWrapper = sliderContainer.querySelector('.berita-slider-wrapper');
        const slides = sliderContainer.querySelectorAll('.berita-slide-item');
        const prevBtn = sliderContainer.querySelector('.prev-btn');
        const nextBtn = sliderContainer.querySelector('.next-btn');
        const dotsContainer = sliderContainer.querySelector('.slider-dots');

        // Disable if only 1 slide
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

            // Update dots
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

        // Setup dots
        if (dotsContainer) {
            slides.forEach((_, index) => {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                dot.addEventListener('click', () => goToSlide(index));
                dotsContainer.appendChild(dot);
            });
        }

        // Event listeners
        if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); resetAutoSlide(); });
        if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); resetAutoSlide(); });

        // Initialize
        updateSlider();
        startAutoSlide();
    }

    // ===========================================
    // 8. FEEDBACK FORM
    // ===========================================
    function initFeedbackForm() {
        const feedbackForm = document.getElementById('feedbackFormMain');
        if (!feedbackForm) return;

        feedbackForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formMessage = document.getElementById('formMessageMain');
            const submitButton = feedbackForm.querySelector('button[type="submit"]');
            const formData = new FormData(feedbackForm);
            const originalButtonText = submitButton.innerHTML;

            // Show loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            submitButton.disabled = true;

            // Submit form
            fetch('proses_feedback.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    // Show result message
                    formMessage.className = `form-message ${data.status}`;
                    formMessage.textContent = data.message;

                    // Reset form if success
                    if (data.status === 'success') feedbackForm.reset();
                })
                .catch(error => {
                    console.error('Error:', error);
                    formMessage.className = 'form-message error';
                    formMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                })
                .finally(() => {
                    // Reset button
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;

                    // Show message
                    formMessage.style.display = 'block';
                    formMessage.style.opacity = 1;

                    // Hide after 5 seconds
                    setTimeout(() => {
                        formMessage.style.opacity = 0;
                        setTimeout(() => formMessage.style.display = 'none', 500);
                    }, 5000);
                });
        });
    }

    // ===========================================
    // 9. COMMENT REPLIES
    // ===========================================
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

                    // Hide all reply forms
                    document.querySelectorAll('.reply-form-container').forEach(form => {
                        form.style.display = 'none';
                    });

                    // Toggle clicked form
                    replyForm.style.display = isVisible ? 'none' : 'block';

                    // Scroll to form if showing
                    if (!isVisible) {
                        replyForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }
            });
        });
    }

    // ===========================================
    // 10. INITIALIZE ALL FUNCTIONS
    // ===========================================
    function initAll() {
        initMobileNavigation();
        initSmoothScroll();
        const updateActiveNavLink = initActiveNavLink(); // Dapatkan fungsi updateActiveNavLink
        initBackToTop();
        initScrollAnimations();
        initNewsSlider();
        initFeedbackForm();
        initCommentReplies();
    }

    // Start the application
    initAll();
});