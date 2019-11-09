<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

/**
 * This is printed as the group footer - only works with vertical results
 *
 * !!!IMPORTANT!!!
 * Do not make changes directly to this file! To have permanent changes copy this
 * file to your theme directory under the "asp" folder like so:
 *    wp-content/themes/your-theme-name/asp/group-footer.php
 *
 * You can use any WordPress function here.
 * Variables to mention:
 *      String $group_name - the group name (including post count)
 *      Array[]  $s_options - holding the search options
 *
 * You can leave empty lines for better visibility, they are cleared before output.
 *
 * MORE INFO: https://wp-dreams.com/knowledge-base/result-templating/
 *
 * @since: 4.0
 */
?>
<div class="item asp_r_pagepost asp_r_pagepost_320 asp_r_post additional">
    <div class="asp_content">
        <h3>Didn't find your answer? <br><span class="boldred"><a href="https://howlingzoeproductions.com/warrenplans/ask-a-question/">Click here to submit your question and we'll have a volunteer policy expert answer it.  ></a></span></h3>
        <div class="asp_res_text">
                </div>
    </div>
    <div class="clear"></div>
</div>