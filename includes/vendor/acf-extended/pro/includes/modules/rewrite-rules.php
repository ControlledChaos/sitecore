<?php

if(!defined('ABSPATH'))
    exit;

// Check setting
if(!acf_get_setting('acfe/modules/rewrite_rules'))
    return;

if(!class_exists('acfe_pro_rewrite_rules')):

class acfe_pro_rewrite_rules{
    
    var $rewrite_rules;
    var $rewrite_rules_ui;
    
    /*
     * Construct
     */
    function __construct(){
        
        add_action('admin_menu', array($this, 'admin_menu'));
        
    }
    
    /*
     * Admin Menu
     */
    function admin_menu(){
    
        if(!acf_get_setting('show_admin')) return;
        
        // add page
        $page = add_management_page(__('Rewrite Rules'), __('Rewrite Rules'), acf_get_setting('capability'), 'acfe-rewrite-rules', array($this, 'html'));
    
        add_action("load-{$page}", array($this, 'load'));
    
    }
    
    /*
     * Load
     */
    function load(){
    
        // Submit
        if(acf_verify_nonce('acfe_rewrite_rules')){
    
            if(acf_maybe_get_POST('flush_permalinks')){
        
                flush_rewrite_rules();
        
                // Redirect
                wp_redirect(add_query_arg(array('message' => 'flush_permalinks')));
                exit;
                
            }
        
        }
    
        // Success message
        if(acf_maybe_get_GET('message') === 'flush_permalinks'){
        
            acf_add_admin_notice('Permalinks flushed.', 'success');
        
        }
        
        // Preload
        $rewrite_rules = $GLOBALS['wp_rewrite']->wp_rewrite_rules();
        $rewrite_rules_ui = array();
        $public_query_vars = apply_filters( 'query_vars', $GLOBALS['wp']->public_query_vars );
        $rewrite_patterns = array();
    
        $idx = 0;
        if($rewrite_rules){
        
            foreach($rewrite_rules as $pattern => $substitution){
            
                $idx++;
                $regex_tree = null;
                $rewrite_patterns[$idx] = addslashes($pattern);
                $rewrite_rule_ui = array(
                    'pattern' => $pattern,
                );
            
                try {
                    $regex_tree = $this->parse( $pattern );
                } catch ( Exception $e ) {
                    $rewrite_rule_ui['error'] = $e;
                }
            
                $regex_groups = $this->collect_groups( $regex_tree );
            
                $rewrite_rule_ui['print'] = $this->print_regex( $regex_tree, $idx );
            
                $substitution_parts = $this->parse_substitution( $substitution );
            
                $substitution_parts_ui = array();
                foreach ( $substitution_parts as $query_var => $query_value ) {
                    $substitution_part_ui = array(
                        'query_var' => $query_var,
                        'query_value' => $query_value,
                    );
                    $query_value_ui = $query_value;
                
                    // Replace `$matches[DD]` with URL regex part
                    // This is so complicated to handle situations where `$query_value` contains multiple `$matches[DD]`
                    $query_value_replacements = array();
                
                    if ( preg_match_all( '/\$matches\[(\d+)\]/', $query_value, $matches, PREG_OFFSET_CAPTURE ) ) {
                    
                        foreach ( $matches[0] as $m_idx => $match ) {
                        
                            $regex_group_idx = $matches[1][$m_idx][0];
                            $query_value_replacements[$match[1]] = array(
                                'replacement' => $this->print_regex( $regex_groups[$regex_group_idx], $idx, true ),
                                'length' => strlen( $match[0] ),
                                'offset' => $match[1],
                            );
                        
                        }
                    
                    }
                    krsort( $query_value_replacements );
                    foreach ( $query_value_replacements as $query_value_replacement ) {
                    
                        $query_value_ui = substr_replace( $query_value_ui, $query_value_replacement['replacement'], $query_value_replacement['offset'], $query_value_replacement['length'] );
                    
                    }
                    $substitution_part_ui['query_value_ui'] = $query_value_ui;
                
                    // Highlight non-public query vars
                    $substitution_part_ui['is_public'] = in_array( $query_var, $public_query_vars );
                    $substitution_parts_ui[] = $substitution_part_ui;
                
                }
            
                $rewrite_rule_ui['substitution_parts'] = $substitution_parts_ui;
                $rewrite_rules_ui[$idx] = $rewrite_rule_ui;
            
            }
            
            $this->rewrite_rules_ui = $rewrite_rules_ui;
        
        }
    
        // Localize data
        acf_localize_data(array(
            'rewrite_rules' => $rewrite_patterns
        ));
    
        // Enqueue
        acf_enqueue_scripts();
    
    }
    
