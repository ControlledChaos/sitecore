<?php

if(!defined('ABSPATH'))
    exit;

/*
 * Get Countries
 */
function acfe_get_countries($args = array()){
    
    // Default args
    $args = wp_parse_args($args, array(
        'type'          => 'countries',
        'code__in'      => false,
        'name__in'      => false,
        'continent__in' => false,
        'language__in'  => false,
        'currency__in'  => false,

        'orderby'       => false,
        'order'         => 'ASC',
        'offset'        => 0,
        'limit'         => -1,

        'field'         => false,
        'display'       => false,
        'prepend'       => false,
        'append'        => false,
        'groupby'       => false,
    ));
    
    // Query
    $query = new ACFE_World_Query($args);
    
    // Results
    return $query->data;
    
}

/*
 * Get Country
 */
function acfe_get_country($code, $field = ''){
    
    $data = acfe_get_countries(array(
        'code__in'  => $code,
        'limit'     => 1
    ));
    
    $data = reset($data);
    
    if($field){
        return acf_maybe_get($data, $field);
    }
    
    return $data;
    
}

/*
 * Get Languages
 */
function acfe_get_languages($args = array()){
    
    // Default args
    $args = wp_parse_args($args, array(
        'type'              => 'languages',
        'name__in'          => false,
        'locale__in'        => false,
        'alt__in'           => false,
        'code__in'          => false,
        'continent__in'     => false,
        'country__in'       => false,
        'currency__in'      => false,
        
        'orderby'           => false,
        'order'             => 'ASC',
        'offset'            => 0,
        'limit'             => -1,

        'field'             => false,
        'display'           => false,
        'prepend'           => false,
        'append'            => false,
        'groupby'           => false,
    ));
    
    // Query
    $query = new ACFE_World_Query($args);
    
    // Results
    return $query->data;
    
}

/*
 * Get Language
 */
function acfe_get_language($locale, $field = ''){
    
    $data = acfe_get_languages(array(
        'locale__in'  => $locale,
        'limit'       => 1
    ));
    
    $data = reset($data);
    
    if($field){
        return acf_maybe_get($data, $field);
    }
    
    return $data;
    
}

/*
 * Get Currencies
 */
function acfe_get_currencies($args = array()){
    
    // Default args
    $args = wp_parse_args($args, array(
        'type'          => 'currencies',
        'name__in'      => false,
        'code__in'      => false,
        'continent__in' => false,
        'country__in'   => false,
        'language__in'  => false,
        
        'countries'     => false,
        'languages'     => false,
        
        'orderby'       => false,
        'order'         => 'ASC',
        'offset'        => 0,
        'limit'         => -1,

        'field'         => false,
        'display'       => false,
        'prepend'       => false,
        'append'        => false,
        'groupby'       => false,
    ));
    
    // Query
    $query = new ACFE_World_Query($args);
    
    // Results
    return $query->data;
    
}

/*
 * Get Currency
 */
function acfe_get_currency($code, $field = ''){
    
    $data = acfe_get_currencies(array(
        'code__in'  => $code,
        'limit'     => 1
    ));
    
    $data = reset($data);
    
    if($field){
        return acf_maybe_get($data, $field);
    }
    
    return $data;
    
}