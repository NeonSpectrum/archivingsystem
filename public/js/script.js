;(function(window, document, undefined) {
  var factory = function($, DataTable) {
    'use strict'

    $('.search-toggle').click(function() {
      if ($('.hiddensearch').css('display') == 'none') $('.hiddensearch').slideDown()
      else $('.hiddensearch').slideUp()
    })

    /* Set the defaults for DataTables initialisation */
    $.extend(true, DataTable.defaults, {
      dom: "<'hiddensearch'f'>" + 'tr' + "<'table-footer'lip'>",
      renderer: 'material'
    })

    /* Default class modification */
    $.extend(DataTable.ext.classes, {
      sWrapper: 'dataTables_wrapper',
      sFilterInput: 'form-control input-sm',
      sLengthSelect: 'form-control input-sm'
    })

    /* Bootstrap paging button renderer */
    DataTable.ext.renderer.pageButton.material = function(settings, host, idx, buttons, page, pages) {
      var api = new DataTable.Api(settings)
      var classes = settings.oClasses
      var lang = settings.oLanguage.oPaginate
      var btnDisplay,
        btnClass,
        counter = 0

      var attach = function(container, buttons) {
        var i, ien, node, button
        var clickHandler = function(e) {
          e.preventDefault()
          if (!$(e.currentTarget).hasClass('disabled')) {
            api.page(e.data.action).draw(false)
          }
        }

        for (i = 0, ien = buttons.length; i < ien; i++) {
          button = buttons[i]

          if ($.isArray(button)) {
            attach(container, button)
          } else {
            btnDisplay = ''
            btnClass = ''

            switch (button) {
              case 'first':
                btnDisplay = lang.sFirst
                btnClass = button + (page > 0 ? '' : ' disabled')
                break

              case 'previous':
                btnDisplay = '<i class="material-icons">chevron_left</i>'
                btnClass = button + (page > 0 ? '' : ' disabled')
                break

              case 'next':
                btnDisplay = '<i class="material-icons">chevron_right</i>'
                btnClass = button + (page < pages - 1 ? '' : ' disabled')
                break

              case 'last':
                btnDisplay = lang.sLast
                btnClass = button + (page < pages - 1 ? '' : ' disabled')
                break
            }

            if (btnDisplay) {
              node = $('<li>', {
                class: classes.sPageButton + ' ' + btnClass,
                id: idx === 0 && typeof button === 'string' ? settings.sTableId + '_' + button : null
              })
                .append(
                  $('<a>', {
                    href: '#',
                    'aria-controls': settings.sTableId,
                    'data-dt-idx': counter,
                    tabindex: settings.iTabIndex
                  }).html(btnDisplay)
                )
                .appendTo(container)

              settings.oApi._fnBindAction(
                node,
                {
                  action: button
                },
                clickHandler
              )

              counter++
            }
          }
        }
      }

      // IE9 throws an 'unknown error' if document.activeElement is used
      // inside an iframe or frame.
      var activeEl

      try {
        // Because this approach is destroying and recreating the paging
        // elements, focus is lost on the select button which is bad for
        // accessibility. So we want to restore focus once the draw has
        // completed
        activeEl = $(document.activeElement).data('dt-idx')
      } catch (e) {}

      attach(
        $(host)
          .empty()
          .html('<ul class="material-pagination"/>')
          .children('ul'),
        buttons
      )

      if (activeEl) {
        $(host)
          .find('[data-dt-idx=' + activeEl + ']')
          .focus()
      }
    }

    /*
     * TableTools Bootstrap compatibility
     * Required TableTools 2.1+
     */
    if (DataTable.TableTools) {
      // Set the classes that TableTools uses to something suitable for Bootstrap
      $.extend(true, DataTable.TableTools.classes, {
        container: 'DTTT btn-group',
        buttons: {
          normal: 'btn btn-default',
          disabled: 'disabled'
        },
        collection: {
          container: 'DTTT_dropdown dropdown-menu',
          buttons: {
            normal: '',
            disabled: 'disabled'
          }
        },
        print: {
          info: 'DTTT_print_info'
        },
        select: {
          row: 'active'
        }
      })

      // Have the collection use a material compatible drop down
      $.extend(true, DataTable.TableTools.DEFAULTS.oTags, {
        collection: {
          container: 'ul',
          button: 'li',
          liner: 'a'
        }
      })
    }
  } // /factory

  // Define as an AMD module if possible
  if (typeof define === 'function' && define.amd) {
    define(['jquery', 'datatables'], factory)
  } else if (typeof exports === 'object') {
    // Node/CommonJS
    factory(require('jquery'), require('datatables'))
  } else if (jQuery) {
    // Otherwise simply initialise as normal, stopping multiple evaluation
    factory(jQuery, jQuery.fn.dataTable)
  }
})(window, document)

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

  $('.datepicker').datepicker({
    autoClose: true,
    container: 'body',
    format: 'mmmm dd, yyyy'
  })

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
        targets: 3,
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
      url: 'data/add',
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
    form_data.append('data', data)

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

    $.ajax({
      context: this,
      url: 'data/edit',
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
      url: 'data/upload',
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
    url: 'data',
    dataType: 'json',
    success: function(response) {
      dTable.clear()
      $.each(response, function(key, value) {
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
          `
            ${
              value.file_name
                ? `<button onclick="window.open('${base_url +
                    '/public/uploads/' +
                    value.file_name}')" class="waves-effect waves-light btn btn-flat">
                    <i class="material-icons">pageview</i>
                  </button>`
                : ''
            }
            <button class="waves-effect waves-light btn btn-flat btnEdit" data-id="${value.id}">
              <i class="material-icons">edit</i>
            </button>
            <button class="waves-effect waves-light btn btn-flat btnDelete" data-id="${value.id}">
              <i class="material-icons">delete</i>
            </button>
          `
        ])
      })
      dTable.draw()
      buttonInit()
    }
  })
}

function buttonInit() {
  $('.btnEdit').unbind('click')
  $('.btnDelete').unbind('click')

  $('.btnEdit').click(function() {
    let id = $(this).data('id')
    let modal = $('#editModal')

    modal.find('.loader-container').show()
    modal.modal('open')

    $.ajax({
      url: 'data/' + id,
      dataType: 'json',
      success: function(response) {
        loadChips(modal)

        let authorsChip = M.Chips.getInstance(modal.find('.chips[data-name=authors]'))
        let keywordsChip = M.Chips.getInstance(modal.find('.chips[data-name=keywords]'))
        let categoryChip = M.Chips.getInstance(modal.find('.chips[data-name=category]'))

        let authors = response.authors.split(',')
        let keywords = response.keywords.split(',')
        let category = response.category.split(',')

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

        modal.find('.loader-container').fadeOut()
      }
    })
  })
  $('.btnDelete').click(function() {
    if (!confirm('Are you sure do you want to delete?')) return

    let id = $(this).data('id')

    $(this).prop('disabled', false)

    $.ajax({
      url: 'data/delete',
      type: 'POST',
      data: { id },
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
  })
}
