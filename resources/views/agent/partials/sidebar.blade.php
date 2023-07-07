<aside id="sidebar-wrapper">
    <ul class="sidebar-menu">
        <li class="menu-header">@lang('Dashboard')</li>
        <li class="nav-item {{menu('agent.dashboard')}}">
          <a href="{{route('agent.dashboard')}}" class="nav-link"><i class="fas fa-fire"></i><span>@lang('Dashboard')</span></a>
        </li>
        
        <li class="nav-item {{menu('agent.transactions')}}">
          <a href="{{route('agent.transactions')}}" class="nav-link"><i class="fas fa-exchange-alt"></i><span>@lang('Transactions')</span></a>
        </li> 

        <li class="nav-item {{menu('agent.qr')}}">
          <a href="{{route('agent.qr')}}" class="nav-link"><i class="fas fa-qrcode"></i><span>@lang('QR Code')</span></a>
        </li> 

        <li class="nav-item {{menu('agent.fund.request')}}">
          <a href="{{route('agent.fund.request')}}" class="nav-link"><i class="fas fa-hand-holding-usd"></i><span>@lang('Fund Requests')</span></a>
        </li> 

        <li class="nav-item {{menu('agent.cashin')}}">
          <a href="{{route('agent.cashin')}}" class="nav-link"><i class="fas fa-wallet"></i><span>@lang('User Cash-In')</span></a>
        </li> 
  
        <li class="menu-header">@lang('Withdraw')</li>

        <li class="nav-item {{menu('agent.withdraw.form')}}"><a class="nav-link" href="{{route('agent.withdraw.form')}}"><i class="fas fa-window-restore"></i> @lang('Withdraw Money')</a></li>
        
        <li class="nav-item {{menu('agent.withdraw.history')}}"><a class="nav-link" href="{{route('agent.withdraw.history')}}"><i class="fas fa-history"></i> @lang('Withdraw History')</a></li>
           
        
        <li class="menu-header">@lang('Setting')</li>
        <li class="nav-item {{menu('agent.profile.setting')}}">
          <a href="{{route('agent.profile.setting')}}" class="nav-link"><i class="far fa-user"></i><span>@lang('Profile Setting')</span></a>
        </li>
        <li class="nav-item {{menu('agent.change.password')}}">
          <a href="{{route('agent.change.password')}}" class="nav-link"><i class="fas fa-key"></i><span>@lang('Change Password')</span></a>
        </li>
        <li class="nav-item {{menu('agent.two.step')}}">
          <a href="{{route('agent.two.step')}}" class="nav-link"><i class="fas fa-lock"></i><span>@lang('Two Step Security')</span></a>
        </li>
        <li class="nav-item {{menu('agent.ticket.index')}}">
          <a href="{{route('agent.ticket.index')}}" class="nav-link"><i class="fas fa-ticket-alt"></i><span>@lang('Support Ticket')</span></a>
        </li>
        <li class="nav-item {{menu('agent.logout')}}">
          <a href="{{route('agent.logout')}}" class="nav-link"><i class="fas fa-sign-out-alt"></i><span>@lang('Log Out')</span></a>
        </li>
      </ul>
  </aside>