/*
 * POS responsive helpers (presentation only).
 *
 * Below 992px the product panel (#pos_sidebar_wrap) is shown as a slide-in drawer
 * (see public/css/pos-responsive.css). This script only adds a "Products" toggle
 * button and a backdrop, and toggles body classes. It does not call, override or
 * depend on any POS function, and it does not move any existing element.
 */
(function () {
    'use strict';

    var DRAWER_BREAKPOINT = 992;

    function ready(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    ready(function () {
        var body = document.body;
        var panel = document.getElementById('pos_sidebar_wrap');

        // Product suggestions disabled in POS settings: nothing to toggle
        if (!body.classList.contains('lockscreen') || !panel) {
            return;
        }

        body.classList.add('pos-rsp-js');

        // Mobile user agents get the existing products modal (header bag button)
        if (document.getElementById('mobile_product_suggestion_modal')) {
            body.classList.add('pos-rsp-has-modal');
        }

        var lang = window.POS_RESPONSIVE_LANG || {};
        var openLabel = lang.products || 'Products';
        var closeLabel = lang.close || 'Close';

        if (!panel.hasAttribute('aria-label')) {
            panel.setAttribute('aria-label', openLabel);
        }

        var backdrop = document.createElement('div');
        backdrop.className = 'pos-rsp-backdrop no-print';
        backdrop.setAttribute('aria-hidden', 'true');

        var toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'pos-rsp-products-toggle no-print';
        toggle.setAttribute('aria-controls', 'pos_sidebar_wrap');
        toggle.setAttribute('aria-expanded', 'false');

        var icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6.331 8h11.339a2 2 0 0 1 1.977 2.304l-1.255 8.152a3 3 0 0 1 -2.966 2.544h-6.852a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304z"/><path d="M9 11v-5a3 3 0 0 1 6 0v5"/></svg>';

        function setOpen(open) {
            body.classList.toggle('pos-products-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.innerHTML = icon;
            var label = document.createElement('span');
            label.textContent = open ? closeLabel : openLabel;
            toggle.appendChild(label);
        }

        toggle.addEventListener('click', function () {
            setOpen(!body.classList.contains('pos-products-open'));
        });
        backdrop.addEventListener('click', function () {
            setOpen(false);
        });
        document.addEventListener('keydown', function (e) {
            // Close on Escape unless a Bootstrap modal or category/brand drawer is handling it
            if (e.key === 'Escape' && body.classList.contains('pos-products-open') &&
                !document.querySelector('.modal.in') &&
                !document.querySelector('.tw-dw-drawer-toggle:checked')) {
                setOpen(false);
            }
        });

        // Leaving the drawer layout (rotate / resize to desktop) resets the state
        var mq = window.matchMedia('(min-width: ' + DRAWER_BREAKPOINT + 'px)');
        var onChange = function () {
            if (mq.matches) {
                setOpen(false);
            }
        };
        if (mq.addEventListener) {
            mq.addEventListener('change', onChange);
        } else if (mq.addListener) {
            mq.addListener(onChange);
        }

        setOpen(false);
        body.appendChild(backdrop);
        body.appendChild(toggle);
    });
})();
