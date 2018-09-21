
	<div class="container">
      <form class="form-signin" role="form" method="POST">
        <h2 class="form-signin-heading">Please sign in</h2>
		<input type="hidden" name="redirect" value="<?php echo $redirect ?>">
        <input type="text" class="form-control" name="user" placeholder="Email address" required autofocus>
        <input type="password" class="form-control" name="pwd" placeholder="Password" required>
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>
<p><?php  echo $redirect ?></p>
    </div> <!-- /container -->
