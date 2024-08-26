<?php

namespace Drupal\rgb_color\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'rgb_color_hex' widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_hex",
 *   label = @Translation("Hex Code"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorHexWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['hex_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hex Color Code'),
      '#default_value' => isset($items[$delta]->hex_value) ? $items[$delta]->hex_value : '',
      '#size' => 7,
      '#maxlength' => 7,
      '#description' => $this->t('Enter the hexadecimal color code, including the leading #.'),
    ];

    return $element;
  }
}
