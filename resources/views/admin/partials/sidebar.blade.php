<aside id="sidebar-wrapper">

  <ul class="sidebar-menu mb-5">
      <li class="menu-header">@lang('Dashboard')</li>
      <li class="nav-item {{menu('admin.dashboard')}}">
        <a href="{{route('admin.dashboard')}}" class="nav-link"><i class="fas fa-fire"></i><span>@lang('Dashboard')</span></a>
      </li>
      @if (access('profit report'))
      <li class="nav-item {{menu('admin.profit.report')}}">
        <a href="{{route('admin.profit.report')}}" class="nav-link"><i class="fas fa-file-invoice-dollar"></i><span>@lang('Profit Report')</span></a>
      </li>
      @endif

      @if (access('transactions'))
      <li class="nav-item {{menu('admin.transactions')}}">
        <a href="{{route('admin.transactions')}}" class="nav-link"><i class="fas fa-exchange-alt"></i><span>@lang('Transaction Report')</span></a>
      </li>
      @endif

      <li class="menu-header">@lang('Manage')</li>
      @if (access('manage user'))
      <li class="nav-item {{menu(['admin.user.index','admin.user.details'])}}">
        <a href="{{route('admin.user.index')}}" class="nav-link"><i class="fas fa-users"></i><span>@lang('Manage User')</span></a>
      </li>
      @endif
  
      @if(access('manage merchant'))
      <li class="nav-item {{menu(['admin.merchant.index','admin.merchant.details'])}}">
        <a href="{{route('admin.merchant.index')}}" class="nav-link"><i class="fas fa-users"></i><span>@lang('Manage Merchant')</span></a>
      </li>
      @endif

     
      @if(access('manage currency'))
      <li class="nav-item {{menu('admin.currency.index')}}">
        <a href="{{route('admin.currency.index')}}" class="nav-link"><i class="fas fa-coins"></i><span>@lang('Manage Currency')</span></a>
      </li>
      @endif
 
      @if(access('manage country'))
      <li class="nav-item {{menu('admin.country.index')}}">
        <a href="{{route('admin.country.index')}}" class="nav-link"> <i class="fas fa-globe"></i><span>@lang('Manage Country')</span></a>
      </li>
      @endif

      @if(access('manage charges'))
      <li class="nav-item {{menu(['admin.manage.charge','admin.edit.charge'])}}">
        <a href="{{route('admin.manage.charge')}}" class="nav-link"><i class="fas fa-comments-dollar"></i><span>@lang('Manage Charges')</span></a>
      </li>
      @endif

      @if(access('manage modules'))
      <li class="nav-item {{menu(['admin.manage.module'])}}">
        <a href="{{route('admin.manage.module')}}" class="nav-link"><i class="fas fa-cogs"></i><span>@lang('Manage Modules')</span></a>
      </li>
      @endif
  
      @if(access('manage kyc') || access('manage kyc form') || access('kyc info'))
      <li class="nav-item dropdown {{menu(['admin.manage.kyc*','admin.kyc*'])}}">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-align-left"></i> <span>@lang('Manage KYC')
          @if ($pending_user_kyc > 0 || $pending_merchant_kyc > 0) <small class="badge badge-primary mr-4">!</small> @endif</span></a>
        <ul class="dropdown-menu">
          @if(access('manage kyc')|| access('manage kyc form'))
          <li class="{{menu(['admin.manage.kyc','admin.manage.kyc.user'])}}"><a class="nav-link" href="{{ route('admin.manage.kyc') }}">@lang('Manage Form')</a></li>
          @endif
          
          @if(access('kyc info'))
          <li class="{{url()->current() == url('admin/kyc-info/user') ? 'active':''}}"><a class="nav-link {{$pending_user_kyc > 0 ? 'beep beep-sidebar':''}}" href="{{route('admin.kyc.info','user')}}">@lang('User KYC Info')</a></li>
          
          <li class="{{url()->current() == url('admin/kyc-info/merchant') ? 'active':''}}"><a class="nav-link {{$pending_merchant_kyc > 0 ? 'beep beep-sidebar':''}}" href="{{route('admin.kyc.info','merchant')}}">@lang('Merchant KYC Info')</a></li>
          @endif
        </ul>
      </li>
      @endif
  
      @if(access('manage escrow') || access('escrow on-hold') || access('escrow disputed'))
      <li class="nav-item dropdown {{menu(['admin.escrow*'])}}">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-hands-helping"></i> <span>@lang('Manage Escrow') @if ($disputed > 0) <small class="badge badge-primary mr-4">!</small> @endif </span></a>
        <ul class="dropdown-menu">
          @if (access('manage escrow'))
          <li class="{{menu('admin.escrow.manage')}}"><a class="nav-link" href="{{ route('admin.escrow.manage') }}">@lang('All Escrow')</a></li> 
          @endif

          @if (access('escrow on-hold'))
          <li class="{{menu('admin.escrow.onHold')}}"><a class="nav-link" href="{{ route('admin.escrow.onHold') }}">@lang('On-hold Escrow')</a></li>
          @endif
          @if (access('escrow disputed'))
          <li class="{{menu('admin.escrow.disputed')}}"><a class="nav-link {{$disputed > 0 ? 'beep beep-sidebar':""}}" href="{{ route('admin.escrow.disputed') }}">@lang('Disputed Escrows')</a></li>
          @endif
        </ul>
      </li>
      @endif
    
      <li class="menu-header">@lang('Staff and Role')</li>

      @if(access('manage role'))
      <li class="nav-item {{menu('admin.role*')}}">
        <a href="{{route('admin.role.manage')}}" class="nav-link"><i class="fas fa-user-tag"></i><span>@lang('Manage Role')</span></a>
      </li>
      @endif
      @if(access('manage staff'))
      <li class="nav-item {{menu('admin.staff*')}}">
        <a href="{{route('admin.staff.manage')}}" class="nav-link"><i class="fas fa-user-shield"></i><span>@lang('Manage Staff')</span></a>
      </li>
      @endif

      <li class="menu-header">@lang('Payment and Withdraw')</li>
      @if(access('withdraw method') || access('pending withdraw') || access('accepted withdraw') || access('rejected withdraw'))
        <li class="nav-item dropdown {{menu('admin.withdraw*')}}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-university"></i> <span>@lang('Manage Withdraw')@if ($pending_withdraw > 0) <small class="badge badge-primary mr-4">!</small> @endif</span></a>
          <ul class="dropdown-menu">
            @if(access('withdraw method'))
            <li class="{{menu('admin.withdraw')}}"><a class="nav-link" href="{{route('admin.withdraw')}}">@lang('Withdraw Method')</a></li>
            @endif
            @if(access('pending withdraw'))
            <li class="{{menu('admin.withdraw.pending')}}"><a class="nav-link {{$pending_withdraw > 0 ? 'beep beep-sidebar':""}}" href="{{route('admin.withdraw.pending')}}">@lang('Pending Withdraws')</a></li>
            @endif
            @if(access('accepted withdraw'))
            <li class="{{menu('admin.withdraw.accepted')}}"><a class="nav-link" href="{{route('admin.withdraw.accepted')}}">@lang('Accepted Withdraws')</a></li>
            @endif
            @if(access('rejected withdraw'))
            <li class="{{menu('admin.withdraw.rejected')}}"><a class="nav-link" href="{{route('admin.withdraw.rejected')}}">@lang('Rejected Withdraws')</a></li>
            @endif
          </ul>
        </li>
        @endif
        
        @if(access('manage payment gateway') || access('manage deposit'))
        <li class="nav-item dropdown {{menu(['admin.gateway*','admin.deposit*'])}}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-money-check-alt"></i> <span>@lang('Manage Payment') @if ($pending_deposits > 0) <small class="badge badge-primary mr-4">!</small> @endif</span></a>
          <ul class="dropdown-menu">
            @if(access('manage payment gateway'))
            <li class="{{menu('admin.gateway')}}"><a class="nav-link" href="{{route('admin.gateway')}}">@lang('Gateways')</a>
            </li>
            @endif
            @if(access('manage deposit'))
            <li class="{{menu('admin.deposit')}}"><a class="nav-link {{$pending_deposits > 0 ? 'beep beep-sidebar':""}}" href="{{route('admin.deposit')}}">@lang('Deposits')</a>
            </li>
            @endif
          </ul>
        </li>
        @endif

      <li class="menu-header">@lang('General')</li>
      @if(access('general setting') || access('general settings logo favicon'))
      <li class="nav-item dropdown {{menu(['admin.gs*','admin.cookie'])}}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i><span>@lang('General Settings')</span></a>
        <ul class="dropdown-menu">
          @if(access('general setting'))
          <li class="{{menu('admin.gs.site.settings')}}"><a class="nav-link" href="{{route('admin.gs.site.settings')}}">@lang('Site Settings')</a></li>
          @endif
          @if(access('general settings logo favicon'))
          <li class="{{menu('admin.gs.logo')}}"><a class="nav-link" href="{{route('admin.gs.logo')}}">@lang('Logo & Favicon')</a></li>
          @endif
          @if(access('manage cookie'))
          <li class="{{menu('admin.cookie')}}"><a class="nav-link" href="{{route('admin.cookie')}}">@lang('Cookie Concent')</a></li>
          @endif
        </ul>
      </li>
      @endif
      @if(access('manage page') || access('menu builder') || access('site contents') ||  access('manage blog-category') ||  access('manage blog') || access('seo settings'))
      <li class="nav-item dropdown {{menu(['admin.front*','admin.page*','admin.frontend*','admin.bcategory*','admin.blog*','admin.seo-setting*'])}}">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i> <span>@lang('Frontend Setting')</span></a>
        <ul class="dropdown-menu">

          @if (access('manage page'))
          <li class="{{menu('admin.page.index')}}"><a class="nav-link" href="{{ route('admin.page.index') }}">@lang('Pages Settings')</a></li>
          @endif
          
          @if (access('menu builder'))
          <li class="{{menu('admin.front.menu')}}"><a class="nav-link" href="{{route('admin.front.menu')}}">@lang('Menu Builder')</a></li>
          @endif

          @if (access('site contents'))
          <li class="{{menu(['admin.frontend.index','admin.frontend.edit'])}}"><a class="nav-link" href="{{route('admin.frontend.index')}}">@lang('Site Contents')</a></li>
          @endif

          @if (access('manage blog-category'))
          <li class="{{menu('admin.bcategory.index')}}"><a class="nav-link" href="{{route('admin.bcategory.index')}}">@lang('Blog Category')</a></li>
          @endif

          @if (access('manage blog'))
          <li class="{{menu(['admin.blog.index','admin.blog.create','admin.blog.edit'])}}"><a class="nav-link" href="{{route('admin.blog.index')}}">@lang('Manage Blog')</a></li>
          @endif

          @if (access('seo settings'))
          <li class="{{menu('admin.seo-setting.index')}}"><a class="nav-link" href="{{route('admin.seo-setting.index')}}">@lang('Seo Settings')</a></li>
          @endif
        </ul>
      </li>
      @endif

      @if(access('email templates') || access('email config') || access('group email'))
      <li class="nav-item dropdown {{menu('admin.mail*')}}">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-envelope-square"></i> <span>@lang('Email Settings')</span></a>
        <ul class="dropdown-menu">
          @if(access('email templates'))
          <li class="{{menu('admin.mail.index')}}"><a class="nav-link" href="{{route('admin.mail.index')}}">@lang('Email Templates')</a></li>
          @endif
          @if(access('email config'))
          <li class="{{menu('admin.mail.config')}}"><a class="nav-link" href="{{route('admin.mail.config')}}">@lang('Email Config')</a></li>
          @endif
          @if(access('group email'))
          <li class="{{menu('admin.mail.group.show')}}"><a class="nav-link" href="{{route('admin.mail.group.show')}}">@lang('Group Email')</a></li>
          @endif
        
        </ul>
      </li>
     
      @endif
      @if(access('sms gateways') || access('sms templates'))
      <li class="nav-item dropdown {{menu('admin.sms*')}}">
        <a href="#" class="nav-link has-dropdown"><i class="far fa-comment-alt"></i> <span>@lang('SMS Settings')</span></a>
        <ul class="dropdown-menu">
          @if (access('sms gateways'))
          <li class="{{menu('admin.sms.index')}}"><a class="nav-link" href="{{route('admin.sms.index')}}">@lang('SMS Gateway')</a></li>
          @endif

          @if (access('sms templates'))
          <li class="{{menu('admin.sms.templates')}}"><a class="nav-link" href="{{route('admin.sms.templates')}}">@lang('SMS Template')</a></li>
          @endif
        </ul>
      </li>
      @endif
    
      @if(access('manage language'))
      <li class="nav-item {{menu(['admin.language*'])}}">
        <a href="{{route('admin.language.index')}}" class="nav-link"><i class="fas fa-language"></i> <span>@lang('Manage Language')</span></a>
      </li>
      @endif

      @if(access('manage ticket'))
      <li class="nav-item dropdown {{menu('admin.ticket.manage')}}">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-ticket-alt"></i> <span>@lang('Support Tickets')  @if ($pending_user_ticket > 0 || $pending_merchant_ticket > 0) <small class="badge badge-primary mr-4">!</small> @endif</span></a>
        <ul class="dropdown-menu">
          <li class="{{url()->current() == url('admin/manage/tickets/user') ? 'active':''}}"><a class="nav-link {{$pending_user_ticket > 0 ? 'beep beep-sidebar':""}}" href="{{route('admin.ticket.manage','user')}}">@lang('User Tickets')</a></li>

          <li class="{{url()->current() == url('admin/manage/tickets/merchant') ? 'active':''}}"><a class="nav-link {{$pending_merchant_ticket > 0 ? 'beep beep-sidebar':""}}" href="{{route('admin.ticket.manage','merchant')}}">@lang('Merchant Tickets')</a></li>

          
        </ul>
      </li>
    @endif

    @if(access('manage addon'))
    <li class="nav-item {{menu('admin.addon')}}">
      <a href="{{route('admin.addon')}}" class="nav-link"><i class="fas fa-puzzle-piece"></i> <span>@lang('Manage Addon')</span></a>
    </li>
    @endif
    @if(access('system update'))
    <li class="nav-item {{menu('admin.update')}}">
      <a href="{{route('admin.update')}}" class="nav-link"><i class="fas fa-arrow-up"></i> <span>@lang('System Update')</span></a>
    </li>
    @endif

    <li class="nav-item {{menu('admin.clear.cache')}}">
      <a href="{{route('admin.clear.cache')}}" class="nav-link"><i class="fas fa-broom"></i> <span>@lang('Clear Cache')</span></a>
    </li>

    @if (admin()->id == 1)
    <li class="nav-item"><a class="nav-link" href="{{route('admin-activation-form')}}"><i class="fas fa-check-circle"></i> @lang('System Activation')</a></li>
    @endif
    
    <li class="nav-item mt-5">
      <h6 class="text-primary text-center"> @lang('Version') : {{sysVersion()}} </h6>
    </li>
    
    </ul>
</aside>

