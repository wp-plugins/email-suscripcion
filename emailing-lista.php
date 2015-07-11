<?php 
/*
Plugin Name: Emailing Subscription
Plugin URI: http://www.seballero.com/blog/plugin-para-wordpress-e-mailing-subscription/
Description: A simple WordPress plugin for e-mailing subscription list.
Author: Sebastian Orellana
Version: 1.4.1
Author URI: http://www.seballero.com 
Text Domain: emailing-list
Domain Path: /lang
*/

load_plugin_textdomain('emailing-list', false, basename( dirname( __FILE__ ) ) . '/lang' );
    
function theme_settings_init(){
    global $plugin_page;
    if ( isset($_POST['exportar_xls']) && $plugin_page == 'emailing_list' ) {
    $hoy = date("Y-m-d");
    header("Content-Type: application/vnd.ms-excel");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("content-disposition: attachment;filename=emailing-$hoy.xls");
    echo "<table>
    <thead>    
    <tr>
    <th>".__( 'Name','emailing-list' )."</th>
    <th>".__( 'Email','emailing-list' )."</th>
    <th>".__( 'Reference','emailing-list' )."</th>         
    </tr>
    </thead>
    ";

    global $wpdb;
    $result = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."emailinglist GROUP BY email"); 
    foreach($result as $r)
    {
            echo "<tbody><tr>";
            echo "<td>".$r->name."</td>";
            echo "<td>".$r->email."</td>";
            echo "<td>".$r->reference."</td>";
            echo "</tr></tbody>";
    }
    echo "</table>";
    exit;
    }
    
    if (isset($_POST['borrar_cont'])) {
     
        if($_POST['check']){
         $cont_ids = $_POST['check'];
         foreach($cont_ids as $cont_id){ 
           global $wpdb;
           $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."emailinglist WHERE id = ".$cont_id));
         }
        } 
    }
    
    register_setting( 'theme_settings_page', 'theme_settings_page' );
}



/*---------------------------------------------------
add settings page to menu
----------------------------------------------------*/
function add_settings_page() {
add_menu_page( __( 'Emailing List' .'' ), __( 'Emailing List' .'' ),'edit_posts',  'emailing_list', 'emailing');
}
 
/*---------------------------------------------------
add actions
----------------------------------------------------*/
add_action( 'admin_init', 'theme_settings_init' );
add_action( 'admin_menu', 'add_settings_page' );



global $emailing_db_version;
$emailing_db_version = "1.0";

function emailing_install() {
   global $wpdb;
   global $emailing_db_version;

   $table_name = $wpdb->prefix . "emailinglist";
      
   $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  name varchar(89) NOT NULL,
  reference varchar(89) NOT NULL,
  email varchar(89) NOT NULL,
  UNIQUE KEY id (id)
    );";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
 
   add_option( "emailing_db_version", $emailing_db_version );
}

function emailing_install_data($name,$reference,$emaling) {
  global $wpdb;
  $table_name = $wpdb->prefix . "emailinglist";
   
  $emailrepet = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."emailinglist WHERE email LIKE '$emaling'");
  
  if(empty($name)){
   echo '<span class="mail-error">'.__( 'Enter your name.', 'emailing-list' ).'</span>';       
  }elseif($emailrepet==0){
   $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $name, 'reference' => $reference, 'email' => $emaling ) );
   echo '<span class="mail-success">'.__( 'Your email was subscribed successfully.', 'emailing-list' ).'</span>'; 
 
  }else{
   echo '<span class="mail-error">'.__( 'Your email is already subscribed.', 'emailing-list' ).'</span>';       
  }  
}

// Creating the widget 
class mailing_list14_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'mailing_list14_widget', 

// Widget name will appear in UI
__('Emailing Subscription', 'emailing-list'), 

// Widget description
array( 'description' => __( 'Emailing Subscription Widget Form', 'emailing-list' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
emailing_form(); 
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'emailing-list' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'mailing_list14_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

function emailing_form() {
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<style type="text/css">
    
#mailing-14 .textin{
	padding: 5px;
	font-size: 13px;
	border: 2px solid #ddd;
}

#mailing-14 .btn_submit{
	padding: 5px 10px;
	font-size: 13px;
	background: #3fa9f5;
	color: #fff;
	border: 1px solid #3fa9f5;	
}

.widget_mailing_list14_widget #mailing-14 .textin{
    display: block;
    margin-bottom: 10px; 
}

