<?php
if ($segment_start):
    echo js_tag('mediaelement', 'javascripts/mediaelement');
    echo js_tag('pfUtils', 'javascripts');
    echo js_tag('jquery', 'javascripts/mediaelement');
    echo js_tag('jquery-ui-1.10.3.custom', 'javascripts/mediaelement');
?>
<div id="vid_player" style="width:100%; margin:0 auto;">
    <div id="mediaelement_plugin" style="margin:0 auto;"><?php echo __('Player failed to load...'); ?></div>
</div>
<script type="text/javascript">
    var is_play = true;
    var startTime= calculateTime(<?php echo json_encode($segment_start); ?>);
    var endTime = calculateTime(<?php echo json_encode($segment_end); ?>);

    mediaelement("mediaelement_plugin").setup({
        playlist:  [{
            sources: <?php echo $sources; ?>
        }],
        <?php if (get_option('videostream_mediaelement_flash_primary')): ?>
        primary: "flash",
        <?php endif;?>
        autostart: false,
        width: "95%",
        height: <?php echo get_option('videostream_mediaelement_height_public'); ?>
    });

    mediaelement("mediaelement_plugin").onReady(function() {
        mediaelement("mediaelement_plugin").seek(startTime);
        <?php if (get_option('videostream_mediaelement_autostart') == 0): ?>
        mediaelement("mediaelement_plugin").pause();
        <?php endif; ?>
    });
</script>
<?php endif;
