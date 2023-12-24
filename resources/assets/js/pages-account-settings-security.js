'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formChangePass = document.querySelector('#formAccountSettings');

    // Form validation for Change password
    if (formChangePass) {
      const fv = FormValidation.formValidation(formChangePass, {
        fields: {
          newPassword: {
            validators: {
              notEmpty: {
                message: 'Por favor, ingrese su contraseña'
              },
              stringLength: {
                min: 8,
                message: 'La contraseña debe tener como mínimo 8 caracteres'
              }
            }
          },
          confirmPassword: {
            validators: {
              notEmpty: {
                message: 'Por favor confirme la nueva contraseña'
              },
              identical: {
                compare: function () {
                  return formChangePass.querySelector('[name="newPassword"]').value;
                },
                message: 'La contraseña y su confirmación no son iguales'
              },
              stringLength: {
                min: 8,
                message: 'La contraseña debe tener como mínimo 8 caracteres'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.col-md-6'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }
  })();
});

// Select2 (jquery)
$(function () {
  var select2 = $('.select2');
});
