<?php

return array(
	
	// admin menu items
	'admin:widgets' => "Blocs d'info",
	'admin:widgets:manage' => "Gérer",
	'admin:widgets:manage:index' => "Gérer l'index",
	'admin:statistics:widgets' => "Utilisation de blocs d'infos",

	// widget edit wrapper
	'widget_manager:widgets:edit:custom_title' => "Votre titre",
	'widget_manager:widgets:edit:custom_url' => "Lien de votre titre",
	'widget_manager:widgets:edit:custom_more_title' => "Plus de texte",
	'widget_manager:widgets:edit:custom_more_url' => "Plus de liens",
	'widget_manager:widgets:edit:hide_header' => "Cacher l'entête",
	'widget_manager:widgets:edit:custom_class' => "Classe CSS sur mesure",
	'widget_manager:widgets:edit:disable_widget_content_style' => "Pas de style",
	'widget_manager:widgets:edit:fixed_height' => "Hauteur du bloc d'infos (en pixels)",
	'widget_manager:widgets:edit:collapse_disable' => "Désactiver l'auto dimensionnement",
	'widget_manager:widgets:edit:collapse_state' => "Dimensionnement par défaut",

	// group
	'widget_manager:groups:enable_widget_manager' => "Activer la gestion des blocs d'info",

	// admin settings
	'widget_manager:settings:index' => "Page d'index",
	'widget_manager:settings:group' => "Groupe",

	'widget_manager:settings:custom_index' => "Utiliser Widget Manager custom index?",
	'widget_manager:settings:custom_index:non_loggedin' => "Pour les utilisateurs non logués seulement",
	'widget_manager:settings:custom_index:loggedin' => "Pour les utilisateurs logués seulement",
	'widget_manager:settings:custom_index:all' => "Pour tous les utilisateurs",

	'widget_manager:settings:widget_layout' => "Choisissez un format de page de widgets",
	'widget_manager:settings:widget_layout:33|33|33' => "Par défaut  (33% par colonne)",
	'widget_manager:settings:widget_layout:50|25|25' => "Colonne gauche large (50%, 25%, 25%)",
	'widget_manager:settings:widget_layout:25|50|25' => "Colonne du milieu large (25%, 50%, 25%)",
	'widget_manager:settings:widget_layout:25|25|50' => "Colonne de droite large (25%, 25%, 50%)",
	'widget_manager:settings:widget_layout:75|25' => "2 colonnes (75%, 25%)",
	'widget_manager:settings:widget_layout:60|40' => "2 colonnes (60%, 40%)",
	'widget_manager:settings:widget_layout:50|50' => "2 colonnes (50%, 50%)",
	'widget_manager:settings:widget_layout:40|60' => "2 colonnes (40%, 60%)",
	'widget_manager:settings:widget_layout:25|75' => "2 colonnes (25%, 75%)",
	'widget_manager:settings:widget_layout:100' => "1 seule colonne (100%)",

	'widget_manager:settings:index_top_row' => "Afficher une colonne en haut de la page d'accueil",
	'widget_manager:settings:index_top_row:none' => "Pas de colonne en haut",
	'widget_manager:settings:index_top_row:full_row' => "1 colonne pleine page",
	'widget_manager:settings:index_top_row:two_column_left' => "2 colonnes alignées à gauche",

	'widget_manager:settings:group:enable' => "Activer la gestion des blocs d'infos pour les groupes",
	'widget_manager:settings:group:enable:yes' => "Oui, gérable par l'option outils de groupes",
	'widget_manager:settings:group:enable:forced' => "Oui, toujours actif",
	'widget_manager:settings:group:option_default_enabled' => "Gestion des blocs d'infos pour les groupe activé par défaut",
	'widget_manager:settings:group:option_admin_only' => "Seul l'administrateur peut activer la gestion des blocs d'infos dans les groupes",
	'widget_manager:settings:group:force_tool_widgets' => "Appliquer la gestion des blocs d'infos pour tous les groupes",
	'widget_manager:settings:group:force_tool_widgets:confirm' => "Êtes vous sûr/e? Cette action s'appliquera pour tous les groupes.",
	
	'widget_manager:settings:extra_contexts' => "Contextes Extra widgets",
	'widget_manager:settings:extra_contexts:add' => "Ajouter une page",
	'widget_manager:settings:extra_contexts:description' => "Saisissez le nom de la page (nom unique) qui aura le même design que la page d'index.",
	'widget_manager:settings:extra_contexts:page' => "Page",
	'widget_manager:settings:extra_contexts:layout' => "Design",
	'widget_manager:settings:extra_contexts:top_row' => "Colonne du haut en plus",
	'widget_manager:settings:extra_contexts:manager' => "Gestionnaire",

	// views
	// settings
	'widget_manager:forms:settings:no_widgets' => "Aucun bloc d'info à gérer",
	'widget_manager:forms:manage_widgets:context' => 'Disponible dans ce contexte',
	'widget_manager:forms:settings:can_add' => "Peut être ajouté",
	'widget_manager:forms:manage_widgets:multiple' => "Widget multiple autorisé",
	'widget_manager:forms:manage_widgets:non_default' => "Ce paramétrage est différent de la valeur par défaut",
	'widget_manager:forms:manage_widgets:unsupported_context:confirm' => "Êtes vous sûr d'activer ce widget pour ce contexte? Si ce widget ne supporte pas ce contexte, il pourrait survenir des problèmes.",

	// groups widget access
	'widget_manager:forms:groups_widget_access:title' => "Accès aux blocs d'infos",
	'widget_manager:forms:groups_widget_access:description' => "Cette action vous permet de mettre à jour le niveau d'accès de tous les blocs d'infos de ce groupe.",
	
	// lightbox
	'widget_manager:button:add' => "Ajouter des widgets",
	'widget_manager:widgets:lightbox:title:dashboard' => "Ajouter des blocs d'infos à votre tableau de bord personnel",
	'widget_manager:widgets:lightbox:title:profile' => "Ajouter des blocs d'infos à votre profil individuel",
	'widget_manager:widgets:lightbox:title:index' => "Ajouter des blocs d'infos à la page d'index",
	'widget_manager:widgets:lightbox:title:groups' => "Ajouter des blocs d'infos au profil du groupe",
	'widget_manager:widgets:lightbox:title:admin' => "Ajouter des blocs d'infos à votre tableau de bord admin",

	// actions
	// manage
	'widget_manager:action:manage:success' => "La configuration des blocs d'infos a été sauvegardée",

	// force tool widgets
	'widget_manager:action:force_tool_widgets:error:not_enabled' => "La gestionnaire de blocs d'info pour les groupes n'est pas activé",
	'widget_manager:action:force_tool_widgets:succes' => "Activer l'outil widgets pour les groupes %s",
	
	// groups update widget access
	'widget_manager:action:groups:update_widget_access:success' => "L'accès à tous les blocs d'infos pour ce groupe a été mis à jour",
	
	// widgets
	'widget_manager:widgets:edit:advanced' => "Avancé",
	'widget_manager:widgets:fix' => "Fixer ce widget sur le tableau de bord/profil",
);
