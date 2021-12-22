<?php

namespace Drupal\centarro_odd_and_even\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller class to render page with text and nodes teaser.
 *
 * @package Drupal\centarro_odd_and_even\Controller
 */
class OddAndEvenController extends ControllerBase {

  /**
   * The OddAndEvenHelper Service.
   *
   * @var \Drupal\centarro_odd_and_even\Service\OddAndEvenHelperInterface
   */
  protected $oddAndEvenHelper;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->oddAndEvenHelper = $container->get('centarro_odd_and_even.odd_and_even_helper');

    return $instance;
  }

  /**
   * Odd and Even Page Content.
   */
  public function content() {
    return [
      '#theme' => 'odd_and_even',
      '#rendered_answer' => $this->oddAndEvenHelper->getRenderedAnswer(),
      '#rendered_node' => $this->oddAndEvenHelper->getRenderedNode(),
      '#cache' => [
        'tags' => $this->getCacheTags(),
        'contexts' => $this->getCacheContext(),
      ],
    ];
  }

  /**
   * Helper function to get cache tags.
   */
  public function getCacheTags(): array {
    return [
      "node:{$this->oddAndEvenHelper->getOddAndEvenConfiguration()->get('node_for_even')}",
      "node:{$this->oddAndEvenHelper->getOddAndEvenConfiguration()->get('node_for_odd')}",
      'odd_and_even_cache_tag',
    ];
  }

  /**
   * Helper function to get cache context.
   */
  public function getCacheContext(): array {
    return ['minute'];
  }

}
