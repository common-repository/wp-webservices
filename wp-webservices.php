<?php

/**
 * Plugin Name: wp-webservices
 * Description: This plugin extends the basic webservices exposed by WordPress
 * Version: 1.0
 * Author: Prasath Nadarajah
 * Author URI: http://nprasath.com
 *
*/


/**
 *  holds details of options that can be modified
 *  @global variable
 */
$admin_options = array(

			'site_title'            => array(
				'desc'              => __( 'Title of the site' ),
				'key'               => 'blogname',
			),

			'tagline'               => array(
				'desc'              => __( 'Description of the site' ),
				'key'               => 'blogdescription',
			),

			'admin_email'           => array(
				'desc'              => __( 'Email ID of the admin' ),
				'key'               => 'admin_email',
			),

			'users_can_register'	=> array(
				'desc'              => __( 'Allow anyone to register' ),
				'key'               => 'users_can_register',
			),

			'default_user_role'	=> array(
				'desc'              => __( 'Default user role when registering users' ),
				'key'               => 'default_user_role',
			),

			'date_format'           => array(
				'desc'              => __( 'Date format' ),
				'key'               => 'date_format',
			),

			'time_format'           => array(
				'desc'              => __( 'Time format' ),
				'key'               => 'time_format',
			),

			'start_of_week'         => array(
				'desc'              => __( 'Start of week' ),
				'key'               => 'start_of_week',
			),

			'default_post_category'	=> array(
				'desc'              => __( 'Default post category' ),
				'key'               => 'default_category',
			),

			'default_post_format'	=> array(
				'desc'              => __( 'Default post format' ),
				'key'               => 'default_post_format',
			),

			'default_link_category'	=> array(
				'desc'              => __( 'Default link category' ),
				'key'               => 'default_link_category',
			),

			'mailserver_url'	=> array(
				'desc'              => __( 'Mail server URL' ),
				'key'               => 'mailserver_url',
			),

			'Login name'            => array(
				'desc'              => __( 'Mailserver login name' ),
				'key'               => 'mailserver_login',
			),

			'mailserver_password'   => array(
				'desc'              => __( 'Mailserver password' ),
				'key'               => 'mailserver_pass',
			),

			'mailserver_port'       => array(
				'desc'              => __( 'Mailserver port number' ),
				'key'               => 'mailserver_port',
			),

			'default_mail_category' => array(
				'desc'              => __( 'Default mail category' ),
				'key'               => 'default_email_category',
			),

    			'default_comment_status' => array(
				'desc'              => __( 'Default comment status' ),
				'key'               => 'default_comment_status',
			),

    			'default_ping_status' => array(
				'desc'              => __( 'Default ping status' ),
				'key'               => 'default_ping_status',
			),

    			'default_pingback_flag' => array(
				'desc'              => __( 'Default pingback flag' ),
				'key'               => 'default_pingback_flag',
			),

                        'posts_per_page' => array(
				'desc'              => __( 'Posts per page' ),
				'key'               => 'posts_per_page',
			),

                        'close_comments_days_old' => array(
				'desc'              => __( 'Days to close comments' ),
				'key'               => 'close_comments_days_old',
			),

                        'thread_comments' => array(
				'desc'              => __( 'Enable Threaded comments' ),
				'key'               => 'thread_comments',
			),

                        'thread_comments_depth' => array(
				'desc'              => __( 'Depth of the comment thread' ),
				'key'               => 'thread_comments_depth',
			),

                        'comments_per_page' => array(
				'desc'              => __( 'Comments per page' ),
				'key'               => 'comments_per_page',
			),

                        'default_comments_page' => array(
				'desc'              => __( 'Default comments page' ),
				'key'               => 'default_comments_page',
			),

                        'comment_order' => array(
				'desc'              => __( 'Comment order' ),
				'key'               => 'comment_order',
			),

                );


add_filter( 'xmlrpc_methods' , 'newMethods' );

function newMethods ( $methods ) {

                // user management
                $methods['wp.newUser']          = 'wp_newUser';
                $methods['wp.editUser']         = 'wp_editUser';
                $methods['wp.deleteUser']       = 'wp_deleteUser';
                $methods['wp.getUser']          = 'wp_getUser';
                $methods['wp.getUsers']         = 'wp_getUsers';

                // custom post type management
                $methods['wp.newPost']          = 'wp_newPost';
                $methods['wp.editPost']         = 'wp_editPost';
                $methods['wp.deletePost']       = 'wp_deletePost';
                $methods['wp.getPost']          = 'wp_getPost';
                $methods['wp.getPosts']         = 'wp_getPosts';
                $methods['wp.getPostTerms']     = 'wp_getPostTerms';
                $methods['wp.setPostTerms']     = 'wp_setPostTerms';
                $methods['wp.getPostType']      = 'wp_getPostType';
                $methods['wp.getPostTypes']     = 'wp_getPostTypes';

                // custom taxonomy management
                $methods['wp.newTerm']          = 'wp_newTerm';
                $methods['wp.editTerm']         = 'wp_editTerm';
                $methods['wp.deleteTerm']       = 'wp_deleteTerm';
                $methods['wp.getTerm']          = 'wp_getTerm';
                $methods['wp.getTerms']         = 'wp_getTerms';
                $methods['wp.getTaxonomy']      = 'wp_getTaxonomy';
                $methods['wp.getTaxonomies']    = 'wp_getTaxonomies';

                // options management
                $methods['wp.getSettings']      = 'wp_getSettings';
                $methods['wp.updateSettings']   = 'wp_updateSettings';

                return $methods;

}

/**
 * Create a new user
 *
 * @uses wp_insert_user()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - array     $content_struct.
 *      The $content_struct must contain:
 *      - 'username'
 *      - 'password'
 *      - 'email'
 *      Also, it can optionally contain:
 *      - 'role'
 *      - 'first_name'
 *      - 'last_name'
 *      - 'website'
 *  - boolean $send_mail optional. Defaults to false
 * @return string user_id
 */
function wp_newUser( $args ) {
    
                global $wp_xmlrpc_server, $wp_roles;
                $wp_xmlrpc_server->escape($args);

                $blog_ID        = (int) $args[0]; // for future use
                $username       = $args[1];
                $password       = $args[2];
                $content_struct = $args[3];
                $send_mail      = isset( $args[4] ) ? $args[4] : false;

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password )  )
                        return $wp_xmlrpc_server->error;

                if ( ! current_user_can( 'create_users' ) )
                        return new IXR_Error( 401, __( 'You are not allowed to create users' ) );

                // this hold all the user data
                $user_data = array();
                
                $user_data['user_login'] = '';
                if( isset ( $content_struct['user_login'] ) ) {

                        $user_data['user_login'] = sanitize_user( $content_struct['user_login'] );
                        //Remove any non-printable chars from the login string to see if we have ended up with an empty username
                        $user_data['user_login'] = trim( $user_data['user_login'] );

                }

                if( empty ( $user_data['user_login'] ) )
                        return new IXR_Error( 403, __( 'Cannot create a user with an empty login name. ' ) );
                if( username_exists ( $user_data['user_login'] ) )
                        return new IXR_Error( 403, __( 'This username is already registered.' ) );

                //password cannot be empty
                if( empty ( $content_struct['user_pass'] ) )
                        return new IXR_Error( 403, __( 'password cannot be empty' ) );

                $user_data['user_pass'] = $content_struct['user_pass'];

                // check whether email address is valid
                if( ! is_email( $content_struct['user_email'] ) )
                        return new IXR_Error( 403, __( 'email id is not valid' ) );

                // check whether it is already registered
                if( email_exists( $content_struct['user_email'] ) )
                        return new IXR_Error( 403, __( 'This email address is already registered' ) );

                $user_data['user_email'] = $content_struct['user_email'];

                // If no role is specified default role is used
                $user_data['role'] = get_option('default_role');
                if( isset ( $content_struct['role'] ) ) {

                        if( ! isset ( $wp_roles ) )
                                $wp_roles = new WP_Roles ();
                        if( ! array_key_exists( $content_struct['role'], $wp_roles->get_names() ) )
                                return new IXR_Error( 403, __( 'The role specified is not valid' ) );
                        $user_data['role'] = $content_struct['role'];
                        
                }

                $user_data['first_name'] = '';
                if( isset ( $content_struct['first_name'] ) )
                        $user_data['first_name'] = $content_struct['first_name'];

                $user_data['last_name'] = '';
                if( isset ( $content_struct['last_name'] ) )
                        $user_data['last_name'] = $content_struct['last_name'];

                $user_data['user_url'] = '';
                if( isset ( $content_struct['user_url'] ) )
                        $user_data['user_url'] = $content_struct['user_url'];

                $user_id =  wp_insert_user( $user_data );

                if ( is_wp_error( $user_id ) )
                            return new IXR_Error( 500, $user_id->get_error_message() );

                if ( ! $user_id )
                            return new IXR_Error( 500, __( 'Sorry, your entry could not be posted. Something wrong happened.' ) );

                if( $send_mail ) {
                    
                    $subject = "[".get_bloginfo('name')."] Your username and password";
                    $message = "Username: ".$user_data['user_login']."\nPassword: ".$user_data['user_pass']."\n".get_bloginfo('siteurl')."/wp-login.php";
                    wp_mail( $user_data['user_email'], $subject, $message );
                    
                }

                return strval( $user_id );

}

