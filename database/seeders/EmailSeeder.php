<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailNotification;

class EmailSeeder extends Seeder
{
	public function run(): void
	{
		// 1: New Member Sign Up
		EmailNotification::updateOrCreate(
			['name' => 'New Member Sign Up'],
			[
				'description' => 'Sent to new members upon successful registration.',
				'subject' => 'Welcome to {{app_name}} — Get started',
				'body' => '<p>Hi {{member_name}},</p><p>Welcome to {{app_name}}! We\'re excited to have you.</p>',
			]
		);

		// 2: Email Verification
		EmailNotification::updateOrCreate(
			['name' => 'Email Verification'],
			[
				'description' => 'Sends an 8-digit code to verify email during sign-up.',
				'subject' => 'Verify your email for {{app_name}}',
				'body' => '<p>Your verification code is: <strong>{{verification_code}}</strong></p>',
			]
		);

		// 3: New Profile Match
		EmailNotification::updateOrCreate(
			['name' => 'New Profile Match'],
			[
				'description' => 'Sent to both matched members with clickable profile links.',
				'subject' => 'You\'ve been matched on {{app_name}}',
				'body' => '<p>Hi {{member_name}},</p><p>You\'ve been matched with {{matched_member_name}}. View their profile: {{matched_member_link}}</p>',
			]
		);
	}
}

