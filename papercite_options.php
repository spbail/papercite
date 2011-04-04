<?php

/*
  Documentation:
  - http://ottopress.com/2009/wordpress-settings-api-tutorial/ 
  - 
 */

add_action('admin_menu', 'papercite_create_menu');

function papercite_create_menu() {
  add_options_page('Custom Papercite Page', 'Papercite plug-in', 'manage_options', 'papercite', 'papercite_options_page');
}


function register_mysettings() {
  //register our settings
  register_setting( 'papercite_options', 'papercite_default_bibtex_file' );
}

function papercite_options_page() {
?>
  <div>
    <h2>Papercite options</h2>
    
    Options relating to the papercite plugin.
    
    <form action="options.php" method="post">
    <?php settings_fields('papercite_options'); ?>
    <?php do_settings_sections('papercite'); ?>
    
    <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
    </form>
    </div>

<?php
}

// add the admin settings and such
add_action('admin_init', 'papercite_admin_init');
function papercite_admin_init(){
  register_setting( 'papercite_options', 'papercite_options', 'papercite_options_validate' );

  add_settings_section('papercite_main', 'Defaults settings', 'papercite_section_text', 'papercite');
  add_settings_field('file', 'Default bibtex file', 'papercite_file', 'papercite', 'papercite_main');
  add_settings_field('format', 'Default format', 'papercite_format', 'papercite', 'papercite_main');
  add_settings_field('timeout', 'Default timeout to reload pages', 'papercite_timeout', 'papercite', 'papercite_main');

  add_settings_field('bibtex_template', 'Main bibtex template', 'papercite_bibtex_template', 'papercite', 'papercite_main');
  add_settings_field('bibshow_template', 'Main bibshow template', 'papercite_bibshow_template', 'papercite', 'papercite_main');
}

function papercite_section_text() {
  echo '<p>Set the default settings - leave the fields empty to use papercite default values</p>';
} 


function papercite_file() {
  $options = get_option('papercite_options');
  echo "<input id='papercite_file' name='papercite_options[file]' size='40' type='text' value='{$options['file']}' />";
} 

function papercite_format() {
  $options = get_option('papercite_options');
  echo "<input id='papercite_format' name='papercite_options[format]' size='40' type='text' value='{$options['format']}' />";
} 

function papercite_timeout() {
  $options = get_option('papercite_options');
  echo "<input id='papercite_timeout' name='papercite_options[timeout]' size='40' type='text' value='{$options['timeout']}' />";
} 

function papercite_bibtex_template() {
  $options = get_option('papercite_options');
  echo "<input id='papercite_bibtex_template' name='papercite_options[bibtex_template]' size='40' type='text' value='{$options['bibtex_template']}' />";
}
 function papercite_bibshow_template() {
  $options = get_option('papercite_options');
  echo "<input id='papercite_bibshow_template' name='papercite_options[bibshow_template]' size='40' type='text' value='{$options['bibshow_template']}' />";
} 


function papercite_set(&$options, &$input, $name) {
  if (array_key_exists($name, $input)) {
    $options[$name] = trim($input[$name]);
    if (!$options[$name]) 
      unset($options[$name]);
  }
}

function papercite_options_validate($input) {
  $options = get_option('papercite_options');
  
  $options['file'] = trim($input['file']);
  $options['timeout'] = trim($input["timeout"]);
  
  papercite_set($options, $input, "bibshow_template");
  papercite_set($options, $input, "bibtex_template");
  papercite_set($options, $input, "format");

  return $options;
}

?>