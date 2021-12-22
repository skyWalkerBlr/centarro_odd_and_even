<?php

namespace Drupal\centarro_odd_and_even\Cache\Context;

use Drupal\centarro_odd_and_even\Service\OddAndEvenHelperInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;

/**
 * Cache context ID: 'minute'.
 */
class MinuteCacheContext implements CacheContextInterface {

  /**
   * The OddAndEvenHelper service.
   *
   * @var \Drupal\centarro_odd_and_even\Service\OddAndEvenHelperInterface
   */
  protected $oddAndEvenHelper;

  /**
   * Constructs a new MinuteCacheContext class.
   *
   * @param \Drupal\centarro_odd_and_even\Service\OddAndEvenHelperInterface $odd_and_even_service
   *   The OddAndEvenHelper service.
   */
  public function __construct(OddAndEvenHelperInterface $odd_and_even_service) {
    $this->oddAndEvenHelper = $odd_and_even_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Minute cache context');
  }

  /**
   * {@inheritdoc}
   */
  public function getContext() {
    return $this->oddAndEvenHelper->minuteIsEven() ? 'even' : 'odd';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }
}