/**
 * Edit a new user
 *
 * @uses wp_update_user()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - int     $user_id
 *  - string  $username
 *  - string  $password
 *  - array     $content_struct.
 *      It can optionally contain:
 *      - 'email'
 *      - 'first_name'
 *      - 'last_name'
 *      - 'website'
 *      - 'role'
 *      - 'nickname'
 *      - 'usernicename'
 *      - 'bio'
 *      - 'usercontacts'
 *      - 'password'
 *  - boolean $send_mail optional. Defaults to false
 * @return string user_id
 */
function wp_editUser( $args ) {

                global $wp_xmlrpc_server, $wp_roles;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID        = (int) $args[0];
                $user_ID        = (int) $args[1];
                $username       = $args[2];
                $password       = $args[3];
                $content_struct = $args[4];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password )  )
                        return $wp_xmlrpc_server->error;

                $user_info = get_userdata( $user_ID );

                if( ! $user_info )
                        return new IXR_Error(404, __('Invalid user ID'));

                if( ! ( $user_ID == $user->ID || current_user_can( 'edit_users' ) ) )
                        return new IXR_Error(401, __('Sorry, you cannot edit this user.'));

                // holds data of the user
                $user_data = array();
                $user_data['ID'] = $user_ID;

                if ( isset( $content_struct['user_login'] ) )
                        return new IXR_Error(401, __('Username cannot be changed'));

                if ( isset( $content_struct['user_email'] ) ) {

                        if( ! is_email( $content_struct['user_email'] ) )
                                return new IXR_Error( 403, __( 'Email id is not valid' ) );
                        // check whether it is already registered
                        if( email_exists( $content_struct['user_email'] ) )
                                return new IXR_Error( 403, __( 'This email address is already registered' ) );
                        $user_data['user_email'] = $content_struct['user_email'];
                        
                }

                if( isset ( $content_struct['role'] ) ) {

                        if ( ! current_user_can( 'edit_users' ) )
                                return new IXR_Error( 401, __( 'You are not allowed to change roles for this user' ) );

                        if( ! isset ( $wp_roles ) )
                                $wp_roles = new WP_Roles ();
                        if( !array_key_exists( $content_struct['role'], $wp_roles->get_names() ) )
                                return new IXR_Error( 403, __( 'The role specified is not valid' ) );
                        $user_data['role'] = $content_struct['role'];
                        
                }

                // only set the user details if it was given
                if ( isset( $content_struct['first_name'] ) )
                        $user_data['first_name'] = $content_struct['first_name'];

                if ( isset( $content_struct['last_name'] ) )
                        $user_data['last_name'] = $content_struct['last_name'];

                if ( isset( $content_struct['user_url'] ) )
                        $user_data['user_url'] = $content_struct['user_url'];

                if ( isset( $content_struct['nickname'] ) )
                        $user_data['nickname'] = $content_struct['nickname'];

                if ( isset( $content_struct['user_nicename'] ) )
                        $user_data['user_nicename'] = $content_struct['user_nicename'];

                if ( isset( $content_struct['description'] ) )
                        $user_data['description'] = $content_struct['description'];

                if( isset ( $content_struct['usercontacts'] ) ) {

                        $user_contacts = _wp_get_user_contactmethods( $user_data );
                        foreach( $content_struct['usercontacts'] as $key => $value ) {

                                if( ! array_key_exists( $key, $user_contacts ) )
                                        return new IXR_Error( 401, __( 'One of the contact method specified is not valid' ) );
                                $user_data[ $key ] = $value;

                        }

                }

                if( isset ( $content_struct['user_pass'] ) )
                        $user_data['user_pass'] = $content_struct['user_pass'];

                $result = wp_update_user( $user_data );

                if ( is_wp_error( $result ) )
                            return new IXR_Error( 500, $result->get_error_message() );

                if ( ! $result )
                            return new IXR_Error( 500, __( 'Sorry, the user cannot be updated. Something wrong happened.' ) );

                return $result;
        
}

/**
 * Delete a  post
 *
 * @uses wp_delete_user()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - array   $user_ids
 * @return array user_ids
 */
