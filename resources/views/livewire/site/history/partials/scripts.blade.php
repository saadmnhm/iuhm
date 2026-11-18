<script>
    (function () {
        'use strict';

        const boot = () => {
            const page = document.querySelector('[data-history-page]');
            if (!page) return;

            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const supportsObserver = 'IntersectionObserver' in window;

            /* ---------------------------------------------------------------
               Scroll-triggered reveals (staggering handled by --reveal-delay)
               --------------------------------------------------------------- */
            const revealItems = Array.from(page.querySelectorAll('[data-reveal]'));

            if (!supportsObserver || reduceMotion) {
                revealItems.forEach((item) => item.classList.add('is-visible'));
            } else {
                const revealObserver = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;
                        entry.target.classList.add('is-visible');
                        revealObserver.unobserve(entry.target);
                    });
                }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

                revealItems.forEach((item) => revealObserver.observe(item));
            }

            /* ---------------------------------------------------------------
               Animated counters
               --------------------------------------------------------------- */
            const formatNumber = (value, mode) => {
                if (mode === 'compact' && value >= 1000) {
                    return (value / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
                }

                return String(Math.round(value)).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            };

            const animateCounter = (element) => {
                const target = Number(element.dataset.target || 0);
                const mode = element.dataset.format || 'plain';
                const prefix = element.dataset.prefix || '';
                const suffix = element.dataset.suffix || '';
                const duration = 1600;
                const start = performance.now();

                const tick = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    element.textContent = prefix + formatNumber(target * eased, mode) + suffix;

                    if (progress < 1) {
                        window.requestAnimationFrame(tick);
                    } else {
                        element.textContent = prefix + formatNumber(target, mode) + suffix;
                    }
                };

                window.requestAnimationFrame(tick);
            };

            const counters = Array.from(page.querySelectorAll('[data-hist-counter]'));

            if (counters.length && (!supportsObserver || reduceMotion)) {
                counters.forEach((element) => {
                    const prefix = element.dataset.prefix || '';
                    const suffix = element.dataset.suffix || '';

                    element.textContent = prefix + formatNumber(Number(element.dataset.target || 0), element.dataset.format || 'plain') + suffix;
                });
            } else if (counters.length) {
                const counterObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;
                        observer.unobserve(entry.target);
                        animateCounter(entry.target);
                    });
                }, { threshold: 0.5 });

                counters.forEach((element) => counterObserver.observe(element));
            }

            /* ---------------------------------------------------------------
               Timeline rail progress
               --------------------------------------------------------------- */
            const timeline = page.querySelector('[data-hist-timeline]');
            const progressBar = page.querySelector('[data-hist-progress]');
            let progressFrame = null;

            const paintProgress = () => {
                progressFrame = null;

                if (!timeline || !progressBar) return;

                const rect = timeline.getBoundingClientRect();
                const viewport = window.innerHeight || document.documentElement.clientHeight;
                const travelled = viewport * 0.72 - rect.top;
                const ratio = Math.max(0, Math.min(1, travelled / Math.max(rect.height, 1)));

                progressBar.style.transform = 'scaleY(' + ratio + ')';
            };

            const requestProgress = () => {
                if (progressFrame !== null) return;
                progressFrame = window.requestAnimationFrame(paintProgress);
            };

            if (timeline && progressBar) {
                if (reduceMotion) {
                    progressBar.style.transform = 'scaleY(1)';
                } else {
                    paintProgress();
                    window.addEventListener('scroll', requestProgress, { passive: true });
                    window.addEventListener('resize', requestProgress);
                }
            }

            /* ---------------------------------------------------------------
               Active chapter + jump navigation
               --------------------------------------------------------------- */
            const chapters = Array.from(page.querySelectorAll('[data-hist-chapter]'));
            const jumps = Array.from(page.querySelectorAll('[data-hist-jump]'));

            if (supportsObserver && chapters.length) {
                const chapterObserver = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;

                        const index = entry.target.getAttribute('data-hist-chapter');

                        chapters.forEach((chapter) => {
                            chapter.classList.toggle('is-active', chapter === entry.target);
                        });

                        jumps.forEach((jump) => {
                            const isActive = jump.getAttribute('data-hist-jump') === index;

                            jump.classList.toggle('is-active', isActive);

                            if (isActive) {
                                jump.setAttribute('aria-current', 'true');
                            } else {
                                jump.removeAttribute('aria-current');
                            }
                        });
                    });
                }, { rootMargin: '-38% 0px -46% 0px', threshold: 0 });

                chapters.forEach((chapter) => chapterObserver.observe(chapter));
            }

            /* ---------------------------------------------------------------
               Accessible image lightbox
               --------------------------------------------------------------- */
            const lightbox = page.querySelector('[data-hist-lightbox]');
            const galleryButtons = Array.from(page.querySelectorAll('[data-hist-gallery]'));

            if (!lightbox || !galleryButtons.length) return;

            const image = lightbox.querySelector('[data-hist-lightbox-image]');
            const title = lightbox.querySelector('[data-hist-lightbox-title]');
            const counter = lightbox.querySelector('[data-hist-lightbox-counter]');
            const closeButton = lightbox.querySelector('[data-hist-close]');
            const counterTemplate = (counter && counter.getAttribute('data-template')) || ':current / :total';

            const items = galleryButtons.map((button) => ({
                src: button.getAttribute('data-src') || '',
                alt: button.getAttribute('data-alt') || '',
                title: button.getAttribute('data-title') || '',
            }));

            let current = 0;
            let lastFocused = null;

            const render = () => {
                const item = items[current];
                if (!item) return;

                if (image) {
                    image.src = item.src;
                    image.alt = item.alt;
                }

                if (title) title.textContent = item.title;

                if (counter) {
                    counter.textContent = counterTemplate
                        .replace(':current', String(current + 1))
                        .replace(':total', String(items.length));
                }
            };

            const open = (index) => {
                current = index;
                lastFocused = document.activeElement;

                render();
                lightbox.classList.add('is-open');
                lightbox.setAttribute('aria-hidden', 'false');
                document.documentElement.style.overflow = 'hidden';

                if (closeButton instanceof HTMLElement) closeButton.focus();
            };

            const close = () => {
                lightbox.classList.remove('is-open');
                lightbox.setAttribute('aria-hidden', 'true');
                document.documentElement.style.overflow = '';

                if (lastFocused instanceof HTMLElement) lastFocused.focus();
            };

            const step = (direction) => {
                current = (current + direction + items.length) % items.length;
                render();
            };

            galleryButtons.forEach((button, index) => {
                button.addEventListener('click', () => open(index));
            });

            if (closeButton) closeButton.addEventListener('click', close);

            lightbox.querySelectorAll('[data-hist-step]').forEach((button) => {
                button.addEventListener('click', () => {
                    step(Number(button.getAttribute('data-hist-step')) || 0);
                });
            });

            lightbox.addEventListener('click', (event) => {
                if (event.target === lightbox) close();
            });

            document.addEventListener('keydown', (event) => {
                if (!lightbox.classList.contains('is-open')) return;

                const isRtl = document.documentElement.getAttribute('dir') === 'rtl';

                if (event.key === 'Escape') {
                    event.preventDefault();
                    close();
                } else if (event.key === 'ArrowRight') {
                    event.preventDefault();
                    step(isRtl ? -1 : 1);
                } else if (event.key === 'ArrowLeft') {
                    event.preventDefault();
                    step(isRtl ? 1 : -1);
                } else if (event.key === 'Tab') {
                    const focusable = Array.from(lightbox.querySelectorAll('button:not([disabled])'));

                    if (!focusable.length) return;

                    const first = focusable[0];
                    const last = focusable[focusable.length - 1];

                    if (event.shiftKey && document.activeElement === first) {
                        event.preventDefault();
                        last.focus();
                    } else if (!event.shiftKey && document.activeElement === last) {
                        event.preventDefault();
                        first.focus();
                    }
                }
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }
    })();
</script>
