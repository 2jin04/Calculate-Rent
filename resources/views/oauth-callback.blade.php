<!DOCTYPE html>
<html>
<head>
    <title>OAuth Callback</title>
</head>
<body>
    <script>
        const data = @json([
            'success' => $success,
            'token' => $token ?? null,
            'user' => $user ?? null,
            'message' => $message ?? null
        ]);
        
        // Gửi data về parent window
        if (window.opener) {
            window.opener.postMessage({
                type: 'oauth-callback',
                data: data
            }, 'http://localhost:3000');
        }
        
        // Đóng popup
        window.close();
    </script>
</body>
</html>