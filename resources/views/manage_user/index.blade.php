@extends('layouts.app')
@section('title', __( 'user.users' ))

@section('css')
<style>
    /* Users list only: role/status pills and icon-only action buttons */
    #users_table .user-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 2px 10px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        line-height: 18px;
        white-space: nowrap;
        color: var(--pill-fg);
        background: var(--pill-bg);
    }
    #users_table .user-pill__dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
    #users_table .user-pill--purple { --pill-fg: #7e22ce; --pill-bg: #f3e8ff; }
    #users_table .user-pill--blue   { --pill-fg: #1d4ed8; --pill-bg: #dbeafe; }
    #users_table .user-pill--green  { --pill-fg: #15803d; --pill-bg: #dcfce7; }
    #users_table .user-pill--orange { --pill-fg: #c2410c; --pill-bg: #ffedd5; }
    #users_table .user-pill--red    { --pill-fg: #b91c1c; --pill-bg: #fee2e2; }
    #users_table .user-pill--indigo { --pill-fg: #4338ca; --pill-bg: #e0e7ff; }
    #users_table .user-pill--teal   { --pill-fg: #0f766e; --pill-bg: #ccfbf1; }
    #users_table .user-pill--pink   { --pill-fg: #be185d; --pill-bg: #fce7f3; }
    #users_table .user-pill--cyan   { --pill-fg: #0e7490; --pill-bg: #cffafe; }
    #users_table .user-pill--amber  { --pill-fg: #b45309; --pill-bg: #fef3c7; }
    #users_table .user-pill--lime   { --pill-fg: #4d7c0f; --pill-bg: #ecfccb; }
    #users_table .user-pill--sky    { --pill-fg: #0369a1; --pill-bg: #e0f2fe; }
    #users_table .user-pill--gray   { --pill-fg: #4b5563; --pill-bg: #f3f4f6; }

    #users_table .user-actions {
        display: inline-flex;
        gap: 6px;
        white-space: nowrap;
    }
    #users_table .user-action-btn {
        width: 28px;
        height: 28px;
        min-height: 28px;
        padding: 0;
        justify-content: center;
        border-radius: 6px;
        color: var(--act-color);
        border-color: var(--act-color);
        background: #fff;
    }
    #users_table .user-action-btn:hover,
    #users_table .user-action-btn:focus {
        color: var(--act-color);
        border-color: var(--act-color);
        background: var(--act-tint);
    }
    #users_table .user-action-btn--edit   { --act-color: #2563eb; --act-tint: #dbeafe; }
    #users_table .user-action-btn--view   { --act-color: #0ea5e9; --act-tint: #e0f2fe; }
    #users_table .user-action-btn--delete { --act-color: #dc2626; --act-tint: #fee2e2; }
</style>
@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang( 'user.users' )
        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang( 'user.manage_users' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'user.all_users' )])
        @can('user.create')
            @slot('tool')
                <div class="box-tools">
                    <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full" href="{{action([\App\Http\Controllers\ManageUserController::class, 'create'])}}">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>                        @lang( 'messages.add' )
                    </a>
                 </div>
            @endslot
        @endcan
        @can('user.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="users_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang( 'business.username' )</th>
                            <th>@lang( 'user.name' )</th>
                            <th>@lang( 'user.role' )</th>
                            <th>@lang( 'business.email' )</th>
                            <th>@lang( 'user.status' )</th>
                            <th class="not-export">@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade user_modal" tabindex="-1" role="dialog"
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
@stop
@section('javascript')
<script type="text/javascript">
    //Roles table
    $(document).ready( function(){
        var users_table = $('#users_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader:false,
                    ajax: '/users',
                    order: [[1, 'asc']],
                    columnDefs: [ {
                        "targets": [0, 6],
                        "orderable": false,
                        "searchable": false
                    } ],
                    "columns":[
                        {"data":"DT_RowIndex", "name":"DT_RowIndex"},
                        {"data":"username"},
                        {"data":"full_name"},
                        {"data":"role"},
                        {"data":"email"},
                        {"data":"status"},
                        {"data":"action"}
                    ],
                    preDrawCallback: function() {
                        // Remove tooltips of rows about to be replaced so none stay stuck on screen.
                        $('#users_table [data-toggle="tooltip"]').tooltip('destroy');
                    },
                    drawCallback: function() {
                        $('#users_table [data-toggle="tooltip"]').tooltip({container: 'body', trigger: 'hover'});
                    }
                });
        $(document).on('click', 'button.delete_user_button', function(){
            swal({
              title: LANG.sure,
              text: LANG.confirm_delete_user,
              icon: "warning",
              buttons: true,
              dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).data('href');
                    var data = $(this).serialize();
                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        data: data,
                        success: function(result){
                            if(result.success == true){
                                toastr.success(result.msg);
                                users_table.ajax.reload();
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
