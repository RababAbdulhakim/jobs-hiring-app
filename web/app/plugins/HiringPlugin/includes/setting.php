<?php
function custom_plugin_register_settings()
{
    register_setting("custom_plugin_options_group", "records_nums");
}

add_action("admin_init", "custom_plugin_register_settings");
function custom_plugin_setting_page()
{

    add_options_page('Jobs Vacancy Setting', 'Jobs Vacancy Setting', 'manage_options', 'custom-plugin-setting-url', 'custom_page_html_form');
}

add_action('admin_menu', 'custom_plugin_setting_page');

function custom_page_html_form()
{
    ?>
    <div class="wrap">
        <h2>Custom Plugin Setting Page Heading</h2>
        <form method="post" action="options.php">
            <?php settings_fields('custom_plugin_options_group'); ?>

            <table class="form-table">
                <tr>
                    <th><label for="records_nums">allowed records to display</label></th>

                    <td>
                        <input type='number' id="records_nums" name="records_nums"
                               value="<?php echo get_option('records_nums'); ?>">
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>

    </div>
<?php } ?>
