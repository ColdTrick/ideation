<?php

return [
	
	// generic
	'item:object:idea' => 'Idea',
	'collection:object:idea' => 'Ideas',
	'notification:object:idea:create' => 'Send a notification when an Idea is created',
	
	'add:object:idea' => 'Add an idea',
	'ideation:no_results' => 'No ideas created yet',
	
	'ideation:target_audience' => "Target audience",
	'ideation:problem' => "This problem will be solved",
	'ideation:problem:question' => "What problem does this idea solve",
	'ideation:idea:delete:confirm' => "Are you sure you wish to delete this Idea. All related questions will also be deleted!",
	
	// plugin settings
	'ideation:settings:enable_personal' => "Enable Ideation for personal use",
	'ideation:settings:enable_personal:help' => "When enabled users can create use Ideation from their personal context, eg. not in groups.",
	'ideation:settings:enable_groups' => "Enable Ideation for use in groups",
	'ideation:settings:enable_groups:help' => "When enabled group owners can enable Ideation for their group. By default this feature is disabled in the group.",
	'ideation:settings:enable_comments' => "Enable comments on Ideas",
	'ideation:settings:enable_comments:help' => "When enabled users can leave comments on Ideas.",
	
	'ideation:settings:questions:title' => "Question plugin integration settings",
	'ideation:settings:enable_questions' => "Enable Questions integration",
	'ideation:settings:enable_questions:help' => "When enabled users can ask Questions about Ideas, which will be handled by the Questions plugin.",
	'ideation:settings:suggested_questions_profile_fields' => "Which profile fields should be used in the suggested questions widget",
	'ideation:settings:suggested_questions_profile_fields:help' => "Configure the profile fields which will be used in the suggested questions widget. Mulitple profile fields must be separated by a comma.",
	
	// groups
	'ideation:group_tool_option:label' => "Enable group ideation",
	'ideation:group_tool_option:description' => "Allow group members to share ideas and work on implementing the idea",
	
	// river
	'river:object:idea:create' => "%s posted a new idea %s",
	'river:object:idea:comment' => "%s commented on the idea %s",
	'ideation:river:object:question:attachment' => 'Asked on the idea: %s',
	
	// menus
	// owner_block
	'ideation:menu:owner_block:groups' => "Group ideation",
	// site
	'ideation:menu:site:all' => "Ideation",
	'ideation:menu:filter:suggested' => "Suggested",
	
	// pages
	'ideation:all:title' => "All site ideas",
	'ideation:owner:title' => "%s's ideas",
	'ideation:owner:title:mine' => "My ideas",
	'ideation:friends:title' => "%s's friends ideas",
	'ideation:friends:title:mine' => "My friends ideas",
	'ideation:group:title' => "%s's ideas",
	'ideation:add:title' => "Create a new idea",
	'ideation:edit:title' => "Edit idea: %s",
	'ideation:suggested:title' => "Suggested questions for you",
	
	// suggested
	'ideation:suggested:error:profile_fields' => "No profile fields have been configured to show interesting questions, please contact your site administrator",
	'ideation:suggested:error:logged_out' => "You need to be logged in to view this list",
	'ideation:suggested:error:user_profile' => "Not enough information on your profile to suggest questions, please fill your profile.",
	'ideation:suggested:error:no_results' => "No questions could be found based on your profile information.",
	
	// status
	'ideation:status' => "Status",
	'ideation:status:new' => "New",
	'ideation:status:accepted' => "Accepted",
	'ideation:status:in_progress' => "In progress",
	'ideation:status:rejected' => "Rejected",
	'ideation:status:implemented' => "Implemented",
	'ideation:status:closed' => "Closed",
	
	// notifications
	'ideation:notification:create:subject' => "New idea created: %s",
	'ideation:notification:create:summary' => "New idea created: %s",
	'ideation:notification:create:body' => "%s created a new idea '%s'. Help make this a reality.

To view the idea, click here:
%s",
	
	'ideation:notification:idea:owner:question:create:subject' => "A new question was asked on your idea: %s",
	'ideation:notification:idea:owner:question:create:summary' => "A new question was asked on your idea: %s",
	'ideation:notification:idea:owner:question:create:summary' => "%s asked a question on your idea '%s'.

%s

To view the idea, click here:
%s

To view the question, click here:
%s",
	
	'ideation:notification:idea:owner:answer:create:subject' => "A new answer was posted for your idea: %s",
	'ideation:notification:idea:owner:answer:create:summary' => "A new question was posted for your idea: %s",
	'ideation:notification:idea:owner:answer:create:summary' => "%s posted an answer to the question '%s' on your idea '%s'.

%s

To view the idea, click here:
%s

To view the answer, click here:
%s",
	
	// questions support
	'ideation:questions:edit:link' => "This question will be linked to the idea: %s",
	'ideation:questions:related' => "Questions related to this idea",
	'ideation:questions:idea:title' => "Related idea",
	
	// widgets
	'widgets:ideation_suggested_questions:name' => "Suggested questions",
	'widgets:ideation_suggested_questions:description' => "List open questions for ideas based on user profile tags",
	
	'widgets:ideation:name' => "Ideas",
	'widgets:ideation:description' => "List ideas with different selection options",
	'widgets:ideation:status' => "Idea status",
	'widgets:ideation:sort:updated' => "Last updated",
	
	// actions
	'ideation:action:edit:success' => "Your idea was saved successfully",
];
