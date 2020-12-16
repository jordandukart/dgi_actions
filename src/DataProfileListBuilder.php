<?php

namespace Drupal\dgi_actions;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a listing of Identifier setting entities.
 */
class DataProfileListBuilder extends ConfigEntityListBuilder {

  /**
   * The config factory that knows what is overwritten.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('config.factory')
    );
  }

  /**
   * Constructs a new EntityListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, ConfigFactoryInterface $config_factory) {
    parent::__construct($entity_type, $storage);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Profile Label');
    $header['id'] = $this->t('Machine name');
    $header['entity'] = $this->t('Entity');
    $header['bundle'] = $this->t('Bundle');
    $header['dataprofile'] = $this->t('Data Profile');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $config = $this->configFactory->get('dgi_actions.data_profile.' . $entity->id());
    $row['entity'] = $config->get('entity');
    $row['bundle'] = $config->get('bundle');
    if ($config->get('dataprofile')) {
      $data_profile_type = $this->configFactory->get($config->get('dataprofile'))->get('label');
    }
    $row['dataprofile'] = ($data_profile_type) ?: '';

    return $row + parent::buildRow($entity);
  }

}
