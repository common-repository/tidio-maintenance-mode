<?php
$settings = get_option('tidio_maintenance_settings');
//var_dump($settings);
$background = $settings['background'];
$background = str_replace('url(', '', $background);
$background = str_replace(')', '', $background);
$url = plugin_dir_url(__FILE__);

$default = TidioMaintenance::getDefaultData();

foreach ($default as $key => $d) {
    if (!isset($settings[$key]) || strlen($settings[$key]) == 0) {
        $settings[$key] = $d;
    }
}
$date = strtotime($settings['countdown'] . ' ' . $settings['countdown_time']);
$left = abs($date - time());

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $email = trim($email);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emails = get_option('tidio_maintenance_emails', array());

        $exist = false;
        foreach ($emails as $e) {
            if ($email == $e) {
                $exist = true;
                break;
            }
        }
        if ($exist) {
            $msg = "Email address already exist.";
        } else {
            array_push($emails, $email);
            update_option('tidio_maintenance_emails', $emails);
            $msg = "Thanks!";
        }
    } else {
        $msg = "Wrong email!";
    }
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $settings['title']; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon(s) in the root directory -->
        <link href='http://fonts.googleapis.com/css?family=Titillium+Web:300' rel='stylesheet' type='text/css'>
        <?php
        if ($settings['countdown_on'] == 1) {
            echo '<link rel="stylesheet" href="' . $url . 'css/flipclock.min.css">';
        }
        ?>
        <link rel="stylesheet" href="<?php echo $url; ?>css/main.css">
        <style>
            #header .logo{
                color: <?php echo $settings['logo-color']; ?>;
            }
            #content header h1,
            #content header p{
                color: <?php echo $settings['header-color']; ?>;
            }
            #content footer{
                border-top-color: <?php echo $settings['newsletter-color']; ?>;   
            }
            footer p{
                color: <?php echo $settings['newsletter-color']; ?>;
            }
            #footer{
                color: <?php echo $settings['footer-color']; ?>;
                border-top-color: <?php echo $settings['footer-color']; ?>;
            }
        </style>
        <?php
        if (strlen(trim($settings['google_analytics_code'])) > 3):
            ?>
            <script>
                (function(i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function() {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                            m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
                ga('create', '<?php echo $settings['google_analytics_code']; ?>', 'auto');
                ga('send', 'pageview');
            </script>
            <?php
        endif;
        ?>
    </head>
    <body>

        <div id="header">
            <div class="logo">
                <?php
                if ($settings['logo-type'] == 'image'):
                    ?>
                    <img src="<?php echo $settings['logo-image']; ?>"/>
                    <?php
                else:
                    bloginfo('name');
                endif;
                ?>
            </div>
            <?php
            if ($settings['password_on'] == 1):
                ?>
                <div id="sign-in-wrapper">
                    <form id="login-form" action="" method="post">
                        <input type="password" name="pwd" placeholder="Password...">
                    </form>
                    <a href="javascript:void(0);" class="button sign-in">
                        Sign In
                    </a>
                    <a href="javascript:void();" class="close">&#215;</a>
                </div>
                <?php
            elseif (is_user_logged_in()):
                ?>
                <div id="sign-in-wrapper">
                    <a href="javascript:void(0);" class="button logged-mode-off">
                        Sign In
                    </a>
                    <a href="javascript:void();" class="close">&#215;</a>
                </div>
                <?php
            endif;
            ?>
        </div>
        <div id="content">
            <header>
                <h1>
                    <?php echo $settings['header']; ?>
                </h1>
                <p>
                    <?php echo $settings['header-sub']; ?>
                </p>
            </header>
            <?php
            if ($settings['countdown_on'] == 1):
                ?>
                <div class="counter">
                    <div id="clock"></div>
                    <div id="labels">
                        <div>Days</div>
                        <div>Hours</div>
                        <div>Minutes</div>
                        <div>Seconds</div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <?php
            endif;
            ?>
            <?php
            if ($settings['newsletter_on'] == 1):
                ?>
                <footer>
                    <?php
                    if (isset($msg)):
                        ?>
                        <p class="alert">
                            <?php echo $msg; ?>
                        </p>
                        <?php
                    endif;
                    ?>
                    <div class="clearfix"></div>
                    <p>
                        Be the first to know when website is ready:
                    </p>
                    <form action="" method="post">
                        <input type="text" name="email" placeholder="Enter your e-mail..."/>
                        <input type="submit" name="submit" value="Subscribe"/>
                    </form>
                    <div class="clearfix"></div>
                </footer>
                <?php
            endif;
            ?>
        </div>
        <div id="footer-wrap">
            <footer id="footer">
                &copy;<?php bloginfo('name'); ?>  <?php echo date('Y'); ?>
            </footer>
        </div>
        <a id="tidio-logo" target="_blank" href="http://www.tidioelements.com/?utm_source=wordpress_maintenance&utm_medium=inside_form&utm_campaign=wordpress_plugin">
            Tidio Elements
        </a>
        <?php
        if ($settings['social_on'] == 1):
            ?>
            <?php
            if (strlen($settings['facebook']) !== 0):
                ?>

                <?php
            endif;
            ?>
            <?php
            if (strlen($settings['pinterest']) !== 0):
                ?>

                <?php
            endif;
            ?>
            <?php
            if (strlen($settings['twitter']) !== 0):
                ?>

                <?php
            endif;
            ?>
            <?php
            if (strlen($settings['google']) !== 0):
                ?>

                <?php
            endif;
            ?>
            <?php
        endif;
        ?>

        <script>
            document.onLoad = function() {

                tidioCountdown.url = "<?php echo $url; ?>";
                tidioCountdown.background = "<?php echo $background; ?>";
                tidioCountdown.left = <?php echo $left; ?>;
                tidioCountdown.init();
            }
        </script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
        <script src="<?php echo $url; ?>js/jquery.backstretch.min.js"></script>
        <script src="<?php echo $url; ?>js/flipclock.min.js"></script>
        <script src="<?php echo $url; ?>js/main.js" async></script>
    </body>
</html>
