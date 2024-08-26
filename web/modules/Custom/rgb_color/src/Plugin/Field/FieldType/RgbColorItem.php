<?php

namespace Drupal\rgb_color\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Plugin implementation of the 'rgb_color' field type.
 *
 * @FieldType(
 *   id = "rgb_color",
 *   label = @Translation("RGB Color"),
 *   description = @Translation("A custom field for RGB color."),
 *   default_widget = "rgb_color_hex",
 *   default_formatter = "rgb_color_default"
 * )
 */
class RgbColorItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_storage_def) {
    return [
      'columns' => [
        'hex_value' => [
          'type' => 'varchar',
          'length' => 7,
          'not null' => FALSE,
        ],
        'r' => [
          'type' => 'int',
          'size' => 'tiny',
          'unsigned' => TRUE,
          'not null' => FALSE,
        ],
        'g' => [
          'type' => 'int',
          'size' => 'tiny',
          'unsigned' => TRUE,
          'not null' => FALSE,
        ],
        'b' => [
          'type' => 'int',
          'size' => 'tiny',
          'unsigned' => TRUE,
          'not null' => FALSE,
        ],
      ],
      'indexes' => [
        'hex_value' => ['hex_value'],
        'rgb' => ['r', 'g', 'b'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['hex_value'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Hex Color Code'))
      ->setDescription(new TranslatableMarkup('The RGB color in hexadecimal format.'));

    $properties['r'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Red'))
      ->setDescription(new TranslatableMarkup('The red component of the color.'));

    $properties['g'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Green'))
      ->setDescription(new TranslatableMarkup('The green component of the color.'));

    $properties['b'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Blue'))
      ->setDescription(new TranslatableMarkup('The blue component of the color.'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $hex_value = $this->get('hex_value')->getValue();
    $r = $this->get('r')->getValue();
    $g = $this->get('g')->getValue();
    $b = $this->get('b')->getValue();

    return ($hex_value === NULL || $hex_value === '') && ($r === NULL && $g === NULL && $b === NULL);
  }
}
