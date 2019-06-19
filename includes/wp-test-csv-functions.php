<?php

/**
 * Add our plugin to menu in admin panel
 */

add_action('admin_menu', 'test_csv_add_admin_link');

function test_csv_add_admin_link()
{
    add_menu_page(
        'Test CSV',
        'Test CSV',
        'manage_options',
        'wp-test-csv/includes/wp-test-csv-admin.php'
    );
}

/**
 * Upload CSV file into folder
 */
add_action('wp_ajax_csv', 'wp_test_csv_upload_file');
add_action('wp_ajax_nopriv_csv', 'wp_test_csv_upload_file');

function wp_test_csv_upload_file(){
    try {
        WpTestCsv::uploadFile();
        echo 'File was uploaded';
    }
    catch (Exception $e) {
        echo 'Error, ',  $e->getMessage();
    }
}


/**
 * main class WpTestCsv
 */
class WpTestCsv
{
    private static function getUploadDir()
    {
       return wp_upload_dir()['basedir'].'/wp-test-csv';
    }

    public static function uploadFile()
    {
        if ($_FILES['file']["type"] != 'text/csv') {
           throw new  Exception('Please upload CSV format');
        }

        $csvFile = self::getUploadDir(). '/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $csvFile);

        self::getCsvData($csvFile);
    }

    /**
     * Get data from CSV
     */
    private static function getCsvData($csvFile)
    {
        $csv = array_map('str_getcsv', file($csvFile));

        chmod($csvFile , 0777);
        array_shift($csv);

        foreach ($csv as $wp_post ) {

            WpTestCsv::insert_data_into_post($wp_post[0], $wp_post[1], $wp_post[2], $wp_post[3]);
        }
    }

    /**
     * Put data into wp_post table
     *
     */
    private static function insert_data_into_post($content, $title, $categories, $date)
    {

        $post_data = [

            'post_content'   => $content,
            'post_date'      => $date,
            'post_title'     => $title,
            'post_category'  => self::getCategoriesArr($categories),
            'post_status'    => 'publish'

        ];

        wp_insert_post($post_data);
    }

    private static function getCategoriesArr($categories)
    {
        $categories = explode(',', $categories);

        return $categories;
    }

    /**
     * init when activation plugin
     */
    public static function activation()
    {
        if (!file_exists(self::getUploadDir())) {
            mkdir(self::getUploadDir(), 0777);
        }
    }

    /**
     * delete data if user uninstall plugin
     */
    public static function uninstall()
    {
        $scanFiles = scandir(self::getUploadDir());

        foreach ($scanFiles as $scanFile) {
            wp_delete_file($scanFile);
        }

        rmdir(self::getUploadDir());
    }
}

