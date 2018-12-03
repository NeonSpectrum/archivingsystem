$(document).ready(function() {
  loadDatatable()
  loadTable()
})

function loadTable() {
  $.ajax({
    url: api_url + 'college/',
    dataType: 'json',
    success: function(response) {
      dTable.clear()
      $.each(response, function(id, value) {
        value = _.mapObject(value, function(val) {
          return _.escape(val)
        })

        dTable.row.add([
          id + 1,
          value.name,
          value.description,
          `
            <img class="materialboxed" src="img/logo/${value.logo}" height="100px">
          `,
          `
            <img class="materialboxed" src="img/${value.background}" height="100px">
          `,
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
      $('.materialboxed').materialbox()
    }
  })
}

function editData(id) {
  let modal = $('#editCollegeModal')

  modal.find('.loader-container').show()
  modal.modal('open')

  $.ajax({
    url: api_url + 'college/' + id,
    dataType: 'json',
    success: function(response) {
      modal.find('input[name=id]').val(response.id)
      modal.find('input[name=name]').val(response.name)
      modal.find('input[name=description]').val(response.description)

      modal.find('.loader-container').fadeOut()
    }
  })
}

function deleteData(id) {
  if (!confirm('Are you sure do you want to delete?')) return

  $(this).prop('disabled', false)

  $.ajax({
    url: api_url + 'college/' + id,
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

$('form[name=frmAddCollege]').submit(function(e) {
  e.preventDefault()

  $(this)
    .find('input')
    .prop('readonly', true)
  $(this)
    .find('button')
    .prop('disabled', true)

  $.ajax({
    context: this,
    url: api_url + 'college/',
    type: 'POST',
    data: new FormData($(this)[0]),
    dataType: 'json',
    contentType: false,
    processData: false,
    success: function(response) {
      console.log(response)
      if (response.success == true) {
        alert('Added Successfully!')
        $(this).trigger('reset')
        $('#addCollegeModal').modal('close')
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

$('form[name=frmEditCollege]').submit(function(e) {
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
      'college/' +
      $(this)
        .find('input[name=id]')
        .val(),
    type: 'POST',
    data: new FormData($(this)[0]),
    dataType: 'json',
    contentType: false,
    processData: false,
    success: function(response) {
      console.log(response)
      if (response.success == true) {
        alert('Updated Successfully!')
        $('#editCollegeModal').modal('close')
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
