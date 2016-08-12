<?php

if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array (
    'key' => 'group_57acdaa51dc58',
    'title' => 'Agency Affiliation',
    'fields' => array (
      array (
        'key' => 'field_57acdad9e680e',
        'label' => 'Agency Top',
        'name' => 'agency_top',
        'type' => 'radio',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array (
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => array (
          'college' => 'College',
          'college/extension' => 'College and Extension',
          'extension' => 'Extension',
          'research' => 'Research',
          'tfs' => 'TFS',
          'tvmdl' => 'TVMDL',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => 'college',
        'layout' => 'vertical',
      ),
      array (
        'key' => 'field_57acdd9bc9f10',
        'label' => 'Extension Type',
        'name' => 'ext_type',
        'type' => 'radio',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => array (
          array (
            array (
              'field' => 'field_57acdad9e680e',
              'operator' => '==',
              'value' => 'extension',
            ),
          ),
          array (
            array (
              'field' => 'field_57acdad9e680e',
              'operator' => '==',
              'value' => 'college/extension',
            ),
          ),
        ),
        'wrapper' => array (
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => array (
          'typical' => 'Typical',
          '4h' => '4-H',
          'county' => 'County Office',
          'tce' => 'County TCE Office',
          'mg' => 'Master Gardener Chapter',
          'mn' => 'Master Naturalist Chapter',
          'sg' => 'Sea Grant',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => 'typical',
        'layout' => 'vertical',
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'agriflex3-settings',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
  ));

endif;