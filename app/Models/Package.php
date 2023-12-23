<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;




class Package extends Model
{

    protected $fillable = [
      'tracking_code',
      'qr_code_path',
      'label_path',
      'delivery_date',
    ];

    protected static function boot()
    {
        parent::boot();

        // Evento durante la creación del paquete
        static::creating(function ($package) {
            // Generación del código de seguimiento
            do {
                $randomCode = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            } while (self::where('tracking_code', $randomCode)->exists());

            $package->tracking_code = $randomCode;
            Log::info('Código de seguimiento generado: ' . $randomCode);
        });

        // Evento después de crear el paquete
        static::created(function ($package) {

            // Definir la ruta del QR en el sistema de archivos
            $qrCodePath = 'qr-codes/' . $package->tracking_code . '.svg';
            $absolutePath = public_path($qrCodePath);

            // Asegurarse de que el directorio exista
            if (!file_exists(public_path('qr-codes'))) {
                mkdir(public_path('qr-codes'), 0755, true);
            }

            // Generar QR
            QrCode::size(300)->generate(route('package.show', ['packageId' => $package->id]), $absolutePath);
            $package->qr_code_path = $qrCodePath;
            $package->save(); // Importante: guardar el modelo después de actualizar el QR code path
            Log::info('QR Code generado y guardado en: ' . $qrCodePath);

            // Generar PDF Etiqueta
            $mpdf = new Mpdf();
            $html = view('content.pages.packages.label', compact('package'))->render();
            $mpdf->WriteHTML($html);
            // Define la ruta donde guardarás el PDF, relativa al directorio public
            $pdfPath = 'pdfs/' . $package->tracking_code . '.pdf';
            // Guarda el PDF directamente en el directorio public
            $mpdf->Output(public_path($pdfPath), 'F');
            // Actualiza la ruta del PDF en el modelo de paquete
            $package->label_path = $pdfPath;
            $package->save();

        });

        static::deleting(function ($package) {
          // Eliminar el archivo del código QR, si existe
          $qrCodePath = public_path($package->qr_code_path);
          if (File::exists($qrCodePath)) {
              File::delete($qrCodePath);
          }

          // Eliminar el archivo PDF, si existe
          $pdfPath = public_path($package->label_path);
          if (File::exists($pdfPath)) {
              File::delete($pdfPath);
          }
        });
    }

    // Relación con Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relación muchos a muchos con Product
    public function products()
    {
       return $this->belongsToMany(Product::class)->withPivot('cantidad');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function activities()
  {
    return $this->hasMany(Activity::class);
  }

  public function latestActivity()
    {
        return $this->hasOne(Activity::class)->latestOfMany();
    }
}
