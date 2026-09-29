<div class="tw-shadow-[rgba(17,_17,_26,_0.08)_0px_0px_16px] tw-rounded-2xl tw-bg-white tw-border tw-border-slate-100 tw-p-3 tw-flex tw-flex-col tw-h-full tw-gap-4">
    
    @if(in_array('tables', $enabled_modules))
    <div class="tw-flex-1 tw-flex tw-flex-col tw-min-h-[250px]">
        <h4 class="tw-font-bold tw-text-lg tw-mb-3 tw-text-slate-800">Tables</h4>
        
        <!-- Types of Service / Filter pills in header (horizontal scrollable) -->
        @if(in_array('types_of_service', $enabled_modules) && !empty($types_of_service))
        <div id="custom_service_list" class="tw-flex tw-gap-2 tw-mb-3 tw-overflow-x-auto custom-scroll tw-pb-1">
            @foreach($types_of_service as $key => $value)
                <button type="button" class="custom-service-btn tw-px-4 tw-py-1.5 tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-text-slate-700 tw-text-sm tw-font-semibold tw-whitespace-nowrap tw-shrink-0 tw-cursor-pointer tw-transition-all hover:tw-bg-slate-50 hover:tw-border-slate-300 hover:tw-text-slate-900 active:tw-scale-95" data-val="{{$key}}">
                    {{$value}}
                </button>
            @endforeach
        </div>
        @endif

        <div id="custom_tables_grid" class="tw-gap-3 tw-overflow-y-auto tw-p-1 custom-scroll tw-content-start" style="display: grid; grid-template-columns: repeat(3, 1fr);">
            <!-- Tables will be injected here by JS -->
            <div class="tw-text-slate-400 tw-text-sm" style="grid-column: span 3;">Loading tables...</div>
        </div>
    </div>
    @elseif(in_array('types_of_service', $enabled_modules) && !empty($types_of_service))
    <div class="tw-flex-1 tw-flex tw-flex-col tw-min-h-[200px]">
        <h4 class="tw-font-bold tw-text-lg tw-mb-3 tw-text-slate-800">Type of Service</h4>
        <div id="custom_service_list" class="tw-flex tw-gap-2 tw-mb-3 tw-overflow-x-auto custom-scroll tw-pb-1">
            @foreach($types_of_service as $key => $value)
                <button type="button" class="custom-service-btn tw-px-4 tw-py-1.5 tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-text-slate-700 tw-text-sm tw-font-semibold tw-whitespace-nowrap tw-shrink-0 tw-cursor-pointer tw-transition-all hover:tw-bg-slate-50 hover:tw-border-slate-300 hover:tw-text-slate-900 active:tw-scale-95" data-val="{{$key}}">
                    {{$value}}
                </button>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
    /* Scrollbar styling for the sidebar */
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

    /* Table Grid Button Styling */
    .custom-table-btn {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
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
        font-size: 16px;
        margin-bottom: 2px;
    }
    .custom-table-btn .table-status {
        font-size: 11px;
        font-weight: 500;
        opacity: 0.7;
    }
    
    /* Service List Active Styling */
    .custom-service-btn.active-service {
        background-color: #0f172a !important;
        color: white !important;
        border-color: #0f172a !important;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.2) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- TYPE OF SERVICE LOGIC ---
        const serviceBtns = document.querySelectorAll('.custom-service-btn');
        const hiddenServiceSelect = document.getElementById('types_of_service_id');
        if (hiddenServiceSelect) {
            // Hide the native dropdown container
            const container = hiddenServiceSelect.closest('div.col-md-4, div.col-sm-6');
            if(container) container.style.display = 'none';

            // Initial Sync
            const currentVal = hiddenServiceSelect.value;
            if (currentVal && document.querySelector('.custom-service-btn[data-val="'+currentVal+'"]')) {
                document.querySelector('.custom-service-btn[data-val="'+currentVal+'"]').classList.add('active-service');
            } else if (serviceBtns.length > 0) {
                // If not pre-selected, activate first service
                serviceBtns[0].classList.add('active-service');
                hiddenServiceSelect.value = serviceBtns[0].getAttribute('data-val');
                $(hiddenServiceSelect).trigger('change');
            }
        }

        serviceBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all
                serviceBtns.forEach(b => b.classList.remove('active-service'));
                // Add active class to clicked
                this.classList.add('active-service');
                // Update hidden select and trigger change event
                if (hiddenServiceSelect) {
                    hiddenServiceSelect.value = this.getAttribute('data-val');
                    $(hiddenServiceSelect).trigger('change');
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
                        // Remove active from all
                        document.querySelectorAll('.custom-table-btn').forEach(b => b.classList.remove('active-table'));
                        this.classList.add('active-table');
                        // Update native select
                        selectElement.value = opt.value;
                        $(selectElement).trigger('change');
                    });

                    grid.appendChild(btn);
                }
            });

            if (!hasTables) {
                grid.innerHTML = '<div class="tw-text-slate-400 tw-text-sm tw-col-span-3">No tables available</div>';
            }
        }
        
        // Initial build just in case it's already there
        const initialTableSelect = document.getElementById('res_table_id');
        if (initialTableSelect) {
            buildCustomTableGrid(initialTableSelect);
        }
    });
</script>
