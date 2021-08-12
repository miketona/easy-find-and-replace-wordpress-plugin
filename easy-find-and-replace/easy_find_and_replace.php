<?php

/**
 * Plugin Name:  Easy post/page content find and replace
 * Description:  Gives a graphical easy to use interface for finding text in the content of all posts and pages, and replacing it.
 * Version:      1.0
 * Plugin URI:   
 * Author:       Michael Tona
 * Author URI:   https://github.com/miketona/easy-find-and-replace-wordpress-plugin
 * Requires PHP: 5.3
 */

require_once(plugin_dir_path(__FILE__) . 'settings_page.php');

if (!class_exists('easy_find_and_replace')) {
    class easy_find_and_replace
    {
        public function __construct($find, $replace)
        {
            $results  = $this->query_database(['post_content', 'id']);
            $changedData = $this->findAndReplace($find, $replace, $results);
            $this->updateDatabase($changedData);
        }
        //creates settings page
        protected function query_database($selectOptions)
        {
            $selectString = "";
            for ($i = 0; $i < count($selectOptions); $i++) {
                $selectString .= $selectOptions[$i];
                if ($i + 1 < count($selectOptions))
                    $selectString .= ",";
            }
            // foreach ($selectOptions as $selectOption)
            global $wpdb;
            return   $wpdb->get_results("SELECT $selectString FROM wp_posts");
        }
        protected function findAndReplace($stringToFind, $stringToReplace, $results)
        {
            $changedResults = [];
            //loop throught data
            foreach ($results as $result) {
                //skip if content is null
                if ($result->post_content == NULL) {
                    continue;
                }

                //if found
                if (strpos($result->post_content, $stringToFind) !== false) {
                    //replace string
                    $result->post_content = str_replace($stringToFind, $stringToReplace, $result->post_content);
                    array_push($changedResults, $result);
                }
            }
            //return array of the columns id & object for the rows that were changed
            return $changedResults;
        }
        protected function updateDatabase($changedDatas)
        {
            global $wpdb;
            foreach ($changedDatas as $changedData) {
                // var_dump($changedData->id);

                $wpdb->update("wp_posts", array("post_content" => $changedData->post_content),  array("id" => $changedData->id));
            }
            echo "<p class = 'instancesFound'>" . count($changedDatas) . " instances have been found and replaced </p>";
        }
    }
}
if (isset($_POST['find']) && isset($_POST['replace']))
    new easy_find_and_replace($_POST['find'], $_POST['replace']);

?>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {

        console.log("running");
        if (document.getElementById("findAndReplaceForm") && document.querySelector(".instancesFound")) {
            document.getElementById("findAndReplaceForm").appendChild(document.querySelector(".instancesFound"));
        }
    })
</script>