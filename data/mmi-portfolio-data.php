<?php



add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_66d418fbe4f71',
	'title' => 'Infos AC',
	'fields' => array(
		array(
			'key' => 'field_66d418fca9ff6',
			'label' => 'Compétence',
			'name' => 'competence',
			'aria-label' => '',
			'type' => 'radio',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Comprendre' => 'Comprendre',
				'Concevoir' => 'Concevoir',
				'Exprimer' => 'Exprimer',
				'Développer' => 'Développer',
				'Entreprendre' => 'Entreprendre',
			),
			'default_value' => '',
			'return_format' => 'value',
			'allow_null' => 0,
			'other_choice' => 0,
			'layout' => 'vertical',
			'save_other_choice' => 0,
		),
		array(
			'key' => 'field_66d4194ea9ff7',
			'label' => 'Niveau',
			'name' => 'niveau',
			'aria-label' => '',
			'type' => 'radio',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				1 => '1',
				2 => '2',
				3 => '3',
			),
			'default_value' => '',
			'return_format' => 'value',
			'allow_null' => 0,
			'other_choice' => 0,
			'layout' => 'vertical',
			'save_other_choice' => 0,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'ac',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );

	acf_add_local_field_group( array(
	'key' => 'group_66d41a1d95fcd',
	'title' => 'Infos réalisation',
	'fields' => array(
		array(
			'key' => 'field_66f57024c58fc',
			'label' => 'Date de début',
			'name' => 'date_de_debut',
			'aria-label' => '',
			'type' => 'date_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'display_format' => 'd/m/Y',
			'return_format' => 'd/m/Y',
			'first_day' => 1,
		),
		array(
			'key' => 'field_66f57045c58fd',
			'label' => 'Date de fin',
			'name' => 'date_de_fin',
			'aria-label' => '',
			'type' => 'date_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'display_format' => 'd/m/Y',
			'return_format' => 'd/m/Y',
			'first_day' => 1,
		),
		array(
			'key' => 'field_66d70c527ac92',
			'label' => 'Pédagogie',
			'name' => 'pedagogie',
			'aria-label' => '',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'block',
			'sub_fields' => array(
				array(
					'key' => 'field_66d41a1d8487c',
					'label' => 'Apprentissages critiques engagés',
					'name' => 'apprentissages_critiques_engages',
					'aria-label' => '',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'block',
					'pagination' => 0,
					'min' => 0,
					'max' => 0,
					'collapsed' => '',
					'button_label' => 'Ajouter un élément',
					'rows_per_page' => 20,
					'sub_fields' => array(
						array(
							'key' => 'field_66d41a558487d',
							'label' => 'Apprentissage critique',
							'name' => 'apprentissage_critique',
							'aria-label' => '',
							'type' => 'relationship',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'post_type' => array(
								0 => 'ac',
							),
							'post_status' => '',
							'taxonomy' => '',
							'filters' => array(
								0 => 'search',
								1 => 'taxonomy',
							),
							'return_format' => 'object',
							'min' => 1,
							'max' => 1,
							'elements' => '',
							'bidirectional' => 0,
							'bidirectional_target' => array(
							),
							'parent_repeater' => 'field_66d41a1d8487c',
						),
						array(
							'key' => 'field_66d41ab88487e',
							'label' => 'Niveau estimé',
							'name' => 'niveau_estime',
							'aria-label' => '',
							'type' => 'range',
							'instructions' => 'Auto-évaluation sur une échelle de 1 à 4.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'min' => 1,
							'max' => 4,
							'step' => 1,
							'prepend' => '',
							'append' => '',
							'parent_repeater' => 'field_66d41a1d8487c',
						),
						array(
							'key' => 'field_66d41aff8487f',
							'label' => 'Trace(s) picturale(s)',
							'name' => 'traces_images',
							'aria-label' => '',
							'type' => 'gallery',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'return_format' => 'array',
							'library' => 'all',
							'min' => '',
							'max' => '',
							'min_width' => '',
							'min_height' => '',
							'min_size' => '',
							'max_width' => '',
							'max_height' => '',
							'max_size' => '',
							'mime_types' => '',
							'insert' => 'append',
							'preview_size' => 'medium',
							'parent_repeater' => 'field_66d41a1d8487c',
						),
						array(
							'key' => 'field_66d41b6484880',
							'label' => 'Trace(s) hypertexte',
							'name' => 'traces_hypertexte',
							'aria-label' => '',
							'type' => 'url',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'parent_repeater' => 'field_66d41a1d8487c',
						),
						array(
							'key' => 'field_66d41b7e84881',
							'label' => 'Analyse / justification',
							'name' => 'analyse',
							'aria-label' => '',
							'type' => 'wysiwyg',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 1,
							'delay' => 0,
							'parent_repeater' => 'field_66d41a1d8487c',
						),
					),
				),
				array(
					'key' => 'field_66d70cf57ac95',
					'label' => 'Cadre',
					'name' => 'cadre',
					'aria-label' => '',
					'type' => 'radio',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'SAE' => 'SAE',
						'Cours' => 'Cours',
						'Stage' => 'Stage',
						'Alternance' => 'Alternance',
						'Personnel' => 'Personnel',
					),
					'default_value' => '',
					'return_format' => 'value',
					'allow_null' => 0,
					'other_choice' => 0,
					'layout' => 'vertical',
					'save_other_choice' => 0,
				),
				array(
					'key' => 'field_66d70c9a7ac93',
					'label' => 'Modalité',
					'name' => 'modalite',
					'aria-label' => '',
					'type' => 'checkbox',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'Individuel' => 'Individuel',
						'Groupe' => 'Groupe',
					),
					'default_value' => array(
					),
					'return_format' => 'value',
					'allow_custom' => 0,
					'layout' => 'vertical',
					'toggle' => 0,
					'save_custom' => 0,
					'custom_choice_button_text' => 'Ajouter un nouveau choix',
				),
				array(
					'key' => 'field_66d70cc07ac94',
					'label' => 'Temps de travail estimé',
					'name' => 'temps_de_travail_estime',
					'aria-label' => '',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'min' => '',
					'max' => '',
					'placeholder' => 'Nombre d\'heures travaillées',
					'step' => 1,
					'prepend' => '',
					'append' => 'H',
				),
			),
		),
		array(
			'key' => 'field_66f56f17ffec0',
			'label' => 'Utiliser dans le portfolio pro',
			'name' => 'portfolio_pro',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_66f566556f969',
			'label' => 'Titre alternatif',
			'name' => 'titre',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_66f56fead1319',
			'label' => 'Image à la une',
			'name' => 'image_a_la_une',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
			'preview_size' => 'medium',
		),
		array(
			'key' => 'field_66f5668a6f96a',
			'label' => 'Description alternative',
			'name' => 'description',
			'aria-label' => '',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
			'delay' => 0,
		),
		array(
			'key' => 'field_66d70f08cee37',
			'label' => 'Galerie',
			'name' => 'galerie',
			'aria-label' => '',
			'type' => 'gallery',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min' => '',
			'max' => '',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
			'insert' => 'append',
			'preview_size' => 'medium',
		),
		array(
			'key' => 'field_66d70deecee36',
			'label' => 'Pro',
			'name' => 'pro',
			'aria-label' => '',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_66f56f17ffec0',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'block',
			'sub_fields' => array(
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'realisation-mmi',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );
} );

