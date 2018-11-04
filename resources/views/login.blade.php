@include('header')
@include('modal')
<div class="container">
  <div class="row">
    <div class="col s12 m6 offset-m3" style="margin-top:30px">
      <div class="card">
        <form name="frmLogin">
          <div class="card-content" style="overflow:auto">
            <span class="card-title black-text">Login</span>
            <br>
            <div class="input-field">
              <input id="username" name="username" type="text" class="validate  " required autofocus>
              <label for="username">Username</label>
            </div>
            <div class="input-field">
              <input id="password" name="password" type="password" class="validate  " required>
              <label for="password">Password</label>
            </div>
            <button type="submit" class="btn green assent-3" style="float:right">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@include('footer')
