<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
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
    .register-container {
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }
    .register-card {
      backdrop-filter: blur(10px);
      border: 1px solid rgba(59, 130, 246, 0.2);
    }
    .image-preview {
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%233b82f6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2'%3E%3C/path%3E%3Ccircle cx='12' cy='7' r='4'%3E%3C/circle%3E%3C/svg%3E");
    }
  </style>
</head>
<body>
  <div class="min-h-screen py-12 flex items-center justify-center register-container">
    <div class="card w-full max-w-2xl register-card shadow-xl bg-base-100/80 my-8">
      <div class="card-body">
        <div class="flex justify-center mb-4">
          <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
          </div>
        </div>
        
        <h2 class="text-2xl font-bold text-center text-primary mb-2">Create Account</h2>
        <p class="text-center text-base-content/70 mb-6">Join our community today</p>
        
        <form>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text text-base-content">Full Name</span>
                </label>
                <input type="text" placeholder="John Doe" class="input input-bordered bg-base-200 border-primary/30 focus:border-primary" required />
              </div>
              
              <div class="form-control mt-4">
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
              </div>
              
              <div class="form-control mt-4">
                <label class="label">
                  <span class="label-text text-base-content">Confirm Password</span>
                </label>
                <input type="password" placeholder="••••••••" class="input input-bordered bg-base-200 border-primary/30 focus:border-primary" required />
              </div>
            </div>
            
            <!-- Right Column -->
            <div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text text-base-content">Birthday</span>
                </label>
                <input type="date" class="input input-bordered bg-base-200 border-primary/30 focus:border-primary" required />
              </div>
              
              <div class="form-control mt-4">
                <label class="label">
                  <span class="label-text text-base-content">Sex</span>
                </label>
                <div class="flex gap-4 mt-1">
                  <label class="label cursor-pointer justify-start gap-2">
                    <input type="radio" name="sex" class="radio radio-primary" value="male" checked />
                    <span class="label-text">Male</span>
                  </label>
                  <label class="label cursor-pointer justify-start gap-2">
                    <input type="radio" name="sex" class="radio radio-primary" value="female" />
                    <span class="label-text">Female</span>
                  </label>
                  <label class="label cursor-pointer justify-start gap-2">
                    <input type="radio" name="sex" class="radio radio-primary" value="other" />
                    <span class="label-text">Other</span>
                  </label>
                </div>
              </div>
              
              <div class="form-control mt-4">
                <label class="label">
                  <span class="label-text text-base-content">Profile Photo</span>
                </label>
                <div class="flex flex-col items-center">
                  <div id="imagePreview" class="image-preview w-32 h-32 rounded-full border-2 border-primary/30 mb-3 flex items-center justify-center"></div>
                  <input type="file" id="profilePhoto" accept="image/*" class="file-input file-input-bordered file-input-primary w-full max-w-xs bg-base-200" />
                </div>
              </div>
            </div>
          </div>
          
          <div class="form-control mt-6">
            <label class="cursor-pointer label justify-start gap-2">
              <input type="checkbox" class="checkbox checkbox-primary checkbox-sm" required />
              <span class="label-text">I agree to the <a href="#" class="text-primary hover:underline">Terms of Service</a> and <a href="#" class="text-primary hover:underline">Privacy Policy</a></span>
            </label>
          </div>
          
          <div class="form-control mt-6">
            <button class="btn btn-primary text-white">Create Account</button>
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
          Already have an account? <a href="#" class="text-primary hover:underline">Sign in</a>
        </p>
      </div>
    </div>
  </div>

  <script>

    document.getElementById('profilePhoto').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          document.getElementById('imagePreview').style.backgroundImage = `url(${event.target.result})`;
        }
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>