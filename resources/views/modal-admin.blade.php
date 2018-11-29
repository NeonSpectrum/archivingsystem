 <div id="addAccountModal" class="modal modal-fixed-footer">
  <form name="frmAddAccount">
    <div class="modal-content">
      <h4>Add</h4>
      <div class="row">
        <div class="input-field col s12">
          <p class="caption">Username</p>
          <input name="username" type="text" class="validate" placeholder="Enter username" required>
        </div>
        <div class="input-field col s12">
          <p class="caption">Password</p>
          <input name="password" type="password" class="validate" placeholder="Enter password" required>
        </div>
        <div class="input-field col s12">
          <p class="caption">First Name</p>
          <input name="first_name" type="text" class="validate" placeholder="Enter first name" required>
        </div>
        <div class="input-field col s12">
          <p class="caption">Middle Initial</p>
          <input name="middle_initial" type="text" class="validate" placeholder="Enter middle initial">
        </div>
        <div class="input-field col s12">
          <p class="caption">Last Name</p>
          <input name="last_name" type="text" class="validate" placeholder="Enter last name" required>
        </div>
        @if(Auth::user()->isSuperAdmin)
          <div class="input-field col s12">
            <p class="caption">College</p>
            <select name="college">
              @foreach(\App\Roles::all() as $role)
                <option value="{{ $role->name }}" data-icon="{{ asset('img/logo/' . $role->logo) }}">{{ $role->description }}</option>
              @endforeach
            </select>
          </div>
        @endif
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-green btn-flat">Close</button>
      <button type="submit" class="waves-effect waves-green btn-flat">Add</button>
    </div>
  </form>
</div>
 <div id="editAccountModal" class="modal modal-fixed-footer">
  <form name="frmEditAccount">
    @method('put')
    <input type="hidden" name="id" disabled>
    <div class="modal-content">
      <h4>Edit</h4>
      <div class="loader-container">
        <div class="preloader-wrapper big active">
          <div class="spinner-layer spinner-blue-only">
            <div class="circle-clipper left">
              <div class="circle"></div>
            </div>
            <div class="gap-patch">
              <div class="circle"></div>
            </div>
            <div class="circle-clipper right">
              <div class="circle"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <p class="caption">First Name</p>
          <input name="first_name" type="text" class="validate" placeholder="Enter first name" required>
        </div>
        <div class="input-field col s12">
          <p class="caption">Middle Initial</p>
          <input name="middle_initial" type="text" class="validate" placeholder="Enter middle initial">
        </div>
        <div class="input-field col s12">
          <p class="caption">Last Name</p>
          <input name="last_name" type="text" class="validate" placeholder="Enter last name" required>
        </div>
        @if(Auth::user()->isSuperAdmin)
          <div class="input-field col s12">
            <p class="caption">College</p>
            <select name="college">
              @foreach(\App\Roles::all() as $role)
                <option value="{{ $role->name }}" data-icon="{{ asset('img/logo/' . $role->logo) }}">{{ $role->description }}</option>
              @endforeach
            </select>
          </div>
        @endif
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-green btn-flat">Close</button>
      <button type="submit" class="waves-effect waves-green btn-flat">Save</button>
    </div>
  </form>
</div>