    /*
     * HTML
     */
    function html(){
    
        // vars
        $prefix = '';
        $rewrite_rules = $GLOBALS['wp_rewrite']->wp_rewrite_rules();
    
        if(!got_mod_rewrite() && !iis7_supports_permalinks()){
            $prefix = '/index.php';
        }
    
        $url_prefix = get_option('home') .  $prefix . '/';
        
        ?>
        <div class="wrap" id="acfe-scripts">
            
            <h1><?php _e('Rewrite Rules'); ?></h1>
        
            <?php if(!$rewrite_rules): ?>
                <div class="error"><p>Pretty permalinks are disabled, you can change this on <a href="<?php echo admin_url('options-permalink.php'); ?>">the Permalinks settings page</a>.</p></div>
            <?php else : ?>
                <form method="post" class="heading">
                    
                    <?php
                    // Set form data
                    acf_form_data(array(
                        'screen'        => 'acfe_rewrite_rules',
                        'validation'    => false,
                    ));
                    ?>
                    
                    <div class="heading-url">
                        <div class="acf-field acf-field-text">
                            <div class="acf-input">
                                <div class="acf-input-prepend"><?php echo $url_prefix; ?></div>
                                <div class="acf-input-wrap">
                                    <input type="text" class="acf-is-prepended" id="acfe-rewrite-rules-url" value="" placeholder="Test URL" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="heading-clear">
                        <button class="button" id="acfe-rewrite-rules-clear">Clear</button>
                    </div>

                    <div class="heading-actions">
                        <a href="<?php echo admin_url("edit.php?post_type=acf-field-group&page=acf-tools&tool=acfe_rewrite_rules_export&action=php"); ?>" class="button">Export Rules</a>
                        <button class="button button-primary" name="flush_permalinks" value="1">Flush Permalinks</button>
                    </div>
                    
                </form>
            
                <table class="widefat fixed">
                    <thead>
                        <tr>
                            <th>Pattern</th>
                            <th>Matches</th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th>Pattern</th>
                            <th>Matches</th>
                        </tr>
                    </tfoot>
                
                    <tbody>
                    
                    <?php foreach($this->rewrite_rules_ui as $idx => $rewrite_rule_ui): ?>
                    
                        <tr id="rewrite-rule-<?php echo $idx; ?>" class="rewrite-rule-line">
                            <?php if ( array_key_exists( 'error', $rewrite_rule_ui ) ) : ?>
                                <td colspan="2">
                                    <code><?php echo $rewrite_rule_ui['pattern']; ?></code>
                                    <p class="error">Error parsing regex: <?php echo $rewrite_rule_ui['error']; ?></p>
                                </td>
                            <?php else : ?>
                                <td><code><?php echo $rewrite_rule_ui['print']; ?></code></td>
                                <td>
                                    <pre><?php foreach ( $rewrite_rule_ui['substitution_parts'] as $substitution_part_ui ) {
                                        if ( $substitution_part_ui['is_public'] ) {
                                            echo '<span class="queryvar-public">';
                                        } else {
                                            echo '<span class="queryvar-unread acf-js-tooltip" title="This query variable is not public and will not be saved">';
                                        }
                                        printf( "%' 18s: <span class='queryvalue'>%s</span>\n", $substitution_part_ui['query_var'], $substitution_part_ui['query_value_ui'] );
                                        echo '</span>';
                                    } ?></pre>
                                </td>
                            <?php endif; ?>
                        </tr>
                    
                    <?php endforeach; ?>
                    
                    </tbody>
                </table>
        
            <?php endif; ?>
        </div>
        <?php
    
    }
    
