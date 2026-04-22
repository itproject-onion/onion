<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Demo Page</title>
    @vite('resources/css/app.css')
</head>
<body>
    <h1>{{ $message }}</h1>

    <!-- Ein einfaches Formular -->
    <form method="POST" action="/demo">
        @csrf <!-- Laravel Sicherheit Token -->
        <input type="text" name="name" placeholder="Name eingeben">
        <button type="submit">Senden</button>
    </form>

    <!-- JavaScript -->
     @vite('resources/js/app.js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.querySelector('button');
            btn.addEventListener('click', () => {
                console.log("Button wurde geklickt!");
                alert("JS funktioniert!");
            });
        });
    </script>
</body>
</html>