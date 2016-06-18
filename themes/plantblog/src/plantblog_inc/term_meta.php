<?php

//https://www.smashingmagazine.com/2015/12/how-to-use-term-meta-data-in-wordpress/



//add the Plural form field to the admin
function pb_create_term_plurals($taxonomy){
  $output = '<div class="form-field term-group">
        <label for="featuret-group">Plural Form</label>
        <input type="text" class="postform" id="plural" name="plural">
    </div>';
   echo $output;
}
add_action('plant-type_add_form_fields', 'pb_create_term_plurals', 10, 2);

//Save the field contents
function pb_save_plant_type_meta( $term_id, $tt_id ){
    if( isset( $_POST['plural'] ) && '' !== $_POST['plural'] ){
        $plural = sanitize_title( $_POST['plural'] );
        add_term_meta( $term_id, 'plural', $plural, true );
    }
}
add_action( 'created_plant-type', 'pb_save_plant_type_meta', 10, 2 );


//enable updating
function edit_feature_group_field( $term, $taxonomy ){
                
          
    // get current group
    $plural_form = get_term_meta( $term->term_id, 'plural', true );
                
    $output = '<tr class="form-field term-group-wrap">
        <th scope="row"><label for="plural">Plural Form</label></th>
        <td><input type="text" class="postform" id="plural" name="plural" value="'. $plural_form . '"></td>
    </tr>';
   echo $output;
}
add_action( 'plant-type_edit_form_fields', 'edit_feature_group_field', 10, 2 );


//save edited data
function pb_update_plant_type_meta( $term_id, $tt_id ){

    if( isset( $_POST['plural'] ) && '' !== $_POST['plural'] ){
        $group = sanitize_title( $_POST['plural'] );
        update_term_meta( $term_id, 'plural', $group );
    }
}
add_action( 'edited_plant-type', 'pb_update_plant_type_meta', 10, 2 );


//display meta in terms list 
function pb_add_plant_type_column( $columns ){
    $columns['plural'] = 'Plural Form';
    return $columns;
}
add_filter('manage_edit-plant-type_columns', 'pb_add_plant_type_column' );


//add meta content in the columns
function pb_add_plant_type_column_content( $content, $column_name, $term_id ){

    if( $column_name !== 'plural' ){
        return $content;
    }

    $term_id = absint( $term_id );
    $plural = get_term_meta( $term_id, 'plural', true );

    if( !empty( $plural ) ){
        $content .= esc_attr( $plural);
    }

    return $content;
}
add_filter('manage_plant-type_custom_column', 'pb_add_plant_type_column_content', 10, 3 );