    /*
     * Print Regex
     */
    function print_regex($regex, $idx, $is_target = false){
        
        if ( is_a( $regex, 'ACFE_Regex_Group' ) ) {
            $output = '';
            if ( $is_target ) {
                $output .= '<span class="regexgroup-target-value" id="regex-' . $idx . '-group-' . $regex->counter . '-target-value"></span>';
            }
            $output .= '<span';
            if ( $regex->counter != 0 ) {
                $output .= ' class="regexgroup' . ( $is_target? '-target' : '' ) . '" id="regex-' . $idx . '-group-' . $regex->counter . ( $is_target? '-target' : '' ) . '">';
                $output .= '(';
            } else {
                $output .= '>';
            }
            foreach ( $regex as $regex_part ) {
                $output .= $this->print_regex( $regex_part, $idx );
            }
            if ( $regex->counter != 0 ) {
                $output .= ')';
            }
            $output = $this->wrap_repeater( $regex, $output );
            $output .= '</span>';
            return $output;
        }
        
        if ( is_a( $regex, 'ACFE_Regex_Range' ) ) {
            return $this->wrap_repeater( $regex, '[' . $regex->value . ']' );
        }
        
        if ( is_a( $regex, 'ACFE_Regex_Escape' ) ) {
            return $this->wrap_repeater( $regex, '\\' . $regex->value );
        }
        
        if ( is_a( $regex, 'ACFE_Regex_Char' ) ||
             is_a( $regex, 'ACFE_Regex_Special' ) ) {
            return $this->wrap_repeater( $regex, $regex->value );
        }
        
        if ( is_null( $regex ) ) {
            return 'Regex is empty!';
        }
        
        return 'Unknown regex class!';
        
    }
    
    /*
     * Wrap Repeater
     */
    function wrap_repeater($regex, $value){
        
        if ( $regex->repeater ) {
            
            $value = '<span class="regex-repeater-target">' .
                     $value .
                     '<span class="regex-repeater">' .
                     $regex->repeater->value .
                     '</span>' .
                     '</span>';
            
            // Can a repeater have a repeater?
            // Probably not, '?' is a greedy modifier
            $value = $this->wrap_repeater( $regex->repeater, $value );
            
        }
        
        return $value;
        
    }
    
    /*
     * Collect Groups
     */
    function collect_groups($regex_tree){
        
        $groups = array();
        
        if ( is_a( $regex_tree, 'ACFE_Regex_Group' ) ) {
            
            $groups[$regex_tree->counter] = &$regex_tree;
            
            foreach ( $regex_tree as $regex_child ) {
                $groups += $this->collect_groups( $regex_child );
            }
            
        }
        
        return $groups;
        
    }
    
    /*
     * Parse Substitution
     */
    function parse_substitution($substitution){
        
        if ( strncmp( 'index.php?', $substitution, 10 ) == 0 ) {
            $substitution = substr( $substitution, 10 );
        }
        
        parse_str( $substitution, $parsed_url_parts );
        
        $cleaned_url_parts = array();
        
        foreach ( $parsed_url_parts as $query_var => $query_value ) {
            
            if ( is_array( $query_value ) ) {
                foreach ( $query_value as $idx => $value ) {
                    $cleaned_url_parts[$query_var . '[' . $idx . ']'] = $value;
                }
            } else {
                $cleaned_url_parts[$query_var] = $query_value;
            }
            
        }
        
        return $cleaned_url_parts;
        
    }
    
