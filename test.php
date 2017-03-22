<?= $this->doctype() ?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?= $this->headTitle('登录')->setSeparator(' - ')->setAutoEscape(false) ?>

    <?= $this->headMeta()
    ->appendName('viewport', 'width=device-width, initial-scale=1.0')
    ->appendHttpEquiv('X-UA-Compatible', 'IE=edge')
    ?>

    <!-- Le styles -->
    <?= $this->headLink(['rel' => 'shortcut icon', 'type' => 'image/vnd.microsoft.icon', 'href' => $this->basePath() . '/images/mainlayout/common/favicon.ico'])
    ->prependStylesheet($this->basePath('css/mainlayout/Wopop_files/style.css'))
    ->prependStylesheet($this->basePath('css/mainlayout/Wopop_files/userpanel.css'))
    ->prependStylesheet($this->basePath('css/mainlayout/Wopop_files/style_log.css'))
    ?>

    <!-- Scripts -->
    <?= $this->headScript()
    ->prependFile($this->basePath('js/mainlayout/side.js'))
    ->prependFile($this->basePath('js/mainlayout/jquery-1.10.1.min.js'))
    ?>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
</head>
<body class="login" mycollectionplug="bind">

<?= $this->content ?>
<?php
    $form->setAttribute('action', $this->url('auth', ['action' => 'login']));
$form->setAttribute('class','loginAction');
$form->prepare();
echo $this->form()->openTag($form);
?>
<div class="login_m">
    <div class="login_boder">
        <div class="login_padding" id="login_model">
            <h2>用户名</h2>
            <label>
                <?php

    echo $this->formHidden($form->get('id'));
                $username = $form->get('username');
                $username->setAttribute('class','txt_input txt_input2');
                echo $this->formRow($username);
                ?>
            </label>
            <h2>PASSWORD</h2>
            <label>
                <?php
    $pwd = $form->get('passwd');
                $pwd->setAttribute('class','txt_input');
                echo $this->formRow($pwd);
                ?>
            </label>
            <p class="forgot"><a id="iforget" href="javascript:void(0);">Forgot your password?</a></p>
            <div class="rem_sub">
                <div class="rem_sub_l">
                    <input type="checkbox" name="checkbox" id="save_me">
                    <label for="checkbox">Remember me</label>
                </div>
                <label>
                    <input type="submit" class="sub_button" name="button" id="button" value="SIGN-IN"
                           style="opacity: 0.7;">
                </label>
            </div>
            <?php
    echo $this->formSubmit($form->get('submit'));
            echo $this->form()->closeTag();
            ?>
        </div>
    </div>
</div>

<?= $this->inlineScript() ?>
</body>
</html>
