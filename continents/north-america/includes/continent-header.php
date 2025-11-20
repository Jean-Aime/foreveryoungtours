<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'iForYoungTours - ' . ucfirst($continent_folder); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --gold: #D4AF37;
            --gold-light: #F4E4BC;
            --gold-dark: #B8941F;
            --green: #22C55E;
            --green-light: #86EFAC;
            --green-dark: #16A34A;
        }
        
        .bg-gold { background-color: var(--gold); }
        .bg-gold-light { background-color: var(--gold-light); }
        .text-gold { color: var(--gold); }
        .text-gold-dark { color: var(--gold-dark); }
        .border-gold { border-color: var(--gold); }
        
        .bg-green { background-color: var(--green); }
        .bg-green-light { background-color: var(--green-light); }
        .text-green { color: var(--green); }
        .text-green-dark { color: var(--green-dark); }
        
        .btn-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: white;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, var(--gold-dark), var(--gold));
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
        }
        
        .btn-green {
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            color: white;
            transition: all 0.3s ease;
        }
        .btn-green:hover {
            background: linear-gradient(135deg, var(--green-dark), var(--green));
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
        }
        
        .card-professional {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        .card-professional:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-50">