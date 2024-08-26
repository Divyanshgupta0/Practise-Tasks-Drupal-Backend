<?php

namespace Drupal\user_otll_generator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Url;

/**
 * Provides a form to generate a One-Time Login Link for a user.
 */
class UserOtllForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_otll_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User ID'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate OTLL'),
    ];

    if ($message = $form_state->get('otll_message')) {
      $form['otll_message'] = [
        '#markup' => $message,
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $user_id = $form_state->getValue('user_id');
    if (!is_numeric($user_id) || $user_id <= 0) {
      $form_state->setErrorByName('user_id', $this->t('Please enter a valid User ID.'));
    }

    if (User::load($user_id) === NULL) {
      $form_state->setErrorByName('user_id', $this->t('No user exists with the provided User ID.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $user_id = $form_state->getValue('user_id');
    $user = User::load($user_id);

    if ($user) {
      $link = user_pass_reset_url($user);
      $message = $this->t('The One-Time Login Link for user ID @uid is: <a href=":link" target="_blank">:link</a>', [
        '@uid' => $user_id,
        ':link' => $link,
      ]);
    } else {
      $message = $this->t('No user found with ID @uid.', ['@uid' => $user_id]);
    }

    $form_state->set('otll_message', $message);
    $form_state->setRebuild(TRUE);
  }

}