add_action( 'init', function() {
	register_taxonomy( 'competence', array(
	0 => 'ac',
	1 => 'realisation',
), array(
	'labels' => array(
		'name' => 'Compétences',
		'singular_name' => 'Compétence',
		'menu_name' => 'Compétences',
		'all_items' => 'Tous les Compétences',
		'edit_item' => 'Modifier Compétence',
		'view_item' => 'Voir Compétence',
		'update_item' => 'Mettre à jour Compétence',
		'add_new_item' => 'Ajouter Compétence',
		'new_item_name' => 'Nom du nouveau Compétence',
		'search_items' => 'Rechercher Compétences',
		'not_found' => 'Aucun compétences trouvé',
		'no_terms' => 'Aucun compétences',
		'items_list_navigation' => 'Navigation dans la liste Compétences',
		'items_list' => 'Liste Compétences',
		'back_to_items' => '← Aller à « compétences »',
		'item_link' => 'Lien Compétence',
		'item_link_description' => 'Un lien vers un compétence',
	),
	'public' => false,
	'hierarchical' => true,
	'publicly_queryable' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'show_in_nav_menus' => true,
	'show_in_rest' => true,
	'show_admin_column' => true,
) );

	register_taxonomy( 'domaine', array(
	0 => 'realisation',
), array(
	'labels' => array(
		'name' => 'Domaines',
		'singular_name' => 'Domaine',
		'menu_name' => 'Domaines',
		'all_items' => 'Tous les Domaines',
		'edit_item' => 'Modifier Domaine',
		'view_item' => 'Voir Domaine',
		'update_item' => 'Mettre à jour Domaine',
		'add_new_item' => 'Ajouter Domaine',
		'new_item_name' => 'Nom du nouveau Domaine',
		'parent_item' => 'Domaine parent',
		'parent_item_colon' => 'Domaine parent :',
		'search_items' => 'Rechercher Domaines',
		'not_found' => 'Aucun domaines trouvé',
		'no_terms' => 'Aucun domaines',
		'filter_by_item' => 'Filtrer par domaine',
		'items_list_navigation' => 'Navigation dans la liste Domaines',
		'items_list' => 'Liste Domaines',
		'back_to_items' => '← Aller à « domaines »',
		'item_link' => 'Lien Domaine',
		'item_link_description' => 'Un lien vers un domaine',
	),
	'public' => true,
	'hierarchical' => true,
	'show_in_menu' => true,
	'show_in_rest' => true,
	'show_admin_column' => true,
) );

	register_taxonomy( 'niveau', array(
	0 => 'ac',
	1 => 'realisation',
), array(
	'labels' => array(
		'name' => 'Niveaux',
		'singular_name' => 'Niveau',
		'menu_name' => 'Niveaux',
		'all_items' => 'Tous les Niveaux',
		'edit_item' => 'Modifier Niveau',
		'view_item' => 'Voir Niveau',
		'update_item' => 'Mettre à jour Niveau',
		'add_new_item' => 'Ajouter Niveau',
		'new_item_name' => 'Nom du nouveau Niveau',
		'search_items' => 'Rechercher Niveaux',
		'not_found' => 'Aucun niveaux trouvé',
		'no_terms' => 'Aucun niveaux',
		'items_list_navigation' => 'Navigation dans la liste Niveaux',
		'items_list' => 'Liste Niveaux',
		'back_to_items' => '← Aller à « niveaux »',
		'item_link' => 'Lien Niveau',
		'item_link_description' => 'Un lien vers un niveau',
	),
	'public' => false,
	'hierarchical' => true,
	'publicly_queryable' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'show_in_nav_menus' => true,
	'show_in_rest' => true,
	'show_admin_column' => true,
) );
} );

