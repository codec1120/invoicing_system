<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('validateData') ) {
    function validateData ( $rules, $data) {

        foreach ($rules as $key => $value) {
            $rules = explode( '|', $value['rules'] );
            $errors = explode( '|', $value['errors'] );
            
            foreach ($rules as $keyType => $rule) {
                if ( $rule == 'required' &&
                    ( !isset( $data[ $value['field'] ] ) || 
                    empty($data[ $value['field'] ]) ) ) {
                    
                    die(
                        json_encode(
                            array(
                                'status' => false,
                                'message' => isset( $errors[$keyType] ) ? $errors[$keyType]: $value['field'].' is required.'
                            )
                        )
                    );
                }

                if ( $rule == 'email' &&
                        !filter_var( $data[ $value['field'] ], FILTER_SANITIZE_EMAIL) ) {
                    die(
                        json_encode(
                            array(
                                'status' => false,
                                'message' => isset( $errors[$keyType] )? $errors[$keyType]: $value['field'].' is not an email.'
                            )
                        )
                    );
                }   
            }
        }
    }
}