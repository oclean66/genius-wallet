
<li class="list-group-item d-flex justify-content-between">@lang('Country')<span>{{$details->country}}</span></li>
<li class="list-group-item d-flex justify-content-between">@lang('Address')<span>{{$details->address}}</span></li>
<li class="list-group-item d-flex justify-content-between">@lang('Phone')<span>{{$details->phone}}</span></li>
<li class="list-group-item d-flex justify-content-between">@lang('Business Name')<span>{{$details->business_name}}</span></li>
<li class="list-group-item d-flex justify-content-between">@lang('Business Address')<span>{{$details->business_address}}</span></li>

<li class="list-group-item"><label for="" class="font-weight-bold">@lang('NID Photo :')</label><p><img class="w-100" src="{{getPhoto($details->nid_photo)}}" alt=""></p></li>

