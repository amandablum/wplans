<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

$it_options = wd_asp()->o['asp_it_options'];
$_args = array();
foreach ($it_options as $_k => $_opt) {
    $_args[str_replace('it_', '', $_k)] = $_opt;
}
$index_obj = new asp_indexTable($_args);
$pool_sizes = asp_indexTable::suggestPoolSizes(true);

if (ASP_DEMO) {
    $_POST = null;
}

if ( $index_obj->getPostsIndexed() > 0 || ASP_DEMO ) {
    $_COOKIE['_asp_first_index'] = 1;
}

$asp_cron_data = get_option("asp_it_cron", array(
    "last_run" => "",
    "result" => array()
));

$_comp = wpdreamsCompatibility::Instance();
?>
<?php if ( !wd_asp()->db->exists('index', true) ): ?>
    <div id="wpdreams" class='wpdreams wrap'>
        <div class="wpdreams-box">
            <p class="errorMsg">
                <?php echo __('One or more plugin tables are appear to be missing from the database.', 'ajax-search-pro'); ?>
                <?php echo sprintf( __('Please check <a href="%s" target="_blank">this article</a> to resolve the issue.', 'ajax-search-pro'),
                    'https://wp-dreams.com/go/?to=kb-asp-missing-tables' ); ?>
            </p>
            <p class="errorMsg">
                <?php echo __('Please be <strong>very careful</strong>, before making any changes to your database. Make sure to have a full database back-up, just in case!', 'ajax-search-pro'); ?>
            </p>
            <p>
                <fieldset>
                    <legend><?php echo __('Copy this SQL code in your database editor tool to create them manually', 'ajax-search-pro'); ?></legend>
                    <textarea style="width:100%;height:480px;"><?php echo wd_asp()->db->create(); ?></textarea>
                </fieldset>
            </p>
        </div>
    </div>
    <?php return; ?>
