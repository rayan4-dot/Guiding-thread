<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      daisyui: {
        themes: [
          {
            dark: {
              ...require("daisyui/src/theming/themes")["dark"],
              "primary": "#1e40af",
              "primary-focus": "#1e3a8a",
              "primary-content": "#ffffff",
              "base-100": "#1e293b",
              "base-200": "#0f172a",
              "base-300": "#020617",
              "base-content": "#e2e8f0",
            },
          },
        ],
      },
    }
  </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-base-300 to-base-200 flex items-center justify-center p-4">
  <div class="card w-full max-w-md bg-base-100 shadow-xl">
    <div class="card-body">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-primary">Reset Password</h2>
        <p class="text-base-content/70 mt-2">Enter your email to receive a password reset link</p>
      </div>
      
      <form>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Email</span>
          </label>
          <input type="email" placeholder="your@email.com" class="input input-bordered bg-base-200 focus:border-primary" required />
        </div>
        
        <div class="divider my-6">Secure Reset</div>
        
        <div class="form-control mt-4">
          <button class="btn btn-primary">Send Reset Link</button>
        </div>
        
        <div class="text-center mt-6">
          <a href="#" class="link link-hover text-primary">Remember your password? Sign in</a>
        </div>
      </form>
      
      <div class="alert alert-info bg-primary/10 border-primary/20 mt-6">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-primary shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>We'll send you an email with instructions to reset your password.</span>
      </div>
    </div>
  </div>
  
  <footer class="fixed bottom-4 text-center w-full text-base-content/50 text-sm">
    <p>© 2023 Company Name. All rights reserved.</p>
  </footer>
</body>
</html>