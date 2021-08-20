<?php

add_action( 'wpcf7_before_send_mail', 

    function( $contact_form, $abort, $submission ) {

        $form_ids = array('181','268');
        $form_id = $_POST['_wpcf7'];
        
        if ( in_array($form_id, $form_ids) ) {

            $last_name = $submission->get_posted_data( 'full-name' );
            $email_address = $submission->get_posted_data( 'email-address');
            $phone_number = $submission->get_posted_data( 'phone-number');

            $utm_campaign = $submission->get_posted_data('utm_campaign');
            $utm_medium = $submission->get_posted_data('utm_medium');
            $utm_term = $submission->get_posted_data('utm_term');
            $utm_content = $submission->get_posted_data('utm_content');

            // Login Request

            $curl_login = curl_init();

            curl_setopt_array($curl_login, array(
                CURLOPT_URL => 'https://api5.cnx1.uk/consumer/login',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('username' => '...','password' => '...'),
            ));

            $response_login = curl_exec($curl_login);
            curl_close($curl_login);

            $response_json = json_decode($response_login);
            $response_token = $response_json->token;

            // Create Request

            $user_agent = $_SERVER['HTTP_USER_AGENT'];

            $curl_create = curl_init();

            curl_setopt_array($curl_create, array(
                CURLOPT_URL => 'https://api5.cnx1.uk/customer/create',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_USERAGENT => $user_agent,
                CURLOPT_POSTFIELDS =>'{
                    "first_name": "",
                    "last_name": "' . $last_name . '",
                    "email": "' . $email_address . '",
                    "date_of_birth": "",
                    "main_phone": "' . $phone_number . '",
                    "phone_code": "44",
                    "data_list": 1020,
                    "custom_fields": {
                    "utm_source": "Legmark",
                    "utm_campaign": "' . $utm_campaign . '",
                    "utm_medium": "' . $utm_medium . '",
                    "utm_term": "' . $utm_term . '",
                    "utm_content": "' . $utm_content . '"
                    },
                    "auto_queue":true,
                    "queue_priority":99,
                    "token": "FCYK5vHumrtd9bOXyLiqbHUzIWA4eqQuVBESGL70dPAbZ1zpIB"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $response_token
                ),
            ));

            $response_create = curl_exec($curl_create);

            curl_close($curl_create);

        }

  }, 10, 3

);
