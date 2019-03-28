var old_attachment_list = []
var attachment_list = []
var attachment_to_delete = []

$(document).ready(function() {
  getConfig()

  loadDatatable({
    columnDefs: [
      {
        targets: [3, 5, 6, 7, 8, 9],
        visible: false
      }
    ]
  })

  loadChips()
  loadTable()

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
  let filter = $('input[name=filter]').val()

  getConfig()

  $.ajax({
    url: main_url + 'api/data',
    dataType: 'json',
    data: { filter },
    success: function(response) {
      dTable.clear()
      $.each(response, function(id, value) {
        value = _.mapObject(value, function(val) {
          return _.escape(val)
        })

        buttonsEnabled = false
        deleteEnabled = true

        if (filter == 'all' && config.isSuperAdmin) {
          buttonsEnabled = true
        } else if (filter == 'college' && config.isAdmin) {
          buttonsEnabled = true
        } else if (filter == 'my' && !config.isGuest) {
          buttonsEnabled = true
        }

        if (!config.isAdmin && !config.isSuperAdmin) {
          deleteEnabled = false
        }

        dTable.row.add([
          filter == 'all' ? value.college.toUpperCase() : id + 1,
          value.title,
          (value.authors || '').replace(/;/g, '<br>'),
          (value.keywords || '').replace(/;/g, ', '),
          (value.category || '').replace(/;/g, ', '),
          value.publisher,
          value.proceeding_date,
          value.presentation_date,
          value.publication_date,
          value.note,
          `
            <button onclick="viewData(${value.id})" class="waves-effect waves-light btn btn-flat btnView">
              <i class="material-icons">remove_red_eye</i>
            </button>` +
            (buttonsEnabled
              ? `
            <button onclick="editData(${value.id})" class="waves-effect waves-light btn btn-flat btnEdit">
              <i class="material-icons">edit</i>
            </button>` +
                (deleteEnabled
                  ? `
            <button onclick="deleteData(${value.id})" class="waves-effect waves-light btn btn-flat btnDelete">
              <i class="material-icons">delete</i>
            </button>`
                  : '')
              : '')
        ])
      })
      dTable.draw()
    }
  })
}

function viewData(id) {
  let modal = $('#viewModal')

  modal.find('.loader-container').show()
  modal.modal('open')

  attachment_list = []

  $.ajax({
    url: main_url + 'api/data/' + id,
    dataType: 'json',
    success: function(response) {
      loadChips(modal)

      $('select[name=college]')
        .val(response.college)
        .formSelect()

      let authorsChip = M.Chips.getInstance(modal.find('.chips[data-name=authors]'))
      let keywordsChip = M.Chips.getInstance(modal.find('.chips[data-name=keywords]'))
      let categoryChip = M.Chips.getInstance(modal.find('.chips[data-name=category]'))

      let authors = (response.authors || '').split(';')
      let keywords = (response.keywords || '').split(';')
      let category = (response.category || '').split(';')

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
      modal
        .find('select[name=college]')
        .val(response.college_id)
        .formSelect()

      old_attachment_list = response.attachments.map(x => ({ ...x, title: response.title }))
      refreshAttachmentList(false)

      modal.find('input.input').prop('disabled', true)

      modal.find('.loader-container').fadeOut()
    }
  })
}

function editData(id) {
  let modal = $('#editModal')

  modal.find('.loader-container').show()
  modal.modal('open')

  attachment_to_delete = []
  attachment_list = []

  $.ajax({
    url: main_url + 'api/data/' + id,
    dataType: 'json',
    success: function(response) {
      loadChips(modal)

      $('select[name=college]')
        .val(response.college)
        .formSelect()

      let authorsChip = M.Chips.getInstance(modal.find('.chips[data-name=authors]'))
      let keywordsChip = M.Chips.getInstance(modal.find('.chips[data-name=keywords]'))
      let categoryChip = M.Chips.getInstance(modal.find('.chips[data-name=category]'))

      let authors = (response.authors || '').split(';')
      let keywords = (response.keywords || '').split(';')
      let category = (response.category || '').split(';')

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
      modal
        .find('select[name=college]')
        .val(response.college_id)
        .formSelect()

      old_attachment_list = response.attachments.map(x => ({ ...x, title: response.title }))
      refreshAttachmentList()

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
  if ($('input[type=search]').val()) {
    $('input[name=pdf_data]').val(JSON.stringify(Object.values(dTable.rows({ filter: 'applied' }).data())))
  }
  $('input[name=pdf_data]')
    .closest('form')
    .trigger('submit')
})

$('.generate-excel').click(function() {
  if ($('input[type=search]').val()) {
    $('input[name=excel_data]').val(JSON.stringify(Object.values(dTable.rows({ filter: 'applied' }).data())))
  }
  $('input[name=excel_data]')
    .closest('form')
    .trigger('submit')
})

$('form[name=frmAdd]').submit(function(e) {
  e.preventDefault()

  let authors = _.pluck(M.Chips.getInstance($(this).find('.chips[data-name=authors]')).chipsData, 'tag')
  let keywords = _.pluck(M.Chips.getInstance($(this).find('.chips[data-name=keywords]')).chipsData, 'tag')
  let category = _.pluck(M.Chips.getInstance($(this).find('.chips[data-name=category]')).chipsData, 'tag')

  if (!config.isAdmin && !config.isResearcher && authors.length == 0) {
    return alert('Please enter an author.')
  }

  $(this)
    .find('input')
    .prop('readonly', true)
  $(this)
    .find('button')
    .prop('disabled', true)

  let form_data = new FormData($(this)[0])

  form_data.append('authors', authors.join(';'))
  form_data.append('keywords', keywords.join(';'))
  form_data.append('category', category.join(';'))

  attachment_list.forEach(attachment => {
    form_data.append('attachments[]', attachment)
  })

  $.ajax({
    context: this,
    url: api_url + 'data',
    type: 'POST',
    data: form_data,
    dataType: 'json',
    processData: false,
    contentType: false,
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

  let form_data = new FormData($(this)[0])

  form_data.append('authors', authors.join(';'))
  form_data.append('keywords', keywords.join(';'))
  form_data.append('category', category.join(';'))

  attachment_list.forEach(attachment => {
    form_data.append('attachments[]', attachment)
  })

  attachment_to_delete.forEach(attachment => {
    form_data.append('attachments_to_delete[]', attachment)
  })

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
      if (response.success == true) {
        old_attachment_list = []
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

$('.btnAddFile').click(function() {
  $(this)
    .closest('form')
    .find('input[name=attachment_file]')
    .click()
})

$('input[name=attachment_file]').change(function() {
  let file = $(this)[0].files[0]
  $(this).val('')

  if (isInvalidFileType($(this))) {
    return alert('Invalid file type.')
  }
  attachment_list.push(file)

  refreshAttachmentList()
})

function deleteAttachment(id, name) {
  if (!confirm('Are you sure do you want delete this file?')) return false

  if (id) {
    attachment_to_delete.push(id)
    old_attachment_list = old_attachment_list.filter(x => x.filename != name)
  } else {
    attachment_list.splice(attachment_list.indexOf(name), 1)
  }

  refreshAttachmentList()
}

function refreshAttachmentList(hasAction = true) {
  $('.collection')
    .find('li.collection-item')
    .remove()

  let list = [...old_attachment_list, ...[...attachment_list].reverse()]
  console.log(list)
  list.reverse().forEach(item => {
    let name = item.filename || item.name
    $('.collection').prepend(
      `<li class='collection-item'>
        ${name}
        ` +
        (hasAction
          ? `<a style="margin-left:10px" href="javascript:void(0)" onclick="return deleteAttachment('${
              item.id
            }','${name}')" class="secondary-content"><i class="material-icons">close</i></a>`
          : '') +
        (item.filename
          ? `
        <a href="${main_url}uploads/${item.title}/${
              item.id
            }" target="_blank" class="secondary-content"><i class="material-icons">remove_red_eye</i></a>
        </li>`
          : ``)
    )
  })
}

function isInvalidFileType(input) {
  let fileType = ['php', 'js', 'exe']

  return (
    fileType.indexOf(
      input
        .val()
        .split('.')
        .pop()
        .toLowerCase()
    ) > -1
  )
}
