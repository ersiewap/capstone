<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap"
      rel="stylesheet"
    />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="login_staff.css" />
  </head>
  <body>
    <section class="section">
      <div  class="loggoo">
        <img
          src="logo_login.png"
          alt="Image"
          class="logo_login"
          style="width: 30%; top: 15%"
        />
        <img src="logo_nav1.png" class="logo_login1" />
      </div>

      <div class="card-front">
        <form
          class="login-form"
          id="login-form"
          action="login_staff2.php"
          method="post"
        >
          <div class="login_container">
            <div class="form_field">
              <h4 class="mb">Email Address</h4>
  
              <input
                type="email"
                name="staffemail"
                id="staffemail"
                class="form_input"
                placeholder="Your Email"
                autocomplete="off"
                required
              />
              <i class="input-icon uil uil-at"></i>
            </div>
            <div class="form_field">
              <h4 class="pb">Password</h4>
              <div style="position: relative; width: 100%;">
                <input
                id="password-field"
                type="password"
                name="staffpass"
                id="staffpass"
                class="form_input1"
                placeholder="Your Password"
                autocomplete="off"
                required
              />

              </div>
              
            </div>
  
            <input
              href="admin.php"
              type="submit"
              class="btn"
              value="Login"
              onclick="auth()"
            />
            
          </div>
          
          
        </form>
      </div>
    </section>

  </body>

  <script src="Login.js"></script>
</html>