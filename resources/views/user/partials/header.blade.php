<header class="navbar navbar-expand-xl navbar-light d-print-none">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a href="{{url('/')}}">
        <img src="{{getPhoto($gs->header_logo)}}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
      </a>
    </h1>
    <div class="navbar-nav flex-row order-md-last">

      @if(request()->isMethod('get'))
      <div class="mt-2">
        <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Enable dark mode">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"></path></svg>
        </a>
  
        <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Enable light mode">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="4"></circle><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"></path></svg>
        </a>
      </div>
          
      @endif
      <div class="change-language  ms-auto me-3 text--title">
        <select class="language-bar" onChange="window.location.href=this.value">
          @foreach (DB::table('languages')->get() as $item)
           <option value="{{route('lang.change',$item->code)}}" {{session('lang') == $item->code ? 'selected':''}}>@lang($item->language)</option>
          @endforeach
         </select>
       </div>
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          <span class="avatar avatar-sm" style="background-image: url({{getPhoto(auth()->user()->photo)}})"></span>
          <div class="d-none d-xl-block ps-2">
            <div>{{auth()->user()->name}}</div>
            <div class="mt-1 small text-muted">@lang('User')</div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="{{route('user.profile')}}" class="dropdown-item">@lang('Profile Setting')</a>
          <a href="{{route('user.qr')}}" class="dropdown-item">@lang('QR Code')</a>
          <a href="{{route('user.two.step')}}" class="dropdown-item">@lang('Two Step Security')</a>
          <a href="{{route('user.ticket.index')}}" class="dropdown-item">@lang('Support Ticket')</a>
          <div class="dropdown-divider"></div>
          <a href="{{route('user.logout')}}" class="dropdown-item">@lang('Logout')</a>
        </div>
      </div>
      
    </div>
  </div>
</header>
<div class="navbar-expand-xl">
  <div class="collapse navbar-collapse" id="navbar-menu">
    <div class="navbar navbar-light">
      <div class="container-xl">
        <ul class="navbar-nav">
          <li class="nav-item {{menu('user.dashboard')}}">
            <a class="nav-link" href="{{route('user.dashboard')}}" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="fas fa-home"></i>
              </span>
              <span class="nav-link-title">
                @lang('Home')
              </span>
            </a>
          </li>
          <li class="nav-item {{menu(['user.transfer.money','user.request.money','user.sent.requests','user.received.requests'])}} dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="far fa-paper-plane"></i>
              </span>
              @if ($request_money > 0)
                <span class="badge bg-red"></span>
              @endif
              <span class="nav-link-title">
                @lang('Transfer & Request')
              </span>
            </a>
            <div class="dropdown-menu">
             
              <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                  <a class="dropdown-item" href="{{route('user.transfer.money')}}">
                    @lang('Transfer Money')
                  </a>
                  <a class="dropdown-item" href="{{route('user.request.money')}}">
                    @lang('Request Money')
                  </a>
                  <a class="dropdown-item" href="{{route('user.sent.requests')}}">
                    @lang('Sent Requests')
                  </a>
                  <a class="dropdown-item" href="{{route('user.received.requests')}}">
                    @lang('Received Requests')
                    @if ($request_money > 0)
                       <span class="ms-3 badge bg-red">{{$request_money}}</span>
                    @endif
                  </a>
                </div>
              </div>
            </div>
          </li>

           <li class="nav-item {{menu('user.exchange.money')}}">
              <a class="nav-link" href="{{route('user.exchange.money')}}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <i class="fas fa-exchange-alt"></i>
                </span>
               
                <span class="nav-link-title">
                   @lang('Exchange')
                </span>
              </a>
          </li>
         
          <li class="nav-item {{menu('user.make.payment')}}">
            <a class="nav-link" href="{{route('user.make.payment')}}">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="fas fa-shopping-bag"></i>
              </span>
              <span class="nav-link-title">
                @lang('Payment')
              </span>
            </a>
          </li>
         
          <li class="nav-item dropdown {{menu(['user.create.voucher','user.reedem.voucher','user.reedem.history','user.vouchers'])}}">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="fas fa-ticket-alt"></i>
              </span>
              <span class="nav-link-title">
                @lang('Voucher')
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{route('user.vouchers')}}" >
                @lang('My Vouchers')
              </a>
              <a class="dropdown-item" href="{{route('user.create.voucher')}}" >
                @lang('Create Voucher')
              </a>
              <a class="dropdown-item" href="{{route('user.reedem.voucher')}}" >
                @lang('Redeem Voucher')
              </a>
              <a class="dropdown-item" href="{{route('user.reedem.history')}}" >
                @lang('Redeemed History')
              </a>
            </div>
          </li>
          <li class="nav-item dropdown {{menu(['user.deposit*'])}}">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="fas fa-hand-holding-usd"></i>
              </span>
              <span class="nav-link-title">
                @lang('Deposit')
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{route('user.deposit.index')}}">
                @lang('Deposit')
              </a>
              <a class="dropdown-item" href="{{route('user.deposit.history')}}">
                @lang('Deposit history')
              </a>
            </div>
          </li>
          <li class="nav-item dropdown {{menu(['user.withdraw.form','user.withdraw.history','user.cashout.form'])}}">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="fas fa-university"></i>
              </span>
              <span class="nav-link-title">
                @lang('Withdraw')
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{route('user.withdraw.form')}}">
                @lang('Withdraw Money')
              </a>
              <a class="dropdown-item" href="{{route('user.withdraw.history')}}">
                @lang('Withdraw history')
              </a>
             
            </div>
          </li>

          <li class="nav-item dropdown {{menu(['user.invoice*'])}}">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="fas fa-file-invoice"></i>
              </span>
              <span class="nav-link-title">
                @lang('Invoice')
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{route('user.invoice.index')}}">
                @lang('Invoices')
              </a>
              <a class="dropdown-item" href="{{route('user.invoice.create')}}">
                @lang('Create Invoice')
              </a>
            </div>
          </li>
          <li class="nav-item dropdown {{menu(['user.escrow*'])}}">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="fas fa-hands-helping"></i>
              </span>
              @if ($pending_escrow > 0)
                <span class="badge bg-red"></span>
              @endif
              <span class="nav-link-title">
                @lang('Escrow')
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{route('user.escrow.create')}}">
                @lang('Make Escrow')
              </a>
              <a class="dropdown-item" href="{{route('user.escrow.index')}}">
                @lang('My Escrow')
              </a>
              <a class="dropdown-item" href="{{route('user.escrow.pending')}}">
                @lang('Pending Escrows')
                @if ($pending_escrow > 0)
                  <span class="ms-3 badge bg-red">{{$pending_escrow}}</span>
                @endif
              </a>
              
            </div>
          </li>
         
          <li class="nav-item {{menu('user.transactions')}}">
            <a class="nav-link" href="{{route('user.transactions')}}">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="fas fa-exchange-alt"></i>
              </span>
             
              <span class="nav-link-title">
                 @lang('Transactions')
              </span>
            </a>
          </li>

        
        </ul>
      </div>
    </div>
  </div>
</div>