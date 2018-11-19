var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

;(function (window, document, undefined) {
  var factory = function factory($, DataTable) {
    'use strict';

    $('.search-toggle').click(function () {
      if ($('.hiddensearch').css('display') == 'none') {
        $('.hiddensearch').slideDown(function () {
          $('input[type=search]').focus();
        });
      } else $('.hiddensearch').slideUp();
    });

    /* Set the defaults for DataTables initialisation */
    $.extend(true, DataTable.defaults, {
      dom: "<'hiddensearch'f'>" + 'tr' + "<'table-footer'lip'>",
      renderer: 'material'
    });

    /* Default class modification */
    $.extend(DataTable.ext.classes, {
      sWrapper: 'dataTables_wrapper',
      sFilterInput: 'form-control input-sm',
      sLengthSelect: 'form-control input-sm'
    });

    /* Bootstrap paging button renderer */
    DataTable.ext.renderer.pageButton.material = function (settings, host, idx, buttons, page, pages) {
      var api = new DataTable.Api(settings);
      var classes = settings.oClasses;
      var lang = settings.oLanguage.oPaginate;
      var btnDisplay,
          btnClass,
          counter = 0;

      var attach = function attach(container, buttons) {
        var i, ien, node, button;
        var clickHandler = function clickHandler(e) {
          e.preventDefault();
          if (!$(e.currentTarget).hasClass('disabled')) {
            api.page(e.data.action).draw(false);
          }
        };

        for (i = 0, ien = buttons.length; i < ien; i++) {
          button = buttons[i];

          if ($.isArray(button)) {
            attach(container, button);
          } else {
            btnDisplay = '';
            btnClass = '';

            switch (button) {
              case 'first':
                btnDisplay = lang.sFirst;
                btnClass = button + (page > 0 ? '' : ' disabled');
                break;

              case 'previous':
                btnDisplay = '<i class="material-icons">chevron_left</i>';
                btnClass = button + (page > 0 ? '' : ' disabled');
                break;

              case 'next':
                btnDisplay = '<i class="material-icons">chevron_right</i>';
                btnClass = button + (page < pages - 1 ? '' : ' disabled');
                break;

              case 'last':
                btnDisplay = lang.sLast;
                btnClass = button + (page < pages - 1 ? '' : ' disabled');
                break;
            }

            if (btnDisplay) {
              node = $('<li>', {
                class: classes.sPageButton + ' ' + btnClass,
                id: idx === 0 && typeof button === 'string' ? settings.sTableId + '_' + button : null
              }).append($('<a>', {
                href: '#',
                'aria-controls': settings.sTableId,
                'data-dt-idx': counter,
                tabindex: settings.iTabIndex
              }).html(btnDisplay)).appendTo(container);

              settings.oApi._fnBindAction(node, {
                action: button
              }, clickHandler);

              counter++;
            }
          }
        }
      };

      // IE9 throws an 'unknown error' if document.activeElement is used
      // inside an iframe or frame.
      var activeEl;

      try {
        // Because this approach is destroying and recreating the paging
        // elements, focus is lost on the select button which is bad for
        // accessibility. So we want to restore focus once the draw has
        // completed
        activeEl = $(document.activeElement).data('dt-idx');
      } catch (e) {}

      attach($(host).empty().html('<ul class="material-pagination"/>').children('ul'), buttons);

      if (activeEl) {
        $(host).find('[data-dt-idx=' + activeEl + ']').focus();
      }
    };

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
      });

      // Have the collection use a material compatible drop down
      $.extend(true, DataTable.TableTools.DEFAULTS.oTags, {
        collection: {
          container: 'ul',
          button: 'li',
          liner: 'a'
        }
      });
    }
  }; // /factory

  // Define as an AMD module if possible
  if (typeof define === 'function' && define.amd) {
    define(['jquery', 'datatables'], factory);
  } else if ((typeof exports === 'undefined' ? 'undefined' : _typeof(exports)) === 'object') {
    // Node/CommonJS
    factory(require('jquery'), require('datatables'));
  } else if (jQuery) {
    // Otherwise simply initialise as normal, stopping multiple evaluation
    factory(jQuery, jQuery.fn.dataTable);
  }
})(window, document);

var main_url = $('base').attr('href');
var api_url = $('base').attr('href') + 'api/';

$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('.modal').modal({
    endingTop: '5%',
    dismissible: false
  });

  $('select').formSelect();

  $('.datepicker').datepicker({
    autoClose: true,
    container: 'body',
    format: 'mmmm dd, yyyy'
  });

  $('.dropdown-trigger').dropdown();
});

$('form[name=frmLogin]').submit(function (e) {
  e.preventDefault();

  $(this).find('input').prop('readonly', true);
  $(this).find('button[type=submit]').prop('disabled', true);

  $.ajax({
    context: this,
    type: 'POST',
    url: main_url + 'login',
    data: $(this).serialize(),
    dataType: 'json'
  }).done(function (response) {
    if (response.success) {
      location.href = './';
    } else {
      alert(response.error);
    }
  }).always(function () {
    $(this).find('input').prop('readonly', false);
    $(this).find('button[type=submit]').prop('disabled', false);
  });
});

function loadDatatable() {
  var obj = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

  window.dTable = $('#datatable').DataTable(_extends({
    oLanguage: {
      sStripClasses: '',
      sSearch: '',
      sSearchPlaceholder: 'Enter Keywords Here',
      sInfo: '_START_ -_END_ of _TOTAL_',
      sLengthMenu: '<span>Rows per page:</span><select class="browser-default">' + '<option value="10">10</option>' + '<option value="20">20</option>' + '<option value="30">30</option>' + '<option value="40">40</option>' + '<option value="50">50</option>' + '<option value="-1">All</option>' + '</select></div>'
    },
    bAutoWidth: false,
    search: {
      smart: false
    }
  }, obj));
}

$('form[name=frmChangePassword]').submit(function (e) {
  e.preventDefault();

  if ($(this).find('input[name=new_password]').val() != $(this).find('input[name=v_new_password]').val()) {
    return alert("The new password confirmation doesn't match");
  }

  $(this).find('button').prop('disabled', true);

  $.ajax({
    context: this,
    type: 'POST',
    url: api_url + 'user/changepassword',
    data: $(this).serialize(),
    dataType: 'json'
  }).done(function (response) {
    if (response.success) {
      alert('Password changed successfully!');
      $(this).trigger('reset');
      $('#changePasswordModal').modal('close');
    } else {
      alert(response.error);
    }
  }).always(function () {
    $(this).find('button').prop('disabled', false);
  });
});
