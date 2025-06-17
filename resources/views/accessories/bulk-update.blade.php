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
    <form class="form-horizontal" id="checkout_form" method="post" action="{{ route("accessories/bulkupdate") }}" autocomplete="off">
    <!-- CSRF Token -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <div class="box box-default">
      <div class="box-header with-border">
        <h2 class="box-title">Massenverlängerung</h2>
      </div><!-- /.box-header -->

       <div class="box-body">
        @foreach ($checkouts as $accessory)
          @if ($accessory->name)
            <!-- accessory name -->
            <div class="form-group">
              <label class="col-sm-3 control-label">{{ trans('admin/accessories/general.accessory_name') }}</label>
              <div class="col-md-6">
                <p class="form-control-static">{{ $accessory->name }}</p>
              </div>
            </div>
          @endif
        @endforeach

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
                            id="expected_checkin" value="{{ date("Y-m-d")  }}">
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
          />
    </div> <!-- .box.box-default -->
     @foreach ($checkouts as $checkout)
        <input type="hidden" name="ids[{{ $checkout->id }}]" value="{{ $checkout->id }}">
    @endforeach
  </form>
  </div> <!-- .col-md-9-->
</div> <!-- .row -->


@stop
