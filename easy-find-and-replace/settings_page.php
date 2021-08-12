<?php
// create custom plugin settings menu
add_action('admin_menu', 'easy_find_and_replace_create_menu');

function easy_find_and_replace_create_menu()
{
    //create new top-level menu
    add_menu_page('easy find and replace Settings', 'easy find and replace', 'administrator', __FILE__, 'easy_find_and_replace_settings_page', plugins_url('/images/icon.png', __FILE__));
}
function easy_find_and_replace_settings_page()
{
    //create form
?>
    <div class="wrap">
        <h1>Easy Find and Replace in post and page content</h1>
        <form method="post" id="findAndReplaceForm" action="admin.php?page=easy-find-and-replace%2Fsettings_page.php">
            <label for='find'>String to find</label>
            <input type='text' name='find' />
            <label for='replace'>String to replace found text with</label>
            <input type='text' name='replace' />
            <button style="margin-top:10px;">Run Find and Replace</button>
            <?php
            ?>
        </form>
    </div>
<?php } ?>