<?php

namespace Drupal\rgb_color\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'rgb_color_rgb' widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_rgb",
 *   label = @Translation("RGB Fields"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorRGBWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['r'] = [
      '#type' => 'number',
      '#title' => $this->t('Red'),
      '#default_value' => isset($items[$delta]->r) ? $items[$delta]->r : '',
      '#min' => 0,
      '#max' => 255,
    ];

    $element['g'] = [
      '#type' => 'number',
      '#title' => $this->t('Green'),
      '#default_value' => isset($items[$delta]->g) ? $items[$delta]->g : '',
      '#min' => 0,
      '#max' => 255,
    ];

    $element['b'] = [
      '#type' => 'number',
      '#title' => $this->t('Blue'),
      '#default_value' => isset($items[$delta]->b) ? $items[$delta]->b : '',
      '#min' => 0,
      '#max' => 255,
    ];

    return $element;
  }
}
