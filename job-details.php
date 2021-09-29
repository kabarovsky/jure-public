<?php

use eclipse\plugins\eclipse_recruitment\shortcode_script_loader;

if (!defined('ABSPATH')) {
    die('-1');
}

if (!class_exists('eclipse_recruitment_job_details')) {

    class eclipse_recruitment_job_details extends shortcode_script_loader {

        public function register_button() {
            
        }

        public function form_elements() {
            
        }

        public function handle_shortcode($atts, $content = null) {
        
            ob_start();

            eclipse_recruitment_get_template_part('vacancies/eclipse', 'job-details');

            return ob_get_clean();
        }

        public function add_script() {

            if (!$this->do_add_script) {

                $this->do_add_script = true;
            }
        }

    }

    $eclipse_recruitment_job_details = new eclipse_recruitment_job_details();
    $eclipse_recruitment_job_details->register('eclipse_job_details');
}


?>