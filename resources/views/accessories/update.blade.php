@extends('layouts/default')

{{-- Page title --}}
@section('title')
     {{ trans('admin/accessories/general.checkout') }}
@parent
@stop
@section('header_right')
<a href="{{ URL::previous() }}" class="btn btn-primary pull-right">
  {{ trans('general.back') }}</a>
@stop


{{-- Page content --}}
@section('content')


<div class="row">
  <div class="col-md-9">
    <form class="form-horizontal" id="checkout_form" method="post" action="" autocomplete="off">
    <!-- CSRF Token -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <div class="box box-default">
      @if ($accessory->id)
        <div class="box-header with-border">
          <h2 class="box-title">{{ $accessory->name }}</h2>
        </div><!-- /.box-header -->
      @endif

       <div class="box-body">
         @if ($accessory->name)
          <!-- accessory name -->
          <div class="form-group">
            <label class="col-sm-3 control-label">{{ trans('admin/accessories/general.accessory_name') }}</label>
            <div class="col-md-6">
              <p class="form-control-static">{{ $accessory->name }}</p>
            </div>
          </div>
          @endif

         @if ($accessory->company)
             <!-- accessory name -->
             <div class="form-group">
                 <label class="col-sm-3 control-label">{{ trans('general.company') }}</label>
                 <div class="col-md-6">
                     <p class="form-control-static">{{ $accessory->company->name }}</p>
                 </div>
             </div>
         @endif


         @if ($accessory->category)
          <!-- accessory name -->
          <div class="form-group">
            <label class="col-sm-3 control-label">{{ trans('admin/accessories/general.accessory_category') }}</label>
            <div class="col-md-6">
              <p class="form-control-static">{{ $accessory->category->name }}</p>
            </div>
          </div>
          @endif
            <!-- Checkout/Checkin Date -->
            <div class="form-group {{ $errors->has('checkout_at') ? 'error' : '' }}">
                <label for="checkout_at" class="col-md-3 control-label">
                    {{ trans('admin/hardware/form.checkout_date') }}
                </label>
                <div class="col-md-8">
                    <div class="input-group date col-md-7" data-provide="datepicker"
                          data-date-format="yyyy-mm-dd" data-date-end-date="0d" data-date-clear-btn="true">
                        <input type="text" class="form-control"
                                placeholder="{{ trans('general.select_date') }}" name="checkout_at"
                                id="checkout_at" value="{{ old(date($checkout->created_at), date('Y-m-d')) }}">
                        <span class="input-group-addon">
                            <x-icon type="calendar" /></span>
                    </div>
                    {!! $errors->first('checkout_at', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                </div>
            </div>

            <!-- Expected Checkin Date -->
            <div class="form-group {{ $errors->has('expected_checkin') ? 'error' : '' }}">
                <label for="expected_checkin" class="col-md-3 control-label">
                    {{ trans('admin/hardware/form.expected_checkin') }}
                </label>

                <div class="col-md-8">
                    <div class="input-group date col-md-7" data-provide="datepicker"
                          data-date-format="yyyy-mm-dd" data-date-start-date="0d" data-date-clear-btn="true">
                        <input type="text" class="form-control"
                                placeholder="{{ trans('general.select_date') }}" name="expected_checkin"
                                id="expected_checkin" value="{{ old(date($checkout->expected_checkin)) }}">
                        <span class="input-group-addon">
                            <x-icon type="calendar" />
                        </span>
                    </div>
                    {!! $errors->first('expected_checkin', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                </div>
            </div>
       </div>
          <x-redirect_submit_options
                  index_route="accessories.index"
                  :button_label="trans('general.update')"
                  :options="[
                        'index' => trans('admin/hardware/form.redirect_to_all', ['type' => trans('general.accessories')]),
                        'item' => trans('admin/hardware/form.redirect_to_type', ['type' => trans('general.accessory')]),
                        'target' => trans('admin/hardware/form.redirect_to_checked_out_to'),

                       ]"
          />
    </div> <!-- .box.box-default -->
  </form>
  </div> <!-- .col-md-9-->
</div> <!-- .row -->


@stop
