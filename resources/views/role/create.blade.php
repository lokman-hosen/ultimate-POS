@extends('layouts.app')
@section('title', __('role.add_role'))

@section('content')
<style>
/* Scoped styles for Role Create Page */
.content-wrapper { background-color: #f8fafc !important; }
.content { padding: 0 !important; }

.role-page-wrap {
    background-color: #f8fafc;
    min-height: 100vh;
    padding: 24px 32px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color: #1e293b;
}
.role-main-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    align-items: start;
    width: 100%;
}
@media (min-width: 1024px) {
    .role-main-grid {
        grid-template-columns: minmax(0, 3fr) minmax(0, 1fr);
    }
}
.role-white-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
}
.role-badge-number {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: #2563eb;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    flex-shrink: 0;
}
.permissions-layout {
    display: grid;
    grid-template-columns: 1fr;
    border-top: 1px solid #f1f5f9;
}
@media (min-width: 768px) {
    .permissions-layout {
        grid-template-columns: 210px minmax(0, 1fr);
    }
}
.permissions-tab-sidebar {
    border-right: 1px solid #f1f5f9;
    padding: 16px 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    max-height: 720px;
    overflow-y: auto;
}
.perm-tab-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    cursor: pointer;
    transition: all 0.15s ease;
}
.perm-tab-btn:hover {
    background-color: #f8fafc;
    color: #1e293b;
}
.perm-tab-btn.active {
    background-color: #eff6ff;
    color: #2563eb;
    font-weight: 600;
}
.perm-tab-btn svg {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}
.permissions-content-scroll {
    padding: 24px;
    background-color: #fafbfc;
    max-height: 720px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
    border-bottom-right-radius: 16px;
}
.perm-cat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px 24px;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02);
}
.perm-cat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}
.perm-cat-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.perm-cat-icon svg {
    width: 20px;
    height: 20px;
}
.perm-checkbox-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 14px 24px;
}
@media (min-width: 640px) {
    .perm-checkbox-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
.perm-item-label {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    cursor: pointer !important;
    font-size: 14px !important;
    color: #334155 !important;
    user-select: none !important;
    line-height: 1.4 !important;
    transition: color 0.15s ease !important;
    margin: 0 !important;
    padding: 2px 0 !important;
}
.perm-item-label:hover {
    color: #0f172a !important;
}
.perm-item-label span {
    display: inline-block !important;
    color: inherit !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
}
.perm-checkbox-input {
    width: 17px !important;
    height: 17px !important;
    margin: 0 !important;
    padding: 0 !important;
    border-radius: 4px !important;
    border: 1.5px solid #94a3b8 !important;
    cursor: pointer !important;
    accent-color: #2563eb !important;
    flex-shrink: 0 !important;
    display: inline-block !important;
    position: static !important;
    opacity: 1 !important;
    visibility: visible !important;
}
input[type="radio"].perm-checkbox-input {
    border-radius: 50% !important;
}
.role-quick-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #ffffff;
    color: #334155;
    font-size: 14px;
    font-weight: 500;
    text-align: left;
    cursor: pointer;
    transition: all 0.15s ease;
}
.role-quick-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}
.role-quick-btn svg {
    width: 18px;
    height: 18px;
    color: #64748b;
    flex-shrink: 0;
}
.role-input-field {
    width: 100%;
    background: #ffffff;
    padding: 10px 14px 10px 42px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    color: #0f172a;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.role-input-field:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}
.preset-dropdown {
    position: relative;
    display: inline-block;
    
}
.preset-dropdown-menu {
    position: absolute;
    right: 0;
    top: 110%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    min-width: 280px;
    padding: 6px;
    z-index: 50;
    display: none;
    width: 100%;
}
.preset-dropdown-menu.show {
    display: block;
}
.preset-item {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    color: #334155;
    background: transparent;
    border: none;
    text-align: left;
    cursor: pointer;
    transition: background 0.15s ease;
}
.preset-item:hover {
    background: #f1f5f9;
    color: #2563eb;
}
</style>

{!! Form::open(['url' => action([\App\Http\Controllers\RoleController::class, 'store']), 'method' => 'post', 'id' => 'role_add_form' ]) !!}

<div class="role-page-wrap">
    <!-- Top Header -->
    <div class="tw-flex tw-justify-between tw-items-start tw-mb-8">
        <div>
            <div class="tw-text-xs tw-text-gray-500 tw-mb-2 tw-font-medium">
                <a href="#" class="hover:tw-text-blue-600">{{ __('user.user_management') }}</a> &gt; 
                <a href="{{ action([\App\Http\Controllers\RoleController::class, 'index']) }}" class="hover:tw-text-blue-600">{{ __('user.roles') }}</a> &gt; 
                <span class="tw-text-gray-700 tw-font-semibold">{{ __('role.add_role') }}</span>
            </div>
            <h1 class="tw-text-2xl md:tw-text-3xl tw-font-bold tw-text-gray-900 tw-mb-1">{{ __('role.add_role') }}</h1>
            <p class="tw-text-sm tw-text-gray-500">{{ __('lang_v1.create_role_instruction') }}</p>
        </div>
        <div class="tw-flex tw-gap-3">
            <a href="{{ action([\App\Http\Controllers\RoleController::class, 'index']) }}" class="tw-px-5 tw-py-2.5 tw-bg-white tw-border tw-border-gray-300 tw-rounded-xl tw-text-gray-700 tw-text-sm tw-font-medium hover:tw-bg-gray-50 tw-transition-colors">{{ __('messages.cancel') }}</a>
            <button type="submit" class="tw-px-6 tw-py-2.5 tw-bg-blue-600 tw-text-white tw-rounded-xl tw-text-sm tw-font-semibold hover:tw-bg-blue-700 tw-transition-colors tw-shadow-sm">{{ __('lang_v1.save_role') }}</button>
        </div>
    </div>

    <!-- Main Two-Column Layout -->
    <div class="role-main-grid">
        <!-- Left Side: Role Info & Permissions (75% width) -->
        <div class="tw-flex tw-flex-col tw-gap-6">
            
            <!-- Step 1: Role Information Card -->
            <div class="role-white-card tw-p-6">
                <div class="tw-flex tw-items-center tw-gap-4 tw-mb-6">
                    <div class="role-badge-number">1</div>
                    <div>
                        <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">{{ __('lang_v1.role_information') }}</h2>
                        <p class="tw-text-xs md:tw-text-sm tw-text-gray-500">{{ __('lang_v1.role_info_sub') }}</p>
                    </div>
                </div>
                
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-5 md:tw-pl-13">
                    <div>
                        <label class="tw-block tw-text-sm tw-font-semibold tw-text-gray-800 tw-mb-2">{{ __('user.role_name') }} <span class="tw-text-red-500">*</span></label>
                        <div class="tw-relative">
                            <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-pl-3.5 tw-flex tw-items-center tw-pointer-events-none">
                                <svg class="tw-w-5 tw-h-5 tw-text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </div>
                            {!! Form::text('name', null, ['class' => 'role-input-field', 'required', 'placeholder' => __('lang_v1.role_name_placeholder')]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Permissions Card -->
            <div class="role-white-card">
                <div class="tw-p-6 tw-flex tw-justify-between tw-items-center">
                    <div class="tw-flex tw-items-center tw-gap-4">
                        <div class="role-badge-number">2</div>
                        <div>
                            <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">{{ __('lang_v1.permissions') }}</h2>
                            <p class="tw-text-xs md:tw-text-sm tw-text-gray-500">{{ __('lang_v1.permissions_help_text') }}</p>
                        </div>
                    </div>

                    <!-- Use Preset Dropdown -->
                    <div class="preset-dropdown">
                        <button type="button" id="preset-dropdown-btn" class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-blue-50 tw-text-blue-700 tw-border tw-border-blue-200 tw-rounded-xl tw-text-xs md:tw-text-sm tw-font-semibold hover:tw-bg-blue-100 tw-transition-colors">
                            <svg class="tw-w-4 tw-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            <span>{{ __('lang_v1.use_preset') }}</span>
                            <svg class="tw-w-3.5 tw-h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        <div class="preset-dropdown-menu" id="preset-dropdown-menu">
                            <button type="button" class="preset-item" data-preset="cashier">{{ __('lang_v1.cashier') }}</button>
                            <button type="button" class="preset-item" data-preset="waiter">{{ __('lang_v1.waiter') }}</button>
                            <button type="button" class="preset-item" data-preset="manager">{{ __('lang_v1.store_manager') }}</button>
                            <button type="button" class="preset-item" data-preset="all">{{ __('lang_v1.full_access_all') }}</button>
                            <button type="button" class="preset-item" data-preset="clear">{{ __('lang_v1.reset_clear') }}</button>
                        </div>
                    </div>
                </div>
                
                <!-- Inner Sidebar Tabs + Content -->
                <div class="permissions-layout">
                    <!-- Left Side Vertical Tabs -->
                    <div class="permissions-tab-sidebar" id="permissions-tabs">
                        <!-- JS generated tab buttons -->
                    </div>
                    
                    <!-- Right Side Content Panes -->
                    <div class="permissions-content-scroll" id="permissions-content">
                        <!-- JS generated category cards -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Summary & Quick Actions (25% width) -->
        <div class="tw-flex tw-flex-col tw-gap-6 tw-sticky tw-top-6">
            <!-- Role Summary Card -->
            <div class="role-white-card tw-p-6">
                <div class="tw-flex tw-items-center tw-gap-3 tw-mb-4">
                    <div class="perm-cat-icon" style="background-color: #eff6ff; color: #2563eb;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <h2 class="tw-text-base tw-font-bold tw-text-gray-900">{{ __('lang_v1.role_summary') }}</h2>
                </div>
                
                <div class="tw-text-gray-900 tw-font-bold tw-mb-3 tw-text-base">
                    <span id="selected-permissions-count" class="tw-text-blue-600 tw-text-xl">0</span> 
                    <span class="tw-text-sm tw-font-medium tw-text-gray-500">{{ __('lang_v1.permissions_selected') }}</span>
                </div>
                
                <div id="summary-info-box" class="tw-bg-blue-50 tw-text-blue-700 tw-p-3.5 tw-rounded-xl tw-text-xs md:tw-text-sm tw-flex tw-items-start tw-gap-2.5 tw-leading-relaxed">
                    <svg class="tw-w-4 tw-h-4 tw-shrink-0 tw-mt-0.5 tw-text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    <span>{{ __('lang_v1.select_permissions_to_view_summary') }}</span>
                </div>
                <div id="summary-selected-groups" class="tw-hidden tw-mt-3 tw-flex tw-flex-wrap tw-gap-1.5">
                    <!-- Dynamic badges -->
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="role-white-card tw-p-6">
                <div class="tw-flex tw-items-center tw-gap-3 tw-mb-4">
                    <div class="perm-cat-icon" style="background-color: #eff6ff; color: #2563eb;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    </div>
                    <h2 class="tw-text-base tw-font-bold tw-text-gray-900">{{ __('lang_v1.quick_actions') }}</h2>
                </div>
                <div class="tw-flex tw-flex-col tw-gap-2.5">
                    <button type="button" id="btn-select-all-global" class="role-quick-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ __('lang_v1.select_all_permissions') }}</span>
                    </button>
                    <button type="button" id="btn-clear-all-global" class="role-quick-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        <span>{{ __('lang_v1.clear_all_permissions') }}</span>
                    </button>                    
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden container for original unstyled permissions -->
    <div class="tw-hidden" id="original-permissions-container">

        @if(in_array('stock_transfers', $enabled_modules))
        <div class="row check_group">
          <div class="col-md-1">
            <h4>@lang( 'lang_v1.others' )</h4>
          </div>
          <div class="col-md-2">
            <div class="checkbox">
                <label>
                  <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                </label>
              </div>
          </div>
          <div class="col-md-9">
              @if(in_array('service_staff', $enabled_modules))
                <div class="col-md-12">
                  <div class="checkbox">
                    <label>
                      {!! Form::checkbox('is_service_staff', 1, false, 
                      [ 'class' => 'input-icheck']); !!} {{ __( 'restaurant.service_staff' ) }}
                    </label>
                    @show_tooltip(__('restaurant.tooltip_service_staff'))
                  </div>
                </div>
              @endif

              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'view_export_buttons', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_export_buttons' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'send_payment_received_notification', false,
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.payment_received' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'send_payment_reminder_notification', false,
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.payment_reminder' ) }}
                  </label>
                </div>
              </div>
          </div>
        </div>
        <hr>
        @endif

        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.user' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'user.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.user.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'user.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.user.create' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'user.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.user.update' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'user.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.user.delete' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'user.roles' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'roles.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_role' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'roles.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.add_role' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'roles.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.edit_role' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'roles.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_role' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.supplier' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="radio-group">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[supplier_view]', 'supplier.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_supplier' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[supplier_view]', 'supplier.view_own', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_supplier' ) }}
              </label>
            </div>
          </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'supplier.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.supplier.create' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'supplier.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.supplier.update' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'supplier.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.supplier.delete' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.customer' ) @show_tooltip(__('lang_v1.customer_permissions_tooltip'))</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[customer_view]', 'customer.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_customer' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[customer_view]', 'customer.view_own', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_customer' ) }}
              </label>
            </div>
            <hr>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[customer_view_by_sell]', 'customer_with_no_sell_one_month', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.customer_with_no_sell_one_month' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[customer_view_by_sell]', 'customer_with_no_sell_three_month', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.customer_with_no_sell_three_month' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[customer_view_by_sell]', 'customer_with_no_sell_six_month', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.customer_with_no_sell_six_month' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[customer_view_by_sell]', 'customer_with_no_sell_one_year', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.customer_with_no_sell_one_year' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[customer_view_by_sell]', 'customer_irrespective_of_sell', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.customer_irrespective_of_sell' ) }}
              </label>
            </div>
            <hr>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'customer.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.customer.create' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'customer.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.customer.update' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'customer.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.customer.delete' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'business.product' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'product.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.product.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'product.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.product.create' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'product.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.product.update' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'product.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.product.delete' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'product.opening_stock', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.add_opening_stock' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_purchase_price', false,['class' => 'input-icheck']); !!}
                {{ __('lang_v1.view_purchase_price') }}
              </label>
              @show_tooltip(__('lang_v1.view_purchase_price_tooltip'))
            </div>
          </div>
        </div>
        </div>
        <hr>
        @if(in_array('purchases', $enabled_modules))
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.purchase' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[purchase_view]', 'purchase.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_purchase' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[purchase_view]', 'view_own_purchase', false,['class' => 'input-icheck']); !!}
                {{ __('lang_v1.view_own_purchase') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'purchase.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.purchase.create' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'purchase.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.purchase.update' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'purchase.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.purchase.delete' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'purchase.payments', false,['class' => 'input-icheck']); !!}
                {{ __('lang_v1.add_purchase_payment') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_purchase_payment', false,['class' => 'input-icheck']); !!}
                {{ __('lang_v1.edit_purchase_payment') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'delete_purchase_payment', false,['class' => 'input-icheck']); !!}
                {{ __('lang_v1.delete_purchase_payment') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'purchase.update_status', false,['class' => 'input-icheck']); !!}
                {{ __('lang_v1.update_status') }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        @endif

        @if(in_array('stock_adjustment', $enabled_modules))
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.stock_adjustment' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[stock_adjustment_view]', 'stock_adjustment.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.stock_adjustment.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[stock_adjustment_view]', 'view_own_stock_adjustment', false,['class' => 'input-icheck']); !!}
                {{ __('role.stock_adjustment.view_own') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'stock_adjustment.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.stock_adjustment.create' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'stock_adjustment.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.stock_adjustment.update' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'stock_adjustment.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.stock_adjustment.delete' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        @endif
        @if(in_array('stock_transfers', $enabled_modules))
          <hr>
          <div class="row check_group">
          <div class="col-md-1">
            <h4>@lang( 'role.stock_transfer' )</h4>
          </div>
          <div class="col-md-2">
            <div class="checkbox">
                <label>
                  <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                </label>
              </div>
          </div>
          <div class="col-md-9">
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::radio('radio_option[stock_transfer_view]', 'stock_transfer.view', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'role.stock_transfer.view' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::radio('radio_option[stock_transfer_view]', 'stock_transfer.view_own', false,['class' => 'input-icheck']); !!}
                  {{ __('role.stock_transfer.view_own') }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'stock_transfer.create', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'role.stock_transfer.create' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'stock_transfer.update', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'role.stock_transfer.update' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'stock_transfer.delete', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'role.stock_transfer.delete' ) }}
                </label>
              </div>
            </div>
          </div>
          </div>
          <hr>
        @endif

        @if(!empty($common_settings['enable_purchase_requisition']))
          <div class="row check_group">
            <div class="col-md-1">
              <h4>@lang( 'lang_v1.purchase_requisition' )</h4>
            </div>
            <div class="col-md-2">
              <div class="checkbox">
                  <label>
                    <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                  </label>
                </div>
            </div>
            <div class="col-md-9">
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::radio('radio_option[purchase_requisition_view]', 'purchase_requisition.view_all', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_purchase_requisition' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::radio('radio_option[purchase_requisition_view]', 'purchase_requisition.view_own', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_purchase_requisition' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase_requisition.create', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.create_purchase_requisition' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase_requisition.delete', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_purchase_requisition' ) }}
                  </label>
                </div>
              </div>

            </div>
          </div>
          <hr>
        @endif

        @if(!empty($common_settings['enable_purchase_order']))
          <div class="row check_group">
            <div class="col-md-1">
              <h4>@lang( 'lang_v1.purchase_order' )</h4>
            </div>
            <div class="col-md-2">
              <div class="checkbox">
                  <label>
                    <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                  </label>
                </div>
            </div>
            <div class="col-md-9">
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::radio('radio_option[purchase_order_view]', 'purchase_order.view_all', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_purchase_order' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::radio('radio_option[purchase_order_view]', 'purchase_order.view_own', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_purchase_order' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase_order.create', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.create_purchase_order' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase_order.update', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.edit_purchase_order' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase_order.delete', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_purchase_order' ) }}
                  </label>
                </div>
              </div>

            </div>
          </div>
          <hr>
        @endif
        <div class="row check_group">
            <div class="col-md-1">
                <h4>@lang( 'sale.pos_sale' )</h4>
            </div>
            <div class="col-md-2">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                    </label>
                </div>
            </div>
            <div class="col-md-9">
            @if(in_array('pos_sale', $enabled_modules))
                <div class="col-md-12">
                    <div class="checkbox">
                      <label>
                        {!! Form::checkbox('permissions[]', 'sell.view', false, 
                        [ 'class' => 'input-icheck']); !!} {{ __( 'role.sell.view' ) }}
                      </label>
                    </div>
                </div>
                <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'sell.create', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'role.sell.create' ) }}
                  </label>
                </div>
              </div>
                @endif
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'sell.update', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'role.sell.update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'sell.delete', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'role.sell.delete' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'edit_product_price_from_pos_screen', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.edit_product_price_from_pos_screen') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'edit_product_discount_from_pos_screen', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.edit_product_discount_from_pos_screen') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'edit_pos_payment', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.add_edit_payment') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'print_invoice', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.print_invoice') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'disable_pay_checkout', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.disable_pay_checkout') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'disable_draft', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.disable_draft') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'disable_express_checkout', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.disable_express_checkout') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'disable_discount', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.disable_discount') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'disable_suspend_sale', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.disable_suspend_sale') }}
                  </label>
                </div>
              </div>

              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'disable_credit_sale', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.disable_credit_sale_button') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'disable_quotation', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.disable_quotation') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'disable_card', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.disable_card') }}
                  </label>
                </div>
              </div>
            </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'sale.sale' ) @show_tooltip(__('lang_v1.sell_permissions_tooltip'))</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          @if(in_array('add_sale', $enabled_modules))
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[sell_view]', 'direct_sell.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_sale' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[sell_view]', 'view_own_sell_only', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_sell_only' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_paid_sells_only', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_paid_sells_only' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_due_sells_only', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_due_sells_only' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_partial_sells_only', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_partially_paid_sells_only' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_overdue_sells_only', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_overdue_sells_only' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'direct_sell.access', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.add_sell' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'direct_sell.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.update_sale' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'direct_sell.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_sell' ) }}
              </label>
            </div>
          </div>
          @endif
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_commission_agent_sell', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_commission_agent_sell' ) }}
              </label>
            </div>
          </div>

          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'sell.payments', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.add_sell_payment') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_sell_payment', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.edit_sell_payment') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'delete_sell_payment', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.delete_sell_payment') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_product_price_from_sale_screen', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.edit_product_price_from_sale_screen') }}
              </label>
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_product_discount_from_sale_screen', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.edit_product_discount_from_sale_screen') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'discount.access', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.discount.access') }}
              </label>
            </div>
          </div>
          @if(in_array('types_of_service', $enabled_modules))
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'access_types_of_service', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.access_types_of_service' ) }}
              </label>
            </div>
          </div>
          @endif
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'access_sell_return', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.access_all_sell_return' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'access_own_sell_return', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.access_own_sell_return' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_invoice_number', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.add_edit_invoice_number' ) }}
              </label>
            </div>
          </div>

        </div>
        </div>
        <hr>
      @if(!empty($pos_settings['enable_sales_order']))
        <div class="row check_group">
          <div class="col-md-1">
            <h4>@lang( 'lang_v1.sales_order' )</h4>
          </div>
          <div class="col-md-2">
            <div class="checkbox">
                <label>
                  <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                </label>
              </div>
          </div>
          <div class="col-md-9">
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::radio('radio_option[so_view]', 'so.view_all', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_so' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::radio('radio_option[so_view]', 'so.view_own', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_so' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'so.create', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.create_so' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'so.update', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.edit_so' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'so.delete', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_so' ) }}
                </label>
              </div>
            </div>

          </div>
        </div>
        <hr>
      @endif
      <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'sale.draft' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
        <div class="checkbox">
          <label>
            {!! Form::radio('radio_option[draft_view]', 'draft.view_all', false, 
            [ 'class' => 'input-icheck']) !!} {{ __( 'lang_v1.view_all_drafts' ) }}
          </label>
        </div>
      </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[draft_view]', 'draft.view_own', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_drafts' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'draft.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.edit_draft' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'draft.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_draft' ) }}
              </label>
            </div>
          </div>

        </div>
      </div>
      <hr>
      <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'lang_v1.quotation' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
        <div class="checkbox">
          <label>
            {!! Form::radio('radio_option[quotation_view]', 'quotation.view_all', false, 
            [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_quotations' ) }}
          </label>
        </div>
      </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[quotation_view]', 'quotation.view_own', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_quotations' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'quotation.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.edit_quotation' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'quotation.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_quotation' ) }}
              </label>
            </div>
          </div>

        </div>
      </div>
      <hr>
      <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'lang_v1.shipments' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
            <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::radio('radio_option[shipping_view]', 'access_shipping', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.access_all_shipments') }}
                  </label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::radio('radio_option[shipping_view]', 'access_own_shipping', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.access_own_shipping') }}
                  </label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'access_pending_shipments_only', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.access_pending_shipments_only') }}
                  </label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'access_commission_agent_shipping', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.access_commission_agent_shipping') }}
                  </label>
                </div>
            </div>
        </div>
    </div>
    <hr>
        <div class="row check_group">
      <div class="col-md-1">
        <h4>@lang( 'cash_register.cash_register' )</h4>
      </div>
      <div class="col-md-2">
        <div class="checkbox">
            <label>
              <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
            </label>
          </div>
      </div>
      <div class="col-md-9">
        <div class="col-md-12">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('permissions[]', 'view_cash_register', false, 
              [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_cash_register' ) }}
            </label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('permissions[]', 'close_cash_register', false, 
              [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.close_cash_register' ) }}
            </label>
          </div>
        </div>
      </div>
      </div>
        <hr>
        
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.brand' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'brand.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.brand.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'brand.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.brand.create' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'brand.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.brand.update' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'brand.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.brand.delete' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.tax_rate' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'tax_rate.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.tax_rate.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'tax_rate.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.tax_rate.create' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'tax_rate.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.tax_rate.update' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'tax_rate.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.tax_rate.delete' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.unit' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'unit.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.unit.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'unit.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.unit.create' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'unit.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.unit.update' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'unit.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.unit.delete' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'category.category' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'category.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.category.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'category.create', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.category.create' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'category.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.category.update' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'category.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.category.delete' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.report' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
            @if(in_array('purchases', $enabled_modules) || in_array('add_sale', $enabled_modules) || in_array('pos_sale', $enabled_modules))
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase_n_sell_report.view', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'role.purchase_n_sell_report.view' ) }}
                  </label>
                </div>
              </div>
            @endif
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'tax_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.tax_report.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'contacts_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.contacts_report.view' ) }}
              </label>
            </div>
          </div>
          @if(in_array('expenses', $enabled_modules))
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'expense_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.expense_report.view' ) }}
              </label>
            </div>
          </div>
          @endif
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'profit_loss_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.profit_loss_report.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'stock_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.stock_report.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'trending_product_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.trending_product_report.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'register_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.register_report.view' ) }}
              </label>
            </div>
          </div>

          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'sales_representative.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.sales_representative.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_product_stock_value', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_product_stock_value' ) }}
              </label>
            </div>
          </div> 

        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.settings' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'business_settings.access', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.business_settings.access' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'barcode_settings.access', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.barcode_settings.access' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'invoice_settings.access', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.invoice_settings.access' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'access_printers', false,['class' => 'input-icheck']); !!}
                {{ __('lang_v1.access_printers') }}
              </label>
            </div>
          </div>
        </div>
        </div>
        @if(in_array('expenses', $enabled_modules))
            <hr>
            <div class="row check_group">
                <div class="col-md-1">
                  <h4>@lang( 'lang_v1.expense' )</h4>
                </div>
                <div class="col-md-2">
                  <div class="checkbox">
                      <label>
                        <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                      </label>
                    </div>
                </div>
                <div class="col-md-9">
                  <div class="col-md-12">
                        <div class="checkbox">
                          <label>
                            {!! Form::radio('radio_option[expense_view]', 'all_expense.access', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.access_all_expense' ) }}
                          </label>
                        </div>
                      </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                      <label>
                        {!! Form::radio('radio_option[expense_view]', 'view_own_expense', false,['class' => 'input-icheck']); !!}
                        {{ __('lang_v1.view_own_expense') }}
                      </label>
                        </div>
                  </div>
                  <div class="col-md-12">
                    <div class="checkbox">
                      <label>
                        {!! Form::checkbox('permissions[]', 'expense.add', false, 
                        [ 'class' => 'input-icheck']); !!} {{ __( 'expense.add_expense' ) }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="checkbox">
                      <label>
                        {!! Form::checkbox('permissions[]', 'expense.edit', false, 
                        [ 'class' => 'input-icheck']); !!} {{ __( 'expense.edit_expense' ) }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="checkbox">
                      <label>
                        {!! Form::checkbox('permissions[]', 'expense.delete', false, 
                        [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_expense' ) }}
                      </label>
                    </div>
                  </div>
                </div>
            </div>
        @endif
        <hr>
        <div class="row check_group">
        <div class="col-md-3">
          <h4>@lang( 'role.dashboard' ) @show_tooltip(__('tooltip.dashboard_permission'))</h4>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'dashboard.data', true, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.dashboard.data' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-3">
          <h4>@lang( 'account.account' )</h4>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'account.access', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.access_accounts' ) }}
              </label>
            </div>
          </div>

          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_account_transaction', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.edit_account_transaction' ) }}
              </label>
            </div>
          </div>

          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'delete_account_transaction', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_account_transaction' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        @if(in_array('booking', $enabled_modules))
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'restaurant.bookings' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[bookings_view]', 'crud_all_bookings', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'restaurant.add_edit_view_all_booking' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[bookings_view]', 'crud_own_bookings', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'restaurant.add_edit_view_own_booking' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        @endif
        <div class="row">
        <div class="col-md-3">
          <h4>@lang( 'lang_v1.access_selling_price_groups' )</h4>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'access_default_selling_price', true, 
                [ 'class' => 'input-icheck']); !!} {{ __('lang_v1.default_selling_price') }}
              </label>
            </div>
          </div>
          @if(count($selling_price_groups) > 0)
          @foreach($selling_price_groups as $selling_price_group)
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('spg_permissions[]', 'selling_price_group.' . $selling_price_group->id, false, 
                [ 'class' => 'input-icheck']); !!} {{ $selling_price_group->name }}
              </label>
            </div>
          </div>
          @endforeach
          @endif
        </div>
        </div>
        @if(in_array('tables', $enabled_modules))
          <div class="row">
            <div class="col-md-3">
              <h4>@lang( 'restaurant.restaurant' )</h4>
            </div>
            <div class="col-md-9">
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'access_tables', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __('lang_v1.access_tables') }}
                  </label>
                </div>
              </div>
            </div>
          </div>
        @endif
        
        @include('role.partials.module_permissions')
        </div><!-- /#original-permissions-container -->
        {!! Form::close() !!}
