<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
            color: #495057;
            text-align: center;
        }
        .error-container {
            max-width: 600px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        h1 {
            font-size: 6rem;
            margin-bottom: 20px;
            color: #dc3545;
        }
        h2 {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <a href="<?php echo BASE_URL; ?>">Go to Homepage</a>
    </div>
</body>
</html>
