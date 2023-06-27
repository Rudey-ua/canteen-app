<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome Email</title>
</head>
<body>
<h2>Welcome to Our Website {{ $user->name }}</h2>
<br/>
Your registered email-id is {{ $user->email }} , please click on the below link to verify your email account
<br/>
<a href="https://youtube.com/">Here is YouTube link</a>
</body>
</html>