<?php endif; ?>
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/options_search.css?v='.ASP_CURR_VER; ?>" />
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/jquery-tagging/tag-basic-style.css?v='.ASP_CURR_VER; ?>" />
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/index-table/index_table.css?v='.ASP_CURR_VER; ?>" />
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/index-table/synonyms.css?v='.ASP_CURR_VER; ?>" />
    <div id="wpdreams" class='wpdreams wrap<?php echo isset($_COOKIE['asp-accessibility']) ? ' wd-accessible' : ''; ?>'>
	<?php if ( wd_asp()->updates->needsUpdate() ): ?>
        <p class='infoMsgBox'>
            <?php echo sprintf( __('Version <strong>%s</strong> is available.', 'ajax-search-pro'),
                wd_asp()->updates->getVersionString() ); ?>
            <?php echo __('Download the new version from Codecanyon.', 'ajax-search-pro'); ?>
            <a target="_blank" href="https://documentation.ajaxsearchpro.com/update_notes.html">
                <?php echo __('How to update?', 'ajax-search-pro'); ?>
            </a>
        </p>
	<?php endif; ?>

        <?php if ( $_comp->has_errors() ): ?>
            <div class="wpdreams-box errorbox">
                <p class='errors'>
                <?php echo sprintf( __('Possible incompatibility! Please go to the
                     <a href="%s">error check</a> page to see the details and solutions!', 'ajax-search-pro'),
                    get_admin_url() . 'admin.php?page=asp_compatibility_settings'
                ); ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="wpdreams-box" style="float:left;">

            <?php ob_start(); ?>

            <!-- TODO Relevanssi table detection -->
            <div tabid="1">
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_title", __('Index titles?', 'ajax-search-pro'),
                        $it_options['it_index_title']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_content", __('Index content?', 'ajax-search-pro'),
                        $it_options['it_index_content']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_excerpt", __('Index excerpt?', 'ajax-search-pro'),
                        $it_options['it_index_excerpt']
                    ); ?>
                </div>
                <div class="item">
                    <?php
                    $o = new wpdreamsCustomPostTypes("it_post_types", __('Post types to index', 'ajax-search-pro'),
                        array(
                            "value"=> $it_options['it_post_types'],
                            "args"=> array(
                                "include" => array("attachment")
                            )
                        ));
                    ?>
                </div>
                <fieldset id="it_file_indexing">
                    <legend><?php echo __('File indexing options', 'ajax-search-pro'); ?></legend>
                    <div class="item">
                        <?php $o = new wd_TextareaExpandable("it_attachment_mime_types", __('Attachment mime types to index', 'ajax-search-pro'),
                            $it_options['it_attachment_mime_types']
                        ); ?>
                        <p class="descMsg">
                            <?php echo __('<strong>Comma separated list</strong> of allowed mime types.', 'ajax-search-pro'); ?>
                            <?php echo sprintf( __('List of <a href="%s" target="_blank">default allowed mime types</a> in WordPress.', 'ajax-search-pro'),
                                'https://codex.wordpress.org/Function_Reference/get_allowed_mime_types' ); ?>
                        </p>
                    </div>
                    <div class="item">
                        <div class="descMsg">
                            <?php echo __('Please note, that reading useful content from media files via PHP is a <strong>very difficult task</strong>.', 'ajax-search-pro'); ?>
                            <?php echo __('The plugin uses external libraries as well as internal methods to get the best results, however it is still possible that some information might not be extracted properly.', 'ajax-search-pro'); ?>
                        </div>
                    </div>
                    <div class="item item-flex-nogrow item-conditional" style="flex-wrap: wrap;">
                        <?php $o = new wpdreamsYesNo("it_index_pdf_content", __('Index PDF file contents?', 'ajax-search-pro'),
                            $it_options['it_index_pdf_content']
                        );
                        $o = new wpdreamsCustomSelect("it_index_pdf_method", __('method', 'ajax-search-pro'),
                            array(
                                'selects' => array(
                                    array("option" => "Auto", "value" => "auto"),
                                    array("option" => "Smalot parser (requires php5.3+)", "value" => "smalot"),
                                    array("option" => "PDF2Txt", "value" => "pdf2txt")
                                ),
                                'value' => $it_options['it_index_pdf_method']
                            )
                        );
                        ?>
                        <div class="descMsg" style="margin-top:4px;min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
                            <?php echo __('When set to \'Auto\', the plugin will try both methods if possible.', 'ajax-search-pro'); ?>
                        </div>
                    </div>
                    <div class="item">
                        <?php $o = new wpdreamsYesNo("it_index_text_content", __('Index Text file contents?', 'ajax-search-pro'),
                            $it_options['it_index_text_content']
                        ); ?>
                    </div>
                    <div class="item">
                        <?php $o = new wpdreamsYesNo("it_index_richtext_content", __('Index RichText file contents?', 'ajax-search-pro'),
                            $it_options['it_index_richtext_content']
                        ); ?>
                    </div>
                    <?php if( !class_exists('ZipArchive') ): ?>
                        <div class="errorMsg">NOTICE: The <a href="https://www.google.sk/search?q=enable%20ZipArchive%20php" target="_blank">ZipArchive</a> module is not enabled on your server. The Office document parsers will not work without it!</div>
                    <?php endif; ?>
                    <?php if( !class_exists('DOMDocument') ): ?>
                        <div class="errorMsg">NOTICE: The <a href="https://www.google.sk/search?q=enable%20DOMDocument%20php" target="_blank">DOMDocument</a> module is not enabled on your server. The Office document parsers will not work without it!</div>
                    <?php endif; ?>
                    <div class="item">
                        <?php $o = new wpdreamsYesNo("it_index_msword_content", __('Index Office Word document contents?', 'ajax-search-pro'),
                            $it_options['it_index_msword_content']
                        ); ?>
                    </div>
                    <div class="item">
                        <?php $o = new wpdreamsYesNo("it_index_msexcel_content", __('Index Office Excel document contents?', 'ajax-search-pro'),
                            $it_options['it_index_msexcel_content']
                        ); ?>
                    </div>
                    <div class="item">
                        <?php $o = new wpdreamsYesNo("it_index_msppt_content", __('Index Office PowerPoint document contents?', 'ajax-search-pro'),
                            $it_options['it_index_msppt_content']
                        ); ?>
                    </div>
                    <div class="wd-hint">
                        <?php echo __('<p>These options are hidden unless the <strong>attachment</strong> custom post type is selected above.</p>', 'ajax-search-pro'); ?>
                    </div>
                </fieldset>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_tags", __('Index post tags?', 'ajax-search-pro'),
                        $it_options['it_index_tags']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_categories", __('Index post categories?', 'ajax-search-pro'),
                        $it_options['it_index_categories']
                    ); ?>
                </div>
                <div class="item">
                    <?php
                    $o = new wpdreamsTaxonomySelect("it_index_taxonomies", __('Index taxonomies', 'ajax-search-pro'), array(
                        "value" => $it_options['it_index_taxonomies'],
                        "type" => "include"
                    ));
                    ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_permalinks", __('Index permalinks?', 'ajax-search-pro'),
                        $it_options['it_index_permalinks']
                    ); ?>
                </div>
                <div class="item"><?php
                    $o = new wpdreamsCustomFields("it_index_customfields", __('Index custom fields', 'ajax-search-pro'),
                        array(
                                "value" => $it_options['it_index_customfields'],
                            "show_pods" => true
                        )
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsText("it_post_statuses", __('Post statuses to index', 'ajax-search-pro'),
                        $it_options['it_post_statuses']
                    ); ?>
                    <p class="descMsg">
                        <?php echo __('Comma separated list. WP Defaults: publish, future, draft, pending, private, trash, auto-draft', 'ajax-search-pro'); ?>
                    </p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_author_name", __('Index post author name?', 'ajax-search-pro'),
                        $it_options['it_index_author_name']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_author_bio", __('Index post author bio (description)?', 'ajax-search-pro'),
                        $it_options['it_index_author_bio']
                    ); ?>
                </div>
            </div>
            <div tabid="2">
                <div class="item"><?php
                    $o = new wpdreamsBlogselect("it_blog_ids", __('Blogs to index posts from', 'ajax-search-pro'),
                        $it_options['it_blog_ids']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsTextSmall("it_limit", __('Post limit per iteration', 'ajax-search-pro'),
                        $it_options['it_limit']
                    ); ?>
                    <p class="descMsg"><?php echo __('Posts to index per ajax call. Reduce this number if the process fails. Default: 25', 'ajax-search-pro'); ?></p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_use_stopwords", __('Enable stop-words?', 'ajax-search-pro'),
                        $it_options['it_use_stopwords']
                    ); ?>
                    <p class="descMsg"><?php echo __('Words from the list below (common words, stop words) will be excluded if enabled.', 'ajax-search-pro'); ?></p>
                </div>
                <div class="item">
                    <?php $o = new wd_TextareaExpandable("it_stopwords", __('Stop words list', 'ajax-search-pro'),
                        $it_options['it_stopwords']
                    ); ?>
                    <p class="descMsg"><?php echo __('<strong>Comma</strong> separated list of stop words.', 'ajax-search-pro'); ?></p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsTextSmall("it_min_word_length", __('Min. word length', 'ajax-search-pro'),
                        $it_options['it_min_word_length']
                    ); ?>
                    <p class="descMsg"><?php echo __('Words below this length will be ignored. Default: 2', 'ajax-search-pro'); ?></p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_extract_iframes", __('Extract IFRAME contents?', 'ajax-search-pro'),
                        $it_options['it_extract_iframes']
                    ); ?>
                    <p class="descMsg"><?php echo __('Will try parsing IFRAME sources and extracting them. This <strong>may not work</strong> in some cases.', 'ajax-search-pro'); ?></p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_extract_shortcodes", __('Execute shortcodes?', 'ajax-search-pro'),
                        $it_options['it_extract_shortcodes']
                    ); ?>
                    <p class="descMsg"><?php echo __('Will execute shortcodes in content as well. Great if you have lots of content generated by shortcodes.', 'ajax-search-pro'); ?></p>
                </div>
                <div class="item">
                    <?php $o = new wd_TextareaExpandable("it_exclude_shortcodes", __('Remove these shortcodes', 'ajax-search-pro'),
                        $it_options['it_exclude_shortcodes']
                    ); ?>
                    <p class="descMsg">
                        <?php echo __('<strong>Comma</strong> separated list of shortcodes to remove. Use this to exclude shortcodes, which does not reflect your content appropriately.', 'ajax-search-pro'); ?>
                    </p>
                </div>
            </div>
            <div tabid="4">
                <fieldset>
                    <legend>Pool sizes</legend>
                    <div class="errorMsg">
                        <?php echo __('The pool size greatly affects the search performance in bigger databases (50k+ keywords). While high pool values may give more accurate results, lower values cause much better performance.', 'ajax-search-pro'); ?>
                    </div>
                    <div class="item">
                        <?php $o = new wpdreamsYesNo("it_pool_size_auto", __('Let the plugin determine the pool size values?', 'ajax-search-pro'),
                            $it_options['it_pool_size_auto']
                        ); ?>
                        <p class="descMsg">
                            <?php echo __('When enabled (default), the plugin will adjust these values depending on the index table size and other factors.', 'ajax-search-pro'); ?>
                        </p>
                    </div>
                    <div class="item it_pool_size">
                        <?php $o = new wpdreamsTextSmall("it_pool_size_one", "Pool size for keywords of one character long (recommended: <strong>".$pool_sizes['one']."</strong>)",
                            $it_options['it_pool_size_one']
                        ); ?>
                        <p class="descMsg">
                            <?php echo __('The maximum number in a sub-set of results pool for a search phrase (or part of the phrase) that is one character long.', 'ajax-search-pro'); ?>
                        </p>
                    </div>
                    <div class="item it_pool_size">
                        <?php $o = new wpdreamsTextSmall("it_pool_size_two", "Pool size for keywords of two characters long (recommended: <strong>".$pool_sizes['two']."</strong>)",
                            $it_options['it_pool_size_two']
                        ); ?>
                        <p class="descMsg">
                            <?php echo __('The maximum number in a sub-set of results pool for a search phrase (or part of the phrase) that is one character long.', 'ajax-search-pro'); ?>
                        </p>
                    </div>
                    <div class="item it_pool_size">
                        <?php $o = new wpdreamsTextSmall("it_pool_size_three", "Pool size for keywords of three characters long (recommended: <strong>".$pool_sizes['three']."</strong>)",
                            $it_options['it_pool_size_three']
                        ); ?>
                        <p class="descMsg">
                            <?php echo __('The maximum number in a sub-set of results pool for a search phrase (or part of the phrase) that is one character long.', 'ajax-search-pro'); ?>
                        </p>
                    </div>
                    <div class="item it_pool_size">
                        <?php $o = new wpdreamsTextSmall("it_pool_size_rest", "Pool size for keywords of four and more characters long (recommended: <strong>".$pool_sizes['rest']."</strong>)",
                            $it_options['it_pool_size_rest']
                        ); ?>
                        <p class="descMsg">
                            <?php echo __('The maximum number in a sub-set of results pool for a search phrase (or part of the phrase) that is one character long.', 'ajax-search-pro'); ?>
                        </p>
                    </div>
                </fieldset>
            </div>
            <div tabid="3">
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_on_save", "Index new posts upon creation?",
                        $it_options['it_index_on_save']
                    ); ?>
                    <p class="descMsg">
                        <?php echo __('When turned OFF, the posts will still be indexed only upon updating, or when the cron-job runs (if enabled) or when the index table is extended manually.', 'ajax-search-pro'); ?>
                    </p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_cron_enable", "Use wp_cron() to extend the index table automatically?",
                        $it_options['it_cron_enable']
                    ); ?>
                    <p class="descMsg">
                        <?php echo __('Will register a cron job with wp_cron() and run it periodically.', 'ajax-search-pro'); ?>
                    </p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsCustomSelect("it_cron_period", "Period",
                        array(
                            'selects' => array(
                                array("option" => esc_attr__('Every 2 minutes', 'ajax-search-pro'), "value" => "asp_cr_two_minutes"),
                                array("option" => esc_attr__('Every 3 minutes', 'ajax-search-pro'), "value" => "asp_cr_three_minutes"),
                                array("option" => esc_attr__('Every 5 minutes', 'ajax-search-pro'), "value" => "asp_cr_five_minutes"),
                                array("option" => esc_attr__('Every 15 minutes', 'ajax-search-pro'), "value" => "asp_cr_fifteen_minutes"),
                                array("option" => esc_attr__('Every 30 minutes', 'ajax-search-pro'), "value" => "asp_cr_thirty_minutes"),
                                array("option" => esc_attr__('Hourly', 'ajax-search-pro'), "value" => "hourly"),
                                array("option" => esc_attr__('Twice Daily', 'ajax-search-pro'), "value" => "twicedaily"),
                                array("option" => esc_attr__('Daily', 'ajax-search-pro'), "value" => "daily")
                            ),
                            'value' => $it_options['it_cron_period']
                        )
                    ); ?>
                    <p class="descMsg">
                        <?php echo __('The periodicity of execution. wp_cron() only accepts these values.', 'ajax-search-pro'); ?>
                    </p>
                </div>
                <div class="item">
                    <fieldset class="asp-last-execution-info">
                        <legend>Last execution info</legend>
                        <ul style="float:right;text-align:left;width:50%;">
                            <li>
                                <b><?php echo __('Last exeuction time:', 'ajax-search-pro'); ?> </b><?php echo $asp_cron_data['last_run'] != "" ? date("H:i:s, F j. Y", $asp_cron_data['last_run']) : "No information."; ?>
                            </li>
                            <li>
                                <b><?php echo __('Current system time:', 'ajax-search-pro'); ?> </b><?php echo date("H:i:s, F j. Y", time()); ?></li>
                            <li>
                                <b><?php echo __('Posts indexed:', 'ajax-search-pro'); ?> </b><?php echo w_isset_def($asp_cron_data['result']['postsIndexedNow'], "No information."); ?>
                            </li>
                            <li><b><?php echo __('Keywords found:', 'ajax-search-pro'); ?> </b><?php echo w_isset_def($asp_cron_data['result']['keywordsFound'], "No information."); ?>
                            </li>
                        </ul>
                    </fieldset>
                </div>
            </div>
            <div tabid="5">
                <?php include(ASP_PATH . "backend/tabs/index_table/synonyms.php"); ?>
            </div>
            <?php $_r = ob_get_clean(); ?>

            <?php
            $updated = false;
            if (isset($_POST) && isset($_POST['submit_asp_index_options']) && (wpdreamsType::getErrorNum() == 0)) {
                $values = array(
                    'it_index_title' => $_POST['it_index_title'],
                    'it_index_content' => $_POST['it_index_content'],
                    'it_index_excerpt' => $_POST['it_index_excerpt'],
                    'it_post_types' => $_POST['it_post_types'],
                    'it_index_tags' => $_POST['it_index_tags'],
                    'it_index_categories' => $_POST['it_index_categories'],
                    'it_index_taxonomies' => $_POST['it_index_taxonomies'],

                    'it_attachment_mime_types' => $_POST['it_attachment_mime_types'],
                    'it_index_pdf_content' => $_POST['it_index_pdf_content'],
                    'it_index_pdf_method' => $_POST['it_index_pdf_method'],
                    'it_index_text_content' => $_POST['it_index_text_content'],
                    'it_index_richtext_content' => $_POST['it_index_richtext_content'],
                    'it_index_msword_content' => $_POST['it_index_msword_content'],
                    'it_index_msexcel_content' => $_POST['it_index_msexcel_content'],
                    'it_index_msppt_content' => $_POST['it_index_msppt_content'],

                    'it_index_customfields' => $_POST['it_index_customfields'],
                    'it_post_statuses' => $_POST['it_post_statuses'],
                    'it_index_author_name' => $_POST['it_index_author_name'],
                    'it_index_author_bio' => $_POST['it_index_author_bio'],
                    'it_blog_ids' => $_POST['it_blog_ids'],
                    'it_limit' => $_POST['it_limit'],
                    'it_use_stopwords' => $_POST['it_use_stopwords'],
                    'it_stopwords' => $_POST['it_stopwords'],
                    'it_min_word_length' => $_POST['it_min_word_length'],
                    'it_extract_iframes' => $_POST['it_extract_iframes'],
                    'it_extract_shortcodes' => $_POST['it_extract_shortcodes'],
                    'it_exclude_shortcodes' => $_POST['it_exclude_shortcodes'],
                    'it_index_on_save' => $_POST['it_index_on_save'],
                    'it_cron_enable' => $_POST['it_cron_enable'],
                    'it_cron_period' => $_POST['it_cron_period'],
                    'it_pool_size_auto' => $_POST['it_pool_size_auto'],
                    'it_pool_size_one' => $_POST['it_pool_size_one'],
                    'it_pool_size_two' => $_POST['it_pool_size_two'],
                    'it_pool_size_three' => $_POST['it_pool_size_three'],
                    'it_pool_size_rest' => $_POST['it_pool_size_rest'],
                    'it_synonyms_as_keywords' => $_POST['it_synonyms_as_keywords']
                );
                update_option('asp_it_options', $values);
                wp_clear_scheduled_hook('asp_cron_it_extend');
                asp_parse_options();
                $updated = true;
                update_option("asp_recreate_index", 1);
            }
            ?>
            <div class='wpdreams-slider'>

                <?php if ($updated): ?>
                    <div class='errorMsg asp-notice-ri'>
                        <?php echo __('The options have changed, don\'t forget to re-create the index table with the <b>Create new index</b> button!', 'ajax-search-pro'); ?>
                    </div>
                <?php endif; ?>

                <?php if (ASP_DEMO): ?>
                    <p class="infoMsg">DEMO MODE ENABLED - Please note, that these options are read-only on the demo</p>
                <?php endif; ?>

                <form name='asp_indextable_settings' id='asp_indextable_settings' class="asp_indextable_settings"
                      method='post'>

                    <fieldset>
                        <legend><?php echo __('Index Table Operations', 'ajax-search-pro'); ?></legend>
                        <div id="index_buttons" style="margin: 0 0 15px 0;">
                            <input type="button" name="asp_index_new" id="asp_index_new" class="submit wd_button_green"
                                   index_action='new' index_msg='<?php echo esc_attr__('Do you want to generate a new index table?', 'ajax-search-pro'); ?>'
                                   value="<?php echo esc_attr__('Create new index', 'ajax-search-pro'); ?>">
                            <input type="button" name="asp_index_extend" id="asp_index_extend"
                                   class="submit wd_button_blue"
                                   index_action='extend' index_msg='<?php echo esc_attr__('Do you want to extend the index table?', 'ajax-search-pro'); ?>'
                                   value="<?php echo esc_attr__('Continue existing index', 'ajax-search-pro'); ?>">
                            <input type="button" name="asp_index_delete" id="asp_index_delete" class="submit"
                                   index_action='delete' index_msg='<?php echo esc_attr__('Do you really want to empty the index table?', 'ajax-search-pro'); ?>'
                                   value="<?php echo esc_attr__('Delete the index', 'ajax-search-pro'); ?>">
                        </div>
                        <div class="wd_progress_text hiddend"><?php echo __('Initializing, please wait. This might take a while.', 'ajax-search-pro'); ?></div>
                        <div class="wd_progress wd_progress_75 hiddend"><span style="width:0%;"></span></div>
                        <span class="wd_progress_stop hiddend"><?php echo __('Stop', 'ajax-search-pro'); ?></span>

                        <div id='asp_i_success' class="infoMsg hiddend"><?php echo __('100% - Index table successfully generated!', 'ajax-search-pro'); ?></div>
                        <div id='asp_i_error' class="errorMsg hiddend"><?php echo __('Something went wrong :(', 'ajax-search-pro'); ?></div>
                        <textarea id="asp_i_error_cont" class="hiddend"></textarea>

                        <p class="descMsg">
                            <?php echo sprintf( __('To read more about the index table, please read the <a href="%s">documentation chapter about Index table</a> usage.', 'ajax-search-pro'),
                                'https://documentation.ajaxsearchpro.com/index_table.html' ); ?>
                        </p>
                        <?php if (is_multisite()): ?>
                            <p class="descMsg" style="color:#666; ">
                                <?php echo __('Total keywords:', 'ajax-search-pro'); ?> <b
                                    id="keywords_counter"><?php echo $index_obj->getTotalKeywords(); ?></b>
                            </p>
                        <?php else: ?>
                            <p class="descMsg" style="color:#666; ">
                                <?php echo __('Items Indexed:', 'ajax-search-pro'); ?> <b id="indexed_counter"><?php echo $index_obj->getPostsIndexed(); ?></b>
                                &nbsp;|&nbsp;<?php echo __('Items not indexed:', 'ajax-search-pro'); ?> <b
                                    id="not_indexed_counter"><?php echo $index_obj->getPostIdsToIndexCount(); ?></b>
                                &nbsp;|&nbsp;<?php echo __('Total keywords:', 'ajax-search-pro'); ?> <b
                                    id="keywords_counter"><?php echo $index_obj->getTotalKeywords(); ?></b>
                            </p>
                            <p id='index_db_other_data' style="display:none !important;">Ignored: <?php echo $index_obj->getIgnoredList(true); ?></p>
                        <?php endif; ?>
                    </fieldset>

                    <fieldset id='asp_indextable_options'>
                        <div id="asp_it_disable" class="hiddend"></div>

                        <legend><?php echo __('Index Table options', 'ajax-search-pro'); ?></legend>

                        <?php if ($updated): ?>
                            <div class='infoMsg'><?php echo __('Index table options successfuly updated!', 'ajax-search-pro'); ?></div><?php endif; ?>

                        <ul id="tabs" class='tabs'>
                            <li><a tabid="1" class='current general'><?php echo __('General', 'ajax-search-pro'); ?></a></li>
                            <li><a tabid="2" class='advanced'><?php echo __('Advanced', 'ajax-search-pro'); ?></a></li>
                            <li><a tabid="5" class='advanced'><?php echo __('Synonyms', 'ajax-search-pro'); ?></a></li>
                            <li><a tabid="3" class='advanced'><?php echo __('Indexing & Cron', 'ajax-search-pro'); ?></a></li>
                            <li><a tabid="4" class='advanced'><?php echo __('Performance & Accuracy', 'ajax-search-pro'); ?></a></li>
                        </ul>
                        <div class='tabscontent'>
                            <?php print $_r; ?>
                        </div>
                        <input type='hidden' name='asp_index_table_page' value='1'/>

                        <div class="item">
                            <input name="submit_asp_index_options" type="submit" value="<?php echo esc_attr__('Save options', 'ajax-search-pro'); ?>"/>
                        </div>
                    </fieldset>
                </form>

            </div>
        </div>

        <div id="asp-options-search">
            <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asp-accessibility']) ?
                    __('DISABLE ACCESSIBILITY', 'ajax-search-pro') :
                    __('ENABLE ACCESSIBILITY', 'ajax-search-pro'); ?></a>
        </div>
        <div class="clear"></div>
    </div>

