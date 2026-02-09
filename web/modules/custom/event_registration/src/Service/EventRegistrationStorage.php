<?php

namespace Drupal\event_registration\Service;

use Drupal\Core\Database\Connection;

class EventRegistrationStorage {

  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public function saveRegistration(array $data) {
    return $this->database->insert('event_registration')
      ->fields($data)
      ->execute();
  }

  public function checkDuplicate($email, $event_config_id) {
    return $this->database->select('event_registration', 'er')
      ->condition('email', $email)
      ->condition('event_config_id', $event_config_id)
      ->countQuery()
      ->execute()
      ->fetchField();
  }

  public function getRegistrations($filters = []) {
    $query = $this->database->select('event_registration', 'er');
    $query->fields('er');
    $query->join('event_config', 'ec', 'er.event_config_id = ec.id');
    $query->fields('ec', ['event_name', 'event_date', 'category']);

    if (!empty($filters['event_date'])) {
      $query->condition('ec.event_date', $filters['event_date']);
    }

    if (!empty($filters['event_name'])) {
      $query->condition('ec.event_name', $filters['event_name']);
    }

    return $query->execute()->fetchAll();
  }

  public function getEventDatesByCategory($category) {
    return $this->database->select('event_config', 'ec')
      ->fields('ec', ['event_date'])
      ->condition('category', $category)
      ->condition('event_date', date('Y-m-d'), '>=')
      ->distinct()
      ->execute()
      ->fetchCol();
  }

  public function getEventNamesByDateAndCategory($event_date, $category) {
    return $this->database->select('event_config', 'ec')
      ->fields('ec', ['id', 'event_name'])
      ->condition('event_date', $event_date)
      ->condition('category', $category)
      ->execute()
      ->fetchAllKeyed();
  }

  public function getCategories() {
    return $this->database->select('event_config', 'ec')
      ->fields('ec', ['category'])
      ->distinct()
      ->execute()
      ->fetchCol();
  }
}