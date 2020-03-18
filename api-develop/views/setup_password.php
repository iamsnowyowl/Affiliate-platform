<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>LSP | Setup Password</title>

    <!-- Bootstrap core CSS -->
    <link href="/files/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/files/assets/css/reset-password.css" rel="stylesheet">
  </head>

  <body>
    <?=form_open(getenv("BASE_URL").'/public/setup/password/'.$token, array("class" => "form-signin"))?>
      <div class="text-center mb-4">
        <img class="mb-4" src="/files/assets/images/logo/logo.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Reset Password</h1>
      </div>

      <div class="form-label-group">
        <input type="password" id="password" name="password" class="form-control" placeholder="New Password" required>
        <label for="password">New Password</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="passconf" name="passconf" class="form-control" placeholder="Confirm Password" required>
        <label for="passconf">Confirm Password</label>
      </div>

      <?=validation_errors('<div class="alert alert-danger" role="alert">','</div>')?>

      <button class="btn btn-lg btn-primary btn-block" type="submit">Save</button>
      <p class="mt-5 mb-3 text-muted text-center fixed-bottom">&copy; nusatek.id 2018</p>
    </form>
  </body>
</html>