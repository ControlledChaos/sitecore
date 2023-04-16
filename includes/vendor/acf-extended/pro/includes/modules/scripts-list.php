<?php

if(!defined('ABSPATH'))
    exit;

/*
 * WP List Table
 */
if(!class_exists('WP_List_Table')):
    
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    
endif;

/*
 * ACFE Scripts List
 */
if(!class_exists('acfe_pro_scripts_list')):

class acfe_pro_scripts_list extends WP_List_Table{
    
    /**
     * Constructor
     *
     */
    public function __construct(){
        
        parent::__construct(array(
            'singular'  => __('Script', 'acfe'),
            'plural'    => __('Scripts', 'acfe'),
            'ajax'      => false
        ));
        
    }
    
    /**
     * Retrieve data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_options($per_page = 100, $page_number = 1, $search = ''){
        
        $scripts = acfe_get_scripts();
        
        // category
        $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : false;
        
        if($category){
            
            foreach(array_keys($scripts) as $i){
                
                $script = $scripts[$i];
                
                $_category = $script->category;
                $_category_slug = sanitize_title($_category);
                
                if($category === $_category_slug) continue;
                
                unset($scripts[$i]);
                
            }
            
        }
        
        // search
        if(!empty($search)){
    
            foreach(array_keys($scripts) as $i){
    
                $script = $scripts[$i];
                
                if(stripos($script->name, $search) !== false || stripos($script->title, $search) !== false){
                    continue;
                }
        
                unset($scripts[$i]);
        
            }
        
        }
        
        // default sort by title
        if(empty($_REQUEST['orderby'])){
            
            usort($scripts, function($a, $b){
                return strcmp($a->title, $b->title);
            });
    
            return $scripts;
        
        }
        
        // order by
        $order = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'asc';
        $orderby = $_REQUEST['orderby'];
    
        usort($scripts, function($a, $b) use($order, $orderby){
        
            $_a = $a;
            $_b = $b;
        
            if($order === 'desc'){
                $a = $_b;
                $b = $_a;
            
            }
        
            return strcmp($a->{$orderby}, $b->{$orderby});
        
        });
        
        return $scripts;
        
    }
    
    
    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count($search = ''){
        
        $count = acfe_count_scripts();
        
        return $count;
        
    }
    
    
    /** Text displayed when no data is available */
    public function no_items(){
        _e('No scripts avaliable.', 'acfe');
    }
    
    protected function get_views(){
    
        $views = array();
        $_views = array();
        
        $categories = acfe_get_scripts_categories();
        $current = (isset($_REQUEST['category']) && !empty($_REQUEST['category']) ? $_REQUEST['category'] : 'all');
    
        //All link
        $class = ($current == 'all' ? ' class="current"' :'');
        $url = remove_query_arg('category');
        $views['all'] = "<a href='{$url}' {$class}>" . __('All') . "</a>";
        
        foreach($categories as $category){
            
            $slug = sanitize_title($category);
    
            $url = add_query_arg('category', $slug);
            $class = ($current == $slug ? ' class="current"' :'');
            $_views[$slug] = "<a href='{$url}' {$class}>{$category}</a>";
            
        }
    
        ksort($_views);
        
        $views = array_merge($views, $_views);
    
        return $views;
        
    }
    
    
    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name){
    
        return print_r($item, true);
        
    }
    
    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    public function column_title($item){
    
        $title = '<strong><a href="?page=' . esc_attr($_REQUEST['page']) . '&script=' . esc_attr($item->name) . '">' . $item->title . '</a></strong>';
    
        $actions = array(
            'view'  => sprintf('<a href="?page=%s&script=%s">' . __('View') . '</a>', esc_attr($_REQUEST['page']), esc_attr($item->name)),
            'run'   => sprintf('<a href="?page=%s&script=%s&action=run">' . __('Run now') . '</a>', esc_attr($_REQUEST['page']), esc_attr($item->name)),
        );
    
        return $title . $this->row_actions($actions);
        
    }
    
    public function column_description($item){
    
        $text = '—';
    
        if($item->description){
            $text = "<p>{$item->description}</p>";
        }
        
        $details = array();
        
        if($item->version){
            
            $details[] = "Version {$item->version}";
            
        }
    
        if($item->author){
        
            $author = $item->author;
        
            if($item->link){
                $author = '<a href="' . $item->link . '" target="_blank">' . $author . '</a>';
            }
            
            $details[] = $author;
        
        }
    
        if($item->category){
        
            $slug = sanitize_title($item->category);
            $url = add_query_arg('category', $slug);
    
            $details[] = '<a href="' . $url . '">' . $item->category . '</a>';
        
        }
        
        $details = implode(' | ', $details);
        
        $text .= "<div>{$details}&nbsp;</div>";
    
        return $text;
        
    }
    
    
    /**
     *  Associative array of columns
     *
     * @return array
     */
    public function get_columns(){
        
        $columns = array(
            'title'         => __('Title', 'acfe'),
            'description'   => __('Description', 'acfe'),
        );
        
        return $columns;
        
    }
    
    
    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns(){
        
        $sortable_columns = array(
            'title' => array('title', true),
        );
        
        return $sortable_columns;
        
    }
    
    
    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items(){
        
        // Get columns
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        
        // Vars
        $per_page = $this->get_items_per_page('options_per_page', 100);
        $current_page = $this->get_pagenum();
        
        // Search
        $search = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : false;
        
        // Get items
        $this->items = self::get_options($per_page, $current_page, $search);
        
        // Get total
        $total_items = self::record_count($search);
        
        if(!empty($search)){
            $per_page = $total_items;
        }
        
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page
        ));
        
    }
    
}

endif;