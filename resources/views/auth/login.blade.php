<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>EPS - Đăng nhập</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <!--base css styles-->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.min.css">

    <!--page specific css styles-->

    <!--flaty css styles-->
    <link rel="stylesheet" href="../css/eps.css">
    <link rel="stylesheet" href="../css/eps-responsive.css">

    <link rel="shortcut icon" href="../img/logo2.png">
</head>
<body class="login-page">

<!-- BEGIN Main Content -->
<div class="login-wrapper">
    <!-- BEGIN Login Form -->
    <form id="form-login" action="{{ route('login') }}" method="POST">
        {{ csrf_field() }}
        <img src="../img/logo-login.png" alt="" width="100%">
        <hr/>
        <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
            <div class="controls">
                <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Tên đăng nhập" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <input id="password" name="password" type="password" placeholder="Mật khẩu" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <label class="checkbox">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Ghi nhớ
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                    <button type="submit"  class="btn btn-primary form-control">Đăng Nhập</button>
            </div>
        </div>
        <hr/>
        <p class="clearfix">
            <a href="{{ route('password.request') }}" class="goto-forgot pull-left">Quên mật khẩu?</a>
            {{--<a href="extra_login.html#" class="goto-register pull-right">Sign up now</a>--}}
        </p>
    </form>
    <!-- END Login Form -->

    <!-- BEGIN Forgot Password Form -->
    <form id="form-forgot" action="index.html" method="get" style="display:none">
        <h3>Get back your password</h3>
        <hr/>
        <div class="form-group">
            <div class="controls">
                <input type="text" placeholder="Email" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary form-control">Recover</button>
            </div>
        </div>
        <hr/>
        <p class="clearfix">
            <a href="extra_login.html#" class="goto-login pull-left">← Back to login form</a>
        </p>
    </form>
    <!-- END Forgot Password Form -->

    <!-- BEGIN Register Form -->
    <form id="form-register" action="index.html" method="get" style="display:none">
        <h3>Sign up</h3>
        <hr/>
        <div class="form-group">
            <div class="controls">
                <input type="text" placeholder="Email" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <input type="text" placeholder="Username" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <input type="password" placeholder="Password" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <input type="password" placeholder="Repeat Password" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <label class="checkbox">
                    <input type="checkbox" value="remember" /> I accept the <a href="extra_login.html#">user aggrement</a>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary form-control">Sign up</button>
            </div>
        </div>
        <hr/>
        <p class="clearfix">
            <a href="extra_login.html#" class="goto-login pull-left">← Back to login form</a>
        </p>
    </form>
    <!-- END Register Form -->
</div>
<!-- END Main Content -->


<!--basic scripts-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../assets/jquery/jquery-2.1.1.min.js"><\/script>')</script>
<script src="../assets/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">
    function goToForm(form)
    {
        $('.login-wrapper > form:visible').fadeOut(500, function(){
            $('#form-' + form).fadeIn(500);
        });
    }
    $(function() {
        $('.goto-login').click(function(){
            goToForm('login');
        });
        $('.goto-forgot').click(function(){
            goToForm('forgot');
        });
        $('.goto-register').click(function(){
            goToForm('register');
        });
    });
</script>
</body>
</html>
