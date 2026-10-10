/*
 * Yaigo POS - responsive helpers (presentation only, see public/css/pos-responsive.css).
 *
 * Adds: the Products / Cart tab switcher (< 992px), the Tables drawer (< 1400px),
 * the header "More" menu (< 1200px), the Total payable in the checkout bar and the
 * collapsible totals (< 768px).
 *
 * It never calls or overrides a POS function and never renames ids, classes or names.
 * Header buttons are moved (not cloned) into the More menu, so they keep their ids and
 * every handler bound to them; checkout buttons are never duplicated, so keyboard
 * shortcuts that trigger them still fire once.
 */
(function () {
    'use strict';

    var TABS_MQ = '(max-width: 991.98px)';
    var DRAWER_MQ = '(max-width: 1399.98px)';
    var MORE_MQ = '(max-width: 1199.98px)';
    var NARROW_MQ = '(max-width: 479.98px)';
    var VIEW_KEY = 'yaigo_pos_view';

    function ready(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    function mq(query) {
        return window.matchMedia(query);
    }

    function onMqChange(list, fn) {
        if (list.addEventListener) {
            list.addEventListener('change', fn);
        } else if (list.addListener) {
            list.addListener(fn);
        }
    }

    function storageGet(key) {
        try {
            return window.sessionStorage.getItem(key);
        } catch (e) {
            return null;
        }
    }

    function storageSet(key, value) {
        try {
            window.sessionStorage.setItem(key, value);
        } catch (e) {
            // Storage blocked (private mode, previews): the tab just isn't remembered
        }
    }

    function el(tag, className, html) {
        var node = document.createElement(tag);
        if (className) {
            node.className = className;
        }
        if (html) {
            node.innerHTML = html;
        }
        return node;
    }

    function textEl(tag, className, text) {
        var node = el(tag, className);
        node.textContent = text;
        return node;
    }

    function modalOpen() {
        return !!document.querySelector('.modal.in');
    }

    var ICONS = {
        tables: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 6a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"/><path d="M4 10h16"/><path d="M10 4v16"/></svg>',
        more: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/><path d="M19 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/></svg>',
        close: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>',
        chevron: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6l6 -6"/></svg>'
    };

    ready(function () {
        var body = document.body;
        if (!body.classList.contains('yaigo-pos')) {
            return;
        }
        body.classList.add('yp-js');

        var lang = window.POS_RESPONSIVE_LANG || {};
        var form = document.getElementById('add_pos_sell_form') || document.getElementById('edit_pos_sell_form');
        var section = document.querySelector('#scrollable-container > section.content');
        var productsPanel = document.getElementById('pos_sidebar_wrap');
        var cartPanel = document.querySelector('.pos-layout-grid > .pos-right-cart-section');
        var tablesPanel = document.querySelector('.pos-layout-grid > .pos-restaurant-section');
        var header = document.querySelector('.pos-header .pos-header-inner');
        var headerActions = document.getElementById('pos_header_more_options');
        var navPos = document.querySelector('.pos-footer-nav-item--pos');
        var navTables = document.querySelector('.pos-footer-nav-item--tables');

        var tabsMq = mq(TABS_MQ);
        var drawerMq = mq(DRAWER_MQ);
        var moreMq = mq(MORE_MQ);
        var narrowMq = mq(NARROW_MQ);

        /* ---------------- Products / Cart tabs ---------------- */
        var setView = function () {};
        var currentView = 'cart';

        if (form && section && productsPanel && cartPanel) {
            var seg = el('div', 'yp-seg no-print');
            seg.setAttribute('role', 'tablist');

            var productsTab = el('button', 'yp-seg-btn');
            productsTab.type = 'button';
            productsTab.setAttribute('role', 'tab');
            productsTab.setAttribute('aria-controls', 'pos_sidebar_wrap');
            productsTab.appendChild(textEl('span', '', lang.products || 'Products'));

            var cartTab = el('button', 'yp-seg-btn');
            cartTab.type = 'button';
            cartTab.setAttribute('role', 'tab');
            cartTab.appendChild(textEl('span', '', lang.cart || 'Cart'));
            var cartCount = textEl('span', 'yp-seg-count', '0');
            cartTab.appendChild(cartCount);

            seg.appendChild(productsTab);
            seg.appendChild(cartTab);
            section.insertBefore(seg, form);

            setView = function (view, remember) {
                currentView = view === 'products' ? 'products' : 'cart';
                body.classList.toggle('yp-view-products', currentView === 'products');
                body.classList.toggle('yp-view-cart', currentView === 'cart');
                productsTab.setAttribute('aria-selected', currentView === 'products' ? 'true' : 'false');
                cartTab.setAttribute('aria-selected', currentView === 'cart' ? 'true' : 'false');
                if (remember !== false) {
                    storageSet(VIEW_KEY, currentView);
                }
            };

            productsTab.addEventListener('click', function () {
                setView('products');
            });
            cartTab.addEventListener('click', function () {
                setView('cart');
            });

            // Bottom nav "POS" toggles the tabs while already on this page
            if (navPos) {
                navPos.addEventListener('click', function (e) {
                    var samePage = navPos.pathname === window.location.pathname;
                    if (tabsMq.matches && samePage) {
                        e.preventDefault();
                        setView(currentView === 'products' ? 'cart' : 'products');
                    }
                });
            }

            // Cart (n): line count, with a bump when something is added from the Products tab
            var tbody = document.querySelector('#pos_table tbody');
            var lastCount = -1;
            var updateCount = function () {
                var count = tbody ? tbody.querySelectorAll('tr.product_row').length : 0;
                cartCount.textContent = String(count);
                if (lastCount >= 0 && count > lastCount && currentView === 'products') {
                    cartCount.classList.remove('yp-bump');
                    void cartCount.offsetWidth; // restart the animation
                    cartCount.classList.add('yp-bump');
                }
                lastCount = count;
            };
            cartCount.addEventListener('animationend', function () {
                cartCount.classList.remove('yp-bump');
            });
            if (tbody && window.MutationObserver) {
                new MutationObserver(updateCount).observe(tbody, { childList: true });
            }
            updateCount();

            // Remembered tab; a cart that already has rows (edit page) opens on Cart
            var saved = storageGet(VIEW_KEY);
            setView(saved || (lastCount > 0 ? 'cart' : 'products'), false);
        } else {
            body.classList.add('yp-view-cart');
        }

        /* ---------------- Tables drawer ---------------- */
        var setTablesOpen = function () {};

        if (tablesPanel) {
            var tablesLabel = lang.tables || 'Tables';
            var backdrop = el('div', 'yp-backdrop no-print');
            backdrop.setAttribute('aria-hidden', 'true');
            body.appendChild(backdrop);

            if (!tablesPanel.id) {
                tablesPanel.id = 'yp_tables_panel';
            }
            tablesPanel.setAttribute('aria-label', tablesLabel);

            var closeBtn = el('button', 'yp-tables-close no-print', ICONS.close);
            closeBtn.type = 'button';
            closeBtn.title = lang.close || 'Close';
            closeBtn.setAttribute('aria-label', lang.close || 'Close');
            var card = tablesPanel.querySelector('.restaurant-sidebar-card') || tablesPanel;
            card.insertBefore(closeBtn, card.firstChild);

            var tablesBtn = el('button', 'pos-header-action-btn yp-tables-btn', ICONS.tables);
            tablesBtn.type = 'button';
            tablesBtn.title = tablesLabel;
            tablesBtn.setAttribute('aria-label', tablesLabel);
            tablesBtn.setAttribute('aria-controls', tablesPanel.id);
            tablesBtn.setAttribute('aria-expanded', 'false');
            tablesBtn.setAttribute('data-yp-priority', '1');
            tablesBtn.appendChild(textEl('span', 'yp-btn-label', tablesLabel));
            if (headerActions) {
                headerActions.insertBefore(tablesBtn, headerActions.firstChild);
            }

            var lastFocus = null;
            setTablesOpen = function (open) {
                open = !!open && drawerMq.matches;
                if (open === body.classList.contains('yp-tables-open')) {
                    return;
                }
                body.classList.toggle('yp-tables-open', open);
                tablesBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (open) {
                    lastFocus = document.activeElement;
                    closeBtn.focus({ preventScroll: true });
                } else if (lastFocus && lastFocus.focus && tabsMq.matches === false) {
                    lastFocus.focus({ preventScroll: true });
                }
            };

            tablesBtn.addEventListener('click', function () {
                setTablesOpen(!body.classList.contains('yp-tables-open'));
            });
            closeBtn.addEventListener('click', function () {
                setTablesOpen(false);
            });
            backdrop.addEventListener('click', function () {
                setTablesOpen(false);
            });
            // Selecting a table closes the drawer; the restaurant sidebar script has
            // already written it to select[name="res_table_id"]
            tablesPanel.addEventListener('click', function (e) {
                if (e.target.closest && e.target.closest('.custom-table-btn')) {
                    window.setTimeout(function () {
                        setTablesOpen(false);
                    }, 150);
                }
            });
            if (navTables) {
                navTables.addEventListener('click', function (e) {
                    if (drawerMq.matches) {
                        e.preventDefault();
                        setTablesOpen(true);
                    }
                });
            }
            onMqChange(drawerMq, function () {
                if (!drawerMq.matches) {
                    setTablesOpen(false);
                }
            });
        }

        /* ---------------- Header: More menu ---------------- */
        var setMoreOpen = function () {};

        if (header && headerActions) {
            var items = Array.prototype.slice.call(headerActions.children);
            var moreLabel = lang.more || 'More';

            var moreBtn = el('button', 'pos-header-action-btn yp-more-btn', ICONS.more);
            moreBtn.type = 'button';
            moreBtn.title = moreLabel;
            moreBtn.setAttribute('aria-label', moreLabel);
            moreBtn.setAttribute('aria-haspopup', 'true');
            moreBtn.setAttribute('aria-expanded', 'false');
            moreBtn.setAttribute('aria-controls', 'yp_more_menu');

            var menu = el('div', 'yp-more-menu no-print');
            menu.id = 'yp_more_menu';
            var clock = document.querySelector('.pos-header span.curr_datetime');
            if (clock) {
                // pos.js refreshes every span.curr_datetime every minute
                var menuClock = el('div', 'yp-more-datetime');
                menuClock.appendChild(textEl('span', 'curr_datetime', clock.textContent.trim()));
                menu.appendChild(menuClock);
            }
            header.appendChild(menu);

            var placeItems = function () {
                var useMenu = moreMq.matches;
                var inMenu = 0;
                items.forEach(function (item) {
                    var priority = parseInt(item.getAttribute('data-yp-priority') || '3', 10);
                    var inline = !useMenu || priority === 1 || (priority === 2 && !narrowMq.matches);
                    var target = inline ? headerActions : menu;
                    target.appendChild(item); // keeps the original order
                    if (!inline && item.tagName !== 'SPAN' && getComputedStyle(item).display !== 'none') {
                        inMenu++;
                    }
                });
                headerActions.appendChild(moreBtn);
                moreBtn.classList.toggle('yp-empty', inMenu === 0);
                if (!useMenu) {
                    setMoreOpen(false);
                }
            };

            setMoreOpen = function (open) {
                body.classList.toggle('yp-more-open', !!open);
                moreBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            };

            moreBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                setMoreOpen(!body.classList.contains('yp-more-open'));
            });
            menu.addEventListener('click', function (e) {
                var item = e.target.closest ? e.target.closest('.yp-more-menu > a, .yp-more-menu > button') : null;
                // Popover buttons (calculator, sell return, ...) open next to the item, so the menu stays open
                if (item && !item.matches('[data-toggle="popover"]')) {
                    setMoreOpen(false);
                }
            });
            document.addEventListener('click', function (e) {
                if (!body.classList.contains('yp-more-open')) {
                    return;
                }
                var t = e.target;
                if (menu.contains(t) || moreBtn.contains(t) || (t.closest && t.closest('.popover'))) {
                    return;
                }
                setMoreOpen(false);
            });

            onMqChange(moreMq, placeItems);
            onMqChange(narrowMq, placeItems);
            placeItems();
        }

        /* ---------------- Checkout bar: Total payable ---------------- */
        var actionBar = document.querySelector('.pos_cart_action_buttons');
        var totalPayable = document.getElementById('total_payable');
        if (actionBar && totalPayable) {
            var barTotal = el('div', 'yp-bar-total');
            barTotal.setAttribute('aria-live', 'polite');
            barTotal.appendChild(textEl('span', 'yp-bar-total-label', lang.total_payable || 'Total payable'));
            var barValue = textEl('span', 'yp-bar-total-value', '');
            barTotal.appendChild(barValue);
            actionBar.insertBefore(barTotal, actionBar.firstChild);

            // Mirrors the text pos.js writes into #total_payable (same formatting)
            var syncTotal = function () {
                barValue.textContent = totalPayable.textContent.trim();
            };
            if (window.MutationObserver) {
                new MutationObserver(syncTotal).observe(totalPayable, { childList: true, characterData: true, subtree: true });
            }
            syncTotal();
        }

        /* ---------------- Totals: collapsible on phones ---------------- */
        var totals = document.querySelector('.pos_form_totals');
        if (totals) {
            var toggle = el('button', 'yp-totals-toggle');
            toggle.type = 'button';
            toggle.setAttribute('aria-expanded', 'false');
            toggle.appendChild(textEl('span', '', lang.details || 'Details'));
            toggle.insertAdjacentHTML('beforeend', ICONS.chevron);
            totals.insertBefore(toggle, totals.firstChild);
            toggle.addEventListener('click', function () {
                var open = !totals.classList.contains('yp-open');
                totals.classList.toggle('yp-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }

        /* ---------------- Escape closes drawer / menu ---------------- */
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape' || modalOpen()) {
                return;
            }
            if (body.classList.contains('yp-tables-open')) {
                setTablesOpen(false);
            } else if (body.classList.contains('yp-more-open')) {
                setMoreOpen(false);
            }
        });

        /* ---------------- No keyboard pop-up on touch screens ---------------- */
        // pos.js focuses the product search after page load and after each added row.
        // On touch screens that opens the on-screen keyboard, so a focus not started by
        // the user (tap or key press) is undone. Desktop behaviour is unchanged.
        var coarse = mq('(pointer: coarse)');
        var search = document.getElementById('search_product');
        if (search) {
            var lastUserInput = 0;
            var markUser = function () {
                lastUserInput = Date.now();
            };
            search.addEventListener('pointerdown', markUser);
            search.addEventListener('touchstart', markUser, { passive: true });
            document.addEventListener('keydown', markUser, true);
            search.addEventListener('focus', function () {
                if (coarse.matches && Date.now() - lastUserInput > 800) {
                    search.blur();
                }
            });
            if (coarse.matches && document.activeElement === search) {
                search.blur();
            }
        }
    });
})();
