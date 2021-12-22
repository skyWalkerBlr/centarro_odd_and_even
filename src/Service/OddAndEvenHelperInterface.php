<?php

namespace Drupal\centarro_odd_and_even\Service;

/**
 * The OddAndEven service interface.
 *
 * @package Drupal\centarro_odd_and_even\Service
 */
interface OddAndEvenHelperInterface {

  /**
   * Helper function to check if minute odd or even.
   *
   * @return bool
   *   Return True if minute is Even.
   */
  public function minuteIsEven(): bool;

  /**
   * Helper function to get the answer based on current minute value.
   *
   * @return object
   *   Return Translatable Markup array.
   */
  public function getRenderedAnswer(): object;

  /**
   * Helper function to get the rendered node based on current minute value.
   *
   * @return mixed
   *   Rendered node entity with "teaser" display mode or Translatable Markup array if node is not exists.
   */
  public function getRenderedNode(): mixed;

  /**
   * Helper function to get configuration from config form.
   *
   * @return object
   */
  public function getOddAndEvenConfiguration(): object;

}
