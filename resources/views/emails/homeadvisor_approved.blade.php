<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Selamat Bergabung di Homefinder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #1e3a8a;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .content p {
            margin-bottom: 20px;
        }
        .credentials {
            background-color: #f1f5f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #f59e0b;
        }
        .credentials p {
            margin: 5px 0;
            font-size: 16px;
        }
        .credentials strong {
            color: #0f172a;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            background-color: #f59e0b;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            background-color: #f8fafc;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Bergabung, {{ $user->name }}! 🎉</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            <p>Selamat! Aplikasi Anda untuk menjadi <strong>Home Advisor</strong> di Homefinder telah <strong>Disetujui</strong>.</p>
            <p>Kami sangat senang menyambut Anda di tim. Berikut adalah kredensial akun Anda yang dapat digunakan untuk masuk ke dashboard Home Advisor:</p>
            
            <div class="credentials">
                <p>Email: <strong>{{ $user->email }}</strong></p>
                <p>Password Sementara: <strong>{{ $password }}</strong></p>
            </div>
            
            <p><strong>Penting:</strong> Demi keamanan akun Anda, mohon segera mengganti password Anda setelah pertama kali berhasil login ke dalam sistem.</p>
            
            <div class="button-container">
                <a href="{{ env('FRONTEND_URL', 'https://account.homefinder.id') }}/login" class="button">Login Sekarang</a>
            </div>
            
            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk membalas email ini atau menghubungi tim support kami.</p>
            <p>Salam hangat,<br>Tim Homefinder</p>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} Homefinder. All rights reserved.
        </div>
    </div>
</body>
</html>

