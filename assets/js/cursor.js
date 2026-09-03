(function () {
    'use strict';

    const root = document.documentElement;

    function isTouchUi() {
        return root.classList.contains('swc-touch-ui');
    }

    function enableCustomCursor() {
        // Normal desktop/laptop: always enable the custom cursor.
        // This avoids unreliable pointer media-query results on some Windows laptops.
        if (!isTouchUi()) {
            root.classList.add('swc-custom-cursor-ready');
            return;
        }

        // Phones/tablets normally have no cursor. If a real mouse/trackpad is attached,
        // allow the custom cursor itself, but never the decorative follower.
        if (!window.matchMedia) return;

        const finePointer = window.matchMedia('(any-pointer: fine)');
        const hoverPointer = window.matchMedia('(any-hover: hover)');

        function syncTouchCursor() {
            root.classList.toggle(
                'swc-custom-cursor-ready',
                !!(finePointer.matches || hoverPointer.matches)
            );
        }

        syncTouchCursor();

        [finePointer, hoverPointer].forEach(function (query) {
            if (typeof query.addEventListener === 'function') {
                query.addEventListener('change', syncTouchCursor);
            } else if (typeof query.addListener === 'function') {
                query.addListener(syncTouchCursor);
            }
        });
    }

    function setupFollower() {
        const follower = document.querySelector('.cursor-follower');
        if (!follower) return;

        // The follower is strictly desktop/laptop only.
        if (!root.classList.contains('swc-desktop-ui')) {
            root.classList.remove('swc-follower-ready');
            follower.setAttribute('aria-hidden', 'true');
            return;
        }

        root.classList.add('swc-follower-ready');
        follower.setAttribute('aria-hidden', 'true');

        function moveFollower(event) {
            follower.style.left = event.clientX + 'px';
            follower.style.top = event.clientY + 'px';
            follower.style.opacity = '1';
        }

        document.addEventListener('mousemove', moveFollower, { passive: true });

        document.addEventListener('mouseleave', function () {
            follower.style.opacity = '0';
        });

        document.addEventListener('mouseenter', function () {
            follower.style.opacity = '1';
        });

        // Delegated hover handling also works for dynamically rendered links/buttons.
        document.addEventListener('mouseover', function (event) {
            if (!(event.target instanceof Element)) return;
            const interactive = event.target.closest(
                'a, button, input, textarea, select, [role="button"], .card-hover'
            );
            if (!interactive) return;
            follower.classList.add('is-interactive');
        });

        document.addEventListener('mouseout', function (event) {
            if (!(event.target instanceof Element)) return;
            const interactive = event.target.closest(
                'a, button, input, textarea, select, [role="button"], .card-hover'
            );
            if (!interactive) return;

            const next = event.relatedTarget;
            if (next instanceof Node && interactive.contains(next)) return;
            follower.classList.remove('is-interactive');
        });
    }

    enableCustomCursor();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupFollower, { once: true });
    } else {
        setupFollower();
    }
})();
