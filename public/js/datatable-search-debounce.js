/**
 * Debounced search for server-side DataTables (DataTables 1.10.x).
 *
 * DataTables' built-in searchDelay is a throttle: it sends a request on the
 * first keystroke and another one after typing pauses. This replaces the
 * default search box handler of every server-side table with a true debounce,
 * so only one request is sent after the user stops typing.
 *
 * - Enter, and clearing the box, search immediately.
 * - No request is sent when the value has not changed.
 * - Client-side tables and tables without a search box are left untouched.
 * - Opt out per table with `data-no-search-debounce` on the <table> element,
 *   or `searchDebounce: false` in the DataTable options.
 *
 * To change the delay, edit SEARCH_DEBOUNCE_MS below.
 *
 * Must be loaded after DataTables (vendor.js) and before any page script
 * that initializes a table.
 */
(function ($) {
    'use strict';

    var SEARCH_DEBOUNCE_MS = 1000;

    if (!$ || !$.fn || !$.fn.dataTable) {
        return;
    }

    /**
     * Returns a debounced version of fn. The returned function also has
     * .cancel() and .flush() helpers.
     */
    function debounce(fn, ms) {
        var timer = null;
        var lastThis, lastArgs;
        var wait = ms === undefined ? SEARCH_DEBOUNCE_MS : ms;

        var debounced = function () {
            lastThis = this;
            lastArgs = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                timer = null;
                fn.apply(lastThis, lastArgs);
            }, wait);
        };
        debounced.cancel = function () {
            clearTimeout(timer);
            timer = null;
        };
        debounced.flush = function () {
            if (timer !== null) {
                debounced.cancel();
                fn.apply(lastThis, lastArgs);
            }
        };
        return debounced;
    }

    window.DATATABLE_SEARCH_DEBOUNCE_MS = SEARCH_DEBOUNCE_MS;
    window.__dtDebounce = debounce;

    function isServerSide(settings) {
        return !!(settings.oFeatures && settings.oFeatures.bServerSide);
    }

    function isOptedOut(settings) {
        var $table = $(settings.nTable);
        if ($table.is('[data-no-search-debounce]')) {
            return true;
        }
        return !!(settings.oInit && settings.oInit.searchDebounce === false);
    }

    function attach(e, settings) {
        // Only handle events fired by DataTables for its own table, not
        // events bubbling up from nested tables.
        if (e.namespace !== 'dt' || !settings || !settings.nTable) {
            return;
        }
        if (!isServerSide(settings) || !settings.oFeatures.bFilter || isOptedOut(settings)) {
            return;
        }

        var containers = settings.aanFeatures && settings.aanFeatures.f;
        if (!containers || !containers.length) {
            return;
        }

        var $input = $('input', containers);
        if (!$input.length || $input.data('dtSearchDebounced')) {
            return;
        }
        $input.data('dtSearchDebounced', true);

        var api = new $.fn.dataTable.Api(settings);
        var composing = false;

        var runSearch = function (value) {
            // api.search() reads the table's current search, so this also
            // stays correct after a stateSave restore or a programmatic search.
            if (value === api.search()) {
                return;
            }
            api.search(value).draw();
        };
        var debouncedSearch = debounce(runSearch, SEARCH_DEBOUNCE_MS);

        var searchNow = function () {
            debouncedSearch.cancel();
            runSearch($input.val() || '');
        };

        // Remove DataTables' own throttled handlers (keypress.DT is kept: it
        // stops Enter from submitting a surrounding form).
        $input
            .off('keyup.DT search.DT input.DT paste.DT cut.DT')
            .on('input.dtDebounce paste.dtDebounce cut.dtDebounce', function () {
                if (composing) {
                    return;
                }
                var value = this.value || '';
                if (value === '') {
                    // Cleared: restore the full list immediately.
                    searchNow();
                } else {
                    debouncedSearch(value);
                }
            })
            .on('keydown.dtDebounce', function (ev) {
                if (ev.keyCode === 13 && !composing) {
                    // Enter (and barcode scanners, which end with Enter):
                    // search immediately.
                    searchNow();
                }
            })
            .on('search.dtDebounce', function () {
                // Native "x" clear button on type="search" inputs.
                if ((this.value || '') === '') {
                    searchNow();
                }
            })
            .on('compositionstart.dtDebounce', function () {
                composing = true;
            })
            .on('compositionend.dtDebounce', function () {
                composing = false;
                debouncedSearch(this.value || '');
            });

        $(settings.nTable).one('destroy.dt', function () {
            debouncedSearch.cancel();
        });
    }

    // preInit fires after the search box is built and before the first
    // request; init is a fallback. The data flag prevents double binding.
    $(document).on('preInit.dt init.dt', attach);
})(window.jQuery);
