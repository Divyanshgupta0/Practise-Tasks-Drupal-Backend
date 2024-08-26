<?php

namespace Drupal\rgb_color\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'rgb_color_background_text' formatter.
 *
 * @FieldFormatter(
 *   id = "rgb_color_background_text",
 *   label = @Translation("Text with Background Color"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorBackgroundTextFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      // Determine the color code, prioritizing the hex value.
      $color_code = !empty($item->hex_value) ? $item->hex_value : sprintf("#%02x%02x%02x", $item->r, $item->g, $item->b);

      $elements[$delta] = [
        '#type' => 'inline_template',
        '#template' => '<div style="background-color: {{ color_code }}; padding: 10px; color: #fff;">{{ text }}</div>',
        '#context' => [
          'color_code' => $color_code,
          'text' => $this->t('This text has a background color.'),
        ],
      ];
    }

    return $elements;
  }
}
