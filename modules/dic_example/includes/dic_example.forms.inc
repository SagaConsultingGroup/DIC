<?php
/**
 * @file
 * This file contains all the dic_example form implementations.
 */

/**
 * Example mail form.
 */
function dic_example_mail_form($form, &$form_state) {
  return DIC::service('dic_example.mail_form')->buildForm($form, $form_state);
}