</div> <!-- end of tw-bg-slate-50 wrapper -->
@endsection

@section('javascript')
<script>
$(document).ready(function() {
    // Destroy any iCheck on original container first so we get clean native inputs & text
    if ($.fn.iCheck) {
        $('#original-permissions-container input').iCheck('destroy');
    }

    var tabsSidebar = $('#permissions-tabs');
    var tabContent = $('#permissions-content');
    
    // Category metadata configuration for exact mockup styling
    var categoryMeta = {
        'general': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
            subtitle: @json(__('lang_v1.perm_desc_general')),
            colorBg: '#eff6ff',
            colorFg: '#2563eb'
        },
        'sales (pos)': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
            subtitle: @json(__('lang_v1.perm_desc_sales')),
            colorBg: '#f0fdf4',
            colorFg: '#16a34a'
        },
        'orders': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>',
            subtitle: @json(__('lang_v1.perm_desc_orders')),
            colorBg: '#fff7ed',
            colorFg: '#ea580c'
        },
        'products': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>',
            subtitle: @json(__('lang_v1.perm_desc_products')),
            colorBg: '#eef2ff',
            colorFg: '#4f46e5'
        },
        'purchases': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>',
            subtitle: @json(__('lang_v1.perm_desc_purchases')),
            colorBg: '#faf5ff',
            colorFg: '#9333ea'
        },
        'stock': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>',
            subtitle: @json(__('lang_v1.perm_desc_stock')),
            colorBg: '#fefce8',
            colorFg: '#ca8a04'
        },
        'customers': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
            subtitle: @json(__('lang_v1.perm_desc_customers')),
            colorBg: '#ecfeff',
            colorFg: '#0891b2'
        },
        'suppliers': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>',
            subtitle: @json(__('lang_v1.perm_desc_suppliers')),
            colorBg: '#ecfdf5',
            colorFg: '#059669'
        },
        'expenses': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>',
            subtitle: @json(__('lang_v1.perm_desc_expenses')),
            colorBg: '#fff1f2',
            colorFg: '#e11d48'
        },
        'bookings': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
            subtitle: @json(__('lang_v1.perm_desc_bookings')),
            colorBg: '#f0f9ff',
            colorFg: '#0284c7'
        },
        'reports': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>',
            subtitle: @json(__('lang_v1.perm_desc_reports')),
            colorBg: '#f5f3ff',
            colorFg: '#7c3aed'
        },
        'settings': {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>',
            subtitle: @json(__('lang_v1.perm_desc_settings')),
            colorBg: '#f8fafc',
            colorFg: '#475569'
        }
    };

    function getMetaForTitle(title) {
        var lower = title.toLowerCase();
        for (var key in categoryMeta) {
            if (lower.indexOf(key) !== -1) {
                return categoryMeta[key];
            }
        }
        if (lower.indexOf('user') !== -1 || lower.indexOf('role') !== -1 || lower.indexOf('other') !== -1 || lower.indexOf('dashboard') !== -1) {
            return categoryMeta['general'];
        }
        if (lower.indexOf('pos') !== -1 || lower.indexOf('sale') !== -1) {
            return categoryMeta['sales (pos)'];
        }
        if (lower.indexOf('table') !== -1 || lower.indexOf('restaurant') !== -1) {
            return categoryMeta['orders'];
        }
        if (lower.indexOf('brand') !== -1 || lower.indexOf('unit') !== -1 || lower.indexOf('tax') !== -1 || lower.indexOf('category') !== -1 || lower.indexOf('account') !== -1 || lower.indexOf('business') !== -1 || lower.indexOf('printer') !== -1) {
            return categoryMeta['settings'];
        }
        return {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
            subtitle: @json(__('lang_v1.perm_desc_modules')),
            colorBg: '#f1f5f9',
            colorFg: '#475569'
        };
    }

    var validGroups = 0;

    $('#original-permissions-container .check_group, #original-permissions-container .row:has(.checkbox)').each(function(index) {
        var $group = $(this);
        var titleElem = $group.find('h4');
        var rawTitle = titleElem.length ? titleElem.text().trim() : 'Settings ' + (index + 1);
        
        // Clean title
        var title = rawTitle.replace(/\s+/g, ' ');
        var tabId = 'perm-card-' + index;
        
        // Find inputs that aren't the group select all
        var inputs = $group.find('input[type="checkbox"], input[type="radio"]').not('.check_all');
        if (inputs.length === 0) return;
        
        var isFirst = validGroups === 0;
        validGroups++;
        
        var meta = getMetaForTitle(title);
        
        // 1. Create tab button in the left sidebar of Permissions card
        var activeClass = isFirst ? 'active' : '';
        var tabBtn = $('<button type="button" class="perm-tab-btn ' + activeClass + '" data-target="#' + tabId + '"></button>');
        tabBtn.append(meta.icon).append('<span>' + title + '</span>');
        tabsSidebar.append(tabBtn);
        
        // 2. Create category card in the right scrollable content pane
        var catCard = $('<div id="' + tabId + '" class="perm-cat-card" data-category="' + title + '"></div>');
        
        // Build category header
        var catHeader = $('<div class="perm-cat-header"></div>');
        var catHeaderLeft = $('<div class="tw-flex tw-items-center tw-gap-3"></div>');
        var iconBadge = $('<div class="perm-cat-icon" style="background-color:' + meta.colorBg + '; color:' + meta.colorFg + ';">' + meta.icon + '</div>');
        var titleWrap = $('<div><h3 class="tw-text-base tw-font-bold tw-text-gray-900 tw-leading-tight">' + title + '</h3><p class="tw-text-xs tw-text-gray-500 tw-mt-0.5">' + meta.subtitle + '</p></div>');
        catHeaderLeft.append(iconBadge).append(titleWrap);
        
        // Check if there is an original "Select all" checkbox
        var $originalSelectAll = $group.find('.check_all').first();
        var selectAllWrapper = $('<label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-font-medium tw-text-gray-600 hover:tw-text-gray-900"></label>');
        var groupCheckAllInput;
        
        if ($originalSelectAll.length) {
            $originalSelectAll.detach();
            $originalSelectAll.removeClass('input-icheck check_all').removeAttr('style').addClass('group_check_all perm-checkbox-input');
            groupCheckAllInput = $originalSelectAll;
        } else {
            groupCheckAllInput = $('<input type="checkbox" class="group_check_all perm-checkbox-input">');
        }
        selectAllWrapper.append(groupCheckAllInput).append('<span>' + @json(__('role.select_all')) + '</span>');
        catHeader.append(catHeaderLeft).append(selectAllWrapper);
        catCard.append(catHeader);
        
        // Build 2-column checkbox grid
        var grid = $('<div class="perm-checkbox-grid"></div>');
        
        $group.find('.checkbox').each(function() {
            var $checkboxDiv = $(this);
            var $originalLabel = $checkboxDiv.find('label');
            if ($originalLabel.length === 0) {
                $originalLabel = $checkboxDiv;
            }
            
            // Ignore if this is group_check_all
            if ($checkboxDiv.find('.check_all, .group_check_all').length > 0) return;
            
            // Find input
            var $input = $checkboxDiv.find('input[type="checkbox"], input[type="radio"]').first();
            if ($input.length === 0) return;
            
            // Clone to extract clean text without mutating input
            var $clone = $originalLabel.clone();
            $clone.find('input, ins, svg, i, .tooltip, .iCheck-helper').remove();
            var labelText = $clone.text().replace(/Tooltip/gi, '').replace(/\s+/g, ' ').trim();
            
            // Fallback if text wasn't in label
            if (!labelText) {
                var $pClone = $checkboxDiv.clone();
                $pClone.find('input, ins, svg, i, .tooltip, .iCheck-helper').remove();
                labelText = $pClone.text().replace(/Tooltip/gi, '').replace(/\s+/g, ' ').trim();
            }
            
            // Fallback from value/name
            if (!labelText) {
                var val = $input.val() || $input.attr('name') || 'Permission';
                labelText = val.replace(/_/g, ' ').replace(/\./g, ' ');
                labelText = labelText.charAt(0).toUpperCase() + labelText.slice(1);
            }
            
            // Detach and clean input
            $input.detach();
            $input.removeClass('input-icheck').removeAttr('style').addClass('perm-checkbox-input');
            
            var newLabel = $('<label class="perm-item-label"></label>');
            var textSpan = $('<span></span>').text(labelText);
            
            newLabel.append($input).append(textSpan);
            grid.append(newLabel);
        });
        
        catCard.append(grid);
        tabContent.append(catCard);
    });

    var isManualScroll = false;

    // Tab button click -> Smooth scroll to category card
    $(document).on('click', '.perm-tab-btn', function(e) {
        e.preventDefault();
        $('.perm-tab-btn').removeClass('active');
        $(this).addClass('active');
        
        var targetId = $(this).data('target');
        var targetElem = $(targetId);
        var container = $('#permissions-content');
        
        if (targetElem.length && container.length) {
            isManualScroll = true;
            var currentScroll = container.scrollTop();
            var targetOffset = targetElem.offset().top;
            var containerOffset = container.offset().top;
            var targetScrollTop = currentScroll + (targetOffset - containerOffset) - 10;
            if (targetScrollTop < 0) targetScrollTop = 0;
            
            container.stop().animate({ scrollTop: targetScrollTop }, 250, function() {
                setTimeout(function() {
                    isManualScroll = false;
                }, 50);
            });
        }
    });

    // ScrollSpy: highlight active sidebar tab as content is scrolled
    $('#permissions-content').on('scroll', function() {
        if (isManualScroll) return;
        
        var container = $(this);
        var containerOffsetTop = container.offset().top;
        var activeTargetId = null;
        
        $('.perm-cat-card').each(function() {
            var $card = $(this);
            var cardRelativeTop = $card.offset().top - containerOffsetTop;
            if (cardRelativeTop <= 50) {
                activeTargetId = '#' + $card.attr('id');
            }
        });
        
        if (activeTargetId) {
            $('.perm-tab-btn').removeClass('active');
            $('.perm-tab-btn[data-target="' + activeTargetId + '"]').addClass('active');
        }
    });

    // Update Role Summary
    function updateRoleSummary() {
        var totalChecked = 0;
        var groupsMap = {};
        
        $('.perm-cat-card').each(function() {
            var $card = $(this);
            var catTitle = $card.data('category');
            var checkedInCard = $card.find('input[type="checkbox"], input[type="radio"]').not('.group_check_all').filter(':checked').length;
            
            if (checkedInCard > 0) {
                groupsMap[catTitle] = checkedInCard;
                totalChecked += checkedInCard;
            }
            
            // Sync category 'Select all' checkbox state
            var totalInCard = $card.find('input[type="checkbox"], input[type="radio"]').not('.group_check_all').length;
            $card.find('.group_check_all').prop('checked', totalInCard > 0 && checkedInCard === totalInCard);
        });
        
        $('#selected-permissions-count').text(totalChecked);
        
        var $infoBox = $('#summary-info-box');
        var $badgesBox = $('#summary-selected-groups');
        
        if (totalChecked === 0) {
            $infoBox.removeClass('tw-hidden');
            $badgesBox.addClass('tw-hidden').empty();
        } else {
            $infoBox.addClass('tw-hidden');
            $badgesBox.removeClass('tw-hidden').empty();
            
            for (var group in groupsMap) {
                var badge = $('<span class="tw-inline-flex tw-items-center tw-px-2 tw-py-1 tw-rounded-lg tw-text-xs tw-font-semibold tw-bg-blue-50 tw-text-blue-700 tw-border tw-border-blue-100"></span>')
                    .text(group + ' (' + groupsMap[group] + ')');
                $badgesBox.append(badge);
            }
        }
    }

    $(document).on('change', '#permissions-content input[type="checkbox"], #permissions-content input[type="radio"]', function() {
        if (!$(this).hasClass('group_check_all')) {
            updateRoleSummary();
        }
    });

    // Category "Select all" toggle
    $(document).on('change', '.group_check_all', function() {
        var isChecked = $(this).is(':checked');
        var $card = $(this).closest('.perm-cat-card');
        $card.find('input[type="checkbox"]').not('.group_check_all').prop('checked', isChecked);
        updateRoleSummary();
    });

    // Global "Select all permissions" button
    $('#btn-select-all-global').click(function(e) {
        e.preventDefault();
        $('#permissions-content input[type="checkbox"]').prop('checked', true);
        updateRoleSummary();
    });

    // Global "Clear all permissions" button
    $('#btn-clear-all-global').click(function(e) {
        e.preventDefault();
        $('#permissions-content input[type="checkbox"], #permissions-content input[type="radio"]').prop('checked', false);
        updateRoleSummary();
    });

    // Copy / Preset Trigger
    $('#btn-copy-role-preset').click(function(e) {
        e.preventDefault();
        $('#preset-dropdown-btn').trigger('click');
    });

    // Preset dropdown toggle
    $('#preset-dropdown-btn').click(function(e) {
        e.stopPropagation();
        $('#preset-dropdown-menu').toggleClass('show');
    });

    $(document).click(function() {
        $('#preset-dropdown-menu').removeClass('show');
    });

    // Apply Presets
    $('.preset-item').click(function(e) {
        e.preventDefault();
        var preset = $(this).data('preset');
        $('#preset-dropdown-menu').removeClass('show');
        
        // First uncheck all
        $('#permissions-content input[type="checkbox"], #permissions-content input[type="radio"]').prop('checked', false);
        
        if (preset === 'all') {
            $('#permissions-content input[type="checkbox"]').prop('checked', true);
        } else if (preset === 'cashier') {
            // Check POS, Sales, and Orders
            $('.perm-cat-card').each(function() {
                var title = ($(this).data('category') || '').toLowerCase();
                if (title.indexOf('sale') !== -1 || title.indexOf('pos') !== -1 || title.indexOf('order') !== -1 || title.indexOf('dashboard') !== -1) {
                    $(this).find('input[type="checkbox"]').prop('checked', true);
                }
            });
        } else if (preset === 'waiter') {
            // Check Restaurant, Tables, Bookings
            $('.perm-cat-card').each(function() {
                var title = ($(this).data('category') || '').toLowerCase();
                if (title.indexOf('restaurant') !== -1 || title.indexOf('table') !== -1 || title.indexOf('booking') !== -1 || title.indexOf('order') !== -1) {
                    $(this).find('input[type="checkbox"]').prop('checked', true);
                }
            });
        } else if (preset === 'manager') {
            // Check Sales, Purchases, Stock, Products, Customers, Reports
            $('.perm-cat-card').each(function() {
                var title = ($(this).data('category') || '').toLowerCase();
                if (title.indexOf('user') === -1 && title.indexOf('setting') === -1) {
                    $(this).find('input[type="checkbox"]').prop('checked', true);
                }
            });
        }
        
        updateRoleSummary();
    });

    // Initial summary calculation
    updateRoleSummary();
});
</script>
@endsection