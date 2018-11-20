@if(Auth::user()->isAdmin)
  <div id="addModal" class="modal modal-fixed-footer">
    <form name="frmAdd">
      <div class="modal-content">
        <h4>Add</h4>
        <div class="row">
          @if(Auth::user()->isSuperAdmin)
            <div class="input-field col s12">
              <p class="caption">College</p>
              <select name="college">
                @foreach(\App\Roles::all()->slice(1) as $role)
                  <option value="{{ $role->name }}" data-icon="{{ asset('public/img/logo/' . $role->logo) }}">{{ $role->description }}</option>
                @endforeach
              </select>
            </div>
          @endif
          <div class="input-field col s12">
            <p class="caption">Title</p>
            <input name="title" type="text" class="validate" placeholder="Enter the title" required>
          </div>
          <div class="input-field col s12">
            <p class="caption">Authors
              @if(!Auth::user()->isAdmin)
                <i>(Your name is already added)</i>
              @endif
            </p>
            <div class="chips chips-placeholder" data-name="authors"></div>
          </div>
          <div class="input-field col s12">
            <p class="caption">Keywords</p>
            <div class="chips chips-placeholder" data-name="keywords"></div>
          </div>
          <div class="input-field col s12">
            <p class="caption">Category</p>
            <div class="chips chips-placeholder" data-name="category"></div>
          </div>
          <div class="input-field col s12">
            <p class="caption">Publisher</p>
            <input name="publisher" type="text" class="validate" placeholder="Enter the publisher">
          </div>
          <div class="input-field col s12">
            <p class="caption">Proceeding Date</p>
            <input name="proceeding_date" type="text" class="validate" placeholder="Enter the proceeding date">
          </div>
          <div class="input-field col s12">
            <p class="caption">Presentation Date</p>
            <input name="presentation_date" type="text" class="validate" placeholder="Enter the presentation date">
          </div>
          <div class="input-field col s12">
            <p class="caption">Publication Date</p>
            <input name="publication_date" type="text" class="validate" placeholder="Enter the publication date">
          </div>
          <div class="input-field col s12">
            <p class="caption">Note</p>
            <input name="note" type="text" class="validate" placeholder="Enter note" required>
          </div>
          <div class="file-field input-field col s12">
            <p class="caption">Upload PDF</p>
            <div class="btn">
              <span>Upload</span>
              <input type="file" name="pdf_file">
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" type="text" placeholder="Select a PDF file">
            </div>
          </div>
          <div class="file-field input-field col s12">
            <p class="caption">Upload Certificate</p>
            <div class="btn">
              <span>Upload</span>
              <input type="file" name="certificate_file">
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" type="text" placeholder="Select a Certificate file">
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
@endif
<div id="editModal" class="modal modal-fixed-footer">
  <form name="frmEdit">
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
        @if(Auth::user()->isSuperAdmin)
          <div class="input-field col s12">
            <p class="caption">College</p>
            <select name="college">
              @foreach(\App\Roles::all()->slice(1) as $role)
                <option value="{{ $role->name }}" data-icon="{{ asset('public/img/logo/' . $role->logo) }}">{{ $role->description }}</option>
              @endforeach
            </select>
          </div>
        @endif
        <div class="input-field col s12">
          <p class="caption">Title</p>
          <input name="title" type="text" class="validate" placeholder="Enter the title" required>
        </div>
        <div class="input-field col s12">
          <p class="caption">Authors</p>
          <div class="chips chips-placeholder" data-name="authors"></div>
        </div>
        <div class="input-field col s12">
          <p class="caption">Keywords</p>
          <div class="chips chips-placeholder" data-name="keywords"></div>
        </div>
        <div class="input-field col s12">
          <p class="caption">Category</p>
          <div class="chips chips-placeholder" data-name="category"></div>
        </div>
        <div class="input-field col s12">
          <p class="caption">Publisher</p>
          <input name="publisher" type="text" class="validate" placeholder="Enter the publisher">
        </div>
        <div class="input-field col s12">
          <p class="caption">Proceeding Date</p>
          <input name="proceeding_date" type="text" class="validate" placeholder="Enter the proceeding date">
        </div>
        <div class="input-field col s12">
          <p class="caption">Presentation Date</p>
          <input name="presentation_date" type="text" class="validate" placeholder="Enter the presentation date">
        </div>
        <div class="input-field col s12">
          <p class="caption">Publication Date</p>
          <input name="publication_date" type="text" class="validate" placeholder="Enter the publication date">
        </div>
        <div class="input-field col s12">
          <p class="caption">Note</p>
          <input name="note" type="text" class="validate" placeholder="Enter note" required>
        </div>
        <div class="file-field input-field col s12">
          <p class="caption">Upload PDF</p>
          <div class="btn">
            <span>Upload</span>
            <input type="file" name="pdf_file">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Select a PDF file">
          </div>
        </div>
        <div class="file-field input-field col s12">
          <p class="caption">Upload Certificate</p>
          <div class="btn">
            <span>Upload</span>
            <input type="file" name="certificate_file">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Select a PDF file">
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
<div id="changePasswordModal" class="modal modal-fixed-footer">
  <form name="frmChangePassword">
    <div class="modal-content">
      <h4>Change Password</h4>
      <div class="row">
        <div class="input-field col s12">
          <p class="caption">Old Password</p>
          <input name="old_password" type="password" class="validate" placeholder="Enter old password">
        </div>
        <div class="input-field col s12">
          <p class="caption">New Password</p>
          <input name="new_password" type="password" class="validate" placeholder="Enter new password">
        </div>
        <div class="input-field col s12">
          <p class="caption">Verify New Password</p>
          <input name="v_new_password" type="password" class="validate" placeholder="Enter new password again">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-green btn-flat">Close</button>
      <button type="submit" class="waves-effect waves-green btn-flat">Save</button>
    </div>
  </form>
</div>
