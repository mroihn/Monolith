<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body{
            background-color: #2f4d4b;
        }
        .container1{
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .reg-container{
            height : 400px;
            width: 550px;
            background-color: #396965;
            opacity: 0.8;
            box-shadow: 0 15px 25px rgba(0, 0, 0, .6);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            color: white;
        }
        .reg-container h1{
            position: absolute;
            top: 180px;
        }
        .reg-container form{
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 100px;
        }
        .reg-container form input{
            margin-bottom: 10px;
            height: 30px;
            width: 200px;
            border-radius: 10px;
        }
        .btn-reg{
            background-color: white;
            border-radius: 10px;
            width: 100px;
        }
    </style>
</head>
<body>
    <div class="container1">
        <div class="reg-container">
            <h1>Register</h1>
            <form action="/process_register" method="post">
                @csrf
                <input type="text" placeholder="First Name *" name="first_name" required />
                <input type="text" placeholder="Last Name *" name="last_name" required />
                <input type="text" placeholder="Username *" name="username" required />
                <input type="email" placeholder="Email *" name="email" required />
                <input type="password" placeholder="Password *" name="password" required />
                <input type="password" placeholder="Password Confirmation *" name="password_confirmation" required />
                <button type="submit" class="btn-reg" >
                    Register
                </button>
                @error('register')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
    
</body>
</html>


