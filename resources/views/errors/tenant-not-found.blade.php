<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dapur Tidak Ditemukan - MBG AkunPro</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #6366f1;
            --dark: #0f172a;
            --light: #f8fafc;
            --accent: #f59e0b;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark);
            background-image: 
                radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(245, 158, 11, 0.1) 0px, transparent 50%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--light);
            overflow: hidden;
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 40px;
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-wrapper {
            font-size: 80px;
            color: var(--accent);
            margin-bottom: 24px;
            text-shadow: 0 0 30px rgba(245, 158, 11, 0.3);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 16px;
            font-weight: 700;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 32px;
        }

        .tenant-id {
            display: inline-block;
            padding: 6px 16px;
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent);
            border-radius: 8px;
            font-weight: 600;
            margin: 0 4px;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .btn-group {
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -3px rgba(79, 70, 229, 0.5);
        }

        .btn-outline {
            background: transparent;
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .btn-outline:hover {
            background: rgba(148, 163, 184, 0.1);
            color: white;
            border-color: rgba(255, 255, 255, 0.4);
        }

        .footer-logo {
            margin-top: 40px;
            opacity: 0.5;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-wrapper">
            <i class="fas fa-store-slash"></i>
        </div>
        <h1>Dapur Tidak Ditemukan</h1>
        <p>
            Maaf, dapur dengan identitas <span class="tenant-id">{{ $tenant_id }}</span> 
            belum terdaftar di sistem kami atau link yang Anda gunakan salah. 
            Pastikan kembali penulisan ID atau hubungi admin pusat.
        </p>
        
        <div class="btn-group">
            <a href="/" class="btn btn-primary">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
            <a href="javascript:history.back()" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali Sebelumnya
            </a>
        </div>

        <div class="footer-logo">
            MBG AKUNPRO <span style="font-weight: 300;">PRO</span>
        </div>
    </div>
</body>
</html>
