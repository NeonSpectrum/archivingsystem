$(document).ready(function() {
  loadDatatable()
  loadTable()
})

function loadTable() {
  getConfig()

  $.ajax({
    url: api_url + 'user/',
    dataType: 'json',
    success: function(response) {
      dTable.clear()
      $.each(response, function(id, value) {
        value = _.mapObject(value, function(val) {
          if (typeof val == 'object') {
            return _.mapObject(val, function(v) {
              return _.escape(v)
            })
          } else {
            return _.escape(val)
          }
        })

        dTable.row.add([
          id + 1,
          value.username,
          value.name,
          value.role.description,
          value.college.description || '',
          `
            <button onclick="editData(${value.id})" class="waves-effect waves-light btn btn-flat btnEdit">
              <i class="material-icons">edit</i>
            </button>
            <button onclick="deleteData(${value.id})" class="waves-effect waves-light btn btn-flat btnDelete">
              <i class="material-icons">delete</i>
            </button>
          `
        ])
      })
      dTable.draw()
    }
  })
}

function editData(id) {
  let modal = $('#editAccountModal')

  modal.find('.loader-container').show()
  modal.modal('open')

  $.ajax({
    url: api_url + 'user/' + id,
    dataType: 'json',
    success: function(response) {
      if (response.college) {
        $('select[name=college]')
          .val(response.college.id)
          .formSelect()
      }
      $('select[name=type]')
        .val(response.role.id)
        .change()
        .formSelect()

      modal.find('input[name=id]').val(response.id)
      modal.find('input[name=first_name]').val(response.first_name)
      modal.find('input[name=last_name]').val(response.last_name)

      modal.find('.loader-container').fadeOut()
    }
  })
}

function deleteData(id) {
  if (!confirm('Are you sure do you want to delete?')) return

  $(this).prop('disabled', false)

  $.ajax({
    url: api_url + 'user/' + id,
    type: 'POST',
    data: { _method: 'DELETE' },
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        alert('Deleted Successfully!')
        loadTable()
      } else {
        alert(response.error)
      }
    }
  })
}

$('form[name=frmAddAccount]').submit(function(e) {
  e.preventDefault()

  $(this)
    .find('input')
    .prop('readonly', true)
  $(this)
    .find('button')
    .prop('disabled', true)

  $.ajax({
    context: this,
    url: api_url + 'user/',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function(response) {
      console.log(response)
      if (response.success == true) {
        alert('Added Successfully!')
        $(this).trigger('reset')
        $('#addAccountModal').modal('close')
        loadTable()
      } else {
        console.log(response)
        alert(response.error)
      }
    }
  }).always(function() {
    $(this)
      .find('input')
      .prop('readonly', false)
    $(this)
      .find('button')
      .prop('disabled', false)
  })
})

$('form[name=frmEditAccount]').submit(function(e) {
  e.preventDefault()

  $(this)
    .find('input')
    .prop('readonly', true)
  $(this)
    .find('button')
    .prop('disabled', true)

  $.ajax({
    context: this,
    url:
      api_url +
      'user/' +
      $(this)
        .find('input[name=id]')
        .val(),
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function(response) {
      console.log(response)
      if (response.success == true) {
        alert('Updated Successfully!')
        $('#editAccountModal').modal('close')
        $(this).trigger('reset')
        loadTable()
      } else {
        console.log(response)
        alert(response.error)
      }
    }
  }).always(function() {
    $(this)
      .find('input')
      .prop('readonly', false)
    $(this)
      .find('button')
      .prop('disabled', false)
  })
})

$('select[name=type]').change(function() {
  console.log($(this).val())
  let college = $(this)
    .closest('form')
    .find('select[name=college]')

  if ($(this).val() == '1') {
    college.attr('disabled', true)
    college.closest('.input-field').hide()
  } else {
    college.attr('disabled', false).formSelect()
    college.closest('.input-field').show()
  }
})
