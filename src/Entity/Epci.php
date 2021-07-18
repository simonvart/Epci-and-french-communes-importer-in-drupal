<?php

namespace Drupal\epci\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the epci entity.
 *
 * @ingroup epci
 *
 * @ContentEntityType(
 *   id = "epci",
 *   label = @Translation("epci"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\epci\EpciListBuilder",
 *     "views_data" = "Drupal\epci\Entity\EpciViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\epci\Form\EpciForm",
 *       "add" = "Drupal\epci\Form\EpciForm",
 *       "edit" = "Drupal\epci\Form\EpciForm",
 *       "delete" = "Drupal\epci\Form\EpciDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\epci\EpciHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\epci\EpciAccessControlHandler",
 *   },
 *   base_table = "epci_epci",
 *   translatable = FALSE,
 *   admin_permission = "administer epci entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/epci/{epci}",
 *     "add-form" = "/admin/epci/add",
 *     "edit-form" = "/admin/epci/{epci}/edit",
 *     "delete-form" = "/admin/epci/{epci}/delete",
 *     "collection" = "/admin/epci",
 *   },
 *   field_ui_base_route = "epci.settings"
 * )
 */
class Epci extends ContentEntityBase implements EpciInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    // Name.
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the epci entity.'))
      ->setSettings([
        'max_length' => 80,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    // Departement.
    $fields['department'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Department'))
      ->setDescription(t('The department code of the epci.'))
      ->setSettings([
        'max_length' => 2,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    // Siren. Entity base identifier.
    $fields['siren'] = BaseFieldDefinition::create('string')
      ->setLabel(t('SIREN'))
      ->setDescription(t('The siren code of the epci.'))
      ->setSettings([
        'max_length' => 9,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    // Adress 1.
    $fields['adress_1'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Adress line 1'))
      ->setDescription(t('The first line of epci adress.'))
      ->setSettings([
        'max_length' => 80,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    // Adress 2.
    $fields['adress_2'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Adress line 2'))
      ->setDescription(t('The second line of epci adress.'))
      ->setSettings([
        'max_length' => 80,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    // Code postall (adress).
    $fields['adress_cp'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Postal code'))
      ->setDescription(t('The postal code epci adress.'))
      ->setSettings([
        'max_length' => 6,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    // Commune (adress).
    $fields['adress_commune'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Adress commune'))
      ->setDescription(t('The Commune of the epci adress.'))
      ->setSettings([
        'max_length' => 40,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    // Status.
    $fields['status']->setDescription(t('A boolean indicating whether the epci is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    // Created.
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    // Changed.
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
