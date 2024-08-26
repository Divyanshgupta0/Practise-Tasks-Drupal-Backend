<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class MyConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['custom_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $form['#attached']['library'][] = 'custom_form/custom_form';

    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full Name'),
      '#required' => TRUE,
    ];

    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone Number'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::validatePhoneNumberAjax',
        'event' => 'change',
      ],
      '#suffix' => '<div id="phone-number-validation-message"></div>',
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email ID'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::validateEmailAjax',
        'event' => 'change',
      ],
      '#suffix' => '<div id="email-validation-message"></div>',
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
        'other' => $this->t('Other'),
      ],
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * AJAX callback to validate the phone number.
   */
  public function validatePhoneNumberAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $phone_number = $form_state->getValue('phone_number');
    $message = '';

    if (!preg_match('/^\d{10}$/', $phone_number)) {
      $message = $this->t('The phone number must be a 10-digit number.');
    }

    $response->addCommand(new HtmlCommand('#phone-number-validation-message', $message));
    return $response;
  }

  /**
   * AJAX callback to validate the email.
   */
  public function validateEmailAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $email = $form_state->getValue('email');
    $message = '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $message = $this->t('The email address is not valid.');
    } else {
      $public_domains = ['yahoo.com', 'gmail.com', 'outlook.com'];
      $email_domain = substr(strrchr($email, "@"), 1);
      if (!in_array($email_domain, $public_domains)) {
        $message = $this->t('The email address must be from a public domain (Yahoo, Gmail, Outlook, etc.).');
      }

      if (substr($email_domain, -4) !== '.com') {
        $message = $this->t('The email address must have a .com domain extension.');
      }
    }

    $response->addCommand(new HtmlCommand('#email-validation-message', $message));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Validate phone number
    $phone_number = $form_state->getValue('phone_number');
    if (!preg_match('/^\d{10}$/', $phone_number)) {
      $form_state->setErrorByName('phone_number', $this->t('The phone number must be a 10-digit number.'));
    }

    // Validate email
    $email = $form_state->getValue('email');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('The email address is not valid.'));
    } else {
      $public_domains = ['yahoo.com', 'gmail.com', 'outlook.com'];
      $email_domain = substr(strrchr($email, "@"), 1);
      if (!in_array($email_domain, $public_domains)) {
        $form_state->setErrorByName('email', $this->t('The email address must be from a public domain (Yahoo, Gmail, Outlook, etc.).'));
      }

      if (substr($email_domain, -4) !== '.com') {
        $form_state->setErrorByName('email', $this->t('The email address must have a .com domain extension.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('custom_form.settings')
      ->set('full_name', $form_state->getValue('full_name'))
      ->set('phone_number', $form_state->getValue('phone_number'))
      ->set('email', $form_state->getValue('email'))
      ->set('gender', $form_state->getValue('gender'))
      ->save();

    $this->messenger()->addStatus($this->t('The configuration has been saved.'));
  }

}
