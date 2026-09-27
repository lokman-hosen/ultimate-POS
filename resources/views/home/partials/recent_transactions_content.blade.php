<style>
    .recent-transactions-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1.25rem;
        width: 100%;
    }
    @media (min-width: 992px) {
        .recent-transactions-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        }
    }
    .status-tag {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 3px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.2;
        letter-spacing: 0.01em;
        white-space: nowrap;
    }
    .status-tag-green {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .status-tag-orange {
        background-color: #ffedd5;
        color: #c2410c;
        border: 1px solid #fed7aa;
    }
    .status-tag-blue {
        background-color: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .status-tag-purple {
        background-color: #f3e8ff;
        color: #7e22ce;
        border: 1px solid #e9d5ff;
    }
    .status-tag-gray {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .status-tag-red {
        background-color: #ffe4e6;
        color: #be123c;
        border: 1px solid #fecdd3;
    }
</style>

<div class="recent-transactions-grid">
    <!-- 1. Recent Sales Card -->
    @if(auth()->user()->can('sell.view') || auth()->user()->can('direct_sell.view'))
        <div class="tw-transition-all tw-duration-200 tw-bg-white tw-shadow-sm tw-rounded-2xl tw-ring-1 hover:tw-shadow-md hover:tw--translate-y-0.5 tw-ring-gray-200 tw-flex tw-flex-col" style="min-width: 0;">
            <div class="tw-p-5 tw-flex-1 tw-flex tw-flex-col">
                <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                    <div class="tw-flex tw-items-center tw-gap-2.5">
                        <div class="tw-flex tw-items-center tw-justify-center tw-rounded-xl tw-w-9 tw-h-9 tw-bg-blue-50 tw-text-blue-600">
                            <i class="fa fa-shopping-cart tw-text-base"></i>
                        </div>
                        <h3 class="tw-font-bold tw-text-base lg:tw-text-lg tw-text-gray-900 tw-m-0">
                            Recent Sales
                        </h3>
                    </div>
                    <a href="{{ action([\App\Http\Controllers\SellController::class, 'index']) }}" class="tw-text-blue-600 hover:tw-text-blue-800 tw-text-xs tw-font-semibold tw-flex tw-items-center tw-gap-1 tw-transition-colors">
                        View all <i class="fa fa-arrow-right tw-text-[10px]"></i>
                    </a>
                </div>

                <div class="tw-overflow-x-auto tw-flex-1">
                    <table class="tw-w-full tw-border-collapse">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-text-left tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-rounded-l-lg">#</th>
                                <th class="tw-text-left tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500">Time</th>
                                <th class="tw-text-right tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500">Amount</th>
                                <th class="tw-text-right tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-rounded-r-lg">Status</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-50">
                            @forelse($recent_sales ?? [] as $sale)
                                <tr class="hover:tw-bg-slate-50/70 tw-transition-colors">
                                    <td class="tw-py-2.5 tw-px-2 tw-text-xs tw-font-medium tw-text-slate-500">
                                        #{{ $sale->invoice_no }}
                                    </td>
                                    <td class="tw-py-2.5 tw-px-2 tw-text-xs tw-font-medium tw-text-slate-600">
                                        {{ \Carbon\Carbon::parse($sale->transaction_date)->format('H:i') }}
                                    </td>
                                    <td class="tw-py-2.5 tw-px-2 tw-text-xs tw-font-bold tw-text-slate-900 tw-text-right">
                                        <span class="display_currency" data-currency_symbol="true">{{ $sale->final_total }}</span>
                                    </td>
                                    <td class="tw-py-2.5 tw-px-2 tw-text-right">
                                        @php
                                            $sale_status = strtolower($sale->payment_status ?: $sale->status);
                                        @endphp
                                        @if(in_array($sale_status, ['paid', 'completed', 'final']))
                                            <span class="status-tag status-tag-green">
                                                Completed
                                            </span>
                                        @elseif(in_array($sale_status, ['due', 'pending']))
                                            <span class="status-tag status-tag-orange">
                                                Due
                                            </span>
                                        @elseif($sale_status == 'partial')
                                            <span class="status-tag status-tag-blue">
                                                Partial
                                            </span>
                                        @elseif(in_array($sale_status, ['draft', 'quotation']))
                                            <span class="status-tag status-tag-gray">
                                                {{ ucfirst($sale_status) }}
                                            </span>
                                        @elseif(in_array($sale_status, ['ordered', 'suspended']))
                                            <span class="status-tag status-tag-purple">
                                                {{ ucfirst($sale_status) }}
                                            </span>
                                        @else
                                            <span class="status-tag status-tag-gray">
                                                {{ ucfirst($sale_status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="tw-text-center tw-py-6 tw-text-xs tw-text-slate-400">
                                        No recent sales
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. Recent Purchases Card -->
    @if(auth()->user()->can('purchase.view'))
        <div class="tw-transition-all tw-duration-200 tw-bg-white tw-shadow-sm tw-rounded-2xl tw-ring-1 hover:tw-shadow-md hover:tw--translate-y-0.5 tw-ring-gray-200 tw-flex tw-flex-col" style="min-width: 0;">
            <div class="tw-p-5 tw-flex-1 tw-flex tw-flex-col">
                <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                    <div class="tw-flex tw-items-center tw-gap-2.5">
                        <div class="tw-flex tw-items-center tw-justify-center tw-rounded-xl tw-w-9 tw-h-9 tw-bg-sky-50 tw-text-sky-500">
                            <i class="fa fa-truck tw-text-base"></i>
                        </div>
                        <h3 class="tw-font-bold tw-text-base lg:tw-text-lg tw-text-gray-900 tw-m-0">
                            Recent Purchases
                        </h3>
                    </div>
                    <a href="{{ action([\App\Http\Controllers\PurchaseController::class, 'index']) }}" class="tw-text-blue-600 hover:tw-text-blue-800 tw-text-xs tw-font-semibold tw-flex tw-items-center tw-gap-1 tw-transition-colors">
                        View all <i class="fa fa-arrow-right tw-text-[10px]"></i>
                    </a>
                </div>

                <div class="tw-overflow-x-auto tw-flex-1">
                    <table class="tw-w-full tw-border-collapse">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-text-left tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-rounded-l-lg">Date</th>
                                <th class="tw-text-left tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500">Supplier</th>
                                <th class="tw-text-right tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500">Amount</th>
                                <th class="tw-text-right tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-rounded-r-lg">Status</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-50">
                            @forelse($recent_purchases ?? [] as $purchase)
                                <tr class="hover:tw-bg-slate-50/70 tw-transition-colors">
                                    <td class="tw-py-2.5 tw-px-2 tw-text-xs tw-font-medium tw-text-slate-500">
                                        {{ \Carbon\Carbon::parse($purchase->transaction_date)->format('m/d') }}
                                    </td>
                                    <td class="tw-py-2.5 tw-px-2 tw-text-xs tw-font-medium tw-text-slate-700 tw-truncate" style="max-width: 100px;" title="{{ !empty($purchase->contact) ? ($purchase->contact->supplier_business_name ?: $purchase->contact->name) : '-' }}">
                                        {{ !empty($purchase->contact) ? ($purchase->contact->supplier_business_name ?: $purchase->contact->name) : '-' }}
                                    </td>
                                    <td class="tw-py-2.5 tw-px-2 tw-text-xs tw-font-bold tw-text-slate-900 tw-text-right">
                                        <span class="display_currency" data-currency_symbol="true">{{ $purchase->final_total }}</span>
                                    </td>
                                    <td class="tw-py-2.5 tw-px-2 tw-text-right">
                                        @php
                                            $purchase_status = strtolower($purchase->payment_status ?: $purchase->status);
                                        @endphp
                                        @if(in_array($purchase_status, ['paid', 'received', 'completed']))
                                            <span class="status-tag status-tag-green">
                                                Paid
                                            </span>
                                        @elseif(in_array($purchase_status, ['due', 'pending', 'ordered']))
                                            <span class="status-tag status-tag-orange">
                                                Pending
                                            </span>
                                        @elseif($purchase_status == 'partial')
                                            <span class="status-tag status-tag-blue">
                                                Partial
                                            </span>
                                        @elseif($purchase_status == 'draft')
                                            <span class="status-tag status-tag-gray">
                                                Draft
                                            </span>
                                        @else
                                            <span class="status-tag status-tag-purple">
                                                {{ ucfirst($purchase_status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="tw-text-center tw-py-6 tw-text-xs tw-text-slate-400">
                                        No recent purchases
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. Recent Expenses Card -->
    @if(auth()->user()->can('expense.access') || auth()->user()->can('view_own_expense'))
        <div class="tw-transition-all tw-duration-200 tw-bg-white tw-shadow-sm tw-rounded-2xl tw-ring-1 hover:tw-shadow-md hover:tw--translate-y-0.5 tw-ring-gray-200 tw-flex tw-flex-col" style="min-width: 0;">
            <div class="tw-p-5 tw-flex-1 tw-flex tw-flex-col">
                <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                    <div class="tw-flex tw-items-center tw-gap-2.5">
                        <div class="tw-flex tw-items-center tw-justify-center tw-rounded-xl tw-w-9 tw-h-9 tw-bg-rose-50 tw-text-rose-500">
                            <i class="fa fa-file-text-o tw-text-base"></i>
                        </div>
                        <h3 class="tw-font-bold tw-text-base lg:tw-text-lg tw-text-gray-900 tw-m-0">
                            Recent Expenses
                        </h3>
                    </div>
                    <a href="{{ action([\App\Http\Controllers\ExpenseController::class, 'index']) }}" class="tw-text-blue-600 hover:tw-text-blue-800 tw-text-xs tw-font-semibold tw-flex tw-items-center tw-gap-1 tw-transition-colors">
                        View all <i class="fa fa-arrow-right tw-text-[10px]"></i>
                    </a>
                </div>

                <div class="tw-overflow-x-auto tw-flex-1">
                    <table class="tw-w-full tw-border-collapse">
                        <thead>
                            <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                <th class="tw-text-left tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-rounded-l-lg">Date</th>
                                <th class="tw-text-left tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500">Category</th>
                                <th class="tw-text-right tw-py-2 tw-px-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-rounded-r-lg">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-50">
                            @forelse($recent_expenses ?? [] as $expense)
                                <tr class="hover:tw-bg-slate-50/70 tw-transition-colors">
                                    <td class="tw-py-2.5 tw-px-2 tw-text-xs tw-font-medium tw-text-slate-500">
                                        {{ \Carbon\Carbon::parse($expense->transaction_date)->format('m/d') }}
                                    </td>
                                    <td class="tw-py-2.5 tw-px-2 tw-text-xs tw-font-medium tw-text-slate-700 tw-truncate" style="max-width: 120px;" title="{{ $expense->category_name ?? ($expense->ref_no ?: 'Expense') }}">
                                        {{ $expense->category_name ?? ($expense->ref_no ?: 'Expense') }}
                                    </td>
                                    <td class="tw-py-2.5 tw-px-2 tw-text-xs tw-font-bold tw-text-slate-900 tw-text-right">
                                        <span class="display_currency" data-currency_symbol="true">{{ $expense->final_total }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="tw-text-center tw-py-6 tw-text-xs tw-text-slate-400">
                                        No recent expenses
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
