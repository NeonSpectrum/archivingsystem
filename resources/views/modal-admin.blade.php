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
        @if(Auth::user()->isAdmin)
          <div class="input-field col s12">
            <p class="caption">Type</p>
            <select name="type">
              @foreach(\App\Role::all()->slice(1) as $role)
                <option value="{{ $role->id }}">{{ $role->description }}</option>
              @endforeach
            </select>
          </div>
        @endif
        @if(Auth::user()->isSuperAdmin)
          <div class="input-field col s12">
            <p class="caption">College</p>
            <select name="college">
              @foreach(\App\College::all() as $college)
                <option value="{{ $college->id }}" data-icon="{{ asset("img/logo/" . $college->logo) }}">{{ $college->description }}</option>
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
        @if(Auth::user()->isAdmin)
          <div class="input-field col s12">
            <p class="caption">Type</p>
            <select name="type">
              @foreach(\App\Role::all()->slice(1) as $role)
                <option value="{{ $role->id }}">{{ $role->description }}</option>
              @endforeach
            </select>
          </div>
        @endif
        @if(Auth::user()->isSuperAdmin)
          <div class="input-field col s12">
            <p class="caption">College</p>
            <select name="college">
              @foreach(\App\College::all() as $college)
                <option value="{{ $college->id }}" data-icon="{{ asset("img/logo/" . $college->logo) }}">{{ $college->description }}</option>
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
<div id="addCollegeModal" class="modal modal-fixed-footer">
  <form name="frmAddCollege">
    <div class="modal-content">
      <h4>Add</h4>
      <div class="row">
        <div class="input-field col s12">
          <p class="caption">College Name</p>
          <input name="name" type="text" class="validate" placeholder="Enter college name" required>
        </div>
        <div class="input-field col s12">
          <p class="caption">Description</p>
          <input name="description" type="text" class="validate" placeholder="Enter college description" required>
        </div>
        <div class="file-field input-field col s12">
          <p class="caption">Logo</p>
          <div class="btn">
            <span>Upload</span>
            <input type="file" name="logo" accept="image/*">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Select a Image file">
          </div>
        </div>
        <div class="file-field input-field col s12">
          <p class="caption">Background</p>
          <div class="btn">
            <span>Upload</span>
            <input type="file" name="background" accept="image/*">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Select a Image file">
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-green btn-flat">Close</button>
      <button type="submit" class="waves-effect waves-green btn-flat">Add</button>
    </div>
  </form>
</div>
 <div id="editCollegeModal" class="modal modal-fixed-footer">
  <form name="frmEditCollege">
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
          <p class="caption">College Name</p>
          <input name="name" type="text" class="validate" placeholder="Enter college name" required>
        </div>
        <div class="input-field col s12">
          <p class="caption">Description</p>
          <input name="description" type="text" class="validate" placeholder="Enter college description" required>
        </div>
        <div class="file-field input-field col s12">
          <p class="caption">Logo</p>
          <div class="btn">
            <span>Upload</span>
            <input type="file" name="logo" accept="image/*">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Select a Image file">
          </div>
        </div>
        <div class="file-field input-field col s12">
          <p class="caption">Background</p>
          <div class="btn">
            <span>Upload</span>
            <input type="file" name="background" accept="image/*">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Select a Image file">
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-green btn-flat">Close</button>
      <button type="submit" class="waves-effect waves-green btn-flat">Save</button>
    </div>
  </form>
</div>
