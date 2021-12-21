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
   * The oddAndEvenHelper Service.
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
    // The main problem here that we can not use max-age with 60 seconds.
    // Because user can load page in any time. In this case we need to use here
    // custom cache tag and clear it via hook_cron every minute.
    return [
      '#theme' => 'odd_and_even',
      '#rendered_answer' => $this->oddAndEvenHelper->getRenderedAnswer(),
      '#rendered_node' => $this->oddAndEvenHelper->getRenderedNode(),
      '#cache' => [
        'tags' => ['odd_and_even_cache_tag'],
      ],
    ];
  }

}