</style>
<form id="mailing-14" name="emailing" action="<?php echo $actual_link;?>#mailing-14" method="post" class="clear">
    
    <input name="name_mail" type="text" class="textin" placeholder="<?php _e( 'Name', 'emailing-list' ) ?>" > 

    <input name="email_mail" id="email" type="email" class="textin" placeholder="<?php _e( 'Email Address', 'emailing-list' ) ?>"/>
    
    <input name="reference_mail" type="hidden" value="<?php the_title();?>">

    <input class="btn_submit" type="submit" name="emailing-send" value="<?php _e( 'Subscribe', 'emailing-list' ) ?>" >
</form>
<?php

if (isset($_POST['emailing-send'])) {
         if (filter_var($_POST['email_mail'], FILTER_VALIDATE_EMAIL)) {
             emailing_install_data($_POST['name_mail'],$_POST['reference_mail'],$_POST['email_mail']);
         }else{
             echo '<span class="mail-error">'.__( 'Email address seems invalid.', 'emailing-list' ).'</span>';
         } 
}
else {
  return false;
}
}
register_activation_hook( __FILE__, 'emailing_install' );


class pagination_mailing {
    /**
     *  Script Name: WP Style Pagination Class
     *  Created From: *Digg Style Paginator Class
     *  Script URI: http://www.intechgrity.com/?p=794
     *  Original Script URI: http://www.mis-algoritmos.com/2007/05/27/digg-style-pagination-class/
     *  Description: Class in PHP that allows to use a pagination like WP in your WP Plugins
     *  Script Version: 1.0.0
     *
     *  Author: Swashata Ghosh <swashata4u@gmail.com
     *  Author URI: http://www.intechgrity.com/
     *  Original Author: Victor De la Rocha
     */
 
    /* Default values */
 
    var $total_pages = -1; //items
    var $limit = null;
    var $target = "";
    var $page = 1;
    var $adjacents = 2;
    var $showCounter = false;
    var $className = "pagination-links";
    var $parameterName = "p";
 
    /* Buttons next and previous */
    var $nextT = "Next";
    var $nextI = "&#187;"; //&#9658;
    var $prevT = "Previous";
    var $prevI = "&#171;"; //&#9668;
 
    /*     * ** */
    var $calculate = false;
 
    #Total items
 
    function items($value) {
        $this->total_pages = (int) $value;
    }
 
    #how many items to show per page
 
    function limit($value) {
        $this->limit = (int) $value;
    }
 
    #Page to sent the page value
 
    function target($value) {
        $this->target = $value;
    }
 
    #Current page
 
    function currentPage($value) {
        $this->page = (int) $value;
    }
 
    #How many adjacent pages should be shown on each side of the current page?
 
    function adjacents($value) {
        $this->adjacents = (int) $value;
    }
 
    #show counter?
 
    function showCounter($value="") {
        $this->showCounter = ($value === true) ? true : false;
    }
 
    #to change the class name of the pagination div
 
    function changeClass($value="") {
        $this->className = $value;
    }
 
    function nextLabel($value) {
        $this->nextT = $value;
    }
 
    function nextIcon($value) {
        $this->nextI = $value;
    }
 
    function prevLabel($value) {
        $this->prevT = $value;
    }
 
    function prevIcon($value) {
        $this->prevI = $value;
    }
 
    #to change the class name of the pagination div
 
    function parameterName($value="") {
        $this->parameterName = $value;
    }
 
    var $pagination;
 
    function pagination_mailing() {
 
    }
 
    function show() {
        if (!$this->calculate)
            if ($this->calculate())
                echo "<span class=\"$this->className\">$this->pagination</span>\n";
    }
 
    function getOutput() {
        if (!$this->calculate)
            if ($this->calculate())
                return "<span class=\"$this->className\">$this->pagination</span>\n";
    }
 
    function get_pagenum_link($id) {
        if (strpos($this->target, '?') === false)
            return "$this->target?$this->parameterName=$id";
        else
            return "$this->target&$this->parameterName=$id";
    }
 
