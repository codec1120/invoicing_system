<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('getSerializeData') ) {
    function getSerializeData() {
        switch ( $_SERVER['REQUEST_METHOD'] ) {
            case 'POST':
                return formData();
            case 'PUT':
                return formData();
            case 'GET':
                return queryData();
            default:
                return json_decode(
                    array(
                        'status' => false,
                        'message' => 'Method Type invalid.'
                    )
                );
        }
    }
}

if ( !function_exists('formData') ) {
    function formData () {
        $CIsess =& get_instance();
        
        $serializePostData = [];
        if ( sizeof($CIsess->input->post()) ) {
            foreach ($CIsess->input->post() as $key => $postValue) {
                    $serializePostData[ $key ] = $postValue; 
            }
        }
    
        return $serializePostData;
    }
}

if ( !function_exists('queryData') ) {
    function queryData () {
        $CIsess =& get_instance();

        $serializeGetData = [];

        if ( sizeof($CIsess->input->get()) ) {
            foreach ($CIsess->input->get() as $key => $getValue) {
                $serializeGetData[ $key ] = $getValue; 
            }
        }
    
        return $serializeGetData;
    }
}
