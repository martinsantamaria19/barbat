<!-- Enable OTP Modal Adaptado para Mostrar Imagen -->
<div class="modal fade" id="enableOTP" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <!-- Botón para cerrar el modal -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        <!-- Área para mostrar la imagen -->
        <div class="text-center mb-4">
          <img src="{{ asset($receiver->image) }}" class="img-fluid mb-4" alt="Imagen" style="max-height: 80vh;"/>
        </div>

        <!-- Botón para cerrar el modal, ubicado en la parte inferior -->
        <div class="text-center">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Enable OTP Modal Adaptado para Mostrar Imagen -->