    function calculate() {
        $this->pagination = "";
        $this->calculate == true;
        $error = false;
 
        if ($this->total_pages < 0) {
            echo "It is necessary to specify the <strong>number of pages</strong> (\$class->items(1000))<br />";
            $error = true;
        }
        if ($this->limit == null) {
            echo "It is necessary to specify the <strong>limit of items</strong> to show per page (\$class->limit(10))<br />";
            $error = true;
        }
        if ($error)
            return false;
 
        $n = trim($this->nextT . ' ' . $this->nextI);
        $p = trim($this->prevI . ' ' . $this->prevT);
 
        /* Setup vars for query. */
        if ($this->page)
            $start = ($this->page - 1) * $this->limit;             //first item to display on this page
        else
            $start = 0;                                //if no page var is given, set start to 0
 
        /* Setup page vars for display. */
        $prev = $this->page - 1;                            //previous page is page - 1
        $next = $this->page + 1;                            //next page is page + 1
        $lastpage = ceil($this->total_pages / $this->limit);        //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;                        //last page minus 1
 
        /*
          Now we apply our rules and draw the pagination object.
          We're actually saving the code to a variable in case we want to draw it more than once.
         */
 
        if ($lastpage > 1) {
            if ($this->page) {
                //anterior button
                if ($this->page > 1)
                    $this->pagination .= "<a href=\"" . $this->get_pagenum_link($prev) . "\" class=\"prev\">$p</a>";
                else
                    $this->pagination .= "<a href=\"javascript: void(0)\" class=\"disabled\">$p</a>";
            }
            //pages
            if ($lastpage < 7 + ($this->adjacents * 2)) {//not enough pages to bother breaking it up
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $this->page)
                        $this->pagination .= "<a href=\"javascript: void(0)\" class=\"current\">$counter</a>";
                    else
                        $this->pagination .= "<a href=\"" . $this->get_pagenum_link($counter) . "\">$counter</a>";
                }
            }
            elseif ($lastpage > 5 + ($this->adjacents * 2)) {//enough pages to hide some
                //close to beginning; only hide later pages
                if ($this->page < 1 + ($this->adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($this->adjacents * 2); $counter++) {
                        if ($counter == $this->page)
                            $this->pagination .= "<a href=\"javascript: void(0)\" class=\"current\">$counter</a>";
                        else
                            $this->pagination .= "<a href=\"" . $this->get_pagenum_link($counter) . "\">$counter</a>";
                    }
                    $this->pagination .= "<span>...</span>";
                    $this->pagination .= "<a href=\"" . $this->get_pagenum_link($lpm1) . "\">$lpm1</a>";
                    $this->pagination .= "<a href=\"" . $this->get_pagenum_link($lastpage) . "\">$lastpage</a>";
                }
                //in middle; hide some front and some back
                elseif ($lastpage - ($this->adjacents * 2) > $this->page && $this->page > ($this->adjacents * 2)) {
                    $this->pagination .= "<a href=\"" . $this->get_pagenum_link(1) . "\">1</a>";
                    $this->pagination .= "<a href=\"" . $this->get_pagenum_link(2) . "\">2</a>";
                    $this->pagination .= "<span>...</span>";
                    for ($counter = $this->page - $this->adjacents; $counter <= $this->page + $this->adjacents; $counter++)
                        if ($counter == $this->page)
                            $this->pagination .= "<a href=\"javascript: void(0)\" class=\"current\">$counter</a>";
                        else
                            $this->pagination .= "<a href=\"" . $this->get_pagenum_link($counter) . "\">$counter</a>";
                    $this->pagination .= "<span>...</span>";
                    $this->pagination .= "<a href=\"" . $this->get_pagenum_link($lpm1) . "\">$lpm1</a>";
                    $this->pagination .= "<a href=\"" . $this->get_pagenum_link($lastpage) . "\">$lastpage</a>";
                }
                //close to end; only hide early pages
                else {
                    $this->pagination .= "<a href=\"" . $this->get_pagenum_link(1) . "\">1</a>";
                    $this->pagination .= "<a href=\"" . $this->get_pagenum_link(2) . "\">2</a>";
                    $this->pagination .= "<span>...</span>";
                    for ($counter = $lastpage - (2 + ($this->adjacents * 2)); $counter <= $lastpage; $counter++)
                        if ($counter == $this->page)
                            $this->pagination .= "<a href=\"javascript: void(0)\" class=\"current\">$counter</a>";
                        else
                            $this->pagination .= "<a href=\"" . $this->get_pagenum_link($counter) . "\">$counter</a>";
                }
            }
            if ($this->page) {
                //siguiente button
                if ($this->page < $counter - 1)
                    $this->pagination .= "<a href=\"" . $this->get_pagenum_link($next) . "\" class=\"next\">$n</a>";
                else
                    $this->pagination .= "<a href=\"javascript: void(0)\" class=\"disabled\">$n</a>";
            }
        }
 
        return true;
    }
 
}


