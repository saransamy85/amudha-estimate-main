/**
 * Modern Counter Animation (0 to Target)
 * High-performance count-up using requestAnimationFrame with ease-out expo easing
 */
function initCountUpAnimation() {
    const counterElements = document.querySelectorAll('.counter-val');
    if (!counterElements.length) return;

    function animateSingleCounter(element, target) {
        const duration = 1400; // 1.4 seconds
        const startTime = performance.now();
        const isDecimal = String(target).includes('.');
        const decimals = isDecimal ? String(target).split('.')[1].length : 0;

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Ease-out expo deceleration curve
            const ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const current = ease * target;

            if (decimals > 0) {
                element.innerText = current.toLocaleString(undefined, {
                    minimumFractionDigits: decimals,
                    maximumFractionDigits: decimals
                });
            } else {
                element.innerText = Math.floor(current).toLocaleString();
            }

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                if (decimals > 0) {
                    element.innerText = target.toLocaleString(undefined, {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    });
                } else {
                    element.innerText = target.toLocaleString();
                }
                element.classList.add('pop');
            }
        }

        requestAnimationFrame(update);
    }

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseFloat(el.getAttribute('data-target')) || 0;
                    animateSingleCounter(el, target);
                    obs.unobserve(el);
                }
            });
        }, { threshold: 0.1 });

        counterElements.forEach(el => observer.observe(el));
    } else {
        counterElements.forEach(el => {
            const target = parseFloat(el.getAttribute('data-target')) || 0;
            animateSingleCounter(el, target);
        });
    }
}

// Run when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCountUpAnimation);
} else {
    initCountUpAnimation();
}
