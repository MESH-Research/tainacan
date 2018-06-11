<?php

function tainacan_migrate_post_type_field_to_metadatum(){
    global $wpdb;

    $wpdb->update($wpdb->posts,
        ['post_type' => 'tainacan-metadatum'],
        ['post_type' => 'tainacan-field'],
        '%s', '%s');
      
    $wpdb->update($wpdb->postmeta,
        ['meta_key' => 'default_displayed_metadata'],
        ['meta_key' => 'default_displayed_fields'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_key' => 'metadata_order'],
        ['meta_key' => 'fields_order'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_key' => 'metadatum'],
        ['meta_key' => 'field'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_key' => 'metadatum_type'],
        ['meta_key' => 'field_type'],
        '%s', '%s');
    
    $wpdb->update($wpdb->postmeta,
        ['meta_key' => 'metadatum_type_options'],
        ['meta_key' => 'field_type_options'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_value' => 'Tainacan\Metadatum_Types\Core_Description'],
        ['meta_value' => 'Tainacan\Field_Types\Core_Description'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_value' => 'Tainacan\Metadatum_Types\Core_Title'],
        ['meta_value' => 'Tainacan\Field_Types\Core_Title'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_value' => 'Tainacan\Metadatum_Types\Text'],
        ['meta_value' => 'Tainacan\Field_Types\Text'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_value' => 'Tainacan\Metadatum_Types\Textarea'],
        ['meta_value' => 'Tainacan\Field_Types\Textarea'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_value' => 'Tainacan\Metadatum_Types\Date'],
        ['meta_value' => 'Tainacan\Field_Types\Date'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_value' => 'Tainacan\Metadatum_Types\Numeric'],
        ['meta_value' => 'Tainacan\Field_Types\Numeric'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_value' => 'Tainacan\Metadatum_Types\Selectbox'],
        ['meta_value' => 'Tainacan\Field_Types\Selectbox'],
        '%s', '%s');

    $wpdb->update($wpdb->postmeta,
        ['meta_value' => 'Tainacan\Metadatum_Types\Relationship'],
        ['meta_value' => 'Tainacan\Field_Types\Relationship'],
        '%s', '%s');


    $wpdb->update($wpdb->postmeta,
        ['meta_value' => 'Tainacan\Metadatum_Types\Category'],
        ['meta_value' => 'Tainacan\Field_Types\Category'],
        '%s', '%s');


    $wpdb->update($wpdb->postmeta,
        ['meta_value' => 'Tainacan\Metadatum_Types\Compound'],
        ['meta_value' => 'Tainacan\Field_Types\Compound'],
        '%s', '%s');
}

?>