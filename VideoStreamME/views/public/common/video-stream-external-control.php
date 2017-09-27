<?php
if ($segment_start):
    echo js_tag('mediaelement', 'javascripts/mediaelement');
    echo js_tag('pfUtils', 'javascripts');
    echo js_tag('jquery', 'javascripts/mediaelement');
    echo js_tag('jquery-ui-1.10.3.custom', 'javascripts/mediaelement');
?>
<div id="vid_player">
    <div id="mediaelement_plugin"><?php echo __('Player failed to load...'); ?></div>
    <div id="vidcontrols">
        <ul class="vidControlsLayout" style="width='<?php echo get_option('videostream_mediaelement_width_public');?>'">
            <li id="start_img"><img src="<?php echo img('pause.png'); ?>" title="Start/Stop" class="btnPlay" /></li>
            <li id="playback-display"><span class="current">0:00:00</span></li>
            <li class="progressBar"></li>
            <li id="slider-display"><span class="duration">0:00:00</span> </li>
            <li id="vol_img"><img class="muted" src="<?php echo img('volume_speaker.png'); ?>" /></li>
            <li class="volumeBar"></li>
        </ul>
    </div>
    <script type="text/javascript">
        var is_play = true;
        var startTime= calculateTime(<?php echo json_encode($segment_start); ?>);
        var endTime = calculateTime(<?php echo json_encode($segment_end); ?>);

        mediaelement("mediaelement_plugin").setup({
            playlist: [{
                sources: <?php echo $sources; ?>
            }],
            <?php if (get_option('videostream_mediaelement_flash_primary')): ?>
            primary: "flash",
            <?php endif; ?>
            autostart: false,
            controls: false,
            width: "100%",
            height: <?php echo json_encode(get_option('videostream_mediaelement_height_public')); ?>
        });

        mediaelement("mediaelement_plugin").onReady(function() {
            jQuery('.current').text(getFormattedTimeString(startTime));
            jQuery('.duration').text(getFormattedTimeString(endTime));
            mediaelement("mediaelement_plugin").seek(startTime);
            <?php if (get_option('videostream_mediaelement_autostart') == 0): ?>
            mediaelement("mediaelement_plugin").pause();
            <?php endif; ?>
        });

        jQuery( ".progressBar" ).slider({
            min: startTime,
            max: endTime,
            range: "max",
            slide: function(event, ui) {
                mediaelement().seek(ui.value);
            },
            change: function(event,ui) {
                if (mediaelement().getPosition() > endTime) {
                    mediaelement().seek(startTime);
                }
            }
        });

        jQuery( ".volumeBar" ).slider({
            min: 0,
            max: 100,
            range: "max",
            slide: function(event, ui) {
                mediaelement().setVolume(ui.value);
            },
            change: function(event,ui) {
                mediaelement().setVolume(ui.value);
            }
        });

        mediaelement("mediaelement_plugin").onTime(function(event) {
            jQuery(".progressBar").slider("value", mediaelement("mediaelement_plugin").getPosition());
            jQuery('.current').text(getFormattedTimeString(mediaelement("mediaelement_plugin").getPosition()));
        });

        mediaelement("mediaelement_plugin").onPlay(function() {
            jQuery('.btnPlay').attr("src", "<?php echo img('pause.png'); ?>");
        });

        mediaelement("mediaelement_plugin").onPause(function() {
            jQuery('.btnPlay').attr("src", "<?php echo img('play.png'); ?>");
        });

        mediaelement("mediaelement_plugin").onMute(function(event) {
            if (event.mute) {
                jQuery('.muted').attr("src", "<?php echo img('volume_speaker_mute.png'); ?>");
            } else {
                jQuery('.muted').attr("src", "<?php echo img('volume_speaker.png'); ?>");
            }
        });

        mediaelement("mediaelement_plugin").onVolume(function(event) {
            if (event.volume <= 0 ) {
                jQuery('.muted').attr("src", "<?php echo img('volume_speaker_mute.png'); ?>");
            } else {
                jQuery('.muted').attr("src", "<?php echo img('volume_speaker.png'); ?>");
            }
        });

        jQuery('.btnPlay').on('click', function() {
            if (mediaelement().getPosition() > endTime) {
                mediaelement().seek(startTime);
            }
            mediaelement().play();
            return false;
        });

        jQuery('.btnStop').on('click', function() {
            mediaelement().stop();
            mediaelement().seek(startTime);
            jQuery(".progressBar").slider("value", mediaelement().getPosition());
            jQuery('.current').text(getFormattedTimeString(mediaelement().getPosition()));
            return false;
        });

        jQuery('.muted').click(function() {
            mediaelement().setMute();
            return false;
        });

        jQuery('#vid_player')[0].onmouseover = (function() {
            var onmousestop = function() {
                jQuery('#vidcontrols').css('display', 'none');
            }, thread;

            return function() {
                jQuery('#vidcontrols').css('display', 'block');
                clearTimeout(thread);
                thread = setTimeout(onmousestop, 3000);
            };
        })();

        jQuery('#vid_player')[0].onmousedown = (function() {
            var moveend = function() {
                jQuery('#vidcontrols').css('display', 'none');
            }, thread;

            return function() {
                jQuery('#vidcontrols').css('display', 'block');
                clearTimeout(thread);
                thread = setTimeout(moveend, 3000);
            };
        })();
    </script>
</div>
<?php endif;
