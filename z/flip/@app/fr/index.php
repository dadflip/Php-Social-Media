<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../public/css/sp-ld-style.css" />
    <title>@Flip App | Auth
    </title>
  </head>
  <body>
    <div class="container">
      <div class="split left">
        <h1>Login</h1>
        <a href="@auth/@login.php" class="btn">@login</a>
      </div>
      <div class="split right">
        <h1>Register</h1>
        <a href="@auth/@sign-in.php" class="btn">@sign in</a>
      </div>
    </div>

    <script>
      const left = document.querySelector('.left')
      const right = document.querySelector('.right')
      const container = document.querySelector('.container')

      left.addEventListener('mouseenter', () => container.classList.add('hover-left'))
      left.addEventListener('mouseleave', () => container.classList.remove('hover-left'))

      right.addEventListener('mouseenter', () => container.classList.add('hover-right'))
      right.addEventListener('mouseleave', () => container.classList.remove('hover-right'))
    </script>
  </body>
</html>
