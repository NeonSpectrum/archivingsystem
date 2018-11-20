const main_url = $('base').attr('href')
const api_url = $('base').attr('href') + 'api/'

$(document).ready(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  })

  $('.modal').modal({
    endingTop: '5%',
    dismissible: false
  })

  $('select').formSelect()

  $('.datepicker').datepicker({
    autoClose: true,
    container: 'body',
    format: 'mmmm dd, yyyy'
  })

  $('.dropdown-trigger').dropdown()
})

$('form[name=frmLogin]').submit(function(e) {
  e.preventDefault()

  $(this)
    .find('input')
    .prop('readonly', true)
  $(this)
    .find('button[type=submit]')
    .prop('disabled', true)

  $.ajax({
    context: this,
    type: 'POST',
    url: main_url + 'login',
    data: $(this).serialize(),
    dataType: 'json'
  })
    .done(function(response) {
      if (response.success) {
        location.href = './'
      } else {
        alert(response.error)
      }
    })
    .always(function() {
      $(this)
        .find('input')
        .prop('readonly', false)
      $(this)
        .find('button[type=submit]')
        .prop('disabled', false)
    })
})

function loadDatatable(obj = {}) {
  window.dTable = $('#datatable').DataTable({
    oLanguage: {
      sStripClasses: '',
      sSearch: '',
      sSearchPlaceholder: 'Enter Keywords Here',
      sInfo: '_START_ -_END_ of _TOTAL_',
      sLengthMenu:
        '<span>Rows per page:</span><select class="browser-default">' +
        '<option value="10">10</option>' +
        '<option value="20">20</option>' +
        '<option value="30">30</option>' +
        '<option value="40">40</option>' +
        '<option value="50">50</option>' +
        '<option value="-1">All</option>' +
        '</select></div>'
    },
    bAutoWidth: false,
    search: {
      smart: false
    },
    ...obj
  })
}

$('form[name=frmChangePassword]').submit(function(e) {
  e.preventDefault()

  if (
    $(this)
      .find('input[name=new_password]')
      .val() !=
    $(this)
      .find('input[name=v_new_password]')
      .val()
  ) {
    return alert("The new password confirmation doesn't match")
  }

  $(this)
    .find('button')
    .prop('disabled', true)

  $.ajax({
    context: this,
    type: 'POST',
    url: api_url + 'user/changepassword',
    data: $(this).serialize(),
    dataType: 'json'
  })
    .done(function(response) {
      if (response.success) {
        alert('Password changed successfully!')
        $(this).trigger('reset')
        $('#changePasswordModal').modal('close')
      } else {
        alert(response.error)
      }
    })
    .always(function() {
      $(this)
        .find('button')
        .prop('disabled', false)
    })
})

function getConfig() {
  $.getJSON(api_url + 'user/config', null, function(response) {
    window.config = response
  })
}
