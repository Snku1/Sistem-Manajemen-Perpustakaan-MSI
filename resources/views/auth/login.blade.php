<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Login Perpustakaan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    :root {
      --primary-color: #191970;
      --secondary-color: #191970;
      --accent-color: #36b9cc;
      --light-bg: #f8f9fc;
    }

    body {
      font-family: 'Nunito', sans-serif;
      background: linear-gradient(135deg, #ADD8E6 0%, #191970 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 15px;
    }

    .login-container {
      max-width: 400px;
      width: 100%;
    }

    .login-card {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      border: none;
      background: rgba(255, 255, 255, 0.95);
    }

    .login-header {
      background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 25px 20px;
      text-align: center;
    }

    .login-header h4 {
      margin: 0;
      font-weight: 700;
      font-size: 24px;
    }

    .login-header p {
      margin: 5px 0 0;
      opacity: 0.9;
      font-size: 14px;
    }

    .login-body {
      padding: 25px;
    }

    .form-control {
      border-radius: 8px;
      padding: 12px 15px;
      border: 1px solid #ddd;
      transition: all 0.3s;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
    }

    .input-group-text {
      background: transparent;
      border-radius: 8px 0 0 8px;
      border-right: none;
    }

    .form-floating>.form-control:not(:placeholder-shown)~label {
      color: var(--primary-color);
    }

    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-login {
      background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(78, 115, 223, 0.4);
    }

    .alert {
      border-radius: 8px;
      border: none;
      padding: 12px 15px;
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
    }

    .password-toggle {
      cursor: pointer;
      background: transparent;
      border: 1px solid #ddd;
      border-left: none;
      border-radius: 0 8px 8px 0;
    }

    .library-icon {
      font-size: 28px;
      margin-bottom: 15px;
      color: white;
    }

    @media (max-width: 576px) {
      .login-card {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .login-body {
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="library-icon">
          <i class="fas fa-book-open"></i>
        </div>
        <h4>Sistem Perpustakaan</h4>
        <p>Masuk untuk mengakses dashboard</p>
      </div>

      <div class="login-body">
        <div id="alert-container"></div>

        <form id="login-form">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              <input type="email" name="email" class="form-control" placeholder="Masukkan alamat email" required
                autofocus>
            </div>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password"
                required>
              <span class="input-group-text password-toggle" id="togglePassword">
                <i class="fas fa-eye"></i>
              </span>
            </div>
          </div>

          <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
          </div>

          <button type="submit" class="btn btn-primary w-100 btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>Login
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function () {
      // Toggle password visibility
      $('#togglePassword').on('click', function () {
        const passwordInput = $('#password');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
      });

      // Login form submission
      $('#login-form').on('submit', function (e) {
        e.preventDefault();

        // Ambil data form
        let formData = $(this).serialize();

        $.ajax({
          url: "{{ route('login') }}",
          type: "POST",
          data: formData,
          dataType: "json",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          beforeSend: function () {
            $('#alert-container').html('');
            $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');
          },
          success: function (response) {
            if (response.success) {
              $('#alert-container').html(
                '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>Login berhasil! Mengarahkan ke dashboard...</div>'
              );
              setTimeout(() => {
                window.location.href = response.redirect;
              }, 1000);
            }
          },
          error: function (xhr) {
            let message = 'Terjadi kesalahan';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              message = xhr.responseJSON.message;
            }
            $('#alert-container').html(
              '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>' + message + '</div>'
            );
          },
          complete: function () {
            $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-sign-in-alt me-2"></i>Login');
          }
        });
      });
    });
  </script>
</body>

</html>