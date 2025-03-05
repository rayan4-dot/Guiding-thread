<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {}
      },
      daisyui: {
        themes: [
          {
            dark: {
              ...require("daisyui/src/theming/themes")["dark"],
              "primary": "#3b82f6",
              "primary-focus": "#2563eb",
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
  <style>
    .login-container {
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }
    .login-card {
      backdrop-filter: blur(10px);
      border: 1px solid rgba(59, 130, 246, 0.2);
    }
  </style>
</head>
<body>
  <div class="min-h-screen flex items-center justify-center login-container">
    <div class="card w-full max-w-md login-card shadow-xl bg-base-100/80">
      <div class="card-body">
        <div class="flex justify-center mb-4">
          <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
        </div>
        
        <h2 class="text-2xl font-bold text-center text-primary mb-2">Welcome Back</h2>
        <p class="text-center text-base-content/70 mb-6">Please sign in to continue</p>
        
        <form>
          <div class="form-control">
            <label class="label">
              <span class="label-text text-base-content">Email</span>
            </label>
            <input type="email" placeholder="your@email.com" class="input input-bordered bg-base-200 border-primary/30 focus:border-primary" required />
          </div>
          
          <div class="form-control mt-4">
            <label class="label">
              <span class="label-text text-base-content">Password</span>
            </label>
            <input type="password" placeholder="••••••••" class="input input-bordered bg-base-200 border-primary/30 focus:border-primary" required />
            <label class="label">
              <a href="#" class="label-text-alt link link-hover text-primary">Forgot password?</a>
            </label>
          </div>
          
          <div class="form-control mt-2">
            <label class="cursor-pointer label justify-start gap-2">
              <input type="checkbox" class="checkbox checkbox-primary checkbox-sm" />
              <span class="label-text">Remember me</span>
            </label>
          </div>
          
          <div class="form-control mt-6">
            <button class="btn btn-primary text-white">Sign In</button>
          </div>
        </form>
        
        <div class="divider text-base-content/50 my-6">OR</div>
        
        <div class="grid grid-cols-2 gap-4">
          <button class="btn btn-outline border-base-content/30 hover:bg-primary hover:border-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20.283 10.356h-8.327v3.451h4.792c-.446 2.193-2.313 3.453-4.792 3.453a5.27 5.27 0 0 1-5.279-5.28 5.27 5.27 0 0 1 5.279-5.279c1.259 0 2.397.447 3.29 1.178l2.6-2.599c-1.584-1.381-3.615-2.233-5.89-2.233a8.908 8.908 0 0 0-8.934 8.934 8.907 8.907 0 0 0 8.934 8.934c4.467 0 8.529-3.249 8.529-8.934 0-.528-.081-1.097-.202-1.625z"></path>
            </svg>
            Google
          </button>
          <button class="btn btn-outline border-base-content/30 hover:bg-primary hover:border-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127A22.336 22.336 0 0 0 14.201 3c-2.444 0-4.122 1.492-4.122 4.231v2.355H7.332v3.209h2.753v8.202h3.312z"></path>
            </svg>
            Facebook
          </button>
        </div>
        
        <p class="text-center mt-6 text-base-content/70">
          Don't have an account? <a href="#" class="text-primary hover:underline">Sign up</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>