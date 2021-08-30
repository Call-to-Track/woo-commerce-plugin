<?php
/**
 * @package calltotrack-phone-orders
 */
/*
Plugin Name: CalltoTrack Phone Orders
Plugin URI: https://www.calltotrack.com/
Description: Search WooCommerce orders using a phone number
Author: Calltotrack
Version: 1.0.0
*/


defined('ABSPATH') or die;


function get_orders_by_phone($request){

    $orders = wc_get_orders([
        'billing_phone' => $request['phone'],
        'limit' => $request['per_page'],
        'paged' => $request['page'],
        'paginate' => true,
    ]);

    foreach($orders->orders as &$order){
        $order = $order->get_data();
    }

    return $orders;
}


add_action('rest_api_init', function(){
    register_rest_route( 'wc/v3', 'calltotrack-search',[
        'methods' => 'GET',
        'callback' => 'get_orders_by_phone',
        'permission_callback' => function($request){
            return is_user_logged_in();
        },
        'args' => [
            'phone' => [
                'required' => true,
            ],
            'per_page' => [
                'default' => 50,
            ],
            'page' => [
                'default' => 1,
            ],

        ],
    ]);

});