add_action( 'init', function() {
	register_post_type( 'ac', array(
	'labels' => array(
		'name' => 'Apprentissages critiques',
		'singular_name' => 'Apprentissage critique',
		'menu_name' => 'Apprentissages critiques',
		'all_items' => 'Tous les Apprentissages critiques',
		'edit_item' => 'Modifier Apprentissage critique',
		'view_item' => 'Voir Apprentissage critique',
		'view_items' => 'Voir Apprentissages critiques',
		'add_new_item' => 'Ajouter Apprentissage critique',
		'add_new' => 'Ajouter Apprentissage critique',
		'new_item' => 'Nouveau Apprentissage critique',
		'parent_item_colon' => 'Apprentissage critique parent :',
		'search_items' => 'Rechercher Apprentissages critiques',
		'not_found' => 'Aucun apprentissages critiques trouvé',
		'not_found_in_trash' => 'Aucun apprentissages critiques trouvé dans la corbeille',
		'archives' => 'Archives des Apprentissage critique',
		'attributes' => 'Attributs des Apprentissage critique',
		'insert_into_item' => 'Insérer dans apprentissage critique',
		'uploaded_to_this_item' => 'Téléversé sur ce apprentissage critique',
		'filter_items_list' => 'Filtrer la liste apprentissages critiques',
		'filter_by_date' => 'Filtrer apprentissages critiques par date',
		'items_list_navigation' => 'Navigation dans la liste Apprentissages critiques',
		'items_list' => 'Liste Apprentissages critiques',
		'item_published' => 'Apprentissage critique publié.',
		'item_published_privately' => 'Apprentissage critique publié en privé.',
		'item_reverted_to_draft' => 'Apprentissage critique repassé en brouillon.',
		'item_scheduled' => 'Apprentissage critique planifié.',
		'item_updated' => 'Apprentissage critique mis à jour.',
		'item_link' => 'Lien Apprentissage critique',
		'item_link_description' => 'Un lien vers un apprentissage critique.',
	),
	'public' => true,
	'show_in_rest' => true,
	'menu_icon' => 'dashicons-admin-network',
	'supports' => array(
		0 => 'title',
		1 => 'editor',
		2 => 'thumbnail',
		3 => 'custom-fields',
	),
	'delete_with_user' => false,
) );

	register_post_type( 'realisation-mmi', array(
	'labels' => array(
		'name' => 'Réalisations',
		'singular_name' => 'Réalisation',
		'menu_name' => 'Réalisations',
		'all_items' => 'Toutes les réalisations',
		'edit_item' => 'Modifier réalisation',
		'view_item' => 'Voir réalisation',
		'view_items' => 'Voir les réalisations',
		'add_new_item' => 'Ajouter une réalisation',
		'add_new' => 'Ajouter une réalisation',
		'new_item' => 'Nouvelle réalisation',
		'parent_item_colon' => 'Réalisation parent :',
		'search_items' => 'Rechercher réalisation',
		'not_found' => 'Aucune réalisation trouvée',
		'not_found_in_trash' => 'Aucune réalisation trouvée dans la corbeille',
		'archives' => 'Archives des réalisations',
		'attributes' => 'Attributs des réalisations',
		'insert_into_item' => 'Insérer dans sae',
		'uploaded_to_this_item' => 'Téléversé sur ce sae',
		'filter_items_list' => 'Filtrer la liste sae',
		'filter_by_date' => 'Filtrer sae par date',
		'items_list_navigation' => 'Navigation dans la liste SAE',
		'items_list' => 'Liste SAE',
		'item_published' => 'SAE publié.',
		'item_published_privately' => 'SAE publié en privé.',
		'item_reverted_to_draft' => 'SAE repassé en brouillon.',
		'item_scheduled' => 'SAE planifié.',
		'item_updated' => 'SAE mis à jour.',
		'item_link' => 'Lien SAE',
		'item_link_description' => 'Un lien vers un sae.',
	),
	'public' => true,
	'show_in_rest' => true,
	'menu_icon' => 'dashicons-clipboard',
	'supports' => array(
		0 => 'title',
		1 => 'editor',
		2 => 'thumbnail',
		3 => 'custom-fields',
	),
	'taxonomies' => array(
		0 => 'competence',
		1 => 'niveau',
		2 => 'domaine',
	),
	'has_archive' => true,
	'rewrite' => array(
		'with_front' => false,
		'feeds' => false,
	),
	'delete_with_user' => false,
) );
} );



