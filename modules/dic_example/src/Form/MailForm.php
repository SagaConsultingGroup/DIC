<?php
/**
 * Contains Drupal\dic_example\Form\MailForm.
 */

namespace Drupal\dic_example\Form;

class MailForm {

  /**
   * Implements hook_menu().
   *
   * @return array
   */
  public function buildMenu() {
    return array(
      'mail' => array(
        'title' => 'Mail Form',
        'description' => 'Mail Form dependency injection example.',
        'page callback' => 'drupal_get_form',
        'page arguments' => array('dic_example_mail_form'),
        'access callback' => TRUE,
        'file' => 'includes/dic_example.forms.inc',
      ),
    );
  }

  /**
   * Build the Mail form.
   *
   * @param array $form
   * @param array $form_state
   *
   * @return array
   */
  public function buildForm($form, &$form_state) {
    $form['label'] = array(
      '#title' => t('Human readable name'),
      '#type' => 'textfield',
      '#description' => t('The human-readable name of the mail.'),
      '#required' => TRUE,
      '#size' => 30,
    );

    $form['subject'] = array(
      '#title' => t('Subject'),
      '#type' => 'textfield',
      '#description' => t('The subject of the mail.'),
      '#required' => TRUE,
      '#size' => 30,
    );

    $form['cc'] = array(
      '#title' => t('CC'),
      '#type' => 'textfield',
      '#size' => 30,
    );

    $form['bcc'] = array(
      '#title' => t('BCC'),
      '#type' => 'textfield',
      '#size' => 30,
    );

    $form['body'] = array(
      '#title' => t('Body'),
      '#type' => 'textarea',
      '#description' => t('The body of the mail.'),
      '#required' => TRUE,
      '#size' => 30,
    );

    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save'),
      '#weight' => 40,
      '#submit' => array(array($this, 'submitForm')),
    );

    return $form;
  }

  /**
   * Form validate functionality.
   *
   * @param array $form
   * @param array $form_state
   */
  public function validateForm($form, &$form_state) {

  }

  /**
   * Form submit functionality.
   *
   * @param array $form
   * @param array $form_state
   */
  public function submitForm($form, &$form_state) {
    drupal_set_message('Thank you for sending the mail!');
  }
}
