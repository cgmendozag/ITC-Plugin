<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


        function itc_background($color) {
            $color = str_replace("#", "", $color);

            if (strlen($color) == 3) {
                $r = hexdec(substr($color, 0, 1) . substr($color, 0, 1));
                $g = hexdec(substr($color, 1, 1) . substr($color, 1, 1));
                $b = hexdec(substr($color, 2, 1) . substr($color, 2, 1));
            } else {
                $r = hexdec(substr($color, 0, 2));
                $g = hexdec(substr($color, 2, 2));
                $b = hexdec(substr($color, 4, 2));
            }

            $background_rgba = "rgba($r,$g,$b,0.3)";
            return $background_rgba;
        }

    
    
    function itc_class($post_id) {
       // $oITC = new ITC_Plugin();
        $categories = get_the_category($post_id);
        //print_r($categories);
        foreach ($categories as $category) {
         return 'itc_'.$category->cat_ID;
        }
    }