function wp_deleteUser( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID    = (int) $args[0];
                $username   = $args[1];
                $password   = $args[2];
                $user_IDs   = $args[3]; // can be an array of user ID's

                if( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                if( ! current_user_can( 'delete_users' ) )
                                return new IXR_Error( 401, __( 'You are not allowed to delete users.' ) );

                // if only a single ID is given convert it to an array
                if( ! is_array( $user_IDs ) )
                    $user_IDs = array( (int)$user_IDs );

                foreach( $user_IDs as $user_ID ) {

                        $user_ID = (int) $user_ID;

                        if( ! get_userdata( $user_ID ) )
                                return new IXR_Error(404, __('Sorry, one of the given user does not exist.'));
                        
                        if( $user->ID == $user_ID )
                                return new IXR_Error( 401, __( 'You cannot delete yourself.' ) );

                }

                // this holds all the id of deleted users and return it
                $deleted_users = array();

                foreach( $user_IDs as $user_ID ) {

                        $result = wp_delete_user( $user_ID );
                        if ( $result )
                                $deleted_users[] = $user_ID;

                }

                return $deleted_users;

}

/**
 * Retrieve  user
 *
 * @uses get_userdata()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - int     $user_id
 * @return array contains:
 *  - 'user_login'
 *  - 'user_firstname'
 *  - 'user_lastname'
 *  - 'user_registered'
 *  - 'user_description'
 *  - 'user_email'
 *  - 'nickname'
 *  - 'user_nicename'
 *  - 'user_url'
 *  - 'display_name'
 *  - 'usercontacts'
 */
function wp_getUser( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID    = (int) $args[0];
                $username   = $args[1];
                $password   = $args[2];
                $user_ID    = (int) $args[3];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                $user_data = get_userdata( $user_ID );

                if( ! $user_data )
                        return new IXR_Error(404, __('Invalid user ID'));

                if( ! ( $user_ID == $user->ID || current_user_can( 'edit_users' ) ))
                        return new IXR_Error( 401, __( 'Sorry, you cannot edit users.' ) );
                
                $user_data = (array)$user_data;

                $contact_methods = _wp_get_user_contactmethods();
                foreach( $contact_methods as $key => $value ) {
                    $user_contacts[ $key ] = $user_data[ $key ];
                }

                $struct = array(
                        'user_login'        => $user_data['user_login'],
                        'user_firstname'    => $user_data['user_firstname'],
                        'user_lastname'     => $user_data['user_lastname'],
                        'user_registered'   => $user_data['user_registered'],
                        'user_description'  => $user_data['user_description'],
                        'user_email'        => $user_data['user_email'],
                        'nickname'          => $user_data['nickname'],
                        'user_nicename'     => $user_data['user_nicename'],
                        'user_url'          => $user_data['user_url'],
                        'display_name'      => $user_data['display_name'],
                        'wp_capabilities'   => $user_data['wp_capabilities'],
                        'wp_user_level'     => $user_data['wp_user_level'],
                        'usercontacts'      => $user_contacts,
                );

                return $struct;

}

/**
 * Retrieve  users
 *
 * @uses get_users()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - array   $filter optional
 * @return array contatins:
 *  - 'ID'
 *  - 'user_login'
 *  - 'user_registered'
 *  - 'user_email'
 *  - 'user_url'
 *  - 'display_name'
 *  - 'user_nicename' 
 */
function wp_getUsers( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID    = (int) $args[0];
                $username   = $args[1];
                $password   = $args[2];
                $filter     = $args[3];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                if( ! current_user_can( 'edit_users' ))
                        return new IXR_Error( 401, __( 'Sorry, you cannot edit this users.' ) );

                $query = array();

                if ( isset( $filter['role'] ) ) {

                        if( ! isset ( $wp_roles ) )
                                $wp_roles = new WP_Roles ();
                        if( ! array_key_exists( $filter['role'], $wp_roles->get_names() ) )
                                return new IXR_Error( 403, __( 'The role specified is not valid' ) );
                        $query['role'] = $filter['role'];
                        
                }
                
                $query['number'] = 50; // default value for querying users
                if ( isset( $filter['numberusers'] ) )
                                $query['number'] = absint( $filter['numberusers'] );

                $users = get_users( $query );

                if ( ! $users )
                        return array( );

                // holds all the user data
                $struct = array();

                foreach ( $users as $user_data ) {

                        $user_data = (array) $user_data;

                        $struct[] = array(
                                'ID'                => $user_data['ID'],
                                'user_login'        => $user_data['user_login'],
                                'user_registered'   => $user_data['user_registered'],
                                'user_email'             => $user_data['user_email'],
                                'user_url'        => $user_data['user_url'],
                                'display_name'      => $user_data['display_name'],
                                'user_nicename'     => $user_data['user_nicename'],

                        );

                }

                return $struct;

}

/**
 * Create a new post
 *
 * @uses wp_insert_post()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - array     $content_struct.
 *      The $content_struct must contain:
 *      - 'post_type'
 *      Also, it can optionally contain:
 *      - 'post_status'
 *      - 'wp_password'
 *      - 'wp_slug
 *      - 'wp_page_order'
 *      - 'wp_page_parent_id'
 *      - 'wp_page_template'
 *      - 'wp_author_id'
 *      - 'title'
 *      - 'description'
 *      - 'mt_excerpt'
 *      - 'mt_allow_comments'
 *      - 'mt_allow_pings'
 *      - 'mt_text_more'
 *      - 'mt_tb_ping_urls'
 *      - 'date_created_gmt'
 *      - 'dateCreated'
 *      - 'sticky'
 *      - 'custom_fields'
 *      - 'terms'
 *      - 'categories'
 *      - 'mt_keywords'
 *      - 'wp_post_format'
 *  - boolean $publish optional. Defaults to true
 * @return string post_id
 */
function wp_newPost( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape($args);

                $blog_ID        = (int) $args[0]; // for future use
                $username       = $args[1];
                $password       = $args[2];
                $content_struct = $args[3];
                $publish        = isset( $args[4] ) ? $args[4] : false;

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;
                
                $post_type = get_post_type_object( $content_struct['post_type'] );
                if( ! ( (bool)$post_type ) )
                        return new IXR_Error( 403, __( 'Invalid post type' ) );

                if( ! current_user_can( $post_type->cap->edit_posts ) )
                        return new IXR_Error( 401, __( 'Sorry, you are not allowed to create posts in this post type' ));

                // this holds all the post data needed
                $post_data = array();
                $post_data['post_type'] = $content_struct['post_type'];

                $post_data['post_status'] = $publish ? 'publish' : 'draft';

                if( isset ( $content_struct["{$content_struct['post_type']}_status"] ) )
                        $post_data['post_status'] = $content_struct["{$post_data['post_type']}_status"];


                switch ( $post_data['post_status'] ) {

                        case 'draft':
                                break;
                        case 'pending':
                                break;
                        case 'private':
                                if( ! current_user_can( $post_type->cap->publish_posts ) )
                                        return new IXR_Error( 401, __( 'Sorry, you are not allowed to create private posts in this post type' ));
                                break;
                        case 'publish':
                                if( ! current_user_can( $post_type->cap->publish_posts ) )
                                        return new IXR_Error( 401, __( 'Sorry, you are not allowed to publish posts in this post type' ));
                                break;
                        default:
                                return new IXR_Error( 401, __( 'Invalid post status' ) );
                                break;
                                
                }

                // Only use a password if one was given.
                if ( isset( $content_struct['wp_password'] ) ) {

                        if( ! current_user_can( $post_type->cap->publish_posts ) )
                                return new IXR_Error( 401, __( 'Sorry, you are not allowed to create password protected posts in this post type' ) );
                        $post_data['post_password'] = $content_struct['wp_password'];
                        
                }

                // Let WordPress generate the post_name (slug) unless one has been provided.
                $post_data['post_name'] = "";
                if ( isset( $content_struct['wp_slug'] ) )
                        $post_data['post_name'] = $content_struct['wp_slug'];

                if ( isset( $content_struct['wp_page_order'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'] , 'page-attributes' ) )
                                return new IXR_Error( 401, __( 'This post type does not support page attributes.' ) );

                        $post_data['menu_order'] = (int)$content_struct['wp_page_order'];
                
                }

                if ( isset( $content_struct['wp_page_parent_id'] ) ) {

                        if( ! $post_type->hierarchical )
                                return new IXR_Error( 401, __( 'This post type does not support post hierarchy.' ) );

                        // validating parent ID
                        $parent_ID = (int)$content_struct['wp_page_parent_id'];
                        if( $parent_ID != 0 ) {

                                $parent_post = (array)wp_get_single_post( $parent_ID );
                                if ( empty( $parent_post['ID'] ) )
                                        return new IXR_Error( 401, __( 'Invalid parent ID.' ) );

                                if ( $parent_post['post_type'] != $content_struct['post_type'] )
                                        return new IXR_Error( 401, __( 'The parent post is of different post type.' ) );

                        }

                        $post_data['post_parent'] = $content_struct['wp_page_parent_id'];

                }

                // page template is only supported only by pages
                if ( isset( $content_struct['wp_page_template'] ) ) {

                        if( $content_struct['post_type'] != 'page'  )
                                return new IXR_Error( 401, __( 'Page templates are only supported by pages.' ) );

                        // validating page template
                        $page_templates = get_page_templates( );
                        $page_templates['Default'] = 'default';
                        
                        if( ! array_key_exists( $content_struct['wp_page_template'], $page_templates ) )
                                return new IXR_Error( 403, __( 'Invalid page template.' ) );

                        $post_data['page_template'] = $content_struct['wp_page_template'];

                }

                $post_data['post_author '] = $user->ID;

                // If an author id was provided then use it instead.
                if( isset( $content_struct['wp_author_id'] ) && ( $user->ID != (int)$content_struct['wp_author_id'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'] , 'author' ) )
                                return new IXR_Error( 401, __( 'This post type does not support to set author.' ) );

                        if( ! current_user_can( $post_type->cap->edit_others_posts ) )
                                return new IXR_Error( 401, __( 'You are not allowed to create posts as this user.' ) );
                        
                        $author_ID = (int)$content_struct['wp_author_id'];

                        $author = get_userdata( $author_ID );
                        if( ! $author )
                                return new IXR_Error( 404, __( 'Invalid author ID.' ) );
                        
                        $post_data['post_author '] = $author_ID;
                        
                }

                if( isset ( $content_struct['title'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'] , 'title' ) )
                                return new IXR_Error( 401, __('This post type does not support title attribute.') );
                        $post_data['post_title'] = $content_struct['title'];
                        
                }

                if( isset ( $content_struct['description'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'] , 'editor' ) )
                                return new IXR_Error( 401, __( 'This post type does not support post content.' ) );
                        $post_data['post_content'] = $content_struct['description'];
                        
                }

                if( isset ( $content_struct['mt_excerpt'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'] , 'excerpt' ) )
                                return new IXR_Error( 401, __( 'This post type does not support post excerpt.' ) );
                        $post_data['post_excerpt'] = $content_struct['mt_excerpt'];
                        
                }

                if( post_type_supports( $content_struct['post_type'], 'comments' ) ) {

                        $post_data['comment_status'] = get_option('default_comment_status');

                        if( isset( $content_struct['mt_allow_comments'] ) ) {

                                if( ! is_numeric( $content_struct['mt_allow_comments'] ) ) {

                                        switch ( $content_struct['mt_allow_comments'] ) {
                                                case 'closed':
                                                        $post_data['comment_status']= 'closed';
                                                        break;
                                                case 'open':
                                                        $post_data['comment_status'] = 'open';
                                                        break;
                                                default:
                                                        return new IXR_Error( 401, __( 'Invalid comment option.' ) );
                                        }

                                } else {

                                        switch ( (int) $content_struct['mt_allow_comments'] ) {
                                                case 0: // for backward compatiblity
                                                case 2:
                                                        $post_data['comment_status'] = 'closed';
                                                        break;
                                                case 1:
                                                        $post_data['comment_status'] = 'open';
                                                        break;
                                                default:
                                                        return new IXR_Error( 401, __( 'Invalid comment option.' ) );
                                        }

                                }
                                
                        }

                } else {

                        if( isset( $content_struct['mt_allow_comments'] ) )
                               return new IXR_Error( 401, __( 'This post type does not support comments.' ) );

                }


                if( post_type_supports( $content_struct['post_type'], 'trackbacks' ) ) {

                        $post_data['ping_status'] = get_option('default_ping_status');

                        if( isset( $content_struct['mt_allow_pings'] ) ) {

                                if ( ! is_numeric( $content_struct['mt_allow_pings'] ) ) {

                                        switch ( $content_struct['mt_allow_pings'] ) {
                                                case 'closed':
                                                        $post_data['ping_status']= 'closed';
                                                        break;
                                                case 'open':
                                                        $post_data['ping_status'] = 'open';
                                                        break;
                                                default:
                                                        return new IXR_Error( 401, __( 'Invalid ping option.' ) );
                                        }

                                } else {

                                        switch ( (int) $content_struct['mt_allow_pings'] ) {
                                                case 0:
                                                case 2:
                                                        $post_data['ping_status'] = 'closed';
                                                        break;
                                                case 1:
                                                        $post_data['ping_status'] = 'open';
                                                        break;
                                                default:
                                                        return new IXR_Error( 401, __( 'Invalid ping option.' ) );
                                        }

                                }

                        }

                } else {

                        if( isset( $content_struct['mt_allow_pings'] ) )
                               return new IXR_Error( 401, __( 'This post type does not support trackbacks.' ) );

                }

                $post_data['post_more'] = null;
                if( isset( $content_struct['mt_text_more'] ) ) {

                        $post_data['post_more'] = $content_struct['mt_text_more'];
                        $post_data['post_content'] = $post_data['post_content'] . '<!--more-->' . $post_data['post_more'];
                        
                }

                $post_data['to_ping'] = null;
                if ( isset( $content_struct['mt_tb_ping_urls'] ) ) {

                        $post_data['to_ping'] = $content_struct['mt_tb_ping_urls'];
                        if ( is_array( $to_ping ) )
                                $post_data['to_ping'] = implode(' ', $to_ping);
                        
                }

                // Do some timestamp voodoo
                if ( ! empty( $content_struct['date_created_gmt'] ) )
                        $dateCreated = str_replace( 'Z', '', $content_struct['date_created_gmt']->getIso() ) . 'Z'; // We know this is supposed to be GMT, so we're going to slap that Z on there by force
                elseif ( !empty( $content_struct['dateCreated']) )
                        $dateCreated = $content_struct['dateCreated']->getIso();

                if ( ! empty( $dateCreated ) ) {
                        $post_data['post_date'] = get_date_from_gmt( iso8601_to_datetime( $dateCreated ) );
                        $post_data['post_date_gmt'] = iso8601_to_datetime( $dateCreated, 'GMT' );
                } else {
                        $post_data['post_date'] = current_time('mysql');
                        $post_data['post_date_gmt'] = current_time('mysql', 1);
                }

                // we got everything we need
                $post_ID = wp_insert_post( $post_data, true );

                if ( is_wp_error( $post_ID ) )
                        return new IXR_Error( 500, $post_ID->get_error_message() );

                if ( ! $post_ID )
                        return new IXR_Error( 401, __( 'Sorry, your entry could not be posted. Something wrong happened.' ) );

                // the default is to unstick
                if( $content_struct['post_type'] == 'post' ) {

                        $sticky = $content_struct['sticky'] ? true : false;
                        if( $sticky ) {

                                if( $post_data['post_status'] != 'publish' )
                                        return new IXR_Error( 401, __( 'Only published posts can be made sticky.' ));

                                if( ! current_user_can( $post_type->cap->edit_others_posts ) )
                                        return new IXR_Error( 401, __( 'Sorry, you are not allowed to stick this post.' ) );

                                stick_post( $post_ID );


                        } else {

                                unstick_post( $post_ID );

                        }

                } else {

                        if( isset ( $content_struct['sticky'] ) )
                                return new IXR_Error( 401, __( 'Sorry, only posts can be sticky.' ) );
                        
                }

                if( isset ( $content_struct['custom_fields'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'], 'custom-fields' ) )
                                return new IXR_Error( 401, __( 'This post type does not support custom fields.' ) );
                        $wp_xmlrpc_server->set_custom_fields( $post_ID, $content_struct['custom_fields'] );
                        
                }               

                $post_type_taxonomies = get_object_taxonomies( $content_struct['post_type'] );

                if( isset( $content_struct['terms'] ) ) {

                        $terms = $content_struct['terms'];
                        $taxonomies = array_keys( $terms );

                        // validating term ids
                        foreach( $taxonomies as $taxonomy ) {

                                if( ! in_array( $taxonomy , $post_type_taxonomies ) )
                                        return new IXR_Error( 401, __( 'Sorry, one of the given taxonomy is not supported by the post type.' ));

                                $term_ids = $terms[ $taxonomy ];
                                foreach ( $term_ids as $term_id) {

                                        $term = get_term( $term_id, $taxonomy );

                                        if ( is_wp_error( $term ) )
                                                return new IXR_Error( 500, $term->get_error_message() );

                                        if ( ! $term )
                                                return new IXR_Error( 401, __( 'Invalid term ID' ) );

                                }

                        }

                        foreach( $taxonomies as $taxonomy ) {

                                $term_ids = $terms[ $taxonomy ];
                                $term_ids = array_map( 'intval', $term_ids );
                                $term_ids = array_unique( $term_ids );
                                wp_set_object_terms( $post_ID , $term_ids, $taxonomy , $append);

                        }

                        return true;

                }

                // backward compatiblity
		if ( isset( $content_struct['categories'] ) ) {

                        if( ! in_array( 'category', $post_type_taxonomies ) )
                                return new IXR_Error( 401, __( 'Sorry, Categories are not supported by the post type' ));
                        
			$category_names = $content_struct['categories'];

                        foreach( $category_names as $category_name ) {
                                $category_ID = get_cat_ID( $category_name );
                                if( ! $category_ID )
                                        return new IXR_Error( 401, __( 'Sorry, one of the given categories does not exist!' ));
                                $post_categories[] = $category_ID;
                        }

                        wp_set_post_categories ($post_ID, $post_categories );

		}

                if( isset( $content_struct['mt_keywords'] ) ) {

                        if( ! in_array( 'post_tag' , $post_type_taxonomies ) )
                                return new IXR_Error( 401, __( 'Sorry, post tags are not supported by the post type' ));

                        wp_set_post_terms( $post_id, $tags, 'post_tag', false); // append is set false here

                }

                if( isset( $content_struct['wp_post_format'] ) ) {

                        if( ! in_array( 'post_format' , $post_type_taxonomies ) )
                                return new IXR_Error( 401, __( 'Sorry, post formats are not supported by the post type' ));
                        
                        wp_set_post_terms( $post_ID, array( 'post-format-' . $content_struct['wp_post_format'] ), 'post_format' );

                }

                // Handle enclosures
                $thisEnclosure = isset($content_struct['enclosure']) ? $content_struct['enclosure'] : null;
                $wp_xmlrpc_server->add_enclosure_if_new($post_ID, $thisEnclosure);
                $wp_xmlrpc_server->attach_uploads( $post_ID, $post_data['post_content'] );

                return strval( $post_ID );

}

/**
 * Edit a  post
 *
 * @uses wp_update_post()
 * @param array $args Method parameters. Contains:
 *  - int     $post_id
 *  - string  $username
 *  - string  $password
 *  - array     $content_struct
 *  - boolean $publish optional. Defaults to true
 * @return string post_id
 */
function wp_editPost($args) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape($args);

                $post_ID        = (int) $args[0];
                $username       = $args[1];
                $password       = $args[2];
                $content_struct = $args[3];
                $publish        = isset( $args[4] ) ? $args[4] : false;

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                $post = wp_get_single_post( $post_ID, ARRAY_A );
		if ( empty( $post['ID'] ) )
			return new IXR_Error( 404, __( 'Invalid post ID.' ) );
                
                $post_type = get_post_type_object( $post['post_type'] );
                if( ! current_user_can( $post_type->cap->edit_posts, $post_ID ) )
                        return new IXR_Error( 401, __( 'Sorry, you are not allowed to create posts in this post type' ));

                // this holds all the post data needed
                $post_data = array();
                $post_data['ID'] = $post_ID;
                $post_data['post_status'] = $publish ? 'publish' : 'draft';

                if( isset ( $content_struct["{$content_struct['post_type']}_status"] ) )
                        $post_data['post_status'] = $content_struct["{$post_data['post_type']}_status"];


                switch ( $post_data['post_status'] ) {

                        case 'draft':
                                break;
                        case 'pending':
                                break;
                        case 'private':
                                if( ! current_user_can( $post_type->cap->publish_posts, $post_ID ) )
                                        return new IXR_Error( 401, __( 'Sorry, you are not allowed to create private posts in this post type' ));
                                break;
                        case 'publish':
                                if( ! current_user_can( $post_type->cap->publish_posts, $post_ID ) )
                                        return new IXR_Error( 401, __( 'Sorry, you are not allowed to publish posts in this post type' ));
                                break;
                        default:
                                return new IXR_Error( 401, __( 'The post status specified is not valid' ) );
                                break;

                }

                // Only use a password if one was given.
                if ( isset( $content_struct['wp_password'] ) ) {

                        if( ! current_user_can( $post_type->cap->publish_posts ) )
                                return new IXR_Error( 401, __( 'Sorry, you are not allowed to create password protected posts in this post type' ));
                        $post_data['post_password'] = $content_struct['wp_password'];

                }

                if ( isset( $content_struct['wp_slug'] ) )
                        $post_data['post_name'] = $content_struct['wp_slug'];

                if ( isset( $content_struct['wp_page_order'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'] , 'page-attributes' ) )
                                return new IXR_Error( 401, __( 'This post type does not support page attributes' ) );

                        $post_data['menu_order'] = $content_struct['wp_page_order'];

                }

                if ( isset( $content_struct['wp_page_parent_id'] ) ) {

                        if( ! $post_type->hierarchical )
                                return new IXR_Error(401, __('This post type does not support post hierarchy'));

                        // validating parent ID
                        $parent_ID = (int)$content_struct['wp_page_parent_id'];
                        if( $parent_ID != 0 ) {

                                $parent_post = (array)wp_get_single_post( $parent_ID );
                                if ( empty( $parent_post['ID'] ) )
                                        return new IXR_Error( 401, __( 'Invalid parent ID.' ) );

                                if ( $parent_post['post_type'] != $content_struct['post_type'] )
                                        return new IXR_Error( 401, __( 'The parent post is of different post type' ) );

                        }

                        $post_data['post_parent'] = $content_struct['wp_page_parent_id'];

                }

                // page template is only supported only by pages
                if ( isset( $content_struct['wp_page_template'] ) ) {

                        if( $content_struct['post_type'] != 'page'  )
                                return new IXR_Error(401, __('Page templates are only supported by pages'));

                        // validating page template
                        $page_templates = get_page_templates( );
                        $page_templates['Default'] = 'default';

                        if( ! array_key_exists( $content_struct['wp_page_template'], $page_templates ) )
                                return new IXR_Error( 403, __( 'Invalid page template.' ) );

                        $post_data['page_template'] = $content_struct['wp_page_template'];

                }


                // If an author id was provided then use it instead.
                if( isset( $content_struct['wp_author_id'] ) && ( $user->ID != $content_struct['wp_author_id'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'] , 'author' ) )
                                return new IXR_Error( 401, __( 'This post type does not support to set author.' ) );

                        if( ! current_user_can( $post_type->cap->edit_others_posts ) )
                                return new IXR_Error( 401, __( 'You are not allowed to create posts as this user.' ) );

                        $author_ID = (int)$content_struct['wp_author_id'];
                        
                        $author = get_userdata( $author_ID );
                        if( ! $author )
                                return new IXR_Error( 404, __( 'Invalid author ID.' ) );

                        $post_data['post_author '] = $author_ID;

                }


                if( isset ( $content_struct['title'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'] , 'title' ) )
                                return new IXR_Error( 401, __( 'This post type does not support title attribute.' ) );
                        $post_data['post_title'] = $content_struct['title'];

                }

                if( isset ( $content_struct['post_content'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'] , 'editor' ) )
                                return new IXR_Error( 401, __( 'This post type does not support post content.' ) );
                        $post_data['post_content'] = $content_struct['post_content'];

                }

                if( isset ( $content_struct['mt_excerpt'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'] , 'excerpt' ) )
                                return new IXR_Error( 401, __( 'This post type does not support post excerpt.' ) );
                        $post_data['post_excerpt'] = $content_struct['mt_excerpt'];

                }

                if( isset( $content_struct['mt_allow_comments'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'], 'comments' ) )
                                return new IXR_Error( 401, __( 'This post type does not support comments.' ) );

                        if ( ! is_numeric( $content_struct['mt_allow_comments'] ) ) {

                                switch ( $content_struct['mt_allow_comments'] ) {
                                        case 'closed':
                                                $post_data['comment_status']= 'closed';
                                                break;
                                        case 'open':
                                                $post_data['comment_status'] = 'open';
                                                break;
                                        default:
                                                return new IXR_Error( 401, __ ( 'Invalid comment option' ) );
                                }

                        } else {

                                switch ( (int) $content_struct['mt_allow_comments'] ) {
                                        case 0:
                                        case 2:
                                                $post_data['comment_status'] = 'closed';
                                                break;
                                        case 1:
                                                $post_data['comment_status'] = 'open';
                                                break;
                                        default:
                                                return new IXR_Error( 401, __ ( 'Invalid ping option.' ) );
                                }

                        }

                }


                if( isset( $content_struct['mt_allow_pings'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'], 'trackbacks' ) )
                                return new IXR_Error(401, __('This post type does not support trackbacks'));

                        if ( ! is_numeric( $content_struct['mt_allow_pings'] ) ) {

                                switch ( $content_struct['mt_allow_pings'] ) {
                                        case 'closed':
                                                $post_data['ping_status']= 'closed';
                                                break;
                                        case 'open':
                                                $post_data['ping_status'] = 'open';
                                                break;
                                        default:
                                                break;
                                }

                        } else {

                                switch ( (int) $content_struct['mt_allow_pings'] ) {
                                        case 0:
                                        case 2:
                                                $post_data['ping_status'] = 'closed';
                                                break;
                                        case 1:
                                                $post_data['ping_status'] = 'open';
                                                break;
                                        default:
                                                break;
                                }

                        }

                }

                if( isset( $content_struct['mt_text_more'] ) ) {

                        $post_data['post_more'] = $content_struct['mt_text_more'];
                        $post_data['post_content'] = $post_data['post_content'] . '<!--more-->' . $post_data['post_more'];

                }

                if ( isset( $content_struct['mt_tb_ping_urls'] ) ) {

                        $post_data['to_ping'] = $content_struct['mt_tb_ping_urls'];
                        if ( is_array($to_ping) )
                                $post_data['to_ping'] = implode(' ', $to_ping);
                        
                }

                // Do some timestamp voodoo
                if ( ! empty( $content_struct['date_created_gmt'] ) )
                        $dateCreated = str_replace( 'Z', '', $content_struct['date_created_gmt']->getIso() ) . 'Z'; // We know this is supposed to be GMT, so we're going to slap that Z on there by force
                elseif ( !empty( $content_struct['dateCreated']) )
                        $dateCreated = $content_struct['dateCreated']->getIso();

                if ( ! empty( $dateCreated ) ) {

                        $post_data['post_date'] = get_date_from_gmt(iso8601_to_datetime($dateCreated));
                        $post_data['post_date_gmt'] = iso8601_to_datetime($dateCreated, 'GMT');
                        
                } 

                // we got everything we need
                $post_ID = wp_update_post( $post_data, true );

                if ( is_wp_error( $post_ID ) )
                        return new IXR_Error(500, $post_ID->get_error_message());

                if ( ! $post_ID )
                        return new IXR_Error(500, __('Sorry, your entry could not be posted. Something wrong happened.'));

                if( isset ( $content_struct['sticky'] ) ) {

                        $sticky = $content_struct['sticky'] ? true : false;
                        
                        if( $sticky ) {

                                if( $post_data['post_status'] != 'publish' )
                                        return new IXR_Error( 401, __( 'Only published posts can be made sticky.' ));

                                if( ! current_user_can( $post_type->cap->edit_others_posts ) )
                                        return new IXR_Error( 401, __( 'Sorry, you are not allowed to stick this post.' ) );

                                stick_post( $post_ID );

                        } else {

                                unstick_post( $post_ID );

                        }
                    
                }

                if( isset ( $content_struct['custom_fields'] ) ) {

                        if( ! post_type_supports( $content_struct['post_type'], 'custom-fields' ) )
                                return new IXR_Error(401, __('This post type does not support custom fields'));
                        $wp_xmlrpc_server->set_custom_fields( $post_ID, $content_struct['custom_fields'] );
                        
                }

                $post_type_taxonomies = get_object_taxonomies( $post['post_type'] );

                if( isset( $content_struct['terms'] ) ) {

                        $terms = $content_struct['terms'];
                        $taxonomies = array_keys( $terms );

                        // validating term ids
                        foreach( $taxonomies as $taxonomy ) {

                                if( ! in_array( $taxonomy , $post_type_taxonomies ) )
                                        return new IXR_Error( 401, __( 'Sorry, one of the given taxonomy is not supported by the post type.' ));

                                $term_ids = $terms[ $taxonomy ];
                                foreach ( $term_ids as $term_id) {

                                        $term = get_term( $term_id, $taxonomy );

                                        if ( is_wp_error( $term ) )
                                                return new IXR_Error( 500, $term->get_error_message() );

                                        if ( ! $term )
                                                return new IXR_Error( 401, __( 'Invalid term ID' ) );

                                }

                        }

                        foreach( $taxonomies as $taxonomy ) {

                                $term_ids = $terms[ $taxonomy ];
                                $term_ids = array_map( 'intval', $term_ids );
                                $term_ids = array_unique( $term_ids );
                                wp_set_object_terms( $post_ID , $term_ids, $taxonomy , $append);

                        }

                        return true;

                }
                
                // backward compatiblity
		if ( isset( $content_struct['categories'] ) ) {

                        if( ! in_array( 'category', $post_type_taxonomies ) )
                                return new IXR_Error( 401, __( 'Sorry, Categories are not supported by the post type' ));

			$category_names = $content_struct['categories'];

                        foreach( $category_names as $category_name ) {
                                $category_ID = get_cat_ID( $category_name );
                                if( ! $category_ID )
                                        return new IXR_Error( 401, __( 'Sorry, one of the given categories does not exist!' ));
                                $post_categories[] = $category_ID;
                        }

                        wp_set_post_categories ($post_ID, $post_categories );

		}

                if( isset( $content_struct['mt_keywords'] ) ) {

                        if( ! in_array( 'post_tag' , $post_type_taxonomies ) )
                                return new IXR_Error( 401, __( 'Sorry, post tags are not supported by the post type' ));

                        wp_set_post_terms( $post_id, $tags, 'post_tag', false); // append is set false here
                }

                if( isset( $content_struct['wp_post_format'] ) ) {

                        if( ! in_array( 'post_format' , $post_type_taxonomies ) )
                                return new IXR_Error( 401, __( 'Sorry, post formats are not supported by the post type' ));

                        wp_set_post_terms( $post_ID, array( 'post-format-' . $content_struct['wp_post_format'] ), 'post_format' );

                }

                // Handle enclosures
                $thisEnclosure = isset($content_struct['enclosure']) ? $content_struct['enclosure'] : null;
                $wp_xmlrpc_server->add_enclosure_if_new($post_ID, $thisEnclosure);
                $wp_xmlrpc_server->attach_uploads( $post_ID, $post_data['post_content'] );

                return strval( $post_ID );

}

/**
 * Delete a  post
 *
 * @uses wp_delete_post()
 * @param array $args Method parameters. Contains:
 *  - int     $post_ids
 *  - string  $username
 *  - string  $password
 * @return array post_ids
 */
function wp_deletePost( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

		$post_IDs   = $args[0]; // this could be an array
		$username   = $args[1];
		$password   = $args[2];


		if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
			return $wp_xmlrpc_server->error;

                if( ! is_array( $post_IDs ) )
                    $post_IDs = array( (int)$post_IDs );

                foreach ( $post_IDs as $post_ID ) {

                        $post_ID = (int)$post_ID;

                        $post = wp_get_single_post( $post_ID, ARRAY_A );
                        if ( empty( $post["ID"] ) )
                                return new IXR_Error( 404, __( 'One of the post ID is invalid.' ) );

                        $post_type = get_post_type_object( $post['post_type'] );
                        if( ! current_user_can( $post_type->cap->delete_post, $post_ID ) )
                                return new IXR_Error( 401, __( 'Sorry, you are not allowed to delete one of the posts.' ));
                    
                }

                // this holds all the id of deleted posts and return it
                $deleted_posts = array();

                foreach( $post_IDs as $post_ID ) {

                        $result = wp_delete_post( $post_ID );
                        if ( $result )
                                $deleted_posts[] = $post_ID;

                }

		return $deleted_posts;

}

/**
 * Retrieve  post
 *
 * @uses wp_get_single_post()
 * @param array $args Method parameters. Contains:
 *  - int     $post_id
 *  - string  $username
 *  - string  $password
 * @return array contains:
 *  - 'postid'
 *  - 'title'
 *  - 'description'
 *  - 'mt_excerpt'
 *  - 'post_status'
 *  - 'post_type'
 *  - 'wp_slug'
 *  - 'wp_password'
 *  - 'wp_page_order'
 *  - 'wp_page_parent_id'
 *  - 'wp_author_id'
 *  - 'mt_allow_comments'
 *  - 'mt_allow_pings'
 *  - 'dateCreated'
 *  - 'date_created_gmt'
 *  - 'userid'
 *  - 'sticky'
 *  - 'custom_fields'
 *  - 'terms'
 *  - 'link'
 *  - 'permaLink'
 *  - 'categories'
 *  - 'mt_keywords'
 *  - 'wp_post_format'
 */
function wp_getPost( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $post_ID            = (int) $args[0];
                $username           = $args[1];
                $password           = $args[2];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                $post = wp_get_single_post( $post_ID, ARRAY_A );
		if ( empty( $post["ID"] ) )
			return new IXR_Error( 404, __( 'Invalid post ID.' ) );

                $post_type = get_post_type_object( $post['post_type'] );
                if( ! current_user_can( $post_type->cap->edit_posts, $post_ID ) )
                        return new IXR_Error( 401, __( 'Sorry, you are not allowed to edit posts in this post type' ));

                //return $post;

                $post_date = mysql2date( 'Ymd\TH:i:s', $post['post_date'], false );
                $post_date_gmt = mysql2date( 'Ymd\TH:i:s', $post['post_date_gmt'], false );

                // For drafts use the GMT version of the post date
                if ( $post['post_status'] == 'draft' )
                        $post_date_gmt = get_gmt_from_date( mysql2date( 'Y-m-d H:i:s', $post['post_date'] ), 'Ymd\TH:i:s' );

                $post_content = get_extended( $post['post_content'] );
                $link = post_permalink( $post['ID'] );

                // Consider future posts as published
                if ( $post['post_status'] === 'future' )
                        $post['post_status'] = 'publish';

                // Get post format
                $post_format = get_post_format( $post_ID );
                if ( empty( $post_format ) )
                        $post_format = 'standard';

                $sticky = null;
                if( $post['post_type'] == 'post' ) {

                        $sticky = false;
                        if ( is_sticky( $post_ID ) )
                                $sticky = true;

                }


                $post_type_taxonomies = get_object_taxonomies( $post['post_type'] , 'names');
                $terms = wp_get_object_terms( $post_ID, $post_type_taxonomies );

                $enclosure = array();
                foreach ( (array) get_post_custom($post_ID) as $key => $val) {
                        if ($key == 'enclosure') {
                                foreach ( (array) $val as $enc ) {
                                        $encdata = split("\n", $enc);
                                        $enclosure['url'] = trim(htmlspecialchars($encdata[0]));
                                        $enclosure['length'] = (int) trim($encdata[1]);
                                        $enclosure['type'] = trim($encdata[2]);
                                        break 2;
                                }
                        }
                }


                // backward compatiblity
                $categories = array();
                $catids = wp_get_post_categories($post_ID);
                foreach($catids as $catid) {
                        $categories[] = get_cat_name($catid);
                }
                
                $tagnames = array();
                $tags = wp_get_post_tags( $post_ID );
                if ( !empty( $tags ) ) {
                        foreach ( $tags as $tag )
                                $tagnames[] = $tag->name;
                        $tagnames = implode( ', ', $tagnames );
                } else {
                        $tagnames = '';
                }

                $struct = array(
                            'postid'            => $post['ID'],
                            'title'             => $post['post_title'],
                            'description'       => $post_content['main'],
                            'mt_excerpt'        => $post['post_excerpt'],

                            'post_status'       => $post['post_status'],
                            'post_type'         => $post['post_type'],
                            'wp_slug'           => $post['post_name'],
                            'wp_password'       => $post['post_password'],
                    
                            'wp_page_order'     => $post['menu_order'],
                            'wp_page_parent_id' => $post['post_parent'],

                            'wp_author_id'      => $post['post_author'],

                            'mt_allow_comments' => $post['comment_status'],
                            'mt_allow_pings'    => $post['ping_status'],
                    
                            'dateCreated'       => new IXR_Date($post_date),
                            'date_created_gmt'  => new IXR_Date($post_date_gmt),
                    
                            'userid'            => $post['post_author'],
                            'sticky'            => $sticky,
                            'custom_fields'     => $wp_xmlrpc_server->get_custom_fields( $post_ID ),
                            'terms'             => $terms,

                            'link'              => $link,
                            'permaLink'         => $link,
                    
                            // backward compatibility
                            'categories'	=> $categories,
                            'mt_keywords'       => $tagnames,
                            'wp_post_format'    => $post_format,

                );

                if ( ! empty( $enclosure ) )
                        $resp['enclosure'] = $enclosure;

                return $struct;


}

/**
 * Retrieve  posts
 *
 * @uses wp_get_recent_posts()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - array   $filter optional
 * @return array
 */
function wp_getPosts( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID    = (int) $args[0];
                $username   = $args[1];
                $password   = $args[2];
                $filter     = $args[3];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;


                $query = array();

                if ( isset( $filter['post_type'] ) ) {

                        $post_type = get_post_type_object( $filter['post_type'] );
                        if( ! ( (bool)$post_type ) )
                                return new IXR_Error( 403, __( 'The post type specified is not valid' ) );

                        if( ! current_user_can( $post_type->cap->edit_posts ) )
                                return new IXR_Error( 401, __( 'Sorry, you are not allowed to edit posts in this post type' ));
                        $query['post_type'] = $filter['post_type'];

                }

                $query['numberposts'] = apply_filters( 'xmlrpc_getPosts_maxvalue', 10 );// maximum value
                if ( isset ( $filter['numberposts'] ) ) {

                        if( absint( $filter['numberposts'] ) < 10 )
                                $query['number'] = absint( $filter['numberposts'] );

                }

		$posts = wp_get_recent_posts( $query );

		if ( ! $posts )
			return array( );

                // holds all the post data
                $struct = array();

                foreach ( $posts as $post ) {

                        $post_type = get_post_type_object( $post['post_type'] );

                        if( ! current_user_can( $post_type->cap->edit_posts ) )
                                continue;

                        $post_date = mysql2date( 'Ymd\TH:i:s', $post['post_date'], false );
                        $post_date_gmt = mysql2date( 'Ymd\TH:i:s', $post['post_date_gmt'], false );

                        // For drafts use the GMT version of the post date
                        if ( $post['post_status'] == 'draft' )
                                $post_date_gmt = get_gmt_from_date( mysql2date( 'Y-m-d H:i:s', $post['post_date'] ), 'Ymd\TH:i:s' );

                        $post_content = get_extended( $post['post_content'] );
                        $link = post_permalink( $post['ID'] );

                        // Consider future posts as published
                        if ( $post['post_status'] === 'future' )
                                $post['post_status'] = 'publish';

                        // Get post format
                        $post_format = get_post_format( $post_ID );
                        if ( empty( $post_format ) )
                                $post_format = 'standard';

                        $sticky = null;
                        if( $post['post_type'] == 'post' ) {

                                $sticky = false;
                                if ( is_sticky( $post_ID ) )
                                        $sticky = true;

                        }


                        $post_type_taxonomies = get_object_taxonomies( $post['post_type'] , 'names');
                        $terms = wp_get_object_terms( $post_ID, $post_type_taxonomies );

                        $enclosure = array();
                        foreach ( (array) get_post_custom($post_ID) as $key => $val) {
                                if ($key == 'enclosure') {
                                        foreach ( (array) $val as $enc ) {
                                                $encdata = split("\n", $enc);
                                                $enclosure['url'] = trim(htmlspecialchars($encdata[0]));
                                                $enclosure['length'] = (int) trim($encdata[1]);
                                                $enclosure['type'] = trim($encdata[2]);
                                                break 2;
                                        }
                                }
                        }


                        // backward compatiblity
                        $categories = array();
                        $catids = wp_get_post_categories($post_ID);
                        foreach($catids as $catid) {
                                $categories[] = get_cat_name($catid);
                        }

                        $tagnames = array();
                        $tags = wp_get_post_tags( $post_ID );
                        if ( !empty( $tags ) ) {
                                foreach ( $tags as $tag )
                                        $tagnames[] = $tag->name;
                                $tagnames = implode( ', ', $tagnames );
                        } else {
                                $tagnames = '';
                        }

                        $post_data[] = array(
                                    'postid'            => $post['ID'],
                                    'title'             => $post['post_title'],
                                    'description'       => $post_content['main'],
                                    'mt_excerpt'        => $post['post_excerpt'],

                                    'post_status'       => $post['post_status'],
                                    'post_type'         => $post['post_type'],
                                    'wp_slug'           => $post['post_name'],
                                    'wp_password'       => $post['post_password'],

                                    'wp_page_order'     => $post['menu_order'],
                                    'wp_page_parent_id' => $post['post_parent'],

                                    'wp_author_id'      => $post['post_author'],

                                    'mt_allow_comments' => $post['comment_status'],
                                    'mt_allow_pings'    => $post['ping_status'],

                                    'dateCreated'       => new IXR_Date($post_date),
                                    'date_created_gmt'  => new IXR_Date($post_date_gmt),

                                    'userid'            => $post['post_author'],
                                    'sticky'            => $sticky,
                                    'custom_fields'     => $wp_xmlrpc_server->get_custom_fields($post_ID),
                                    'terms'             => $terms,

                                    'link'              => $link,
                                    'permaLink'         => $link,

                                    // backward compatibility
                                    'categories'	=> $categories,
                                    'mt_keywords'       => $tagnames,
                                    'wp_post_format'    => $post_format,

                        );

                        if ( ! empty( $enclosure ) )
                                $post_data['enclosure'] = $enclosure;

                        $struct[] = $post_date;

                }
                
                return $struct;   
        
}

/**
 * Retrieve post terms
 *
 * @uses wp_get_object_terms()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - int     $post_id
 * @return array term data
 */
function wp_getPostTerms( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID            = (int) $args[0];
                $username           = $args[1];
                $password           = $args[2];
                $post_ID            = (int) $args[3];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                $post = wp_get_single_post( $post_ID, ARRAY_A );
		if ( empty( $post['ID'] ) )
			return new IXR_Error( 404, __( 'Invalid post ID.' ) );

                $post_type = get_post_type_object( $post['post_type'] );

                if( ! current_user_can( $post_type->cap->edit_post , $post_ID ) )
                        return new IXR_Error( 401, __( 'Sorry, You are not allowed to edit this post.' ));

                $taxonomies = get_taxonomies( '' );

                $terms = wp_get_object_terms( $post_ID , $taxonomies );

                if ( is_wp_error( $terms ) )
                        return new IXR_Error( 500 , $term->get_error_message());

                return $terms;
                
}

/**
 * Set post terms
 *
 * @uses wp_set_object_terms()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - int     $post_id
 *  - array   $content_struct contains term_ids with taxonomy as keys
 * @return boolean true
 */
function wp_setPostTerms( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape($args);

                $blog_ID            = (int) $args[0];
                $username           = $args[1];
                $password           = $args[2];
                $post_ID            = (int) $args[3];
                $content_struct     = $args[4];
                $append             = $args[5] ? true : false;


                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                $post = wp_get_single_post( $post_ID, ARRAY_A );
		if ( empty( $post['ID'] ) )
			return new IXR_Error( 404, __( 'Invalid post ID.' ) );

                $post_type = get_post_type_object( $post['post_type'] );

                if( ! current_user_can( $post_type->cap->edit_post , $post_ID ) )
                        return new IXR_Error( 401, __( 'Sorry, You are not allowed to edit this post.' ));

                $post_type_taxonomies = get_object_taxonomies( $post['post_type'] );

                $taxonomies = array_keys( $content_struct );

                // validating term ids
                foreach( $taxonomies as $taxonomy ) {
                    
                        if( ! in_array( $taxonomy , $post_type_taxonomies ) )
                                return new IXR_Error( 401, __( 'Sorry, one of the given taxonomy is not supported by the post type.' ));
    
                        $term_ids = $content_struct[ $taxonomy ];
                        foreach ( $term_ids as $term_id) {

                                $term = get_term( $term_id, $taxonomy );

                                if ( is_wp_error( $term ) )
                                        return new IXR_Error( 500, $term->get_error_message() );

                                if ( ! $term )
                                        return new IXR_Error( 401, __( 'Invalid term ID' ) );

                        }
                        
                }

                foreach( $taxonomies as $taxonomy ) {

                        $term_ids = $content_struct[ $taxonomy ];
                        $term_ids = array_map( 'intval', $term_ids );
                        $term_ids = array_unique( $term_ids );
                        wp_set_object_terms( $post_ID , $term_ids, $taxonomy , $append);
                        
                }

                return true;

}

/**
 * Retrieves a post type
 *
 * @uses get_post_type_object()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - string  $post_type_name
 * @return array contains:
 *  - 'labels'
 *  - 'description'
 *  - 'capability_type'
 *  - 'cap'
 *  - 'map_meta_cap'
 *  - 'hierarchical'
 *  - 'menu_position'
 *  - 'taxonomies'
 */
function wp_getPostType( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID        = (int) $args[0];
                $username       = $args[1];
                $password       = $args[2];
                $post_type_name = $args[3];

                if ( !$user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                $post_type_names = get_post_types('', 'names');

                if( ! in_array( $post_type_name, $post_type_names ) )
                        return new IXR_Error( 403, __( 'The post type specified is not valid' ) );

                $post_type = get_post_type_object( $post_type_name );

                //capability check
                if( ! current_user_can( $post_type->cap->edit_posts ) )
                        return new IXR_Error( 401, __( 'Sorry, you are not allowed to edit this post type' ) );

                $post_type = (array)$post_type;

                $post_type_data = array(
                                'labels'            => $post_type['labels'],
                                'description'       => $post_type['description'],
                                'capability_type'   => $post_type['capability_type'],
                                'cap'               => $post_type['cap'],
                                'map_meta_cap'      => $post_type['map_meta_cap'],
                                'hierarchical'      => $post_type['hierarchical'],
                                'menu_position'     => $post_type['menu_position'],
                                'taxonomies'        => get_object_taxonomies( $post_type['name'] ),
                        );

                return $post_type_data;
        
}

/**
 * Retrieves a post types
 *
 * @uses get_post_types()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 * @return array
 */
function wp_getPostTypes( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID            = (int) $args[0];
                $username           = $args[1];
                $password           = $args[2];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                $post_types = get_post_types( '','objects' );

                $struct = array();

                foreach( $post_types as $post_type ) {

                        // capability check for post_types
                        if( ! current_user_can( $post_type->cap->edit_posts ) )
                                continue;

                        $post_type = (array)$post_type;

                        $post_type_data = array(
                                'labels'            => $post_type['labels'],
                                'description'       => $post_type['description'],
                                'capability_type'   => $post_type['capability_type'],
                                'cap'               => $post_type['cap'],
                                'map_meta_cap'      => $post_type['map_meta_cap'],
                                'hierarchical'      => $post_type['hierarchical'],
                                'menu_position'     => $post_type['menu_position'],
                                'taxonomies'        => get_object_taxonomies( $post_type['name'] ),
                        );

                        $struct[ $post_type['name'] ] =  $post_type_data;

                }

                return $struct;
        
}

/**
 * Create a new term
 *
 * @uses wp_insert_term()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - array   $content_struct.
 *      The $content_struct must contain:
 *      - 'name'
 *      - 'taxonomy'
 *      Also, it can optionally contain:
 *      - 'parent'
 *      - 'description'
 *      - 'slug'
 * @return int term_id
 */
function wp_newTerm( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID            = (int) $args[0];
                $username           = $args[1];
                $password           = $args[2];
                $content_struct     = $args[3];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                if ( ! taxonomy_exists( $content_struct['taxonomy'] ) )
                        return new IXR_Error( 403, __( 'Invalid taxonomy' ) );

                $taxonomy = get_taxonomy( $content_struct['taxonomy'] );

                if( ! current_user_can( $taxonomy->cap->manage_terms ) )
                        return new IXR_Error( 401, __( 'You are not allowed to create terms in this taxonomy' ) );

                $taxonomy   = (array)$taxonomy;

                // hold the data of the term
                $term_data  = array();
                
                $term_data['name'] = trim( $content_struct['name'] );
                if ( empty ( $term_data['name'] ) )
                        return new IXR_Error( 403, __( 'The term name cannot be empty' ) );

                if( isset ( $content_struct['parent'] ) ) {

                        if( ! $taxonomy['hierarchical'] )
                                return new IXR_Error( 403, __( 'This taxonomy is not hieararchical' ) );

                        $parent_term_id = (int)$content_struct['parent'];
                        $parent_term = get_term( $parent_term_id , $taxonomy['name'] );

                        if ( is_wp_error( $parent_term ) )
                            return new IXR_Error( 500, $term->get_error_message() );

                        if ( ! $parent_term )
                            return new IXR_Error(500, __('Parent term does not exist'));

                        $term_data['parent'] =  $content_struct['parent'];
                        
                }

                $term_data['description'] = '';
                if( isset ( $content_struct['description'] ) )
                        $term_data['description'] = $content_struct['description'];

                $term_data['slug'] = '';
                if( isset ( $content_struct['slug'] ) )
                        $term_data['slug'] = $content_struct['slug'];

                $term_ID = wp_insert_term( $term_data['name'] , $taxonomy['name'] , $term_data );

                if ( is_wp_error( $term_ID ) )
                        return new IXR_Error(500, $term_ID->get_error_message());

                if ( ! $term_ID )
                        return new IXR_Error(500, __('Sorry, your entry could not be posted. Something wrong happened.'));

                return $term_ID;

}

/**
 * Edit a term
 *
 * @uses wp_update_term()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - int     $term_id
 *  - array   $content_struct.
 *      The $content_struct must contain:
 *      - 'taxonomy'
 *      Also, it can optionally contain:
 *      - 'name'
 *      - 'parent'
 *      - 'description'
 *      - 'slug'
 *  - boolean $send_mail optional. Defaults to false
 * @return int term_id
 */
function wp_editTerm( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID            = (int) $args[0];
                $username           = $args[1];
                $password           = $args[2];
                $term_ID            = (int)$args[3];
                $content_struct     = $args[4];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                if ( ! taxonomy_exists( $content_struct['taxonomy'] ) )
                        return new IXR_Error( 403, __( 'Invalid taxonomy' ) );

                $taxonomy = get_taxonomy( $content_struct['taxonomy'] );

                if( ! current_user_can( $taxonomy->cap->edit_terms ) )
                        return new IXR_Error( 401, __( 'You are not allowed to edit terms in this taxonomy' ) );

                $taxonomy   = (array)$taxonomy;
                
                // hold the data of the term
                $term_data  = array();

                $term = get_term( $term_ID , $content_struct['taxonomy'] );

                if ( is_wp_error( $term ) )
                            return new IXR_Error(500, $term->get_error_message());

                if ( ! $term )
                            return new IXR_Error(500, __('The term ID does not exists'));

                if( isset ( $content_struct['name'] ) ) {

                        $term_data['name'] = trim( $content_struct['name'] );
                        if( empty ( $term_data['name'] ) )
                                return new IXR_Error( 403, __( 'The term name cannot be empty' ) );

                }

                if( isset ( $content_struct['parent'] ) ) {

                        if( ! $taxonomy['hierarchical'] )
                                return new IXR_Error( 403, __( 'This taxonomy is not hieararchical' ) );

                        $parent_term_id = (int)$content_struct['parent'];
                        $parent_term = get_term( $parent_term_id , $taxonomy['name'] );

                        if ( is_wp_error( $parent_term) )
                            return new IXR_Error(500, $term->get_error_message());

                        if ( ! $parent_term )
                            return new IXR_Error(500, __('Parent term does not exists'));

                        $term_data['parent'] =  $content_struct['parent'];

                }

                if( isset ( $content_struct['description'] ) )
                        $term_data['description'] = $content_struct['description'];

                if( isset ( $content_struct['slug'] ) )
                        $term_data['slug'] = $content_struct['slug'];

                $term_ID = wp_update_term( $term_ID , $taxonomy['name'] , $term_data );

                if ( is_wp_error( $term_ID ) )
                        return new IXR_Error(500, $term_ID->get_error_message());

                if ( ! $term_ID )
                        return new IXR_Error(500, __('Sorry, your entry could not be posted. Something wrong happened.'));

                return $term_ID;
                
}

/**
 * Delete a  term
 *
 * @uses wp_delete_term()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - int     $term_id
 *  - array   $content_struct contains:
 *      - 'taxonomy'
 * @return boolean true
 */
function wp_deleteTerm( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID            = (int) $args[0];
                $username           = $args[1];
                $password           = $args[2];
                $term_ID            = (int)$args[3];
                $content_struct     = $args[4];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                if ( ! taxonomy_exists( $content_struct['taxonomy'] ) )
                        return new IXR_Error( 403, __( 'Invalid taxonomy' ) );

                $taxonomy = get_taxonomy( $content_struct['taxonomy'] );

                if( ! current_user_can( $taxonomy->cap->delete_terms ) )
                        return new IXR_Error( 401, __( 'You are not allowed to delete terms in this taxonomy' ) );

                $term = get_term ( $term_ID, $content_struct['taxonomy'] );

                if ( is_wp_error( $term ) )
                            return new IXR_Error(500, $term->get_error_message());

                if ( ! $term )
                            return new IXR_Error(500, __('The specified term does not exist'));

                if( $term_ID == get_option('default_category') )
                        return new IXR_Error( 403, __( 'You cannot delete the default category' ) );

                $result = wp_delete_term( $term_ID, $content_struct['taxonomy'] );

                if ( is_wp_error( $result ) )
                        return new IXR_Error(500, $term->get_error_message());

                if ( ! $result )
                        return new IXR_Error(500, __('For some strange yet very annoying reason, this term could not be deleted.'));

                return true;
}

/**
 * Retrieve a term
 *
 * @uses get_term()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - int     $term_id
 *  - array   $content_struct contains:
 *      - 'taxonomy'
 * @return array contains:
 *  - 'term_id'
 *  - 'name'
 *  - 'slug'
 *  - 'term_group'
 *  - 'term_taxonomy_id'
 *  - 'taxonomy'
 *  - 'description'
 *  - 'parent'
 *  - 'count'
 */
function wp_getTerm( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID            = (int) $args[0];
                $username           = $args[1];
                $password           = $args[2];
                $term_ID            = (int)$args[3];
                $content_struct     = $args[4];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                if ( ! taxonomy_exists( $content_struct['taxonomy'] ) )
                        return new IXR_Error( 403, __( 'Invalid taxonomy' ) );

                $taxonomy = get_taxonomy( $content_struct['taxonomy'] );

                if( ! current_user_can( $taxonomy->cap->assign_terms ) )
                        return new IXR_Error( 401, __( 'You are not allowed to assign terms in this taxonomy' ) );

                $term = get_term( $term_ID , $content_struct['taxonomy'] );

                if ( is_wp_error( $term ) )
                            return new IXR_Error(500, $term->get_error_message());

                if ( ! $term )
                            return new IXR_Error(500, __('The term ID does not exists'));

                return $term;

}

/**
 * Retrieve terms
 *
 * @uses get_terms()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - array   $content_struct contains:
 *      - 'taxonomy'
 * @return array terms
 */
function wp_getTerms($args) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID            = (int) $args[0];
                $username           = $args[1];
                $password           = $args[2];
                $content_struct     = $args[3];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                if ( ! taxonomy_exists( $content_struct['taxonomy'] ) )
                        return new IXR_Error( 403, __( 'Invalid taxonomy' ) );

                $taxonomy = get_taxonomy( $content_struct['taxonomy'] );

                if( ! current_user_can( $taxonomy->cap->assign_terms ) )
                        return new IXR_Error( 401, __( 'You are not allowed to assign terms in this taxonomy' ) );

                $terms = get_terms( $content_struct['taxonomy'] , array('get' => 'all') );

                if ( is_wp_error( $terms ) )
                            return new IXR_Error(500, $term->get_error_message());

                if ( ! $terms )
                            return new IXR_Error(500, __('The term ID does not exists'));

                return $terms;

}

/**
 * Retrieve a taxonomy
 *
 * @uses get_taxonomy()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - string  $taxonomy_name
 * @return array contains:
 *  - 'labels'
 *  - 'cap'
 *  - 'hierarchical'
 *  - 'object_type'
 */
function wp_getTaxonomy( $args ) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID        = (int) $args[0];
                $username       = $args[1];
                $password       = $args[2];
                $taxonomy_name  = $args[3];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                $taxonomy_names = get_taxonomies('','names');

                if( ! in_array( $taxonomy_name, $taxonomy_names ) )
                        return new IXR_Error( 403, __( 'The taxonomy type specified is not valid' ) );

                $taxonomy = get_taxonomy( $taxonomy_name );

                //capability check
                if( ! current_user_can( $taxonomy->cap->edit_terms ) )
                        return new IXR_Error( 401, __( 'Sorry, You are not allowed to edit this post type' ) );

                $taxonomy = (array)$taxonomy;

                $taxonomy_type_data = array(
                                'labels'            => $taxonomy['labels'],
                                'cap'               => $taxonomy['cap'],
                                'hierarchical'      => $taxonomy['hierarchical'],
                                'object_type'       => $taxonomy['object_type'],
                        );

                return $taxonomy_type_data;

}

/**
 * Retrieve taxonomies
 *
 * @uses get_taxonomies()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 * @return array taxonomies
 */
function wp_getTaxonomies($args) {

                global $wp_xmlrpc_server;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID            = (int) $args[0];
                $username           = $args[1];
                $password           = $args[2];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                $taxonomies = get_taxonomies('','objects');

                // holds all the taxonomy data
                $struct = array();

                foreach( $taxonomies as $taxonomy ) {

                        // capability check for post_types
                        if( ! current_user_can( $taxonomy->cap->edit_terms ) )
                                continue;

                        $taxonomy = (array)$taxonomy;

                        $taxonomy_data = array(
                                'labels'            => $taxonomy['labels'],
                                'cap'               => $taxonomy['cap'],
                                'hierarchical'      => $taxonomy['hierarchical'],
                                'object_type'       => $taxonomy['object_type'],
                        );

                        $struct[ $taxonomy['name'] ] =  $taxonomy_data;

                }

                return $struct;

}

/**
 * Retrieve settings
 *
 * @uses get_option()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - array   $options optional
 *      if empty will return all the options
 * @return array option data
 */
function wp_getSettings( $args ) {

                global $wp_xmlrpc_server, $admin_options;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID    = (int) $args[0];
                $username   = $args[1];
                $password   = $args[2];
                $options    = $args[3];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                if( ! current_user_can( 'manage_options') )
                        return new IXR_Error( 401, __( 'Sorry, You are not allowed to edit options' ) );

                // If no specific options where asked for, return all of them
		if ( count( $options ) == 0 )
			$options = array_keys( $admin_options );

                foreach( $options as $option ) {

                        if( ! array_key_exists( $option, $admin_options ) )
                                return new IXR_Error( 401, __( 'Given option is not valid' ) );
                        $value = get_option( $option);
                        $option_data[$option] = $admin_options[$option];
                        $option_data[$option]['value'] = get_option( $option);

                }

                return $option_data;
  
}

/**
 * Update settings
 *
 * @uses update_option()
 * @param array $args Method parameters. Contains:
 *  - int     $blog_id
 *  - string  $username
 *  - string  $password
 *  - array   $options
 * @return bool true
 */
function wp_updateSettings( $args ) {

                global $wp_xmlrpc_server, $admin_options;
                $wp_xmlrpc_server->escape( $args );

                $blog_ID    = (int) $args[0];
                $username   = $args[1];
                $password   = $args[2];
                $options    = $args[3];

                if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
                        return $wp_xmlrpc_server->error;

                if( ! current_user_can( 'manage_options') )
                        return new IXR_Error( 401, __( 'Sorry, You are not allowed to edit options' ) );

                foreach( $options as $key => $value ) {

                        if( ! array_key_exists( $key , $admin_options ) )
                                return new IXR_Error( 401, __( 'Given option is not valid' ) );
                        //validating options
                        update_option($admin_options[$option]['key'], $value);

                }

                return true;

}

?>
