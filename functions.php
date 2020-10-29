<?php

/** dynamic function to create the register form */
function register_form($inputs, $error)
{
    $register = '';

    $register .= '<div class="container">';
    $register .= '<br>';
    $register .= "<h2>".$error."</h2>";
    $register .= '<form method="post">';

    foreach ($inputs as $key => $value) {

        $register .= '<div class="form-group">';
        $register .= "<label for='name'> " . $value['label'] . " : </label>";
        $register .= "<input type='" . $value['type'] . "' class='form-control' name='" . $value['name'] . "' placeholder='" . $value['placeholder'] . "' required> <br>";
        $register .= '</div><br>';

    }
    $register .= '<input type="submit" class="btn btn-primary" name="register_form"/>';
    $register .= '</form>';
    $register .= '</div>';

    return $register;
}

/** dynamic function to create the subscribe form */
function subscribe_form($basic, $limited, $unlimited, $message)
{
    $user = wp_get_current_user();

    $subscribe = '';
    $subscribe .= '<div class="container mb-5 mt-5">';
    $subscribe .= "<h2 style='color:green;'>" . $message . "</h2>";
    $subscribe .= '<div class="pricing card-deck flex-column flex-md-row mb-3">';
    $subscribe .= '<div class="fa-border">';
    $subscribe .= '<form method="post">';
    $subscribe .= '<div class="card card-pricing text-center px-3 mb-4">';
    $subscribe .= '<h3 class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary shadow-sm basic ">
                       <strong>Basic</strong></h3>';
    foreach ($basic as $key => $value) {

        $subscribe .= '<div class="ml-2">';
        $subscribe .= "<input type='" . $value['type'] . "' value='" . $value['value'] . "' name='" . $value['name'] . "' hidden>";
        $subscribe .= '</div>';

    }
    $subscribe .= '<li>Totally Free</li>';
    $subscribe .= '<li>Can Upload 1 Cvs</li>';
    $subscribe .= '<li>Basic support on Github</li>';
    $subscribe .= '<li>Monthly updates</li>';
    $subscribe .= '<li>Free cancelation</li>';

    if ($user->exists()) {
        $subscribe .= '<button type="submit" name="order_now" class="btn btn-outline-secondary mb-3">
                        Order now </button>';
    }
    $subscribe .= '</div>';
    $subscribe .= '</div>';
    $subscribe .= '</form>';

    $subscribe .= '</div>';
    $subscribe .= '<div class="fa-border">';
    $subscribe .= '<form method="post">';
    $subscribe .= '<div class="card card-pricing text-center px-3 mb-4">';
    $subscribe .= '<h3 class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary                                                shadow-sm basic ">
                                <strong>Limited</strong></h3>';
    foreach ($limited as $key => $value) {

        $subscribe .= '<div class="ml-2">';
        $subscribe .= "<input type='" . $value['type'] . "' value='" . $value['value'] . "' name='" . $value['name'] . "' hidden>";
        $subscribe .= '</div>';

    }
    $subscribe .= '<li>3$/Month</li>';
    $subscribe .= '<li>Can Upload 3 Cvs</li>';
    $subscribe .= '<li>Basic support on Github</li>';
    $subscribe .= '<li>Monthly updates</li>';
    $subscribe .= '<li>Free cancelation</li>';
    $subscribe .= '</div>';
    $subscribe .= '</div>';
    $subscribe .= '</form>';

    if ($user->exists()) {
        $subscribe .= do_shortcode('[wp_paypal button="subscribe" name="limited" a3="3.00" p3="1" t3="M" src="1" return= "http://localhost/subscription/subscription-page"]');
    }

    $subscribe .= '</div>';
    $subscribe .= '<div class="fa-border">';
    $subscribe .= '<form method="post">';
    $subscribe .= '<div class="card card-pricing text-center px-3 mb-4">';
    $subscribe .= '<h3 class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary shadow-sm basic ">
                       <strong>Unlimited</strong></h3>';
    foreach ($unlimited as $key => $value) {

        $subscribe .= '<div class="ml-2">';
        $subscribe .= "<input type='" . $value['type'] . "' value='" . $value['value'] . "' name='" . $value['name'] . "' hidden>";
        $subscribe .= '</div>';

    }
    $subscribe .= '<li>5$/Month</li>';
    $subscribe .= '<li>Can Upload 5 Cvs</li>';
    $subscribe .= '<li>Basic support on Github</li>';
    $subscribe .= '<li>Monthly updates</li>';
    $subscribe .= '<li>Free cancelation</li>';
    $subscribe .= '</div>';
    $subscribe .= '</div>';
    $subscribe .= '</form>';

    if ($user->exists()) {
        $subscribe .= do_shortcode('[wp_paypal button="subscribe" name="Unlimited" a3="5.00" p3="1" t3="M" src="1" return= "http://localhost/subscription/subscription-page"]');
    }

    $subscribe .= '</div>';
    $subscribe .= '</div>';
    return $subscribe;
}

/** dynamic function to create the resume form */
function resume_form()
{
    $resume = '';
    global $wpdb, $error;
    $user = wp_get_current_user();
    $user_id = get_current_user_id();
    if ($user->exists()) {
        $get_deal = $wpdb->get_results("SELECT deal_name FROM {$wpdb->prefix}subscribe_deals where user_id = $user_id");

        if (empty($get_deal)){
            $error = "Please get a subscription deal first";
        }else {

            $deal_name = $get_deal[0]->deal_name;

            switch ($deal_name) {
                case 'basic':
                    $heading = '<h2 style="color: green"> You have choose deal <strong>BASIC</strong> ,                         You can upload 1 resume</h2><br>';
                    break;
                case 'limited':
                    $heading = '<h2 style="color: green"> You have choose deal <strong>Limited</strong>,                        You can upload 3 resume</h2><br>';
                    break;
                case 'unlimited':
                    $heading = '<h2 style="color: green"> You have choose deal <strong>Unlimited</strong>,                      You can upload 5 resume</h2><br>';
            }

            $resume .= '<div class="outer-border">';
            $resume .= '</div><br>';
            $resume .= '<form method="post" enctype="multipart/form-data">';
            $resume .= '<div class="form-group">';

            $resume .= '<div>';
            $resume .= $heading;
            $resume .= "<input type='file' class='form-control' name='resume[]' multiple><br>";
            $resume .= '</div>';
            $resume .= '<br>';

            $resume .= '<button type="submit" class="btn btn-primary btn-lg" name="submit_resume">Post</button>';
            $resume .= '</form>';
            $resume .= '</div>';
        }

    } else {

        $resume .= '<span style="color: red">Please Register Your self First</span>';

    }
    return $resume;

}

/** login form function */
function login_form()
{
    $login = '';

    $login .= '<div class="container">';
    $login .= '<div class="outer-border">';
    $login .= '<h2 style="color: #0A246A">Enter 4 digit code that sent you to on your email to login</h2>';
    $login .= '</div>';
    $login .= '<br>';
    $login .= '<form method="post">';
    $login .= '<div class="form-group">';
    $login .= '<label for="login">Enter Code:</label>';
    $login .= '<input type="number" class="form-control" placeholder="Enter 4 digit code" name="code">';
    $login .= '</div>';
    $login .= '<br>';
    $login .= '<input type="submit" class="btn btn-primary" name="login_form"/>';
    $login .= '</form>';
    $login .= '</div>';

    return $login;
}