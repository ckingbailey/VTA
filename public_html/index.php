<?php
  $base = $_SERVER['DOCUMENT_ROOT'] . '/..';
  require "$base/vendor/autoload.php";

  session_start();
  
  // instantiate Twig
  $loader = new Twig_Loader_Filesystem("$base/templates");
  $twig = new Twig_Environment($loader,
      [
          'debug' => true
      ]
  );
  $twig->addExtension(new Twig_Extension_Debug());
  
  // base context
  if (!empty($_SERVER['PATH_INFO'])
    && explode('/', $_SERVER['PATH_INFO'])[0] === 'demo')
  {
    $template = $twig->load('login.html.twig');
    $context = [
    ];
  } else {
    $template = $twig->load('landing.html.twig');
    $context = [
      'title' => 'Under Construction - Come back soon',
      'navbarHeading' => null,
      'navHeadingLink' => '/',
      'navItems' => [],
      'pageHeading' => '🚧 Under Construction 🚧'
    ];
  }
  
  $template->display($context);
