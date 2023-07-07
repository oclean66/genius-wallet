<aside id="sidebar-wrapper">
  <ul class="sidebar-menu">
      <li class="menu-header">@lang('Dashboard')</li>
      <li class="nav-item {{menu('merchant.dashboard')}}">
        <a href="{{route('merchant.dashboard')}}" class="nav-link"><i class="fas fa-fire"></i><span>@lang('Dashboard')</span></a>
      </li>
      
      <li class="nav-item {{menu('merchant.qr')}}">
        <a href="{{route('merchant.qr')}}" class="nav-link"><i class="fas fa-qrcode"></i><span>@lang('QR Code')</span></a>
      </li>

      <li class="nav-item {{menu('merchant.api.key.form')}}">
        <a href="{{route('merchant.api.key.form')}}" class="nav-link"><i class="fas fa-key"></i><span>@lang('API Access Key')</span></a>
      </li>

      <li class="nav-item {{menu('merchant.transactions')}}">
        <a href="{{route('merchant.transactions')}}" class="nav-link"><i class="fas fa-exchange-alt"></i><span>@lang('Transactions')</span></a>
      </li> 

      <li class="menu-header">@lang('Withdraw')</li>
      <li class="nav-item dropdown {{menu('merchant.withdraw*')}}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-university"></i> <span>@lang('Withdraw')</span></a>
        <ul class="dropdown-menu">
          <li class="{{menu('merchant.withdraw.form')}}"><a class="nav-link" href="{{route('merchant.withdraw.form')}}">@lang('Withdraw Money')</a></li>
          <li class="{{menu('merchant.withdraw.history')}}"><a class="nav-link" href="{{route('merchant.withdraw.history')}}">@lang('Withdraw History')</a></li>
         
        </ul>
      </li>
      <li class="menu-header">@lang('Setting')</li>
      <li class="nav-item {{menu('merchant.profile.setting')}}">
        <a href="{{route('merchant.profile.setting')}}" class="nav-link"><i class="far fa-user"></i><span>@lang('Profile Setting')</span></a>
      </li>
      <li class="nav-item {{menu('merchant.change.password')}}">
        <a href="{{route('merchant.change.password')}}" class="nav-link"><i class="fas fa-key"></i><span>@lang('Change Password')</span></a>
      </li>
      <li class="nav-item {{menu('merchant.two.step')}}">
        <a href="{{route('merchant.two.step')}}" class="nav-link"><i class="fas fa-lock"></i><span>@lang('Two Step Security')</span></a>
      </li>
      <li class="nav-item {{menu('merchant.ticket.index')}}">
        <a href="{{route('merchant.ticket.index')}}" class="nav-link"><i class="fas fa-ticket-alt"></i><span>@lang('Support Ticket')</span></a>
      </li>
      <li class="nav-item {{menu('merchant.logout')}}">
        <a href="{{route('merchant.logout')}}" class="nav-link"><i class="fas fa-sign-out-alt"></i><span>@lang('Log Out')</span></a>
      </li>
    </ul>
</aside>