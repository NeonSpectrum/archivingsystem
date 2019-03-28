<nav style="height:100px" >
  <div class="nav-wrapper">
    <img src="{{ asset("img/lualhati.png") }}" alt="" height="100%">
    <div style="position:absolute;top:5px;right:5px;height:55px">
      <span style="font-size:25px;color:black;line-height:60px;vertical-align:top;margin-right:10px">University Research Archiving System</span>
      <img src="{{ asset('img/logo/ue.png') }}" alt="" height="60px">
      @if(!Auth::user()->isSuperAdmin)
        <img src="{{ asset('img/logo/' . Auth::user()->college->logo) }}" alt="" height="60px">
      @endif
    </div>
    <ul id="nav-mobile" class="right" style="margin-top:60px;height:40px">
      @if(!Auth::user()->isAdmin && !Auth::user()->isGuest)
        <li><a href="{{ url('/') }}">My Researches</a></li>
      @endif
      @if(!Auth::user()->isSuperAdmin)
        <li><a href="{{ url('college') }}">College Researches</a></li>
      @endif
      <li><a href="{{ url('all') }}">All Researches</a></li>
      @if(Auth::user()->isAdmin)
        <li><a href="{{ url('accounts') }}">Accounts</a></li>
      @endif
      @if(Auth::user()->isSuperAdmin)
        <li><a href="{{ url('colleges') }}">Colleges</a></li>
        <li><a href="{{ url('logs') }}">Logs</a></li>
      @endif
      <li><a class="dropdown-trigger" href="#!" data-target="dropdown1">Hi, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}<i class="material-icons right">arrow_drop_down</i></a></li>
    </ul>
  </div>
</nav>
<ul id="dropdown1" class="dropdown-content">
  <li><a href="#changePasswordModal" class="modal-trigger">Change Password</a></li>
  <li class="divider"></li>
  <li><a href="{{ url('logout') }}" onclick="return confirm('Are you sure do you want to logout?')">Logout</a></li>
</ul>
