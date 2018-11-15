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