/**
 * Import one-shot du contenu pédagogique (apprentissages critiques + termes).
 *
 * ATTENTION : cette fonction écrit en base. Elle ne doit JAMAIS être branchée sur
 * un hook front (« wp », « init »…) : elle est déclenchée une seule fois après
 * l'activation du plugin, ou manuellement via WP-CLI :
 *
 *     wp mmi-portfolio import
 *
 * @return array Compteurs { terms, posts }.
 */
function mmi_portfolio_xml_import() {
	$counts = array(
		'terms' => 0,
		'posts' => 0,
	);

	if ( ! function_exists( 'post_exists' ) ) {
		require_once ABSPATH . 'wp-admin/includes/post.php';
	}

	$file = __DIR__ . '/all_content.xml';
	if ( ! file_exists( $file ) ) {
		return $counts;
	}

	$xml = simplexml_load_file( $file, 'SimpleXMLElement', LIBXML_NOCDATA );
	if ( false === $xml ) {
		return $counts;
	}

	$xml->registerXPathNamespace( 'wp', 'http://wordpress.org/export/1.2/' );
	$terms = $xml->channel->xpath( '//wp:term' );
	$items = $xml->channel->xpath( '//item' );

	$taxonomies = array( 'competence', 'domaine', 'niveau' );

	foreach ( (array) $terms as $term ) {
		$term_name     = $term->xpath( 'wp:term_name' );
		$term_taxonomy = $term->xpath( 'wp:term_taxonomy' );

		if ( empty( $term_name ) || empty( $term_taxonomy ) ) {
			continue;
		}

		$term_name     = (string) $term_name[0];
		$term_taxonomy = (string) $term_taxonomy[0];

		if ( ! in_array( $term_taxonomy, $taxonomies, true ) ) {
			continue;
		}

		if ( term_exists( $term_name, $term_taxonomy ) ) {
			continue;
		}

		$inserted = wp_insert_term( $term_name, $term_taxonomy );
		if ( ! is_wp_error( $inserted ) ) {
			$counts['terms']++;
		}
	}

	foreach ( (array) $items as $item ) {
		$post_type = $item->xpath( 'wp:post_type' );
		if ( empty( $post_type ) || 'ac' !== (string) $post_type[0] ) {
			continue;
		}

		$post_title = (string) $item->title;
		if ( '' === $post_title || post_exists( $post_title ) ) {
			continue;
		}

		$content     = $item->xpath( 'content:encoded' );
		$post_content = empty( $content ) ? '' : (string) $content[0];

		$post_id = wp_insert_post(
			array(
				'post_title'   => $post_title,
				'post_type'    => 'ac',
				'post_content' => $post_content,
				'post_status'  => 'publish',
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		$counts['posts']++;

		// Termes (niveau / compétence).
		foreach ( array( 'niveau', 'competence' ) as $taxonomy ) {
			$category = $item->xpath( 'category[@domain="' . $taxonomy . '"]' );
			if ( empty( $category ) ) {
				continue;
			}

			$slug = (string) $category[0]['nicename'];
			$term = get_term_by( 'slug', $slug, $taxonomy );
			if ( $term && ! is_wp_error( $term ) ) {
				wp_set_post_terms( $post_id, array( (int) $term->term_id ), $taxonomy );
			}
		}

		// Champs ACF.
		if ( function_exists( 'update_field' ) ) {
			foreach ( (array) $item->xpath( 'wp:postmeta' ) as $meta ) {
				$meta_key   = $meta->xpath( 'wp:meta_key' );
				$meta_value = $meta->xpath( 'wp:meta_value' );

				if ( empty( $meta_key ) || empty( $meta_value ) ) {
					continue;
				}

				$meta_key = (string) $meta_key[0];
				if ( in_array( $meta_key, array( 'competence', 'niveau' ), true ) ) {
					update_field( $meta_key, (string) $meta_value[0], $post_id );
				}
			}
		}
	}

	return $counts;
}

/**
 * Déclenche l'import une seule fois, après activation, côté admin uniquement
 * (les CPT et taxonomies ne sont pas encore enregistrés pendant le hook
 * d'activation lui-même).
 */
function mmi_portfolio_maybe_run_import() {
	if ( ! get_option( 'mmi_portfolio_pending_import' ) ) {
		return;
	}

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	// Supprimé avant l'import : en cas d'erreur fatale, on ne boucle pas.
	delete_option( 'mmi_portfolio_pending_import' );

	$counts = mmi_portfolio_xml_import();

	update_option( 'mmi_portfolio_import_done', current_time( 'mysql' ) );
	set_transient( 'mmi_portfolio_import_notice', $counts, 60 );
}
add_action( 'admin_init', 'mmi_portfolio_maybe_run_import' );

/**
 * Retour visuel après l'import.
 */
function mmi_portfolio_import_notice() {
	$counts = get_transient( 'mmi_portfolio_import_notice' );
	if ( false === $counts ) {
		return;
	}

	delete_transient( 'mmi_portfolio_import_notice' );

	printf(
		'<div class="notice notice-success is-dismissible"><p><strong>MMI Portfolio</strong> : import terminé — %d terme(s) et %d apprentissage(s) critique(s) créé(s).</p></div>',
		(int) $counts['terms'],
		(int) $counts['posts']
	);
}
add_action( 'admin_notices', 'mmi_portfolio_import_notice' );

/**
 * Commande WP-CLI pour rejouer l'import manuellement.
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'mmi-portfolio import',
		function () {
			$counts = mmi_portfolio_xml_import();
			WP_CLI::success( sprintf( 'Import terminé : %d terme(s), %d apprentissage(s) critique(s).', $counts['terms'], $counts['posts'] ) );
		}
	);
}