    /*
     * Parse
     */
    function parse($regex){
        
        // Groups
        $group_counter = 0;
        $current_group = new ACFE_Regex_Group( $group_counter );
        $group_stack = array();
        
        // Ranges
        $is_in_range = false;
        $range = '';
        
        // Repeaters
        $repeat_target = $current_group;
        
        $regex_len = strlen( $regex );
        for ( $idx = 0; $idx < $regex_len; $idx++ ) {
            $letter = $regex[$idx];
            $is_greedy_switched = false;
            switch ( $letter ) {
                case '.':
                    $repeat_target = new ACFE_Regex_Any();
                    $current_group[] = $repeat_target;
                    break;
                case '$':
                    $repeat_target = new ACFE_Regex_End();
                    $current_group[] = $repeat_target;
                    break;
                // Escaping
                case '\\':
                    $idx++;
                    $repeat_target = new ACFE_Regex_Escape( $regex[$idx] );
                    $current_group[] = $repeat_target;
                    break;
                
                // Repeaters
                case '?':
                    if ( $idx + 1 < $regex_len && '?' == $regex[$idx + 1] ) {
                        $is_greedy_switched = true;
                        $idx++;
                        $letter .= $regex[$idx];
                    }
                    $repeater = new ACFE_Regex_Repeater( $letter, 0, 1, $is_greedy_switched );
                    $repeat_target->repeater = $repeater;
                    $repeat_target = $repeater;
                    break;
                case '*':
                    if ( '?' == $regex[$idx + 1] ) {
                        $is_greedy_switched = true;
                        $idx++;
                        $letter .= $regex[$idx];
                    }
                    $repeater = new ACFE_Regex_Repeater( $letter, 0, null, $is_greedy_switched );
                    $repeat_target->repeater = $repeater;
                    $repeat_target = $repeater;
                    break;
                case '+':
                    if ( '?' == $regex[$idx + 1] ) {
                        $is_greedy_switched = true;
                        $idx++;
                        $letter .= $regex[$idx];
                    }
                    $repeater = new ACFE_Regex_Repeater( $letter, 1, null, $is_greedy_switched );
                    $repeat_target->repeater = $repeater;
                    $repeat_target = $repeater;
                    break;
                case '{':
                    if ( ! is_null( $repeat_target ) && preg_match( '/\{(\d*)(,?)(\d*)\}(\??)/', $regex, $repeat_matches, PREG_OFFSET_CAPTURE, $idx ) && $repeat_matches[0][1] == $idx ) {
                        $min_len = $repeat_matches[1][0];
                        $max_len = $repeat_matches[3][0];
                        if ( '' == $repeat_matches[2][0] ) {
                            $max_len = $min_len;
                        }
                        $is_greedy_switched = ( '' != $repeat_matches[4][0] );
                        $repeat_target->repeater = new ACFE_Regex_Repeater( $repeat_matches[0][0], $min_len, $max_len, $is_greedy_switched );
                        $repeat_target = $repeat_target->repeater;
                        $idx += strlen( $repeat_matches[0][0] ) - 1;
                    } else {
                        $repeat_target = new ACFE_Regex_String( $letter );
                        $current_group[] = $repeat_target;
                    }
                    break;
                
                // Grouping
                case '(':
                    $group_counter++;
                    array_push( $group_stack, $current_group );
                    $current_group = new ACFE_Regex_Group( $group_counter );
                    break;
                case ')':
                    if ( $prev_group = array_pop( $group_stack ) ) {
                        $prev_group[] = $current_group;
                        $repeat_target = $current_group;
                        $current_group = $prev_group;
                    } else {
                        throw new ACFE_Regex_Exception( 'Unexpected ")"', $idx, $regex );
                    }
                    break;
                
                // Or-group
                /*case '|':
                    $current_group->nextOrGroup();
                    break;
                */
                
                // Ranges
                case '[':
                    if ( $is_in_range ) {
                        throw new ACFE_Regex_Exception( 'Unexpected "["', $idx, $regex );
                        return;
                    } else {
                        $is_in_range = true;
                        $range = '';
                        $repeat_target = null;
                    }
                    break;
                case ']':
                    if ( $is_in_range ) {
                        $repeat_target = new ACFE_Regex_Range( $range );
                        $current_group[] = $repeat_target;
                        $is_in_range = false;
                    } else {
                        throw new ACFE_Regex_Exception( 'Unexpected "]"', $idx, $regex );
                    }
                    break;
                
                default:
                    if ( $is_in_range ) {
                        $range .= $letter;
                    } else {
                        $repeat_target = new ACFE_Regex_Char( $letter );
                        $current_group[] = $repeat_target;
                    }
                    break;
            }
        }
        
        if ( ! empty( $group_stack ) ) {
            throw new ACFE_Regex_Exception( 'Unexpected end, still ' . count( $group_stack ) . ' open group(s) (missing ")")', $idx, $regex );
        }
        if ( $is_in_range ) {
            throw new ACFE_Regex_Exception( 'Unexpected end, still in range (missing "]")', $idx, $regex );
        }
        
        return $current_group;
    }
    
}

