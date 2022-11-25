<?php

/**
 * Plugin tables
 */
function tables() {
  global $wpdb;

  $table_name = $wpdb->prefix . "approved_candidates";

  if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) !== $table_name ) {

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE " . $table_name . " (
      id INT NOT NULL AUTO_INCREMENT,
      entry_id INT,
      approved INT,
      approved_second_part INT,
      form_id INT,
      post_id INT,
      is_approved_for_import INT,
      imported INT,
      teacher_import_id INT,
      teacher_import_second_part_id INT,
      approved_date DATETIME,
      approved_second_part_date DATETIME,
      PRIMARY KEY  (id)
          ) " . $charset_collate . ";";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

  } else {

    $sql_drop = "ALTER TABLE " . $table_name . " 
      DROP COLUMN date_created,
      DROP COLUMN date_updated,
      DROP COLUMN is_starred,
      DROP COLUMN is_read,
      DROP COLUMN ip,
      DROP COLUMN source_url,
      DROP COLUMN user_agent,
      DROP COLUMN currency,
      DROP COLUMN payment_status,
      DROP COLUMN payment_date,
      DROP COLUMN payment_amount,
      DROP COLUMN payment_method,
      DROP COLUMN transaction_id,
      DROP COLUMN is_fulfilled,
      DROP COLUMN created_by,
      DROP COLUMN transaction_type,
      DROP COLUMN status,
      DROP COLUMN registration_date,
      DROP COLUMN class,
      DROP COLUMN unit,
      DROP COLUMN name,
      DROP COLUMN gender,
      DROP COLUMN age,
      DROP COLUMN ethnicity,
      DROP COLUMN email,
      DROP COLUMN per_capita_income,
      DROP COLUMN benefit_income,
      DROP COLUMN familiar_income,
      DROP COLUMN residents,
      DROP COLUMN schooling,
      DROP COLUMN grade,
      DROP COLUMN progress,
      DROP COLUMN public_private,
      DROP COLUMN school,
      DROP COLUMN university,
      DROP COLUMN scholarship,
      DROP COLUMN scholarship_INCOME,
      DROP COLUMN residence_phone,
      DROP COLUMN mobile_phone,
      DROP COLUMN contact_phone,
      DROP COLUMN contact_name,
      DROP COLUMN cep,
      DROP COLUMN address,
      DROP COLUMN district,
      DROP COLUMN municipality,
      DROP COLUMN handicapped,
      DROP COLUMN impairment_type;";

    $wpdb->query($sql_drop);

    $sql_add = "ALTER TABLE " . $table_name . " 
      ADD approved INT,
      ADD approved_second_part INT,
      ADD teacher_import_second_part_id INT,
      ADD approved_second_part_date DATETIME;";

    $wpdb->query($sql_add);

  }

}