<div class="hiddend">
    <div id="it_first_modal">
        <p><?php echo __("The index table had been created, so don't forget to enable it on the search instances, where you need it.", 'ajax-search-pro'); ?></p>
        <p>
            <?php echo __("If you don't know how to do that, these documentations will help (links open in new tab):", 'ajax-search-pro'); ?>
            <ul>
                <li><?php echo __("Enabling for", 'ajax-search-pro'); ?>&nbsp;
                    <a href="https://documentation.ajaxsearchpro.com/index-table/enabling-index-table-engine" target="_blank">
                        <?php echo __("Custom Post Types", 'ajax-search-pro'); ?>
                    </a>
                </li>
                <li><?php echo __("Enabling for", 'ajax-search-pro'); ?>&nbsp;
                    <a href="https://documentation.ajaxsearchpro.com/general-settings/search-in-attachment-contents-pdf-word-excel-etc..#step-2-search-instance-configuration" target="_blank">
                        <?php echo __("Attachments", 'ajax-search-pro'); ?>
                    </a>
                </li>
            </ul>
        </p>
    </div>
</div>
<?php
$media_query = ASP_DEBUG == 1 ? asp_gen_rnd_str() : get_option("asp_media_query", "defn");
wp_enqueue_script('asp-backend-synonyms', plugin_dir_url(__FILE__) . 'settings/assets/index-table/synonyms.js', array(
    'jquery'
), $media_query, true);
wp_localize_script('asp-backend-synonyms', 'ASP_SYN_MSG', array(
    "gen_ms1" => __('Success:', 'ajax-search-pro'),
    "gen_ms2" => __('items were imported!', 'ajax-search-pro'),
    "gen_er1" => __('Something went wrong, please try again later.', 'ajax-search-pro'),
    "gen_er2" => __('There are no synonyms in the database to export.', 'ajax-search-pro'),
    "gen_er3" => __('The file is empty or invalid. Please make sure to upload and choose the correct one.', 'ajax-search-pro'),
    "gen_er4" => __('Nothing was imported. The items in this file are already in the database.', 'ajax-search-pro'),
    "edt_er1" => __('Something went wrong, please check your connection, and try again.', 'ajax-search-pro'),
    "edt_er2" => __('This keyword already exists in the database! (use the search above if you look to edit it)', 'ajax-search-pro'),
    "edt_er3" => __('The keyword was not deleted, please try refreshing this page!', 'ajax-search-pro'),
    "edt_er4" => __('The keyword field is empty, please enter a keyword!', 'ajax-search-pro'),
    "edt_er5" => __('The synonyms field is missing, please enter some synonyms!', 'ajax-search-pro'),
    "res_ms1" => __('Are you sure you want to delete this keyword?', 'ajax-search-pro'),
    "del_all" => __('Are you sure you want to delete all of the synonyms?', 'ajax-search-pro'),
    "mod_ms1" => __('Export Synonyms', 'ajax-search-pro'),
    "mod_ms2" => __('Import Synonyms', 'ajax-search-pro'),
    "mod_ms3" => __('Close', 'ajax-search-pro')
));
wp_enqueue_script('asp-backend-index-table', plugin_dir_url(__FILE__) . 'settings/assets/index_table.js', array(
    'jquery', 'wpdreams-tabs'
), $media_query, true);
wp_localize_script('asp-backend-index-table', 'ASP_IT', array(
    "current_blog_id" => array(get_current_blog_id()),
    "first_index" => $index_obj->getPostsIndexed() == 0 && !isset($_COOKIE['_asp_first_index']) ? 1 : 0
));
wp_localize_script('asp-backend-index-table', 'ASP_IT_MSG', array(
    "mod_ms1" => __('Okay!', 'ajax-search-pro'),
    "mod_h1" => __('Congratulations, but wait!', 'ajax-search-pro'),
    "msg_pro" => __('Progress:', 'ajax-search-pro'),
    "msg_kwf" => __('Keywords found so far:', 'ajax-search-pro'),
    "msg_blo" => __('Processing blog no.', 'ajax-search-pro'),
    "msg_skw" => __('Success. <strong>%s</strong> new keywords were added to the database.', 'ajax-search-pro'),
    "msg_emp" => __('Success. The index table was emptied.', 'ajax-search-pro'),
    "msg_er1" => __('Something went wrong. Here is the error message returned:', 'ajax-search-pro'),
    "msg_er2" => __('Timeout error. Try lowering the <strong>Post limit per iteration</strong> option below.', 'ajax-search-pro'),
    "msg_sta" => __('Status:', 'ajax-search-pro'),
    "msg_cod" => __('Code:', 'ajax-search-pro'),
    "msg_ini" => __('Initializing, please wait.', 'ajax-search-pro')
));
wp_enqueue_script('asp-backend-jquery-tag', plugin_dir_url(__FILE__) . 'settings/assets/jquery-tagging/tagging.min.js', array(
    'jquery'
), $media_query, true);