acf_new_instance('acfe_pro_rewrite_rules');

endif;

/*
 * ACFE Regex Exception
 */
class ACFE_Regex_Exception extends Exception{
    
    protected $idx = null;
    protected $regex = null;
    
    public function __construct( $message, $idx, $regex ){
        parent::__construct( $message );
        $this->idx = $idx;
        $this->regex = $regex;
    }
    
    public function __toString(){
        $regex_pieces = sprintf( '"%s"', substr( $this->regex, 0, $this->idx ) );
        if ( $this->idx < strlen( $this->regex ) ) {
            $regex_pieces .= sprintf( ' + "%s" + "%s"',
                $this->regex[$this->idx],
                substr( $this->regex, $this->idx + 1 )
            );
        }
        
        return $this->message . ' at char ' . $this->idx . ' (' . $regex_pieces . ')';
    }
    
}

/*
 * ACFE Regex Group
 */
class ACFE_Regex_Group extends ArrayObject{
    
    public $counter = null;
    public $repeater = null;
    
    public function __construct( $counter = 0 ){
        $this->counter = $counter;
    }
    
    public function __toString()
    {
        $output = '';
        if ( $this->counter != 0 ) {
            $output .= '(';
        }
        foreach ( $this as $el ) {
            $output .= $el;
        }
        if ( $this->counter != 0 ) {
            $output .= ')';
        }
        $output .= $this->repeater;
        return $output;
    }
    
}

/*
 * ACFE Regex Price
 */
class ACFE_Regex_Piece{
    
    public $repeater = null;
    public $value = null;
    
    public function __toString(){
        return $this->value . $this->repeater;
    }
    
}

/*
 * ACFE Regex String
 */
class ACFE_Regex_String extends ACFE_Regex_Piece{
    
    public function __construct( $value = '' ){
        $this->value = $value;
    }
    
}

/*
 * ACFE Regex Char
 */
class ACFE_Regex_Char extends ACFE_Regex_Piece{
    
    public function __construct( $value = '' ){
        $this->value = $value;
    }
    
}

/*
 * ACFE Regex Escape
 */
class ACFE_Regex_Escape extends ACFE_Regex_Piece{
    
    public function __construct( $value = '' ){
        $this->value = $value;
    }
    
    public function __toString(){
        return '\\' . $this->value . $this->repeater;
    }
    
}

/*
 * ACFE Regex Special
 */
class ACFE_Regex_Special extends ACFE_Regex_Piece{
    
    public $desc = null;
    
    public function __construct( $value = '', $desc = '' ){
        $this->value = $value;
        $this->desc = $desc;
    }
    
}

/*
 * ACFE Regex Any
 */
class ACFE_Regex_Any extends ACFE_Regex_Special{
    
    public function __construct(){
        parent::__construct( '.', 'any' );
    }
    
}

/*
 * ACFE Regex End
 */
class ACFE_Regex_End extends ACFE_Regex_Special{
    
    public function __construct(){
        parent::__construct( '$', 'end' );
    }
    
}

/*
 * ACFE Regex Repeater
 */
class ACFE_Regex_Repeater extends ACFE_Regex_Piece{
    
    public $min_len = null;
    public $max_len = null;
    public $is_greedy_switched = null;
    
    public function __construct( $value = '', $min_len = null, $max_len = null, $is_greedy_switched = false ){
        $this->value = $value;
        $this->min_len = $min_len;
        $this->max_len = $max_len;
        $this->is_greedy_switched = $is_greedy_switched;
    }
    
}

/*
 * ACFE Regex Range
 */
class ACFE_Regex_Range extends ACFE_Regex_Piece{
    
    public function __construct( $value = '' ){
        $this->value = $value;
    }
    
}