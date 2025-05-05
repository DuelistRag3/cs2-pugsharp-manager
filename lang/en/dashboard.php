<?php

return [

    'title' => 'Dashboard',

    'menu' => [
        'home' => 'Home',
        'settings' => 'Settings',
        'profile' => 'Profile',
        'logout' => 'Log Out',
    ],

    'headings' => [
        'platform' => 'Platform',
    ],

    'settings' => [
        'description' => 'Manage your settings here.',

        'save' => 'Save',

        'app' => [
            'title' => 'Application Settings',
            'description' => 'Manage your app settings.',
            'name' => 'App Name',
            'language' => 'Language',
            'languages' => [
                'en' => 'English',
                'de' => 'German',
            ],
            'saved' => 'Settings saved successfully.',
        ],

        'profile' => [
            'title' => 'Profile',
            'description' => 'Update your name and email address.',
            'username' => 'Username',
            'email' => 'Email',
            'email_unverified' => 'Your email address is unverified.',
            'email_verifie_link' => 'Click here to re-send the verification email.',
            'email_verifie_link_sent' => 'A new verification link has been sent to your email address.',
            'saved' => 'Profile updated successfully.',
            'delete' => 'Delete Account',
            'delete_description' => 'Delete your account permanently and all of its resources.',
            'delete_button' => 'Delete Account',
            'delete_confirmation' => 'Are you sure you want to delete your account?',
            'delete_confirmation_description' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.',
            'delete_password' => 'Password',
            'delete_cancel' => 'Cancel',
        ],

        'password' => [
            'title' => 'Change Password',
            'description' => 'Ensure your account is using a long, random password to stay secure.',
            'current_password' => 'Current Password',
            'new_password' => 'New Password',
            'confirm_password' => 'Confirm New Password',
            'saved' => 'Password changed successfully.',
        ],

        'appearance' => [
            'title' => 'Appearance',
            'description' => 'Customize the look and feel of your application.',
            'light' => 'Light',
            'dark' => 'Dark',
            'system' => 'System',
            'theme' => 'Theme',
            'language' => 'Language',
            'saved' => 'Appearance settings saved successfully.',
        ],
    ],

];