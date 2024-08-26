<?php

namespace Drupal\rgb_color\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'rgb_color_hex_only' formatter.
 *
 * @FieldFormatter(
 *   id = "rgb_color_hex_only",
 *   label = @Translation("Hex Code Only"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorHexOnlyFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $color_code = !empty($item->hex_value) ? $item->hex_value : sprintf("#%02x%02x%02x", $item->r, $item->g, $item->b);

      $elements[$delta] = [
        '#markup' => $this->t('@code', ['@code' => $color_code]),
      ];
    }

    return $elements;
  }

}
