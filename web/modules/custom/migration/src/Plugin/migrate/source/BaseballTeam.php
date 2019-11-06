<?php

namespace Drupal\migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Provides a 'Team' migrate source.
 *
 * @MigrateSource(
 *  id = "baseball_team",
 *  source_module = "migration"
 * )
 */
class BaseballTeam extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('teams', 't')
      ->fields('t', array (
        'teamID',
        'park',
        'name',
      ));
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'teamID' => $this->t('Team ID' ),
      'name' => $this->t('name' ),
      'park' => $this->t('Park'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'teamID' => [
        'type' => 'text',
        // This is an optional key currently only used by SqlBase.
        'alias' => 't',
      ],
    ];
  }

}
