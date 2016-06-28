<?php

// Helper functions called in other files

// Plant CPT

function pb_get_latin_name($id=null){
    $latin_name = get_field('latin_name', $id, true);
    if($latin_name){
        return $latin_name;
    }
    return '--';

}