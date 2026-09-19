<div class="modal-dialog" role="document">
  <div class="modal-content modal-lg">
    <div class="modal-header no-print">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title"></h4>
    </div>

    <div class="modal-body">
      <div class="row">
        <div class="col-xs-6">
          <div class="well well-sm">
            <strong>@lang('business.business_name'): </strong> {{$system["invoice_business_name"]}} <br>
            <strong>@lang('business.email'): </strong> {{$system["email"]}} <br>
            <strong>@lang('business.landmark'): </strong> {{$system["invoice_business_landmark"]}} <br>
            <strong>@lang('business.city'): </strong> {{$system["invoice_business_city"]}} <br>
            <strong>@lang('business.zip_code'): </strong> {{$system["invoice_business_zip"]}} <br>
            <strong>@lang('business.state'): </strong> {{$system["invoice_business_state"]}} <br>
            <strong>@lang('business.country'): </strong> {{$system["invoice_business_country"]}}
          </div>
        </div>
        <div class="col-xs-6">
          <div class="well well-sm">
            <strong>@lang('business.business_name'): </strong> {{$subscription->business->name}} <br>
            @if(!empty($subscription->business->tax_number_1) && !empty($subscription->business->tax_label_1))
              <strong>{{$subscription->business->tax_label_1}}: </strong> {{$subscription->business->tax_number_1}} <br>
            @endif
            
            @if(!empty($subscription->business->tax_number_2) && !empty($subscription->business->tax_label_2))
              <strong>{{$subscription->business->tax_label_2}}: </strong> {{$subscription->business->tax_number_2}} <br>
            @endif
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          @php
            $total_amount = (float) $subscription->package_price;
            $price = $total_amount / 1.21;
            $vat_amount = $total_amount - $price;
          @endphp
          <table class="table subscription-details">
            <thead>
              <tr>
                <th>@lang('superadmin::lang.package_name')</th>
                <th>@lang('lang_v1.quantity')</th>
                <th>@lang('lang_v1.price')</th>
                <th>Vat(21%)</th>
                <th>Total(@lang('superadmin::lang.price_vat'))</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{$subscription->package->name}}</td>
                <td>1</td>
                <td>
                  <span class="display_currency" data-currency_symbol="true" data-use_page_currency="true">{{ $price }}</span>
                
                </td>
                <td>
                  <span class="display_currency" data-currency_symbol="true" data-use_page_currency="true">{{ $vat_amount }}</span>
                </td>
                <td>
                  <b><span class="display_currency" data-currency_symbol="true" data-use_page_currency="true">{{ $total_amount }}</span></b>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-xs-12">
          <table class="table">
            <tr>
              <th>@lang('lang_v1.created_at'):</th>
              <td>{{@format_date($subscription->created_at)}}</td>
              <th> @lang('superadmin::lang.payment_transaction_id'):</th>
              <td>{{$subscription->payment_transaction_id}}</td>
            </tr>
            <tr>
              <th>@lang('business.created_by'):</th>
              <td>{{$subscription->created_user->user_full_name}}</td>
              <th>@lang('superadmin::lang.paid_via'):</th>
              <td>{{$subscription->paid_via}}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="modal-footer no-print">
      <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white" aria-label="Print" 
      onclick="$(this).closest('div.modal-content').printThis();"><i class="fa fa-print"></i> @lang( 'messages.print' )
      </button>
      <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
  $(document).ready(function(){
    __currency_convert_recursively($('.subscription-details'));
  })
</script>