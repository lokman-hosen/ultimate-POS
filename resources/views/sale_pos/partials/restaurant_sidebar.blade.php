<div class="restaurant-sidebar-card tw-shadow-[rgba(17,_17,_26,_0.08)_0px_0px_16px] tw-rounded-2xl tw-bg-white tw-border tw-border-slate-100 tw-p-3 tw-flex tw-flex-col tw-h-full tw-gap-4">
    
    @if(in_array('tables', $enabled_modules))
    <div class="restaurant-tables-container tw-flex-1 tw-flex tw-flex-col lg:tw-min-h-[250px]">
        <h4 class="restaurant-section-title tw-font-bold tw-text-lg tw-mb-3 tw-text-slate-800">Tables</h4>
        
        <!-- Types of Service / Filter pills in header (horizontal scrollable) -->
        @if(in_array('types_of_service', $enabled_modules) && !empty($types_of_service))
        @php
            $selected_service_id = $transaction->types_of_service_id ?? request()->get('types_of_service_id');
        @endphp
        <div id="custom_service_list" class="tw-flex tw-gap-2 tw-mb-3 tw-overflow-x-auto tw-pb-1">
            @foreach($types_of_service as $key => $value)
                <button type="button" class="custom-service-btn @if(!empty($selected_service_id) && $selected_service_id == $key) active-service @endif tw-px-4 tw-py-1.5 tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-text-slate-700 tw-text-sm tw-font-semibold tw-whitespace-nowrap tw-shrink-0 tw-cursor-pointer tw-transition-all hover:tw-bg-slate-50 hover:tw-border-slate-300 hover:tw-text-slate-900 active:tw-scale-95" data-val="{{$key}}">
                    {{$value}}
                </button>
            @endforeach
        </div>
        @endif

        <div id="custom_tables_grid" class="tw-gap-3 tw-overflow-y-auto tw-p-1 custom-scroll tw-content-start">
            <!-- Tables will be injected here by JS -->
            <div class="tw-text-slate-400 tw-text-sm" style="grid-column: 1 / -1;">Loading tables...</div>
        </div>
    </div>
    @elseif(in_array('types_of_service', $enabled_modules) && !empty($types_of_service))
    @php
        $selected_service_id = $transaction->types_of_service_id ?? request()->get('types_of_service_id');
    @endphp
    <div class="restaurant-tables-container tw-flex-1 tw-flex tw-flex-col lg:tw-min-h-[200px]">
        <h4 class="restaurant-section-title tw-font-bold tw-text-lg tw-mb-3 tw-text-slate-800">Type of Service</h4>
        <div id="custom_service_list" class="tw-flex tw-gap-2 tw-mb-3 tw-overflow-x-auto tw-pb-1">
            @foreach($types_of_service as $key => $value)
                <button type="button" class="custom-service-btn @if(!empty($selected_service_id) && $selected_service_id == $key) active-service @endif tw-px-4 tw-py-1.5 tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-text-slate-700 tw-text-sm tw-font-semibold tw-whitespace-nowrap tw-shrink-0 tw-cursor-pointer tw-transition-all hover:tw-bg-slate-50 hover:tw-border-slate-300 hover:tw-text-slate-900 active:tw-scale-95" data-val="{{$key}}">
                    {{$value}}
                </button>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
    /* Scrollbar styling for the sidebar table grid */
    .custom-scroll::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .custom-scroll:hover::-webkit-scrollbar-thumb {
        background: #94a3b8;
    }

    /* Hide scrollbar for service list while keeping swipe/scrollability */
    #custom_service_list {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        user-select: none;
        -webkit-user-select: none;
        cursor: grab;
        scroll-behavior: smooth;
    }
    #custom_service_list.active-dragging {
        cursor: grabbing;
        scroll-behavior: auto;
    }
    #custom_service_list::-webkit-scrollbar {
        display: none;
        width: 0;
        height: 0;
    }

    /* Default / Desktop Grid Styling */
    #custom_tables_grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }

    /* Table Grid Button Styling */
    .custom-table-btn {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 6px 8px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .custom-table-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .custom-table-btn:active {
        transform: scale(0.95);
    }
    .custom-table-btn.active-table {
        background-color: #0f172a;
        color: white;
        border-color: #0f172a;
    }
    .custom-table-btn .table-name {
        font-weight: 800;
        font-size: 15px;
        line-height: 1.25;
        margin-bottom: 2px;
        text-align: center;
        width: 100%;
        word-break: break-word;
    }
    .custom-table-btn .table-status {
        font-size: 11px;
        font-weight: 500;
        opacity: 0.7;
        text-align: center;
        width: 100%;
    }
    
    /* Service List Active Styling */
    .custom-service-btn.active-service {
        background-color: #0f172a !important;
        color: white !important;
        border-color: #0f172a !important;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.2) !important;
    }

    /* Medium & Mobile Devices (< 992px) */
    @media (max-width: 991px) {
        .restaurant-sidebar-card {
            padding: 10px !important;
            gap: 8px !important;
        }
        .restaurant-tables-container {
            min-height: auto !important;
        }
        .restaurant-section-title {
            font-size: 15px !important;
            margin-bottom: 6px !important;
        }
        #custom_service_list {
            margin-bottom: 6px !important;
        }
        .custom-service-btn {
            padding: 4px 12px !important;
            font-size: 12px !important;
        }
        #custom_tables_grid {
            grid-template-columns: repeat(auto-fill, minmax(85px, 1fr)) !important;
            gap: 8px !important;
            max-height: 150px !important;
            overflow-y: auto !important;
        }
        .custom-table-btn {
            aspect-ratio: auto !important;
            min-height: 52px !important;
            padding: 5px 6px !important;
            border-radius: 10px !important;
        }
        .custom-table-btn .table-name {
            font-size: 13px !important;
            font-weight: 700 !important;
            margin-bottom: 2px !important;
        }
        .custom-table-btn .table-status {
            font-size: 10px !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- DRAG / SWIPE TO SCROLL FOR SERVICE LIST ---
        document.querySelectorAll('#custom_service_list').forEach(slider => {
            let isDown = false;
            let startX;
            let scrollLeft;
            let hasMoved = false;

            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                hasMoved = false;
                slider.classList.add('active-dragging');
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });

            slider.addEventListener('mouseleave', () => {
                isDown = false;
                slider.classList.remove('active-dragging');
            });

            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('active-dragging');
            });

            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 1.5;
                if (Math.abs(walk) > 4) {
                    hasMoved = true;
                }
                slider.scrollLeft = scrollLeft - walk;
            });

            // Prevent accidental button toggle when dragging/swiping
            slider.querySelectorAll('.custom-service-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    if (hasMoved) {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                        hasMoved = false;
                    }
                }, true);
            });

            // Mouse wheel horizontal scroll
            slider.addEventListener('wheel', (e) => {
                if (e.deltaY !== 0) {
                    e.preventDefault();
                    slider.scrollLeft += e.deltaY;
                }
            }, { passive: false });
        });

        // --- TYPE OF SERVICE LOGIC ---
        const serviceBtns = document.querySelectorAll('.custom-service-btn');
        const hiddenServiceSelect = document.getElementById('types_of_service_id');
        if (hiddenServiceSelect) {
            // Hide the native dropdown container
            const container = hiddenServiceSelect.closest('div.col-md-4, div.col-sm-6');
            if(container) container.style.display = 'none';

            // Initial Sync if already selected
            const currentVal = hiddenServiceSelect.value;
            if (currentVal && document.querySelector('.custom-service-btn[data-val="'+currentVal+'"]')) {
                document.querySelector('.custom-service-btn[data-val="'+currentVal+'"]').classList.add('active-service');
            }

            // Sync when hidden select changes
            $(hiddenServiceSelect).on('change', function() {
                const val = $(this).val();
                serviceBtns.forEach(btn => {
                    if (val && btn.getAttribute('data-val') === val) {
                        btn.classList.add('active-service');
                    } else {
                        btn.classList.remove('active-service');
                    }
                });
            });
        }

        serviceBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const val = this.getAttribute('data-val');
                const isAlreadyActive = this.classList.contains('active-service');
                
                if (isAlreadyActive) {
                    // Toggle / Unselect
                    this.classList.remove('active-service');
                    if (hiddenServiceSelect) {
                        hiddenServiceSelect.value = '';
                        $(hiddenServiceSelect).trigger('change');
                    }
                } else {
                    // Select
                    serviceBtns.forEach(b => b.classList.remove('active-service'));
                    this.classList.add('active-service');
                    if (hiddenServiceSelect) {
                        hiddenServiceSelect.value = val;
                        $(hiddenServiceSelect).trigger('change');
                    }
                }
            });
        });

        // --- TABLES LOGIC ---
        // Tables are loaded dynamically via AJAX into #restaurant_module_span -> select#res_table_id
        let lastTableOptions = "";
        const observer = new MutationObserver(function(mutations) {
            const tableSelect = document.querySelector('select[name="res_table_id"]');
            if (tableSelect) {
                const currentOptions = tableSelect.innerHTML;
                // Only rebuild if the actual options changed to prevent infinite loops
                if (currentOptions !== lastTableOptions && tableSelect.options.length > 0) {
                    lastTableOptions = currentOptions;
                    buildCustomTableGrid(tableSelect);
                }
            }
        });
        
        observer.observe(document.body, { childList: true, subtree: true });

        function buildCustomTableGrid(selectElement) {
            const grid = document.getElementById('custom_tables_grid');
            if (!grid) return;

            // Hide native container
            const container = selectElement.closest('#table_dropdown_group') || selectElement.closest('[class*="col-"]') || selectElement.closest('.form-group');
            if (container) container.style.display = 'none';

            grid.innerHTML = ''; // Clear loading text

            const options = selectElement.querySelectorAll('option');
            let hasTables = false;

            options.forEach(opt => {
                if (opt.value !== '') {
                    hasTables = true;
                    const btn = document.createElement('div');
                    btn.className = 'custom-table-btn';
                    btn.setAttribute('data-table-id', opt.value);
                    if (opt.selected) {
                        btn.classList.add('active-table');
                    }
                    
                    // Simple text splitting to mimic the design (Name vs Number)
                    const text = opt.innerText.trim();
                    btn.innerHTML = `
                        <div class="table-name">${text}</div>
                        <div class="table-status">Free</div>
                    `;

                    btn.addEventListener('click', function() {
                        const isAlreadyActive = btn.classList.contains('active-table');
                        if (isAlreadyActive) {
                            // Toggle / Unselect
                            btn.classList.remove('active-table');
                            selectElement.value = '';
                            $(selectElement).trigger('change');
                        } else {
                            // Select
                            grid.querySelectorAll('.custom-table-btn').forEach(b => b.classList.remove('active-table'));
                            btn.classList.add('active-table');
                            selectElement.value = opt.value;
                            $(selectElement).trigger('change');
                        }
                    });

                    grid.appendChild(btn);
                }
            });

            if (!hasTables) {
                grid.innerHTML = '<div class="tw-text-slate-400 tw-text-sm" style="grid-column: 1 / -1;">No tables available</div>';
            }

            // Sync table grid when native select changes externally
            $(selectElement).off('change.syncSidebar').on('change.syncSidebar', function() {
                const selectedVal = $(this).val();
                grid.querySelectorAll('.custom-table-btn').forEach(b => {
                    if (selectedVal && b.getAttribute('data-table-id') === selectedVal) {
                        b.classList.add('active-table');
                    } else {
                        b.classList.remove('active-table');
                    }
                });
            });
        }
        
        // Initial build just in case it's already there
        const initialTableSelect = document.getElementById('res_table_id');
        if (initialTableSelect) {
            buildCustomTableGrid(initialTableSelect);
        }

        // --- RESET ON FORM RESET / SALE CREATION ---
        function resetRestaurantSidebar() {
            // Reset Service buttons
            serviceBtns.forEach(b => b.classList.remove('active-service'));
            if (hiddenServiceSelect && hiddenServiceSelect.value !== '') {
                hiddenServiceSelect.value = '';
                $(hiddenServiceSelect).trigger('change');
            }
            // Reset Table buttons
            const grid = document.getElementById('custom_tables_grid');
            if (grid) {
                grid.querySelectorAll('.custom-table-btn').forEach(b => b.classList.remove('active-table'));
            }
            const tableSelect = document.querySelector('select[name="res_table_id"]');
            if (tableSelect && tableSelect.value !== '') {
                tableSelect.value = '';
                $(tableSelect).trigger('change');
            }
        }

        $(document).on('sell_form_reset', function() {
            resetRestaurantSidebar();
        });

        $(document).on('reset', '#add_pos_sell_form, #edit_pos_sell_form', function() {
            setTimeout(resetRestaurantSidebar, 50);
        });
    });
</script>
