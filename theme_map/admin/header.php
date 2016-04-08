<style type="text/css" media="screen">
    .command { background-color: white; color: #2E2E2E; border: 1px solid black; padding: 8px; }
    .theme-files { min-width: 500px; }
</style>
<h2 class="render-title"><?php _e('Header logo', 'theme_map'); ?></h2>

<?php if( !is_writable( WebThemes::newInstance()->getCurrentThemePath() . "images/") && (!defined('MULTISITE') || MULTISITE==0)) { ?>
    <div class="flashmessage flashmessage-error" style="display: block;">
        <p>
            <?php
                $msg  = sprintf(__('The images folder <strong>%s</strong> is not writable on your server', 'theme_map'), WebThemes::newInstance()->getCurrentThemePath() ."images/" ) .", ";
                $msg .= __("OSClass can't upload the logo image from the administration panel.", 'theme_map') . ' ';
                $msg .= __("Please make the aforementioned image folder writable.", 'theme_map') . ' ';
                echo $msg;
            ?>
        </p>
        <p>
            <?php _e('To make a directory writable under UNIX execute this command from the shell:','theme_map'); ?>
        </p>
        <p class="command">
            chmod a+w <?php echo WebThemes::newInstance()->getCurrentThemePath() ."images/" ; ?>
        </p>
    </div>
<?php } ?>

    <?php if(file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ||
                file_exists( osc_uploads_path() . "logo.jpg") ) { ?>
        <h3 class="render-title"><?php _e('Preview', 'theme_map') ?></h3>
        <?php echo logo_header(); ?>
        <form action="<?php echo osc_admin_render_theme_url('oc-content/themes/theme_map/admin/header.php');?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action_specific" value="remove" />
            <fieldset>
                <div class="form-horizontal">
                    <div class="form-actions">
                        <input id="button_remove" type="submit" value="<?php echo osc_esc_html(__('Remove logo','theme_map')); ?>" class="btn btn-red">
                    </div>
                </div>
            </fieldset>
        </form>
    </p>
    <?php } else { ?>
        <div class="flashmessage flashmessage-warning flashmessage-inline" style="display: block;">
            <p><?php _e("No logo has been uploaded yet", 'theme_map'); ?></p>
        </div>
    <?php } ?>
    <h2 class="render-title separate-top"><?php _e('Upload logo', 'theme_map') ?></h2>
    <p>
        <?php _e('The preferred size of the logo is 600x100.', 'theme_map'); ?>
        <?php if( file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ) { ?>
        <?php _e('<strong>Note:</strong> Uploading another logo will overwrite the current logo.', 'theme_map'); ?>
        <?php } ?>
    </p>
    <form action="<?php echo osc_admin_render_theme_url('oc-content/themes/theme_map/admin/header.php'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action_specific" value="upload_logo" />
        <fieldset>
            <div class="form-horizontal">
                <div class="form-row">
                    <div class="form-label"><?php _e('Logo image (png,gif,jpg)','theme_map'); ?></div>
                    <div class="form-controls">
                        <input type="file" name="logo" id="package" />
                    </div>
                </div>
                <div class="form-actions">
                    <input id="button_save" type="submit" value="<?php echo osc_esc_html(__('Upload','theme_map')); ?>" class="btn btn-submit">
                </div>
            </div>
        </fieldset>
    </form>
