<?php

namespace Drupal\rgb_color\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'rgb_color_default' formatter.
 *
 * @FieldFormatter(
 *   id = "rgb_color_default",
 *   label = @Translation("RGB Color Default"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $color_code = !empty($item->hex_value) ? $item->hex_value : sprintf("#%02x%02x%02x", $item->r, $item->g, $item->b);

      $elements[$delta] = [
        '#markup' => $this->t('Color Code: @code', ['@code' => $color_code]),
        '#prefix' => '<div style="background-color: ' . $color_code . '; padding: 5px;">',
        '#suffix' => '</div>',
      ];
    }

    return $elements;
  }
}