/*---------------------------------------------------
Theme Emaling Suscripción
----------------------------------------------------*/
function emailing() {?>
         <div class="wrap">
         <div id="icon-users" class="icon32"></div>
         <h2><?php _e( 'E-mailing List', 'emailing-list' ) ?></h2><br/><br/>
         <form method="post" id="download_form" action="">
            <input type="submit" name="exportar_xls" class="button-primary" value="<?php _e('Export to Excel', 'emailing-list'); ?>" />
         </form><br/>
         <form method="get" id="filtrar" action="">
            <label><?php _e('Show per page', 'emailing-list'); ?></label>
            <input type="hidden" name="page" value="emailing_list" />
            <select name="perpage">
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="150">150</option>
                <option value="999999"><?php _e('All', 'emailing-list'); ?></option>
            </select>
            <input type="submit" class="button-primary" value="<?php _e('Show', 'emailing-list'); ?>" />
         </form>
         
         <br/><br/>
         <?php
global $wpdb;
$pagination_count = $wpdb->get_var("SELECT COUNT(DISTINCT email) FROM ".$wpdb->prefix."emailinglist");
if($pagination_count > 0) {
    //get current page
    $this_page = ($_GET['p'] && $_GET['p'] > 0)? (int) $_GET['p'] : 1;
    //Records per page
    $cantidad = $_GET['perpage'];
    if($cantidad){
        $per_page = $cantidad;
    }else{
        $per_page = 20;
    }
    //Total Page
    $total_page = ceil($pagination_count/$per_page);
 
    //initiate the pagination variable
    $pag = new pagination_mailing();
    //Set the pagination variable values
    $pag->Items($pagination_count);
    $pag->limit($per_page);
    if($cantidad){
        $pag->target("admin.php?page=emailing_list&perpage=".$cantidad);
    }else{
        $pag->target("admin.php?page=emailing_list");
    }
    
    
    $pag->currentPage($this_page);
 
    //Done with the pagination
    //Now get the entries
    //But before that a little anomaly checking
    $list_start = ($this_page - 1)*$per_page;
    if($list_start >= $pagination_count)  //Start of the list should be less than pagination count
        $list_start = ($pagination_count - $per_page);
    if($list_start < 0) //list start cannot be negative
        $list_start = 0;
    $list_end = ($this_page * $per_page) - 1;
 
    //Get the data from the database
    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."emailinglist GROUP BY email DESC LIMIT %d, %d", $list_start, $per_page));

    if($result) {        
         
        echo "<form name='borrar-conts' action='' method='post' ><table class='widefat'>"; 
        echo "<table class='widefat'>
        <thead>    
        <tr>
        <th width='70px'>".__( 'Select', 'emailing-list' )."</th>
        <th>".__( 'Date', 'emailing-list' )."</th>
        <th>".__( 'Name', 'emailing-list' )."</th>
        <th>".__( 'Email', 'emailing-list' )."</th>
        <th>".__( 'Reference', 'emailing-list' )."</th>
        </tr>
        </thead>
        <tfoot>    
        <tr>
        <th>".__( 'Select', 'emailing-list' )."</th>
        <th>".__( 'Date', 'emailing-list' )."</th>
        <th>".__( 'Name', 'emailing-list' )."</th>
        <th>".__( 'Email', 'emailing-list' )."</th>
        <th>".__( 'Reference', 'emailing-list' )."</th>
        </tr>
        </tfoot>";
        foreach($result as $r)
        {
                echo "<tbody><tr>";
                echo "<td><input type='checkbox' name='check[]' value='".$r->id."' /></td>";
                echo "<td>".$r->time."</td>";
                echo "<td>".$r->name."</td>";
                echo "<td>".$r->email."</td>";
                echo "<td>".$r->reference."</td>";
                echo "</tr></tbody>";
        }
        echo " </table><br/><br/><input type='hidden' name='borrar_cont' value='true' /><input type='submit' value='".__( 'delete selected', 'emailing-list' )."' class='button-primary' /></form> ";?>
        <div class="tablenav">
            <div class="tablenav-pages">
                <span class="displaying-num"><?php echo $pagination_count; ?> items</span>
                <?php $pag->show(); ?>
            </div>
        </div> 
        </div> 
        <?php
        }else{
            echo '<h3>'.__('This Information is empty', 'emailing-list').'</h3>';
        }

}
}?>