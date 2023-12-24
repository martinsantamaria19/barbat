<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Etiqueta de Envío</title>
  <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


  </style>
</head>
<body class="mt-4">
  <div class="container">
    <div class="shipping-label">
      <div class="row justify-content-between">
        <div class="sender">
          <strong>Remitente:</strong><br>
          Ruben Barbat<br>
          Francisco Plá, 3433<br>
          Cerrito de la Victoria,<br>
          Montevideo, Uruguay
        </div>

      </div>
      <hr>
      <div class="recipient">
        <p><strong> {{ $package->client->company_name }}</strong><br>
          Sucursal: <strong>{{ $package->branch->branch_name }}</strong> <br>
          Dirección: {{ $package->branch->branch_address }}, {{ $package->branch->branch_city }}, {{ $package->branch->branch_state }} <br>
          Teléfono: {{ $package->branch->branch_phone }}
        </p>
      </div>
      <hr>
      <div class="d-flex justify-content-between">
        <div class="col-4">
          <div>
            Envío N°: {{ $package->id }}
          </div>
          <div>
            Código de Rastreo: <br> <strong>{{ $package->tracking_code }}</strong>
          </div>
        </div>
        <div class="barcode">
          <!-- You will need to replace 'path_to_barcode_image.png' with the actual path to your barcode image -->
          <img src="{{ asset($package->qr_code_path)}}" alt="Código QR del envío {{ $package->codigo }}">
        </div>
      </div>
    </div>
  </div>
</body>

</html>
