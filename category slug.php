<?php
/**
 * Plugin Name: CSV Category Importer
 * Description: Import categories from a CSV file with name and parent columns.
 * Version: 1.0
 * Author: Gauca Tudor
 */

// Hook into WordPress admin menu
add_action('admin_menu', 'csv_category_importer_menu');

// Create a menu item under the "Tools" menu
function csv_category_importer_menu() {
    add_management_page(
        'CSV Category Importer',
        'CSV Category Importer',
        'manage_options',
        'csv-category-importer',
        'csv_category_importer_page'
    );
}

// Display the importer page
function csv_category_importer_page() {
    ?>
    <div class="wrap">
        <h1>CSV Category Importer</h1>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="csv_file">
            <?php wp_nonce_field('csv_category_importer', 'csv_category_importer_nonce'); ?>
            <?php submit_button('Import Categories'); ?>
        </form>
    </div>
    <?php
}

// Process the CSV file and import categories
add_action('admin_init', 'csv_category_importer_process');

function csv_category_importer_process() {
    // Check if the form is submitted and the user has appropriate permissions
    if (isset($_POST['csv_category_importer_nonce']) && wp_verify_nonce($_POST['csv_category_importer_nonce'], 'csv_category_importer')) {
        if (current_user_can('manage_options')) {
            // Check if a file is uploaded
            if (!empty($_FILES['csv_file']['tmp_name'])) {
                $file = $_FILES['csv_file']['tmp_name'];

                // Parse the CSV file
                if (($handle = fopen($file, 'r')) !== false) {
                    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                        $category_name = $data[0];
                        $parent_slug = $data[1];

                        // Check if the category already exists with the same parent
                        $category = get_term_by('name', $category_name, 'product_cat');
                        $category_exists_with_parent = false;
                        if ($category) {
                            $parent_category = get_term_by('slug', $parent_slug, 'product_cat');
                            $parent_id = $parent_category ? $parent_category->term_id : 0;
                            if ($category->parent == $parent_id) {
                                $category_exists_with_parent = true;
                            }
                        }

                        if (!$category_exists_with_parent) {
                            // Category doesn't exist with the same parent, create it
                            $parent_category = get_term_by('slug', $parent_slug, 'product_cat');
                            $parent_id = $parent_category ? $parent_category->term_id : 0;

                            $new_category = wp_insert_term($category_name, 'product_cat', array('parent' => $parent_id));

                            if (!is_wp_error($new_category)) {
                                echo 'Category created: ' . $category_name . '<br>';
                            } else {
                                echo 'Error creating category: ' . $category_name . ' - ' . $new_category->get_error_message() . '<br>';
                            }
                        } else {
                            echo 'Category already exists with the same parent: ' . $category_name . '<br>';
                        }
                    }
                    fclose($handle);
                }
            }
        }
    }
}
