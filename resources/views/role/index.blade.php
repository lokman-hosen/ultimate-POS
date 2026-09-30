@extends('layouts.app')
@section('title', __('user.roles'))

@section('css')
<style>
    /* Roles list icon-only action buttons matching Users list */
    #roles_table .role-actions {
        display: inline-flex;
        gap: 6px;
        white-space: nowrap;
    }
    #roles_table .role-action-btn {
        width: 28px;
        height: 28px;
        min-height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        color: var(--act-color);
        border-color: var(--act-color);
        background: #fff;
    }
    #roles_table .role-action-btn:hover,
    #roles_table .role-action-btn:focus {
        color: var(--act-color);
        border-color: var(--act-color);
        background: var(--act-tint);
    }
    #roles_table .role-action-btn--edit   { --act-color: #2563eb; --act-tint: #dbeafe; }
    #roles_table .role-action-btn--delete { --act-color: #dc2626; --act-tint: #fee2e2; }

    #roles_table th:first-child,
    #roles_table td:first-child {
        width: 220px;
        max-width: 260px;
    }
    #roles_table th:last-child,
    #roles_table td:last-child {
        text-align: left;
    }
</style>
@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang( 'user.roles' )
        <small  class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang( 'user.manage_roles' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'user.all_roles' )])
        @can('roles.create')
            @slot('tool')
                <div class="box-tools">
                
                    <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full"
                    href="{{action([\App\Http\Controllers\RoleController::class, 'create'])}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg> @lang('messages.add')
                    </a>
                </div>
            @endslot
        @endcan
        @can('roles.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="roles_table">
                    <thead>
                        <tr>
                            <th style="width: 220px;">@lang( 'user.roles' )</th>
                            <th class="not-export text-left">@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

</section>
<!-- /.content -->
@stop
@section('javascript')
<script type="text/javascript">
    //Roles table
    $(document).ready( function(){
        var roles_table = $('#roles_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader: false,
                    ajax: '/roles',
                    columns: [
                        { data: 'name', name: 'name', width: '220px' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    buttons: [],
                    columnDefs: [ {
                        "targets": 1,
                        "orderable": false,
                        "searchable": false
                    } ],
                    preDrawCallback: function() {
                        $('#roles_table [data-toggle="tooltip"]').tooltip('destroy');
                    },
                    drawCallback: function() {
                        $('#roles_table [data-toggle="tooltip"]').tooltip({container: 'body', trigger: 'hover'});
                    }
                });
        $(document).on('click', '.delete_role_button', function(e){
            e.preventDefault();
            swal({
              title: LANG.sure,
              text: LANG.confirm_delete_role,
              icon: "warning",
              buttons: true,
              dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).data('href') || $(this).attr('href');
                    var data = $(this).serialize();

                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        data: data,
                        success: function(result){
                            if(result.success == true){
                                toastr.success(result.msg);
                                roles_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
