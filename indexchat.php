<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Live Chat Kuliner Nusantara</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        #chat-box { background: white; padding: 10px; height: 300px; overflow-y: scroll; border: 1px solid #ccc; }
        .message { margin-bottom: 10px; }
        .message span { font-weight: bold; }
        input, button { padding: 10px; }
    </style>
</head>
<body>
    <h2>💬 Live Chat - Kuliner Nusantara</h2>
    <div id="chat-box"></div><br>

    <form id="chat-form">
        <input type="text" name="username" id="username" placeholder="Nama Anda" required>
        <input type="text" name="message" id="message" placeholder="Pesan" required>
        <button type="submit">Kirim</button>
    </form>

    <script>
        function loadChat() {
            $.get('fetch_chat.php', function(data) {
                $('#chat-box').html(data);
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            });
        }

        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            $.post('chat.php', {
                username: $('#username').val(),
                message: $('#message').val()
            }, function() {
                $('#message').val('');
                loadChat();
            });
        });

        setInterval(loadChat, 3000); // refresh setiap 3 detik
        loadChat(); // load awal
    </script>
</body>
</html>
