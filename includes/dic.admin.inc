<?php

/**
 * The admin settings form.
 *
 * @param $form
 * @param $form_state
 *
 * @return array
 */
function dic_admin_settings_form($form, &$form_state) {
  $form['dic_require'] = array(
    '#title' => t('Require'),
    '#type' => 'textarea',
    '#description' => t('Define extra composer require entries. These values should be formatted as package|version.'),
    '#default_value' => dic_create_textlist_from_array(variable_get('dic_require', array())),
  );

  $form['dic_repositories'] = array(
    '#title' => t('Repositories'),
    '#type' => 'textarea',
    '#description' => t('Define extra repositories. These values should be formatted as type|url.'),
    '#default_value' => dic_create_textlist_from_array(variable_get('dic_repositories', array())),
  );

  $form['dic_installer_paths'] = array(
    '#title' => t('Installer paths'),
    '#type' => 'textarea',
    '#description' => t('Define extra installer paths. These values should be formatted as path|property.
                           <b>The paths you define here have to be relative to the composer file directory
                           you configured in the composer manager settings.</b>'),
    '#default_value' => dic_create_textlist_from_array(variable_get('dic_installer_paths', array())),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

  return $form;
}

/**
 * The admin settings submit function.
 *
 * @param array $form
 * @param array $form_state
 */
function dic_admin_settings_form_submit($form, &$form_state) {
  variable_set('dic_require', dic_create_array_from_textlist($form_state['values']['dic_require']));
  variable_set('dic_repositories', dic_create_array_from_textlist($form_state['values']['dic_repositories']));
  variable_set('dic_installer_paths', dic_create_array_from_textlist($form_state['values']['dic_installer_paths']));

  drupal_set_message(t('Settings saved correctly... Don\'t forget to rebuild the composer.json file!'));
}

/**
 * Create a structured array from the key/value textarea lines.
 *
 * @param string $text_list
 *
 * @return array
 */
function dic_create_array_from_textlist($text_list) {
  $list = array();
  $requires = explode("\r\n", $text_list);
  $requires = array_map('trim', $requires);
  $requires = array_filter($requires, 'strlen');

  foreach ($requires as $require) {
    list($k, $v) = explode('|', $require);
    $list[$k] = $v;
  }

  return $list;
}

/**
 * Create a usable key/value string for textarea's from an array.
 *
 * @param array $list
 *
 * @return string
 */
function dic_create_textlist_from_array($list) {
  $output = '';
  foreach ($list as $key => $value) {
    $output .= implode('|', array($key, $value)) . "\r\n";
  }

  return $output;
}
