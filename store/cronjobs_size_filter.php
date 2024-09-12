<?php 
include_once 'includes/configure.php';
include_once 'includes/database_tables.php';
include_once 'includes/functions/database.php';
include_once 'includes/size-filter-helper.php';

tep_db_connect();

if(isset($_GET['opt']) && $_GET['opt'] == "cats") {
    update_filter_size_categories();
}

if(isset($_GET['opt']) && $_GET['opt'] == "calc") {
    load_size_options_calculates(0, false);
}