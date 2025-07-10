<!DOCTYPE html>
<html>
<head>
    <title>Todo Reminder</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Todo Reminder</h1>
        </div>

        <div class="content">
            <div class="alert">
                <strong>Reminder:</strong> Your todo is due in 10 minutes!
            </div>

            <h2>{{ $todo->title }}</h2>

            @if($todo->description)
                <p><strong>Description:</strong> {{ $todo->description }}</p>
            @endif
        </div>
        </div>

        <div class="footer">
            <p>This is an automated reminder from your Todo App.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
