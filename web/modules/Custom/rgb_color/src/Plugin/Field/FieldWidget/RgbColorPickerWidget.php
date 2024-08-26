<?php

namespace Drupal\rgb_color\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'rgb_color_picker' widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_picker",
 *   label = @Translation("Color Picker"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorPickerWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['hex_value'] = [
      '#type' => 'color',
      '#title' => $this->t('Select Color'),
      '#default_value' => isset($items[$delta]->hex_value) ? $items[$delta]->hex_value : '#000000',
    ];

    return $element;
  }
}
