<?php

namespace Drupal\centarro_odd_and_even\Service;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * The OddAndEven Helper service.
 *
 * @package Drupal\centarro_odd_and_even\Service
 */
class OddAndEvenHelper implements OddAndEvenHelperInterface {

  use StringTranslationTrait;

  /**
   * The current time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $currentTimeService;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatterService;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new Odd And Even Helper.
   *
   * @param \Drupal\Component\Datetime\TimeInterface $current_time
   *   The Time object.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The DateFormatter object.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The EntityTypeManager object.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(TimeInterface $current_time, DateFormatterInterface $date_formatter, EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $config_factory) {
    $this->currentTimeService = $current_time;
    $this->dateFormatterService = $date_formatter;
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function minuteIsOdd(): bool {
    // At first, we need to get current time.
    $current_time_timestamp = $this->currentTimeService->getCurrentTime();
    $current_time_in_minutes = $this->dateFormatterService->format($current_time_timestamp, 'custom', 'i');

    // Then we need to check if number odd or even by a bit-wise method.
    return !($current_time_in_minutes & 1);
  }

  /**
   * {@inheritdoc}
   */
  public function getRenderedAnswer(): object {
    $odd_or_even = $this->minuteIsOdd() ? $this->t('even') : $this->t('odd');

    return $this->t('Current minute is @odd_or_even', ['@odd_or_even' => $odd_or_even]);
  }

  /**
   * {@inheritdoc}
   */
  public function getRenderedNode(): mixed {
    // Read the config values
    $config = $this->configFactory->getEditable('centarro_odd_and_even.adminsettings');
    // Here we need to get node id based on minute value.
    $node_id_based_on_minute = $this->minuteIsOdd() ? $config->get('node_for_even') : $config->get('node_for_odd');

    // Once we get the right node ID we need to check if this node exists.
    // Otherwise, we need to say something to the user that the node is not exist.
    $node_storage = $this->entityTypeManager->getStorage('node');
    if ($node_entity = $node_storage->load($node_id_based_on_minute)) {
      return $this->entityTypeManager->getViewBuilder('node')
        ->view($node_entity, 'teaser');
    }

    return $this->t('Sorry, but you don\'t have Node with ID : @node_id.', ['@node_id' => $node_id_based_on_minute]);
  }

}
