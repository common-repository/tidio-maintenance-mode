(function($) {
    var TidioMaintenance = {
        init: function() {
            this.interface();
            this.loadData();
            this.initLogoUploader();
        },
        interface: function() {
            //switchers
            $('a.switch').click(function() {
                var $this = $(this);
                if ($this.attr('rel') == 'on') {
                    $this.attr('rel', 'off');
                    $this.next().val(0);
                    if ($this.parents('.row').next().is('fieldset')) {
                        $this.parents('.row').next().hide('slow');
                    }
                } else {
                    $this.attr('rel', 'on');
                    $this.next().val(1);
                    if ($this.parents('.row').next().is('fieldset')) {
                        $this.parents('.row').next().show('slow');
                    }
                }
            });
            //select background
            $('#tidio-bg-list .e').click(function() {
                $('#tidio-bg-list .e').removeClass('active');
                $(this).toggleClass('active');
                var bg = $(this).css('background-image');
                bg = bg.replace('_thumb', '');
                $('input[name="background"]').val(bg);
                var id = $(this).data('id');
                id--;
                if (typeof backgroundColors[id] == 'undefined')
                    return;
                $('input[name="logo-color"]').minicolors('value', backgroundColors[id][0]);
                $('input[name="header-color"]').minicolors('value', backgroundColors[id][1]);
                $('input[name="newsletter-color"]').minicolors('value', backgroundColors[id][2]);
                $('input[name="footer-color"]').minicolors('value', backgroundColors[id][3]);
            });
            //save settings
            $('form').submit(function(e) {
                e.preventDefault();
                TidioMaintenance.saveData();
            });
            //init minicolors plugin
            $('input.minicolors').minicolors();
            //logo type
            $('select[name="logo-type"]').change(function() {
                var $this = $(this);
                if ($this.val() == 'image') {
                    $('input[name="logo-image"]').parents('.row').show();
                    $('input[name="logo-color"]').parents('.row').hide();
                } else {
                    $('input[name="logo-image"]').parents('.row').hide();
                    $('input[name="logo-color"]').parents('.row').show();
                }
            });
        },
        loadData: function() {
            $.get(window.ajaxurl + '?action=tidio_maintenance_load_settings', {}, function(data) {
                data = JSON.parse(data);
                for (key in data) {
                    var value = data[key];
                    var $input = $('input[name="' + key + '"], select[name="' + key + '"]');
                    $input.val(value);
                    $input.trigger('change');
                    if (key.indexOf('_on') != -1 && value == 1) {
                        $input.prev('a').attr('rel', 'on');
                        if ($input.parents('.row').next().is('fieldset')) {
                            $input.parents('.row').next().show();
                        }
                    }
                    if (key == 'background') {
                        $('#tidio-bg-list .e').each(function() {
                            if ($(this).css('background-image').replace('_thumb', '') == value) {
                                $(this).addClass('active');
                            }
                        });
                    }
                    if (key.indexOf('-color') != -1) {
                        $('input.minicolors[name="' + key + '"]').minicolors('value', value);
                    }
                }
            });
        },
        saveData: function() {
            var data = $('form').serialize();
            $('input[type="submit"]').prop('disabled', true).val('Saving..');
            $.post(window.ajaxurl + '?action=tidio_maintenance_save_settings', data, function(re) {
                console.log('Response:');
                console.log(re);
                setTimeout(function() {
                    $('input[type="submit"]').val('Saved');
                    setTimeout(function() {
                        $('input[type="submit"]').prop('disabled', false).val('Save');
                    }, 2000)
                }, 1000);
            });
        },
        initLogoUploader: function() {
            var custom_uploader;
            $('.upload-image').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                //If the uploader object has already been created, reopen the dialog
                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }
                //Extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                });
                //When a file is selected, grab the URL and set it as the text field's value
                custom_uploader.on('select', function() {

                    var attachment = custom_uploader.state().get('selection').first().toJSON();

                    if ($this.hasClass('custom-bg')) {

                        var bg = attachment.url;
                        bg = 'url(' + bg + ')';
                        $this.css('background-image', bg);

                        $('input[name="background"]').val(bg);
                        $('input[name="custom-background"]').val(bg);

                    } else {
                        $this.prev('input').val(attachment.url);
                    }
                });
                //Open the uploader dialog
                custom_uploader.open();
            });
        }
    }
    TidioMaintenance.init();
})(jQuery);