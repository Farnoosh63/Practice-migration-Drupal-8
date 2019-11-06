<?php

namespace Drupal\migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Provides a 'BaseballPlayer' migrate source.
 *
 * @MigrateSource(
 *  id = "baseball_player",
 *  source_module = "migration"
 * )
 */
class BaseballPlayer extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('master', 'm')
      ->fields('m', array(
        'playerID',
        'birthYear',
        'birthMonth',
        'birthDay',
        'deathYear',
        'deathMonth',
        'deathDay',
        'nameFirst',
        'nameLast',
        'nameGiven',
        'weight',
        'height',
        'bats',
        'throws',
      ));
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'playerID' => $this->t('Player ID'),
      'birthYear' => $this->t('Birth year'),
      'birthMonth' => $this->t('Birth month'),
      'birthDay' => $this->t('Birth day'),
      'deathYear' => $this->t('Death year'),
      'deathMonth' => $this->t('Death month'),
      'deathDay' => $this->t('Death day'),
      'nameFirst' => $this->t('First name'),
      'nameLast' => $this->t('Last name'),
      'nameGiven' => $this->t('Given name'),
      'weight' => $this->t('Weight'),
      'height' => $this->t('Height'),
      'bats' => $this->t('Bats'),
      'throws' => $this->t('Throws'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'playerID' => [
        'type' => 'text',
        // This is an optional key currently only used by SqlBase.
        'alias' => 'm',
      ],
    ];
  }

  /**
 * {@inheritdoc}
 */
  public function prepareRow(Row $row) {
    // This example shows how source properties can be added in
    // prepareRow(). The source dates are stored as 2017-12-17
    // and times as 16:00. Drupal 8 saves date and time fields
    // in ISO8601 format 2017-01-15T16:00:00 on UTC.
    // We concatenate source date and time and add the seconds.
    // The same result could also be achieved using the 'concat'
    // and 'format_date' process plugins in the migration
    // definition.
    $month = $row->getSourceProperty('birthMonth');
    $date = $row->getSourceProperty('birthDay');
    $year = $row->getSourceProperty('birthYear');

    $datetime = $date . '-' . $month . '-' . $year;
    $row->setSourceProperty('datetime', $datetime);
    return parent::prepareRow($row);
  }
}
