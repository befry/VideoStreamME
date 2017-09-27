<h3>Public Theme</h3>
<label style="font-weight:bold;" for="mediaelement_width_public">Viewer width, in pixels:</label>
<p><?php echo get_view()->formText('mediaelement_width_public', 
                              get_option('mediaelement_width_public'), 
                              array('size' => 5));?></p>
<label style="font-weight:bold;" for="mediaelement_height_public">Viewer height, in pixels:</label>
<p><?php echo get_view()->formText('mediaelement_height_public', 
                              get_option('mediaelement_height_public'), 
                              array('size' => 5));?></p>
<label style="font-weight:bold;" for="mediaelement_external_control">Display Specific Segment Using External Controls</label>
<ul style="list-style-type:none;">
<li>Use External Controls?&nbsp<?php
echo get_view()->formCheckbox('mediaelement_external_control', null, array('checked' => get_option('mediaelement_external_control')));?></li>
</ul>
<p class="explanation">Whether the mediaelement plugin should use external controls instead of the builtin player controls. You should check this option if you want to restrict access to the specific video segment represented by the item on display. This option does not allow the user to scrub beyond the start and stop points for the video segment</p>
<label style="font-weight:bold;" for="mediaelement_display_current">Display information about currently playing video segment</label>
<ul style="list-style-type:none;">
<li>Display Current?&nbsp<?php 
echo get_view()->formCheckbox('mediaelement_display_current',null, array('checked' => (get_option('mediaelement_display_current'))));?></li>
</ul>
<p class="explanation">Whether the mediaelement plugin should display information about the current video segment. Use this option with or without external controls to see information about the current video segment appear below the video player. This information may be different from the current item being displayed because it is based on where you are in the video file.</p>

<label style="font-weight:bold;" for="mediaelement_tuning">Should the user be able to use the Segment Tuning Panel in Omeka Admin?</label>
<ul style="list-style-type:none;">
<li>Turn off Segment Tuning Panel?&nbsp<?php
echo get_view()->formCheckbox('mediaelement_tuning',null, array('checked' => (get_option('mediaelement_tuning'))));?></li>
</ul>
<p class="explanation">Whether the Segment Tuning Panel should be available when editing the video segment item in Administration.</p>
