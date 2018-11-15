$(document).ready(function() {
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
    columnDefs: [
      {
        targets: [3, 6, 7, 8],
        visible: false
      }
    ]
  })

  loadChips()
  loadTable()

  $('form[name=frmAdd]').submit(function(e) {
    e.preventDefault()

    $(this)
      .find('input')
      .prop('readonly', true)
    $(this)
      .find('button')
      .prop('disabled', true)

    let authors = _.pluck(M.Chips.getInstance($(this).find('.chips[data-name=authors]')).chipsData, 'tag')
    let keywords = _.pluck(M.Chips.getInstance($(this).find('.chips[data-name=keywords]')).chipsData, 'tag')
    let category = _.pluck(M.Chips.getInstance($(this).find('.chips[data-name=category]')).chipsData, 'tag')

    let form_data = {
      authors: authors.join(','),
      keywords: keywords.join(','),
      category: category.join(',')
    }

    $(this)
      .serializeArray()
      .map(function(x) {
        form_data[x.name] = x.value
      })

    $.ajax({
      context: this,
      url: api_url + 'data',
      type: 'POST',
      data: form_data,
      dataType: 'json',
      success: function(response) {
        console.log(response)
        if (response.success == true) {
          alert('Added Successfully!')
          $(this).trigger('reset')
          loadChips('#addModal')
          $('#addModal').modal('close')
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

  $('form[name=frmEdit]').submit(function(e) {
    e.preventDefault()

    $(this)
      .find('input')
      .prop('readonly', true)
    $(this)
      .find('button')
      .prop('disabled', true)

    let authors = _.pluck(M.Chips.getInstance($(this).find('.chips[data-name=authors]')).chipsData, 'tag')
    let keywords = _.pluck(M.Chips.getInstance($(this).find('.chips[data-name=keywords]')).chipsData, 'tag')
    let category = _.pluck(M.Chips.getInstance($(this).find('.chips[data-name=category]')).chipsData, 'tag')

    let data = {
      authors: authors.join(','),
      keywords: keywords.join(','),
      category: category.join(',')
    }

    $(this)
      .serializeArray()
      .map(function(x) {
        data[x.name] = x.value
      })

    let form_data = new FormData()
    $.each(data, function(key, value) {
      form_data.append(key, value)
    })

    if (
      $(this)
        .find('input[name=file]')
        .val()
    ) {
      form_data.append(
        'file',
        $(this)
          .find('input[name=file]')
          .prop('files')[0]
      )
    }

    form_data.append('_method', 'PUT')

    $.ajax({
      context: this,
      url:
        api_url +
        'data/' +
        $(this)
          .find('input[name=id]')
          .val(),
      type: 'POST',
      data: form_data,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function(response) {
        console.log(response)
        if (response.success == true) {
          alert('Updated Successfully!')
          $('#editModal').modal('close')
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

  $('.btnUpload').click(function() {
    $('input[name=uploadExcel]').trigger('click')
  })

  $('input[name=uploadExcel]').change(function() {
    if (!confirm('Are you sure do you want to upload?')) return

    let form_data = new FormData()

    form_data.append('file', $(this).prop('files')[0])

    $.ajax({
      url: api_url + 'data/upload',
      type: 'POST',
      data: form_data,
      dataType: 'json',
      contentType: false,
      processData: false,
      success: function(response) {
        if (response.success == true) {
          alert('Uploaded Successfully!')
          loadTable()
        } else {
          console.log(response)
          alert(response.error)
        }
      }
    })
  })
})

function loadChips(element = 'body') {
  $(element)
    .find('.chips[data-name=authors]')
    .chips({
      placeholder: 'Enter an author',
      secondaryPlaceholder: '+ Author'
    })

  $(element)
    .find('.chips[data-name=keywords]')
    .chips({
      placeholder: 'Enter a keyword',
      secondaryPlaceholder: '+ Keyword'
    })

  $(element)
    .find('.chips[data-name=category]')
    .chips({
      placeholder: 'Enter a category',
      secondaryPlaceholder: '+ category'
    })
}

function loadTable() {
  $.ajax({
    url: main_url + 'api/data',
    dataType: 'json',
    success: function({ role_id, data: response }) {
      dTable.clear()
      $.each(response, function(key, value) {
        value = _.mapObject(value, function(val) {
          return _.escape(val)
        })

        dTable.row.add([
          value.id,
          value.title,
          (value.authors || '').replace(/,/g, '<br>'),
          (value.keywords || '').replace(/,/g, ', '),
          (value.category || '').replace(/,/g, ', '),
          value.publisher,
          value.proceeding_date,
          value.presentation_date,
          value.publication_date,
          value.note,
          (value.file_name
            ? `<button onclick="window.open('${main_url +
                'public/uploads/' +
                value.file_name}')" class="waves-effect waves-light btn btn-flat">
                    <i class="material-icons">pageview</i>
                  </button>`
            : '') +
            (role_id == 1 || role_id == value.role_id
              ? `
            <button onclick="editData(${value.id})" class="waves-effect waves-light btn btn-flat btnEdit">
              <i class="material-icons">edit</i>
            </button>
            <button onclick="deleteData(${value.id})" class="waves-effect waves-light btn btn-flat btnDelete">
              <i class="material-icons">delete</i>
            </button>`
              : '')
        ])
      })
      dTable.draw()
    }
  })
}

function editData(id) {
  let modal = $('#editModal')

  modal.find('.loader-container').show()
  modal.modal('open')

  $.ajax({
    url: main_url + 'api/data/' + id,
    dataType: 'json',
    success: function({ data: response }) {
      loadChips(modal)

      $('select[name=college]')
        .val(response.role_id)
        .formSelect()

      let authorsChip = M.Chips.getInstance(modal.find('.chips[data-name=authors]'))
      let keywordsChip = M.Chips.getInstance(modal.find('.chips[data-name=keywords]'))
      let categoryChip = M.Chips.getInstance(modal.find('.chips[data-name=category]'))

      let authors = (response.authors || '').split(',')
      let keywords = (response.keywords || '').split(',')
      let category = (response.category || '').split(',')

      $.each(authors, function(key, value) {
        authorsChip.addChip({ tag: value })
      })

      $.each(keywords, function(key, value) {
        keywordsChip.addChip({ tag: value })
      })

      $.each(category, function(key, value) {
        categoryChip.addChip({ tag: value })
      })

      modal.find('input[name=id]').val(id)
      modal.find('input[name=title]').val(response.title)
      modal.find('input[name=publisher]').val(response.publisher)
      modal.find('input[name=proceeding_date]').val(response.proceeding_date)
      modal.find('input[name=presentation_date]').val(response.presentation_date)
      modal.find('input[name=publication_date]').val(response.publication_date)
      modal.find('input[name=note]').val(response.note)

      modal.find('.loader-container').fadeOut()
    }
  })
}

function deleteData(id) {
  if (!confirm('Are you sure do you want to delete?')) return

  $(this).prop('disabled', false)

  $.ajax({
    url: main_url + 'api/data/' + id,
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

$('.generate-pdf').click(function() {
  if ($('input[name=search]').val()) {
    $('input[name=data]').val(JSON.stringify(Object.values(dTable.rows({ filter: 'applied' }).data())))
  }
  $('input[name=data]')
    .closest('form')
    .trigger('submit')
})
