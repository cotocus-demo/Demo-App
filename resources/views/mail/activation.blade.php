Welcome, {{ $emailParams->usersName }}
Please activate your account : {{ url('user/activation', $emailParams->link) }}