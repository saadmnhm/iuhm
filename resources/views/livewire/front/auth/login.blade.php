<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;800&display=swap");

:root {
  --main-color: #648454;
  --secondary-color: #edffe4 ;
  --gradient: linear-gradient(
    135deg,
    var(--main-color),
    var(--secondary-color)
  );
}

* {
  box-sizing: border-box;
}

body {
  background: #f6f5f7;
  font-family: "Nunito", sans-serif;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  margin: 0;
  padding: 1rem;
}

h1 {
  font-weight: bold;
  margin: 0;
  font-size: 1.5rem;
}

p {
  font-size: 14px;
  font-weight: 100;
  line-height: 20px;
  letter-spacing: 0.5px;
  margin: 20px 0 30px;
}

input{
    outline:none;
}

.social-container {
  margin: 20px 0;
}

.social-container a {
  border: 1px solid #dddddd;
  border-radius: 50%;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  margin: 0 5px;
  height: 40px;
  width: 40px;
  transition: all 0.3s ease;
}

.social-container a:hover {
  border-color: var(--main-color);
  color: var(--main-color);
}

span {
  font-size: 12px;
}

a {
  color: #333;
  font-size: 14px;
  text-decoration: none;
  margin: 15px 0;
}

a:hover {
  color: var(--main-color);
}

button {
  cursor: pointer;
  border-radius: 20px;
  border: 1px solid var(--main-color);
  background: var(--main-color);
  color: #fff;
  font-size: 12px;
  font-weight: bold;
  padding: 12px 45px;
  letter-spacing: 1px;
  text-transform: uppercase;
  transition: transform 80ms ease-out;
}

button:hover {
  background: var(--secondary-color);
  color: var(--main-color);
}

button:active {
  transform: scale(0.95);
}

button:focus {
  outline: none;
}

button.ghost {
  background-color: transparent;
  border-color: #fff;
  color: #fff;
}

button.ghost:hover {
  background: #fff;
  color: var(--main-color);
}

form {
  background-color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 0 30px;
  height: 100%;
  text-align: center;
}

input {
  background-color: #eee;
  border: none;
  padding: 12px 15px;
  margin: 8px 0;
  width: 100%;
  font-family: inherit;
  border-radius: 30px;
}

.alert {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 5px;
  font-size: 13px;
}

.alert-danger {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}

.alert-success {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
}

.container {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
  position: relative;
  overflow: hidden;
  width: 100%;
  max-width: 400px;
  min-height: 600px;
}

.form-container {
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
  transition: all 0.6s ease-in-out;
}

.sign-in-container {
  top: 0;
  height: 100%;
  z-index: 2;
  opacity: 1;
}

.container.right-panel-active .sign-in-container {
  opacity: 0;
  z-index: 1;
}

.sign-up-container {
  top: 0;
  height: 100%;
  opacity: 0;
  z-index: 1;
}

.container.right-panel-active .sign-up-container {
  opacity: 1;
  z-index: 5;
  animation: show 0.6s;
}

@keyframes show {
  0%,
  49.99% {
    opacity: 0;
    z-index: 1;
  }
  50%,
  100% {
    opacity: 1;
    z-index: 5;
  }
}

.overlay-container {
  position: absolute;
  left: 0;
  bottom: 0;
  height: auto;
  width: 100%;
  overflow: hidden;
  transition: transform 0.6s ease-in-out;
  z-index: 100;
}

.overlay {
  background: #648454;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: 0 0;
  color: #fff;
  position: relative;
  width: 100%;
  height: 100%;
  padding: 30px 20px;
  text-align: center;
  transition: transform 0.6s ease-in-out;
}

.overlay-panel {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 0 20px;
  text-align: center;
  width: 100%;
  transition: transform 0.6s ease-in-out;   
}
@media screen and (max-width:767px) {
    .overlay-container{
        display:none;
    }
    .sign-in-container {
        left: 0;
    }
    .sign-up-container{
        left: 0;
    }
    
}
.overlay-panel h1 {
  font-size: 1.3rem;
  margin-bottom: 10px;
}

.overlay-panel p {
  margin: 10px 0 20px;
  font-size: 13px;
}

