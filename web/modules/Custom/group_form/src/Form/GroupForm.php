<?php

namespace Drupal\group_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class GroupForm extends FormBase {

  public function getFormId() {
    return 'group_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#tree'] = TRUE;

    $form['groups_fieldset'] = [
      '#type' => 'fieldset',
      '#prefix' => '<div id="group-form-wrapper">',
      '#suffix' => '</div>',
    ];

    // Retrieve the number of groups from form state or set to 1 if not set.
    $num_groups = $form_state->get('num_groups');
    if ($num_groups === NULL) {
      $num_groups = 1;
      $form_state->set('num_groups', $num_groups);
    }

    // Build fields for each group.
    for ($i = 0; $i < $num_groups; $i++) {
      $form['groups_fieldset'][$i] = [
        '#type' => 'container',
        '#prefix' => '<div class="group-wrapper">',
        '#suffix' => '</div>',
      ];

      $form['groups_fieldset'][$i]['group_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the group'),
        '#name' => "groups[$i][group_name]",
        '#required' => TRUE,
      ];

      $form['groups_fieldset'][$i]['label_1'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 1st label'),
        '#name' => "groups[$i][label_1]",
        '#required' => TRUE,
      ];

      $form['groups_fieldset'][$i]['value_1'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 1st value of the 1st label'),
        '#name' => "groups[$i][value_1]",
        '#required' => TRUE,
      ];

      $form['groups_fieldset'][$i]['label_2'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 2nd label'),
        '#name' => "groups[$i][label_2]",
        '#required' => TRUE,
      ];

      $form['groups_fieldset'][$i]['value_2'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 2nd value of the 2nd label'),
        '#name' => "groups[$i][value_2]",
        '#required' => TRUE,
      ];

      $form['groups_fieldset'][$i]['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#submit' => ['::removeGroup'],
        '#ajax' => [
          'callback' => '::ajaxCallback',
          'wrapper' => 'group-form-wrapper',
        ],
        '#limit_validation_errors' => [],
      ];
    }

    $form['groups_fieldset']['add_group'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add more'),
      '#submit' => ['::addGroup'],
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'wrapper' => 'group-form-wrapper',
      ],
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#submit' => ['::submitForm'],
    ];

    return $form;
  }

  public function ajaxCallback(array &$form, FormStateInterface $form_state) {
    // Ensure this callback returns the correct part of the form.
    return $form['groups_fieldset'];
  }

  public function addGroup(array &$form, FormStateInterface $form_state) {
    $num_groups = $form_state->get('num_groups');
    $num_groups++;
    $form_state->set('num_groups', $num_groups);
    $form_state->setRebuild(TRUE);
  }

  public function removeGroup(array &$form, FormStateInterface $form_state) {
    $num_groups = $form_state->get('num_groups');
    if ($num_groups > 1) {
      $num_groups--;
      $form_state->set('num_groups', $num_groups);
    }
    $form_state->setRebuild(TRUE);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage($this->t('Submit form triggered.'));
    $groups = $form_state->getValue('groups_fieldset');
    if ($groups) {
      \Drupal::messenger()->addMessage('<pre>' . print_r($groups, TRUE) . '</pre>');
      foreach ($groups as $group) {
        try {
          \Drupal::database()->insert('group_data')
            ->fields([
              'group_name' => $group['group_name'],
              'label_1' => $group['label_1'],
              'value_1' => $group['value_1'],
              'label_2' => $group['label_2'],
              'value_2' => $group['value_2'],
            ])
            ->execute();
        } catch (\Exception $e) {
          \Drupal::messenger()->addError($this->t('Failed to save data: @message', ['@message' => $e->getMessage()]));
        }
      }
      \Drupal::messenger()->addMessage($this->t('Form data saved successfully.'));
    } else {
      \Drupal::messenger()->addError($this->t('No groups data found.'));
    }
  }
}
