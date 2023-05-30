<?php

/**
 * @file
 *
 * The contents of this file are never loaded, or executed, it is purely for
 * documentation purposes.
 *
 * @link https://www.drupal.org/docs/develop/coding-standards/api-documentation-and-comment-standards#hooks
 * Read the standards for documenting hooks. @endlink
 *
 */

/**
 * apsc_ckeditor_media_dialog_form_editor_media_dialog_alter().
 *
 * This hooks allows modules to modify form fields and behaviours.
 * Current user is editing content and has a media item they want to add a class to.
 * We include a new form field in the "edit media" form, the text contents of the field
 * is then placed in the textarea in the "class" attribute for that item.
 *
 * @param array &$form
 *   The array containing form elements
 *   session.
 * @param \Drupal\node\FormStateInterface $form_state
 *   The form state being viewed.
 */
 
/**
 * Implements hook_form_FORM_ID_alter().
 * Adds a class field to the "edit media" dialog box in CKEditor text area
 */
function apsc_ckeditor_media_dialog_form_editor_media_dialog_alter(array &$form, FormStateInterface $form_state) {
  if (isset($form_state->getUserInput()['editor_object'])) {
    $editor_object = $form_state->getUserInput()['editor_object'];
    $media_embed_element = $editor_object['attributes'];
  } else {
    // Retrieve the user input from form state.
    $media_embed_element = $form_state->get('media_embed_element');
  }
  
  //hide the default align input
  $form['align']['#attributes']['class'][] = 'hidden';
  $form['align']['#default_value'] = 'none';
  
  $form['extra_classes'] = [
    '#title' => t('Align item (Mobile / Desktop)'),
    '#type' => 'select',
    '#options' => [
      '' => t('None'),
      'inline-media--align-left' => t('Left / Left'),
      'inline-media--align-centre sm--inline-media--align-left' => t('Centre / Left'),
      'inline-media--align-centre' => t('Centre / Centre'),
      'inline-media--align-centre sm--inline-media--align-right' => t('Centre / Right'),
      'inline-media--align-right' => t('Right / Right'),
    ],
    '#default_value' => empty($media_embed_element['class']) ? '' : $media_embed_element['class'],
    '#parents' => ['attributes', 'class'],
  ];
}

/**
 * Implements hook_preprocess_node().
 * Attach module CSS library
 */
function apsc_ckeditor_media_dialog_preprocess_node(&$variables) {
	$variables['#attached']['library'][] = 'apsc_ckeditor_media_dialog/apsc-inline-media';
}

/**
 * Implements hook_ckeditor_css_alter().
 * Load the css in the editor iframe for editors to preview the result
 */
function apsc_ckeditor_media_dialog_ckeditor_css_alter(&$css, $editor) {
  $css[] = drupal_get_path('module', 'apsc_ckeditor_media_dialog') . '/css/inline-media-elements.css';
}
