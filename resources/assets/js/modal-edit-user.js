'use strict';

$(function () {
  // Inicialización de Select2
  const select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select value',
        dropdownParent: $this.parent()
      });
    });
  }
});

document.addEventListener('DOMContentLoaded', function () {
  // Validación del formulario de edición de usuario
  FormValidation.formValidation(document.getElementById('editUserForm'), {
    fields: {
      modalEditUserFirstName: {
        validators: {
          notEmpty: {
            message: 'Please enter your first name'
          },
          regexp: {
            regexp: /^[a-zA-Zs]+$/,
            message: 'The first name can only consist of alphabetical'
          }
        }
      },
      modalEditUserLastName: {
        validators: {
            notEmpty: {
              message: 'Please enter your last name'
            },
            regexp: {
              regexp: /^[a-zA-Zs]+$/,
              message: 'The last name can only consist of alphabetical'
            }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '',
        rowSelector: '.col-12'
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });

  // Cargar datos del usuario en el modal
  $(document).on('click', '.edit-user-button', function() {
    var userId = $(this).data('user-id');

    $.ajax({
      url: '/users/' + userId + '/edit',
      type: 'GET',
      success: function(response) {
        var user = response.user;
        $('#modalEditUserFirstName').val(user.name);
        $('#modalEditUserLastName').val(user.lastname);
        $('#modalEditUserEmail').val(user.email);
        $('#modalEditUserPhone').val(user.phone);

        // Actualizar la acción del formulario
        $('#editUserForm').attr('action', '/users/' + userId + '/update');

    // Mostrar el modal
    $('#editUser').modal('show');
  },
  error: function() {
    alert('Error al cargar la información del usuario');
  }
});
});

// Manejar el envío del formulario
$('#editUserForm').on('submit', function(e) {
e.preventDefault();

var formData = $(this).serialize();
var actionUrl = $(this).attr('action');

$.ajax({
  url: actionUrl,
  type: 'PUT', // Asegúrate de que el método coincide con tu ruta de Laravel
  data: formData,
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF
  },
  success: function(response) {
    // Cerrar el modal
    $('#editUser').modal('hide');

    // Opcional: Actualizar la lista de usuarios o mostrar un mensaje de éxito
    alert('Usuario actualizado correctamente');
  },
  error: function(response) {
    // Manejar los errores de validación aquí
    alert('Error al actualizar el usuario');
  }
});
});
});
