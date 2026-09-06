<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de verificación</title>
</head>
<body style="font-family: Arial, sans-serif; background: #0f172a; color: #e2e8f0; padding: 24px;">
    <div style="max-width: 560px; margin: 0 auto; background: #111827; border: 1px solid #334155; border-radius: 12px; padding: 24px;">
        <h2 style="margin-top: 0; color: #f8fafc;">SensorWatch</h2>
        <p>Hola {{ $nombre }},</p>
        <p>Tu código de verificación para iniciar sesión es:</p>
        <div style="text-align: center; margin: 24px 0; padding: 18px; background: #1e293b; border-radius: 10px; font-size: 28px; font-weight: bold; letter-spacing: 4px; color: #fbbf24;">
            {{ $codigo }}
        </div>
        <p>Este código expira en 5 minutos.</p>
        <p>Si no intentaste iniciar sesión, ignora este correo.</p>
    </div>
</body>
</html>
