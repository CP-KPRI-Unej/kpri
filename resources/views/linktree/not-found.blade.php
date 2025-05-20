<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linktree Not Found</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #000;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
        }
        
        .container {
            max-width: 600px;
        }
        
        h1 {
            font-size: 28px;
            margin-bottom: 16px;
        }
        
        p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
        }
        
        .btn {
            background-color: #f39c12;
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .btn:hover {
            background-color: #e67e22;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Linktree Not Found</h1>
        <p>The Linktree page you're looking for doesn't exist yet. Please log in to the admin panel to create one.</p>
        <a href="{{ route('login') }}" class="btn">Admin Login</a>
    </div>
</body>
</html> 