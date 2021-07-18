<?php

namespace Drupal\epci\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Commune entity.
 *
 * @ingroup epci
 *
 * @ContentEntityType(
 *   id = "commune",
 *   label = @Translation("Commune"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\epci\CommuneListBuilder",
 *     "views_data" = "Drupal\epci\Entity\CommuneViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\epci\Form\CommuneForm",
 *       "add" = "Drupal\epci\Form\CommuneForm",
 *       "edit" = "Drupal\epci\Form\CommuneForm",
 *       "delete" = "Drupal\epci\Form\CommuneDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\epci\CommuneHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\epci\CommuneAccessControlHandler",
 *   },
 *   base_table = "epci_commune",
 *   translatable = FALSE,
 *   admin_permission = "administer commune entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/commune/{commune}",
 *     "add-form" = "/admin/commune/add",
 *     "edit-form" = "/admin/commune/{commune}/edit",
 *     "delete-form" = "/admin/commune/{commune}/delete",
 *     "collection" = "/admin/commune",
 *   },
 *   field_ui_base_route = "commune.settings"
 * )
 */
class Commune extends ContentEntityBase implements CommuneInterface {

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
  public function getCodePostal() {
    return $this->get('code_postal')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getEpciId() {
    if ($this->get('epci')->isEmpty()) {
      return NULL;
    }

    return $this->get('epci')->first()->getString();
  }

  /**
   * {@inheritdoc}
   */
  public function setEpciId(int $epciId) {
    return $this->set('epci', $epciId);
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
      ->setDescription(t('The name of the Commune entity.'))
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

    // Département.
    $fields['department'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Departement'))
      ->setDescription(t('The departement code of the Commune.'))
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

    // Insee. Entity base identifier.
    $fields['insee'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Insee code'))
      ->setDescription(t('The insee code of the Commune.'))
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
      ->setRequired(TRUE);

    // Siren.
    $fields['siren'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Siren code'))
      ->setDescription(t('The siren of the Commune.'))
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
      ->setRequired(FALSE);

    // Code Postal.
    $fields['code_postal'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Postal Code'))
      ->setDescription(t('The postal code of the Commune.'))
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
      ->setRequired(TRUE);

    // EPCI.
    $fields['epci'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('epci'))
      ->setDescription(t('The associated epci'))
      ->setSetting('target_type', 'epci')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    // Mayor name.
    $fields['mayor'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Mayor name'))
      ->setDescription(t('The name of the mayor.'))
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

    // Status.
    $fields['status']->setDescription(t('A boolean indicating whether the Commune is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 10,
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
