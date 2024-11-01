<div class="wrap" id="wrap">

    <h2 id="wrap-header"><strong>Maintenance Page</strong> - Settings</h2>

    <hr/>

    <form>
        <div class="row">
            <label>
                Maintenance mode:
            </label>
            <a href="javascript:void(0)" class="switch"></a>
            <input type="hidden" name="mode_on" value="0"/>
        </div>
        <fieldset>
            <hr/>
            <div class="row">
                <label>Logo type:</label>
                <select name="logo-type">
                    <option value="text">Text</option>
                    <option value="image">Image</option>
                </select>
            </div>
            <div class="row hide">
                <label>Upload logo:</label>
                <input type="text" name="logo-image" id="logo-image" />
                <a href="javascript:void(0)" id="upload-logo-button" class="btn upload-image">Upload</a>
            </div>
            <div class="row">
                <label>Logo color:</label>
                <input type="text" name="logo-color" class="minicolors"/>
            </div>
            <hr/>
            <div class="row">
                <label>Title:</label>
                <input type="text" name="title"/>
            </div>
            <div class="row">
                <label>Header:</label>
                <input type="text" name="header"/>
            </div>
            <div class="row">
                <label>Header-sub:</label>
                <input type="text" name="header-sub"/>
            </div>
            <div class="row">
                <label>Header logo:</label>
                <input type="text" name="header-color" class="minicolors"/>
            </div>
            <div class="row">
                <label>Footer color:</label>
                <input type="text" name="footer-color" class="minicolors"/>
            </div>
            <hr/>
            <label>Select background:</label>
            <div id="tidio-bg-list">
                <?php
                $bgs = TidioMaintenance::getBackgrounds();
                foreach ($bgs as $key => $bg) {
                    echo '<a href="javascript:void(0)" data-id="' . ++$key . '" class="e" style="background-image: url(\'' . $bg . '\')"></a>';
                }
                ?>
                <?php
                if (strlen($settings['custom-background']) > 3):
                    ?>
                    <a href="javascript:void(0)" data-id="custom" class="e custom-bg upload-image" style="background-image: <?php echo $settings['custom-background']; ?>"></a>
                    <?php
                else:
                    ?>
                    <a href="javascript:void(0)" data-id="custom" class="e custom-bg upload-image"></a>
                <?php
                endif;
                ?>
                <input type="hidden" name="custom-background"/>
                <input type="hidden" name="background"/>
            </div>
            <hr/>
            <div class="row">
                <label>Countdown:</label>
                <a href="javascript:void(0)" class="switch"></a>
                <input type="hidden" name="countdown_on" value="0"/>
            </div>
            <fieldset>
                <div class="row">
                    <label>Date:</label>
                    <input type="date" name="countdown"/>
                    <input type="time" name="countdown_time"/>
                </div>
            </fieldset>
            <hr/>
            <div class="row">
                <label>Newsletter:</label>
                <a href="javascript:void(0)" class="switch"></a>
                <input type="hidden" name="newsletter_on" value="0"/>
            </div>
            <fieldset>
                <div class="row">
                    <label>Emails:</label>
                    <a class="download-csv" href="//<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>&tidio-maintenance-csv=1">
                        Downoad CSV (<?php echo $emails_count; ?> emails)
                    </a>
                </div>
                <div class="row">
                    <label>Newsletter color:</label>
                    <input type="text" name="newsletter-color" class="minicolors"/>
                </div>
            </fieldset>
            <hr/>
            <div class="row">
                <label>Analytics ID:</label>
                <input type="text" name="google_analytics_code" value="" placeholder="UA-XXXX-Y"/>
            </div>
        </fieldset>
        <hr/>
        <input type="submit" class="btn primary" value="Save"/>
    </form>

</div>
<script>
    var backgroundColors = <?php echo json_encode(TidioMaintenance::getBackgroundColors()); ?>;
</script>