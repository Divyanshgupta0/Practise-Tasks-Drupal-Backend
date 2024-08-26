<?php

namespace Drupal\group_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;

/**
 * Provides a 'GroupDataBlock' block.
 *
 * @Block(
 *   id = "group_data_block",
 *   admin_label = @Translation("Group Data Block"),
 *   category = @Translation("Custom")
 * )
 */
class GroupDataBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Fetch data from the 'group_data' table.
    $connection = Database::getConnection();
    $query = $connection->select('group_data', 'gd')
      ->fields('gd', ['group_name', 'label_1', 'value_1', 'label_2', 'value_2']);
    $results = $query->execute()->fetchAll();

    $data = [];
    foreach ($results as $row) {
      $data[] = [
        'group_name' => $row->group_name,
        'label_1' => $row->label_1,
        'value_1' => $row->value_1,
        'label_2' => $row->label_2,
        'value_2' => $row->value_2,
      ];
    }

    // Return the render array for the block.
    return [
      '#theme' => 'group_data_block',
      '#data' => $data,
      '#attached' => [
        'library' => [
          'group_form/group_data_block',
        ],
      ],
    ];
  }
}
