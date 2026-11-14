<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f0f2f5;
    }

    .login-container {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #1c1e21;
    }

    .input-group {
      margin-bottom: 1rem;
    }

    .input-group label {
      display: block;
      margin-bottom: 0.5rem;
      color: #4b4f56;
      font-size: 0.9rem;
    }

    .input-group input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccd0d5;
      border-radius: 6px;
      font-size: 1rem;
    }

    .input-group input:focus {
      outline: none;
      border-color: #1877f2;
    }

    .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      font-size: 0.85rem;
    }

    .options label {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      color: #4b4f56;
      cursor: pointer;
    }

    .options a {
      color: #1877f2;
      text-decoration: none;
    }

    .options a:hover {
      text-decoration: underline;
    }

    .btn-login {
      width: 100%;
      padding: 0.75rem;
      background-color: #1877f2;
      border: none;
      border-radius: 6px;
      color: #ffffff;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-login:hover {
      background-color: #166fe5;
    }

    .register-link {
      text-align: center;
      margin-top: 1.25rem;
      font-size: 0.9rem;
      color: #4b4f56;
    }

    .register-link a {
      color: #1877f2;
      text-decoration: none;
      font-weight: 500;
    }

    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Sign In</h2>
    <form action="{{ route('login') }}" method="POST">
      @csrf

      <div class="input-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
      </div>

      <div class="options">
        <label>
          <input type="checkbox" name="remember"> Remember me
        </label>
        <a href="#">Forgot password?</a>
      </div>

      <button type="submit" class="btn-login">Log In</button>
    </form>

    <div class="register-link">
      Don't have an account? <a href="#">Sign up</a>
    </div>
  </div>

</body>
</html>
