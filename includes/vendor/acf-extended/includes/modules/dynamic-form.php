<?php

if(!defined('ABSPATH'))
    exit;

// Stop here if ACF Pro is not active.
if ( ! defined( 'ACF_PRO' ) ) {
    return;
}

acfe_include('includes/modules/form/form-front.php');

// Check setting
if(!acf_get_setting('acfe/modules/dynamic_forms'))
    return;

acfe_include('includes/modules/form/admin.php');

acfe_include('includes/modules/form/actions/custom.php');
acfe_include('includes/modules/form/actions/email.php');
acfe_include('includes/modules/form/actions/post.php');
acfe_include('includes/modules/form/actions/redirect.php');
acfe_include('includes/modules/form/actions/term.php');
acfe_include('includes/modules/form/actions/user.php');

acfe_include('includes/modules/form/field-group.php');