<?php
  $base = $_SERVER['DOCUMENT_ROOT'] . '/..';
  require "$base/vendor/autoload.php";
  require "$base/config.php";
  require "$base/inc/session.php";

  // instantiate Twig
  $loader = new Twig_Loader_Filesystem("$base/templates");
  $twig = new Twig_Environment($loader,
      [
          'debug' => true
      ]
  );
  $twig->addExtension(new Twig_Extension_Debug());
  
  $pathParams = !empty($_SERVER['PATH_INFO'])
    ? explode('/', $_SERVER['PATH_INFO']) : null;
  
  // base context
  if (!empty($_SERVER['PATH_INFO'])
    && explode('/', $_SERVER['PATH_INFO'])[1] === 'demo')
  {
    $template = $twig->load('login.html.twig');
    $context = [
      'title' => 'Login',
      'pageHeading' => 'Login'
    ];
  } else {
    $template = $twig->load('landing.html.twig');
    $context = [
      'meta' => $_SERVER['PHP_SELF'],
      'title' => 'Under Construction - Come back soon',
      'navbarHeading' => '🐦',
      'navHeadingLink' => '/',
      'navItems' => [ '🐦' => '/'],
      'pageHeading' => '🚧 Under Construction 🚧',
      'textColor' => 'text-yellow'
    ];
  }
  
  $template->display($context);
