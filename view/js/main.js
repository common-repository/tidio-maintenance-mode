
tidioCountdown = {
    url: '',
    background: '',
    left: 3600,
    init: function() {
        $.backstretch(this.background);
        this.initCounter();
        this.initSignIn();
        this.checkEmail();
    },
    initCounter: function() {
        var clock = $('#clock').FlipClock({
            countdown: true,
            clockFace: 'DailyCounter',
        });
        clock.setTime(this.left);
        clock.start();
    },
    initSignIn: function() {
        var $wrapper = $('#sign-in-wrapper');
        var $button = $wrapper.find('.sign-in');
        $button.click(function() {
            if ($wrapper.hasClass('open')) {
                $('#login-form').submit();
            }
            $wrapper.find('input,.close').show();
            $wrapper.addClass('open');
        });
        $wrapper.find('.close').click(function() {
            $wrapper.find('input,.close').hide();
            $wrapper.removeClass('open');
        });

        var $button = $wrapper.find('.logged-mode-off');
        $button.click(function() {
            var d = new Date();
            //2 minutes
            d.setTime(d.getTime() + 2 * 60 * 1000);
            document.cookie = 'logged-mode-off=1; expires=' + d.toGMTString();
            location.reload();
        });
    },
    checkEmail: function() {
        $('footer form').submit(function(e) {
            var email = $('input[name="email"]').val();
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (re.test(email) == false) {
                alert('Wrong email address.');
                return false;
            } else {
                return true;
            }

        });
    }
};
document.onLoad();