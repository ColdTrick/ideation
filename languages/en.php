<?php

return [
	
	// generic
	'item:object:idea' => 'Idea',
	
	'ideation:breadcrumb:all' => 'Ideation',
	
	'ideation:add' => 'Add an idea',
	'ideation:no_results' => 'No ideas created yet',
	
	// plugin settings
	'ideation:settings:enable_personal' => "Enable Ideation for personal use",
	'ideation:settings:enable_personal:help' => "When enabled users can create use Ideation from their personal context, eg. not in groups.",
	'ideation:settings:enable_groups' => "Enable Ideation for use in groups",
	'ideation:settings:enable_groups:help' => "When enabled group owners can enable Ideation for their group. By default this feature is disabled in the group.",
	'ideation:settings:suggested_questions_profile_fields' => "Which profile fields should be used in the suggested questions widget",
	'ideation:settings:suggested_questions_profile_fields:help' => "Configure the profile fields which will be used in the suggested questions widget. Mulitple profile fields must be separated by a comma.",
	
	// groups
	'ideation:group_tool_option:label' => "Enable group ideation",
	'ideation:group_tool_option:description' => "Allow group members to share ideas and work on implementing the idea",
	
	// river
	'river:create:object:idea' => "%s posted a new idea %s",
	'ideation:river:object:question:attachment' => 'Asked on the idea: %s',
	
	// menus
	// owner_block
	'ideation:menu:owner_block:groups' => "Group ideation",
	// site
	'ideation:menu:site:all' => "Ideation",
	
	// pages
	'ideation:all:title' => "All site ideas",
	'ideation:owner:title' => "%s's ideas",
	'ideation:owner:title:mine' => "My ideas",
	'ideation:friends:title' => "%s's friends ideas",
	'ideation:friends:title:mine' => "My friends ideas",
	'ideation:group:title' => "%s's ideas",
	'ideation:add:title' => "Create a new idea",
	'ideation:edit:title' => "Edit idea: %s",
	
	// status
	'ideation:status:new' => "New",
	'ideation:status:accepted' => "Accepted",
	'ideation:status:rejected' => "Rejected",
	'ideation:status:implemented' => "Implemented",
	
	// notifications
	'ideation:notification:create:subject' => "New idea created: %s",
	'ideation:notification:create:summary' => "New idea created: %s",
	'ideation:notification:create:summary' => "Hi %s,

%s created a new idea '%s'. Help make this a reality.

To view the idea, click here:
%s",
	
	'ideation:notification:idea:owner:question:create:subject' => "A new question was asked on your idea: %s",
	'ideation:notification:idea:owner:question:create:summary' => "A new question was asked on your idea: %s",
	'ideation:notification:idea:owner:question:create:summary' => "Hi %s,

%s asked a question on your idea '%s'.

%s

To view the idea, click here:
%s

To view the question, click here:
%s",
	
	'ideation:notification:idea:owner:answer:create:subject' => "A new answer was posted for your idea: %s",
	'ideation:notification:idea:owner:answer:create:summary' => "A new question was posted for your idea: %s",
	'ideation:notification:idea:owner:answer:create:summary' => "Hi %s,

%s posted an answer to the question '%s' on your idea '%s'.

%s

To view the idea, click here:
%s

To view the answer, click here:
%s",
	
	// questions support
	'ideation:questions:edit:link' => "This question will be linked to the idea: %s",
	'ideation:questions:related' => "Questions related to this idea",
	'ideation:questions:idea:title' => "This question is related to idea: %s",
	
	// widgets
	'widgets:ideation_suggested_questions:name' => "Suggested questions",
	'widgets:ideation_suggested_questions:description' => "List open questions for ideas based on user profile tags",
	'widgets:ideation_suggested_questions:error:profile_fields' => "No profile fields have been configured to show interesting questions, please contact your site administrator",
	'widgets:ideation_suggested_questions:error:logged_out' => "You need to be logged in to use this widget",
	'widgets:ideation_suggested_questions:error:user_profile' => "Not enough information on your profile to suggest questions, please fill your profile.",
	'widgets:ideation_suggested_questions:error:no_results' => "No questions could be found based on your profile information.",
	
	// actions
	'ideation:action:edit:success' => "Your idea was saved successfully",
];