.mobile-toggle {
  display: block;
  text-align: center;
  padding: 20px;
  color: var(--main-color);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}



/* Desktop Styles */
@media (min-width: 768px) {
  body {
    padding: 20px;
  }

  h1 {
    font-size: 2rem;
  }

  form {
    padding: 0 50px;
  }

  .container {
    width: 768px;
    max-width: 100%;
    height: 480px;
    min-height: auto;
  }

  .form-container {
    top: 0;
    height: 100%;
    width: 50%;
  }

  .sign-in-container {
    left: 0;
    width: 50%;
    height: 100%;
    opacity: 1;
  }

  .container.right-panel-active .sign-in-container {
    transform: translateX(100%);
    opacity: 1;
  }

  .sign-up-container {
    left: 0;
    width: 50%;
    height: 100%;
  }

  .container.right-panel-active .sign-up-container {
    transform: translateX(100%);
  }

  .overlay-container {
    left: 50%;
    top: 0;
    bottom: auto;
    height: 100%;
    width: 50%;
  }

  .container.right-panel-active .overlay-container {
    transform: translateX(-100%);
  }

  .overlay {
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    padding: 0;
  }

  .container.right-panel-active .overlay {
    transform: translateX(50%);
  }

  .overlay-panel {
    position: absolute;
    top: 0;
    height: 100%;
    width: 50%;
    padding: 0 40px;
    transform: translateX(0);
  }

  .overlay-panel h1 {
    font-size: 2rem;
  }

  .overlay-panel p {
    font-size: 14px;
    margin: 20px 0 30px;
  }

  .overlay-left {
    transform: translateX(-20%);
  }

  .container.right-panel-active .overlay-left {
    transform: translateX(0);
  }

  .overlay-right {
    right: 0;
    top: 0;
    left: 50%;
    transform: translateX(0);
  }

  .container.right-panel-active .overlay-right {
    transform: translateX(20%);
  }

  .mobile-toggle {
    display: none;
  }
}

    </style>
</head>


<body>
<div class="container" id="container">
    <!-- Sign Up Form -->
    <div class="form-container sign-up-container">
        <form method="POST" action="{{ route('user.register.post') }}">
            @csrf
            <h1>Sign Up</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-apple"></i></a>
                <a href="#" class="social"><i class="fab fa-google"></i></a>
            </div>
            <span>or use your email for registration</span>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required />
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
            <input type="password" name="password" placeholder="Password" required />
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
            <button type="submit" class="mt-4">Sign Up</button>
            
            <div class="mobile-toggle" id="mobileSignIn">
                Already have an account? Sign In
            </div>
        </form>
    </div>

    <!-- Sign In Form -->
    <div class="form-container sign-in-container">
        <form method="POST" action="{{ route('user.login.post') }}">
            @csrf
            <h1>Sign In</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-apple "></i></a>
                <a href="#" class="social"><i class="fab fa-google"></i></a>
            </div>
            <span>or use your account</span>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
            <input type="password" name="password" placeholder="Password" required />
            <a href="#">Forgot your password?</a>
            <button type="submit">Sign In</button>
            
            <div class="mobile-toggle" id="mobileSignUp">
                Don't have an account? Sign Up
            </div>
        </form>
    </div>

    <!-- Overlay (Desktop Only) -->
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>Please login with your personal info</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start your journey with us</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>

<script>
    const signUpButton = document.getElementById("signUp");
    const signInButton = document.getElementById("signIn");
    const mobileSignUp = document.getElementById("mobileSignUp");
    const mobileSignIn = document.getElementById("mobileSignIn");
    const container = document.getElementById("container");

    // Desktop toggle
    if (signUpButton) {
        signUpButton.addEventListener("click", () => {
            container.classList.add("right-panel-active");
        });
    }

    if (signInButton) {
        signInButton.addEventListener("click", () => {
            container.classList.remove("right-panel-active");
        });
    }

    // Mobile toggle
    if (mobileSignUp) {
        mobileSignUp.addEventListener("click", () => {
            container.classList.add("right-panel-active");
        });
    }

    if (mobileSignIn) {
        mobileSignIn.addEventListener("click", () => {
            container.classList.remove("right-panel-active");
        });
    }
</script>
</body>
</html>